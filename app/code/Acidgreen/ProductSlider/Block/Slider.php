<?php
namespace Acidgreen\ProductSlider\Block;

use Magento\Catalog\Model\Product;

class Slider extends \Magento\Framework\View\Element\Template
{


    protected $config;
    protected $categoryFactory;
    protected $logger;
    protected $helper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Acidgreen\ProductSlider\Helper\Data $config,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Image $helper,
        array $data = []
    ){
        $this->config = $config;
        $this->categoryFactory = $categoryFactory;
        $this->logger = $logger;
        $this->helper = $helper;
        parent::__construct($context);
    }


    /***
     * Get confirm value from Backend
     *
     * @param string $code
     * @param integer $storeId
     * @return string
     */
    public function getConfig($code,$storeId = null)
    {
        return $this->config->getConfig("prodslider/".$code,$storeId);
    }


    /***
     * Write debug message
     *
     * @param $message
     */
    public function writeLog($message)
    {
        if($this->getConfig("debug")) {
            $this->logger->info($message);
        }
    }

    /***
     * Get product image
     *
     * @param Product $product
     * @return string
     */
    public function getImage($product)
    {
        $image = $this->helper->init($product,"related_products_list");
        return $image->getUrl();

    }

    /***
     * Get product collection
     *
     * @return mixed
     */
    public function getProducts()
    {
        try{
            $categoryId = $this->getCategoryId();
            $limit = (int) $this->getLimit();
            $limit = $limit > 0? $limit : 10;

            if($categoryId) {

                // TODO: Filter products by status enable and stock
                $category = $this->categoryFactory->create()->load($categoryId);
                $products = $category->getProductCollection()
                    ->addAttributeToSelect('*')
                    ->setPageSize($limit);

               return $products;
            }
        }
        catch(\Throwable $e){
            $this->writeLog($e->getMessage());
        }
    }





}