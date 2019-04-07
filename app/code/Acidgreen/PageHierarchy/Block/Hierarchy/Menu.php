<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Acidgreen\PageHierarchy\Block\Hierarchy;

use \Magento\VersionsCms\Block\Hierarchy\Menu as Cms_Menu;

class Menu extends Cms_Menu
{

	protected $_cmsPage;

	 /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\VersionsCms\Model\Hierarchy\NodeFactory $nodeFactory
     * @param array $data
     * @param \Magento\VersionsCms\Model\CurrentNodeResolverInterface $currentNodeResolver
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\VersionsCms\Model\Hierarchy\NodeFactory $nodeFactory,
        array $data = [],
        \Magento\VersionsCms\Model\CurrentNodeResolverInterface $currentNodeResolver = null,
        \Magento\Cms\Model\Page $cmsPage
    ) {
        $this->_cmsPage = $cmsPage;
        parent::__construct($context, $registry, $nodeFactory, $data, $currentNodeResolver);
    }

	 /**
     * Recursive draw menu
     *
     * @param array $tree
     * @param int $parentNodeId
     * @return string
     */
    public function drawMenu(array $tree, $parentNodeId = 0)
    {   
    	$nodeModel = $this->_nodeFactory->create();
   		$html = '<ul class="cms-menu">';

   		$base_tree = array_keys($tree[0]);
   		
   		foreach($base_tree as $nodeId){
   			$node = $nodeModel->load($nodeId);
   			$html .= '<li '.($this->_cmsPage->getId() == $node->getPageId() ? 'class="current"' : '').' ><a href="'.$this->getUrl($node->getRequestUrl()).'">'.__($node->getLabel()).'</a>';
   			$html .= $this->getChildren($nodeId);
   			$html .= '</li>';
   			$this->_totalMenuNodes++;
   		}

   		$html .= '</ul>';
   		return $html;
    }

    private function getChildren($nodeId){
    	
    	$nodeModel = $this->_nodeFactory->create();
    	$childNodesCollection = $nodeModel->getCollection()->addFieldToFilter('parent_node_id',array('eq' => $nodeId));; //Get Collection of module data
        $children = $childNodesCollection->getData();
        if(count($children) > 0) {
        	$html = '<ul>';
        		foreach($children as $childNode) {
        			$html .= '<li '.($this->_cmsPage->getId() == $childNode['page_id'] ? 'class="current"' : '').'><a href="'.$this->getUrl($childNode['request_url']).'">'.__($childNode['label']).'</a>';
		   			$html .= $this->getChildren($childNode['node_id']);
		   			$html .= '</li>';
        		}
        	$html .= '</ul>';
        } else 
        	$html = '';
        return $html;
    }
}