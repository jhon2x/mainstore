<?php

namespace Brownbag\Presentation\Controller\Adminhtml\Data;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Message\Manager;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Result\PageFactory;
use Brownbag\Presentation\Api\PresentationRepositoryInterface;
use Brownbag\Presentation\Api\Data\PresentationInterface;
use Brownbag\Presentation\Api\Data\PresentationInterfaceFactory;
use Brownbag\Presentation\Controller\Adminhtml\Data;
use Brownbag\Presentation\Model\Uploader;
use Brownbag\Presentation\Model\UploaderPool;

class Save extends Data
{
    /**
     * @var Manager
     */
    protected $messageManager;

    /**
     * @var PresentationRepositoryInterface
     */
    protected $imageRepository;

    /**
     * @var LoggerRepositoryInterfacefaceFactory
     */
    protected $imageFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var UploaderPool
     */
    protected $uploaderPool;

    /**
     * Save constructor.
     *
     * @param Registry $registry
     * @param ImageRepositoryInterface $imageRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Manager $messageManager
     * @param ImageInterfaceFactory $imageFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param UploaderPool $uploaderPool
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        PresentationRepositoryInterface $imageRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Manager $messageManager,
        PresentationInterfaceFactory $imageFactory,
        DataObjectHelper $dataObjectHelper,
        UploaderPool $uploaderPool,
        Context $context
    ) {
        parent::__construct($registry, $imageRepository, $resultPageFactory, $dateFilter, $context);
        $this->messageManager   = $messageManager;
        $this->imageFactory      = $imageFactory;
        $this->imageRepository   = $imageRepository;
        $this->dataObjectHelper  = $dataObjectHelper;
        $this->uploaderPool = $uploaderPool;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();  

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $this->getRequest()->getParam('repository_log_id');
            if ($id) {
                $model = $this->imageRepository->getById($id);
            } else {
                unset($data['repository_log_id']);
                $model = $this->imageFactory->create();
            }

            try {
                $image = $this->getUploader('image')->uploadFileAndGetName('image', $data);
                $data['image'] = $image;

                $this->dataObjectHelper->populateWithArray($model, $data, ImageInterface::class);
                $this->imageRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved this image.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['repository_log_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving the image:' . $e->getMessage())
                );
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['repository_log_id' => $this->getRequest()->getParam('repository_log_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $type
     * @return Uploader
     * @throws \Exception
     */
    protected function getUploader($type)
    {
        return $this->uploaderPool->getUploader($type);
    }
}
