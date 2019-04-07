<?php

namespace Acidgreen\ContentManager\Model\Content;

use Magento\VersionsCms\Model\Hierarchy\NodeFactory;

class Hierarchy {

     /** @var PageFactory */
    protected $nodeFactory;

    /**
     * PageFactory constructor.
     * @param PageFactory $pageFactory
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        NodeFactory $nodeFactory
    ) {
        $this->nodeFactory = $nodeFactory;
    }

    public function upsert(string $identifier, Array $data, string $parent_xpath = null) {
        $nodeModel = $this->nodeFactory->create();
        $node = $nodeModel->load($identifier, 'identifier');
        $id = 0;
        if ($node->getId() > 0) {
            $id = $node->getId();
            foreach($data as $key => $value) {
                $node->setData($key, $value);
            }
            $node->save();
            
        } else {
            $node = $nodeModel->setData($data)
                ->setIdentifier($identifier)
                ->save();
            $id = $node->getId();
            
        }

        if($parent_xpath != null) 
            $node->setXpath($parent_xpath.'/');
        else 
            $node->setXpath($id);
        

        $node->save();
    }

    public function getParentNode($page_id = null){
        $nodeModel = $this->nodeFactory->create();
        $node = $nodeModel->load($page_id, 'page_id');
        return $node;
    }
}