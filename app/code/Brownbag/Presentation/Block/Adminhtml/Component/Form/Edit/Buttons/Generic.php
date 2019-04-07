<?php

namespace Brownbag\Presentation\Block\Adminhtml\Component\Form\Edit\Buttons;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Brownbag\Presentation\Api\PresentationRepositoryInterface;

class Generic
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var PresentationRepositoryInterface
     */
    protected $presentationRepository;

    /**
     * @param Context $context
     * @param PresentationRepositoryInterface $presentationRepository
     */
    public function __construct(
        Context $context,
        PresentationRepositoryInterface $presentationRepository
    ) {
        $this->context = $context;
        $this->presentationRepository = $presentationRepository;
    }

    /**
     * Return Repository Log ID
     *
     * @return int|null
     */
    public function getRepositoryLogId()
    {
        try {
            return $this->presentationRepository->getById(
                $this->context->getRequest()->getParam('repository_log_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
