<?php

namespace Brownbag\Presentation\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Brownbag\Presentation\Api\PresentationRepositoryInterface;

abstract class Data extends Action
{
    /**
     * @var string
     */
    const ACTION_RESOURCE = 'Brownbag_Presentation::image';

    /**
     * Presentation repository
     *
     * @var PresentationRepositoryInterface
     */
    protected $presentationRepository;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Result Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Date filter
     *
     * @var Date
     */
    protected $dateFilter;


    protected $resultForwardFactory;

    /**
     * Sliders constructor.
     *
     * @param Registry $registry
     * @param PresentationRepositoryInterface $presentationRepository
     * @param PageFactory $resultPageFactory
     * @param Date $dateFilter
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        PresentationRepositoryInterface $presentationRepository,
        PageFactory $resultPageFactory,
        Date $dateFilter,
        Context $context
    ) {
        parent::__construct($context);
        $this->coreRegistry         = $registry;
        $this->presentationRepository      = $presentationRepository;
        $this->resultPageFactory    = $resultPageFactory;
        $this->dateFilter = $dateFilter;
    }
}
