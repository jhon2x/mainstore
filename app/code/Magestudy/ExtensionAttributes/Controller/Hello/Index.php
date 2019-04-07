<?php

namespace Magestudy\ExtensionAttributes\Controller\Hello;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/** This class is not required for use Extension Attributes and created only for sample */
class Index extends Action
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param Context $context
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context);
        $this->customerRepository = $customerRepository;
    }

    /**
     * URL: .../index.php/ExtensionAttribute/hello/index
     */
    public function execute()
    {
        try {
            $customer = $this->customerRepository->getById(1);
            echo '<pre>';
            // var_dump($customer->getExtensionAttributes());
            print_r($customer->getExtensionAttributes());

            echo "<h1>Customer</h1>";
            print_r($customer->getEmail());

        } catch (\Throwable $throwable) {
            echo $throwable->getMessage();
        }

        die;
    }
}