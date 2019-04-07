<?php

namespace Acidgreen\Theme\Block\Adminhtml\Widget;

use Magento\Framework\Data\Form\Element\AbstractElement as Element;
use Magento\Backend\Block\Template\Context as TemplateContext;
use Magento\Framework\Data\Form\Element\Factory as FormElementFactory;
use Magento\Backend\Block\Template;


class Html extends Template
{
    /**
     * @param TemplateContext $context
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param Element $element
     * @return Element
     */
    public function prepareElementHtml(Element $element)
    {
        $config = $this->_getData('config');
        if(isset($config['text'])) {
            if(isset($config['datatype'])) {
                switch ($config['datatype']){
                    case "h3":
                        $html = "<h3>".$config['text']."</h3>";
                        break;
                    default:
                        $html = $config['text'];
                }
            }
            else{
                $html = "N".$config["datatype"];
            }
            $element->setData('after_element_html',$html);
        }

        return $element;
    }
}