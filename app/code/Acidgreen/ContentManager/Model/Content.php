<?php

namespace Acidgreen\ContentManager\Model;

use Acidgreen\ContentManager\Model\Content\Page;
use Acidgreen\ContentManager\Model\Content\Block;
 
class Content {

    /** @var Page */
    protected $page;

    /** @var Block */
    protected $block;

    /**
     * @param Page $page
     * @param Block $block
     */
    public function __construct(
        Page $page,
        Block $block
    ) {
        $this->page = $page;
        $this->block = $block;
    }
    
    public function upsertContent(string $identifier, Array $data, string $type = Page::CONTENT_TYPE) {
        $model = $this->getContentModel($type);
        return $model->upsert($identifier, $data);
    }

    private function getContentModel(string $type) {
        switch($type) {
            case Page::CONTENT_TYPE:
                return $this->page;
            case Block::CONTENT_TYPE:
                return $this->block;
            default:
                throw new \Exception('Error: Unsupported content type provided.');
        }
    }
}

