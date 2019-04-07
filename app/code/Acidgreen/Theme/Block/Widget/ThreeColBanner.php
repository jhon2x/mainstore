<?php
namespace Acidgreen\Theme\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class ThreeColBanner extends Template implements BlockInterface
{
    protected $_template = "widget/three-col-banner.phtml";
}