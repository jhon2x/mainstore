<?php

namespace Acidgreen\ContentManager\Helper;

use Acidgreen\ContentManager\Model\Content;
use Acidgreen\ContentManager\Model\Content\Page;
use Acidgreen\ContentManager\Model\Content\Block;
use Acidgreen\ContentManager\Helper\TemplateLoader;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context as HelperContext;

/**
 * Class ContentHelper
 * @package Acidgreen\ContentManager\Helper
 */
class ContentHelper extends AbstractHelper
{
    /** @var TemplateLoader */
    protected $templateLoader;

    /** @var Content */
    protected $content;

    /**
     * ContentHelper constructor.
     * @param HelperContext $context
     * @param TemplateLoader $templateLoader
     * @param Content $content
     */
    public function __construct(
        HelperContext $context,
        TemplateLoader $templateLoader,
        Content $content
    ) {
        $this->templateLoader = $templateLoader;
        $this->content = $content;
        parent::__construct($context);
    }

    public function upsertPages($pages = []) {
        foreach($pages as $identifier => $template) {
            $file = $this->templateLoader->getFileContent(Page::CONTENT_TYPE, $template);
            $data = $this->templateLoader->fileToJson($file);
            $this->upsertPage($identifier, $data);
        }
    }

    public function upsertPage(string $identifier, Array $data)
    {   
        return $this->content->upsertContent($identifier, $data, Page::CONTENT_TYPE);
    }

    public function upsertBlocks($blocks = []) {
        foreach($blocks as $identifier => $template) {
            $file = $this->templateLoader->getFileContent(Block::CONTENT_TYPE, $template);
            $data = $this->templateLoader->fileToJson($file);
           
            $this->upsertBlock($identifier, $data);
        }
    }

    public function upsertBlock(string $identifier, Array $data)
    {     
        return $this->content->upsertContent($identifier, $data, Block::CONTENT_TYPE);
    }
}