<?php

namespace Acidgreen\ContentManager\Model\Content;

use Acidgreen\ContentManager\Model\Content\AbstractContent;

use Magento\Cms\Model\BlockFactory;

class Block extends AbstractContent {

	const CONTENT_TYPE = 'block';

	 /** @var PageFactory */
    protected $blockFactory;

    /**
     * PageFactory constructor.
     * @param PageFactory $pageFactory
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        BlockFactory $blockFactory
    ) {
        $this->blockFactory = $blockFactory;
    }

    public function upsert(string $identifier, Array $data) {

    	$blockModel = $this->blockFactory->create();
        $block = $blockModel->load($identifier, 'identifier');
        $id = 0;
        if ($block->getId() > 0) {
            $id = $block->getId();
            foreach($data as $key => $value) {
                $block->setData($key, $value);
            }
            $block->save();
        } else {
            $block = $blockModel->setData($data)
                ->setIdentifier($identifier)
                ->setIsActive(true)
                ->setStores([0])
                ->save();
            $id = $block->getId();
        }

    }
}