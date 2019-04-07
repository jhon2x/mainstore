<?php
namespace Acidgreen\Theme\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class HeroBanner extends Template implements BlockInterface
{
    protected $_template = "widget/hero-banner.phtml";
}