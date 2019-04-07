<?php

namespace Brownbag\Presentation\Ui\DataProvider\Image\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Brownbag\Presentation\Model\ResourceModel\Presentation\CollectionFactory;

class ImageData implements ModifierInterface
{
    /**
     * @var \Brownbag\Presentation\Model\ResourceModel\Presentation\Collection
     */
    protected $collection;

    /**
     * @param CollectionFactory $presentationCollectionFactory
     */
    public function __construct(
        CollectionFactory $presentationCollectionFactory
    ) {
        $this->collection = $presentationCollectionFactory->create();
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function modifyData(array $data)
    {
        $items = $this->collection->getItems();

        /** @var $image \Brownbag\Presentation\Model\Presentation */
        foreach ($items as $image) {
            $_data = $image->getData();
        
            if (isset($_data['image'])) {
                $imageArr = [];
                $imageArr[0]['name'] = 'Image';
                $imageArr[0]['url'] = $image->getImageUrl();
                $_data['image'] = $imageArr;
            }
            $image->setData($_data);
            $data[$image->getId()] = $_data;
        }
        return $data;
    }
}
