<?php

namespace Acidgreen\ContentManager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context as HelperContext;
use Magento\Framework\Module\Dir\Reader;

/**
 * Class TemplateLoader
 * @package Acidgreen\ContentManager\Helper
 */
class TemplateLoader extends AbstractHelper
{
    /**
     * @var Reader
     */
    protected $moduleReader;

    /**
     * TemplateLoader constructor.
     * @param HelperContext $context
     */
    public function __construct(
        Reader $moduleReader,
        HelperContext $context
    ) {
        $this->moduleReader = $moduleReader;
        parent::__construct($context);
    }

    public function getContentTemplatesPath() {
        $modulePath = $this->moduleReader->getModuleDir('', $this->_getModuleName());
        $contentPath = $modulePath
            . DIRECTORY_SEPARATOR . 'view'
            . DIRECTORY_SEPARATOR . 'frontend'
            . DIRECTORY_SEPARATOR . 'templates'
            . DIRECTORY_SEPARATOR . 'content';

        return $contentPath;
    }

    public function getFileContent($contentType = 'page', $fileName) {
        $filename = $this->getContentTemplatesPath() . DIRECTORY_SEPARATOR . $contentType
            . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $fileName) . '.html';

        try {
            return file_get_contents($filename);
        } catch (\Exception $e) {
            throw new \Exception("Error: Error encounter while trying to load {$filename}. " . $e->getMessage());
        } 
    }

    public function fileToJson($file) {
        try {
            $sourceData = explode('-->', explode('<!--', $file, 2)[1], 2);

            $data = json_decode($sourceData[0], true);
            $data['content']  = trim($sourceData[1]);
            
            return $data;
        } catch (\Exception $e) {
            throw new \Exception("Error: Error encounter while trying to parse file to json. " . $e->getMessage());
        }
    }
}