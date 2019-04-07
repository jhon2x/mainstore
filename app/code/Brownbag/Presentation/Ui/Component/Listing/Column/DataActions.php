<?php

namespace Brownbag\Presentation\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class DataActions extends Column
{
    const URL_PATH_EDIT = 'brownbagpresentation/data/edit';
    const URL_PATH_DELETE = 'brownbagpresentation/data/delete';
    const URL_PATH_RESUME = 'brownbagpresentation/data/resume';

    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['repository_log_id'])) {

                   
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'repository_log_id' => $item['repository_log_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'repository_log_id' => $item['repository_log_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete "${ $.$data.data_title }"'),
                                'message' => __('Are you sure you want to delete the Data: "${ $.$data.data_title }"?')
                            ]
                        ],
                        'resume' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_RESUME,
                                [
                                    'repository_log_id' => $item['repository_log_id']
                                ]
                            ),
                            'label' => __('Resume'),
                            'confirm' => [
                                'title' => __('Resume "${ $.$data.data_title }"'),
                                'message' => __('Are you sure you want to resume the Data: "${ $.$data.data_title }"?')
                            ]
                        ]
                    ];
                }
            }
        }


        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/templog.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        $logger->info($dataSource['data']['items']);
        
        return $dataSource;
    }
}
