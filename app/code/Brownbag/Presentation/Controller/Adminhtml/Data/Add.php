<?php

namespace Brownbag\Presentation\Controller\Adminhtml\Data;

use Brownbag\Presentation\Controller\Adminhtml\Data;

class Add extends Data
{
    
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {   

        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
        
    }
}
