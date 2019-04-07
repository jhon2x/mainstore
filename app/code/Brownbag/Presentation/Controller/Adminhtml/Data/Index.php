<?php

namespace Brownbag\Presentation\Controller\Adminhtml\Data;

use Brownbag\Presentation\Controller\Adminhtml\Data;

class Index extends Data
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
