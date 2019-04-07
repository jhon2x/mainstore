<?php

namespace Brownbag\Presentation\Controller\Adminhtml\Data;

use Brownbag\Presentation\Controller\Adminhtml\Data;

class Edit extends Data
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        
        $repositoryLogId = $this->getRequest()->getParam('repository_log_id');
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Brownbag_Presentation::data')
           ->addBreadcrumb(__('Repositroy Grid Data'), __('Repositroy Grid Data'))
           ->addBreadcrumb(__('Manage Repositroy Grid Data'), __('Manage Repositroy Grid Data'));

        if ($repositoryLogId === null) {
            $resultPage->addBreadcrumb(__('New'), __('New'));
            $resultPage->getConfig()->getTitle()->prepend(__('New'));
        } else {
            $resultPage->addBreadcrumb(__('Edit'), __('Edit'));
            $resultPage->getConfig()->getTitle()->prepend(__('Edit'));
        }

        return $resultPage;
        
    }
}
