<?php
/**
 * Venustheme
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Venustheme.com license that is
 * available through the world-wide-web at this URL:
 * http://www.venustheme.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Venustheme
 * @package    Ves_Themesettings
 * @copyright  Copyright (c) 2014 Venustheme (http://www.venustheme.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
 */
namespace Ves\All\Observer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class ValidKey implements ObserverInterface
{
	public function __construct()
	{
	}
	/**
     * Add coupon's rule name to order data
     *
     * @param EventObserver $observer
     * @return $this
     */
	public function execute(EventObserver $observer)
	{
		$groups = $observer->getEvent()->getPostValue();
		/*echo '<pre>';
		print_r($groups);
		echo '</pre>';
		die('test');
		die("abc");
		$website = $observer->getEvent()->getWebsite();
		$store = $observer->getEvent()->getStore();
		$this->_generator->generateCss($website, $store);*/
	}
}

