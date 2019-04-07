<?php
namespace Acidgreen\Theme\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class TwoColBanner extends Template implements BlockInterface
{
    protected $_template = "widget/two-col-banner.phtml";
}