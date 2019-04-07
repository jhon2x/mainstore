<?php 

namespace Brownbag\Presentation\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

use Magento\Framework\Model\AbstractModel;
use Brownbag\Presentation\Model\ResourceModel\Presentation as ResourceModel;
use Brownbag\Presentation\Api\Data\PresentationInterface;

use Brownbag\Presentation\Model\UploaderPool;

class Presentation extends AbstractModel implements PresentationInterface {

	/**
	 * Cache tag
	 */
	const CACHE_TAG = 'brownbag_ui_component';

	/**
	 * @var UploaderPool
	 */
	protected $uploaderPool;

	/**
	 * Sliders constructor.
	 * @param Context $context
	 * @param Registry $registry
	 * @param UploaderPool $uploaderPool
	 * @param AbstractResource|null $resource
	 * @param AbstractDb|null $resourceCollection
	 * @param array $data
	 */
	public function __construct(
	    Context $context,
	    Registry $registry,
	    UploaderPool $uploaderPool,
	    AbstractResource $resource = null,
	    AbstractDb $resourceCollection = null,
	    array $data = []
	) {
	    parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	    $this->uploaderPool    = $uploaderPool;
	}

	/**
	 * Model construct that should be used for object initialization
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init(ResourceModel::class);
	}

	/**
	 * Get cache identities
	 *
	 * @return array
	 */
	public function getIdentities()
	{
	    return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function setId($id){
		$this->setData(PresentationInterface::ID, $id);
	}

	public function getId(){
		return $this->getData(PresentationInterface::ID);
	}

	/**
	 * @param string $date
	 */
	public function setRepositoryDate($date)
	{
	    $this->setData(PresentationInterface::DATE, $date);
	}

	/**
	 * @return string
	 */
	public function getRepositoryDate()
	{
	    return $this->getData(PresentationInterface::DATE);
	}

	/**
	 * @param string $content
	 */
	public function setRepositoryContent($content)
	{
	    $this->setData(PresentationInterface::CONTENT, $content);
	}

	/**
	 * @return string
	 */
	public function getRepositoryContent()
	{
	    return $this->getData(PresentationInterface::CONTENT);
	}

	/**
	 * Get image
	 *
	 * @return string
	 */
	public function getImage()
	{
	    return $this->getData(PresentationInterface::IMAGE);
	}

	/**
	 * Set image
	 *
	 * @param $image
	 * @return $this
	 */
	public function setImage($image)
	{
	    return $this->setData(PresentationInterface::IMAGE, $image);
	}

	/**
	 * Get image URL
	 *
	 * @return bool|string
	 * @throws LocalizedException
	 */
	public function getImageUrl()
	{
	    $url = false;
	    $image = $this->getImage();
	    if ($image) {
	        if (is_string($image)) {
	            $uploader = $this->uploaderPool->getUploader('image');
	            $url = $uploader->getBaseUrl().$uploader->getBasePath().$image;
	        } else {
	            throw new LocalizedException(
	                __('Something went wrong while getting the image url.')
	            );
	        }
	    }
	    return $url;
	}

}