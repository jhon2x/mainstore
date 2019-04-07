<?php

namespace Acidgreen\ContentManager\Model\Content;

use Acidgreen\ContentManager\Model\Content\AbstractContent;

use Magento\Cms\Model\PageFactory;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Acidgreen\ContentManager\Model\Content\Hierarchy;

class Page extends AbstractContent {

    const CONTENT_TYPE = 'page';

    /** @var PageFactory */
    protected $pageFactory;

    /** @var UrlRewriteFactory */
    protected $urlRewriteFactory;

    protected $hierarchy;

    /**
     * PageFactory constructor.
     * @param PageFactory $pageFactory
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        PageFactory $pageFactory,
        UrlRewriteFactory $urlRewriteFactory,
        Hierarchy $hierarchy
    ) {
        $this->pageFactory = $pageFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->hierarchy = $hierarchy;
    }

    public function upsert(string $identifier, Array $data) {

        

        if(array_key_exists('parent_page', $data)) {
            $hierarchy['parent_page_identifier'] = $data['parent_page'];
            unset($data['parent_page']);
        }

        $pageModel = $this->pageFactory->create();
        $page = $pageModel->load($identifier, 'identifier');
        $id = 0;
        if ($page->getId() > 0) {
            $id = $page->getId();
            foreach($data as $key => $value) {
                $page->setData($key, $value);
            }
            $page->save();
        } else {
            $page = $pageModel->setData($data)
                ->setIdentifier($identifier)
                ->setIsActive(true)
                ->setStores([0])
                ->save();
            $id = $page->getId();
        }

        if($hierarchy != null) {

            if (! empty($data['url']))
                $hierarchy['url'] = $data['url'];

            $this->upsertHierarchy($page, $hierarchy);

        }

        if (! empty($data['url'])) {
            $this->setPageUrl($id, $data['url']);
        }
    }

    private function setPageUrl($id, $url) {
        $urlModel = $this->urlRewriteFactory->create();
        $urlRewrite = $urlModel->getResourceCollection()
            ->addFieldToFilter('entity_id', $id)
            ->addFieldToFilter('entity_type', 'cms-page')
            ->getFirstItem();
        return $urlRewrite->setRequestPath($url)->save();
    }

    private function upsertHierarchy($page, $hierarchy){
        
        if(! empty($hierarchy['parent_page_identifier'])) {
            $pageModel = $this->pageFactory->create();
            $parent_page = $pageModel->load($hierarchy['parent_page_identifier'], 'identifier');
            $parent_page_id = $parent_page->getId();
            $parent_node = $this->hierarchy->getParentNode($parent_page_id);
        }

        $data = [
            'parent_node_id' => ($hierarchy['parent_page_identifier'] != '' ? $parent_node->getId()  : null),
            'page_id'   =>    $page->getId(),
            'label'     =>    $page->getTitle(),
            'identifier'=>    $page->getIdentifier(),
            'sort_order'=>    99,
            'level'     =>    ($hierarchy['parent_page_identifier'] != '' ? $parent_node->getLevel() + 1 : 1),
            'request_url' =>    $hierarchy['url'],
            'scope'     =>    'default',
            'scope_id'  =>    0,
            'menu_visibility'=>'1',
            'menu_layout'=>    'left_column'

        ];
        
        $this->hierarchy->upsert($page->getIdentifier(), $data, ($hierarchy['parent_page_identifier'] != '' ? $parent_node->getXpath() : null));
    }


}