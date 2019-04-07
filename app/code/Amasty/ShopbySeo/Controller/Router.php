<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbySeo
 */


namespace Amasty\ShopbySeo\Controller;

use Amasty\ShopbySeo\Helper\Url;
use Amasty\ShopbySeo\Helper\UrlParser;
use Magento\Framework\App\RequestInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\Framework\Module\Manager;
use Magento\Store\Model\ScopeInterface;
use Amasty\ShopbyBase\Helper\Data as BaseData;
use Amasty\ShopbySeo\Helper\Data;

/**
 * Class Router
 * @package Amasty\ShopbySeo\Controller
 */
class Router implements \Magento\Framework\App\RouterInterface
{
    const INDEX_ALIAS       = 1;
    const INDEX_CATEGORY    = 2;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var UrlParser
     */
    protected $urlParser;

    /**
     * @var UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Manager
     */
    protected $moduleManager;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var BaseData
     */
    private $baseHelper;

    /**
     * @var \Amasty\ShopbyBase\Model\AllowedRoute
     */
    private $allowedRoute;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\Registry $registry,
        \Amasty\ShopbyBase\Model\AllowedRoute $allowedRoute,
        UrlParser $urlParser,
        Url $urlHelper,
        UrlFinderInterface $urlFinder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        Manager $moduleManager,
        Data $helper,
        BaseData $baseHelper
    ) {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
        $this->registry = $registry;
        $this->urlHelper = $urlHelper;
        $this->urlParser = $urlParser;
        $this->urlFinder = $urlFinder;
        $this->scopeConfig = $scopeConfig;
        $this->moduleManager = $moduleManager;
        $this->logger = $logger;
        $this->helper = $helper;
        $this->allowedRoute = $allowedRoute;
        $this->baseHelper = $baseHelper;
    }

    /**
     * @param RequestInterface $request
     * @return bool|\Magento\Framework\App\ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $shopbyPageUrl = $this->scopeConfig->getValue('amshopby_root/general/url', ScopeInterface::SCOPE_STORE);

        $identifier = rtrim(
            trim($request->getPathInfo(), '/'),
            $this->scopeConfig->getValue('catalog/seo/category_url_suffix', ScopeInterface::SCOPE_STORE)
        );

        if ($identifier == $shopbyPageUrl) {
            // Forward Shopby
            if ($this->allowedRoute->isRouteAllowed($request)) {
                if (!$result = $this->createSeoRedirect($request, true)) {
                    $request->setModuleName('amshopby')->setControllerName('index')->setActionName('index');
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                    $params = $this->getRequestParams($request);
                    $request->setParams($params);
                    return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
                }

                return $result;
            }
        }

        $identifier = trim($request->getPathInfo(), '/');
        $brandUrlKey = $this->baseHelper->getBrandUrlKey();
        $positionBrandUrlKey = $brandUrlKey ? strpos($identifier, $brandUrlKey) : false;

        if ($positionBrandUrlKey !== false) {
            $lastSymbolBrandKey = $positionBrandUrlKey + iconv_strlen($brandUrlKey);
            $matches[self::INDEX_ALIAS] = substr($identifier, 0, $lastSymbolBrandKey);
            $matches[self::INDEX_CATEGORY] = substr($identifier, $lastSymbolBrandKey + 1);
        } else {
            $posLastValue = strrpos($identifier, "/");
            $matches[self::INDEX_ALIAS] = substr($identifier, 0, $posLastValue);
            $positionFrom = ($posLastValue === false) ? 0 : $posLastValue + 1;
            $matches[self::INDEX_CATEGORY] = substr($identifier, $positionFrom);
        }

        $seoPart = $this->urlHelper->removeCategorySuffix($matches[self::INDEX_CATEGORY]);
        $suffix = $this->scopeConfig
            ->getValue('catalog/seo/category_url_suffix', ScopeInterface::SCOPE_STORE);
        $suffixMoved = $seoPart != $matches[self::INDEX_CATEGORY] || $suffix == '/';
        $regex = $this->helper->getFilterWord() ? '/\/+(' . $this->helper->getFilterWord() . ')/' : '';
        $alias = $regex ? preg_replace($regex, '', $matches[self::INDEX_ALIAS]) : $matches[self::INDEX_ALIAS];

        $fullSeoPart = $this->urlHelper->removeCategorySuffix($identifier);
        $params = $this->urlParser->parseSeoPart($fullSeoPart);
        if (empty($params)) {
            $params = $this->urlParser->parseSeoPart($seoPart);
            if (empty($params)) {
                return false;
            }
        }

        /**
         * for brand pages with key, e.g. /brand/adidas
         */
        $matchedAlias = null;

        /* For regular seo category */
        if (!$matchedAlias) {
            $category = $suffixMoved ? $alias . $suffix : $alias;
            $rewrite = $this->urlFinder->findOneByData([
                UrlRewrite::REQUEST_PATH => $category,
            ]);

            if ($rewrite) {
                $matchedAlias = $category;
            }
        }

        if ($matchedAlias) {
            $this->registry->unregister(BaseData::SHOPBY_SEO_PARSED_PARAMS);
            $this->registry->register(BaseData::SHOPBY_SEO_PARSED_PARAMS, $params);
            $request->setParams($params);
            $request->setPathInfo($matchedAlias);
            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        }

        $this->registry->unregister(BaseData::SHOPBY_SEO_PARSED_PARAMS) ;
        $this->registry->register(BaseData::SHOPBY_SEO_PARSED_PARAMS, $params);

        if ($this->allowedRoute->isRouteAllowed($request)) {
            $request->setModuleName('amshopby')->setControllerName('index')->setActionName('index');
            $shopbyPageUrl = $this->scopeConfig->getValue('amshopby_root/general/url', ScopeInterface::SCOPE_STORE);
            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $shopbyPageUrl);
            $params = array_merge($params, $request->getParams());
            $request->setParams($params);

            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        }

        return false;
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    private function getRequestParams(RequestInterface $request)
    {
        $params = array_merge($this->parseAmShopByParams($request), $request->getParams());

        return $params;
    }

    public function parseAmShopByParams($request)
    {
        $params = [];
        if ($request->getParam('amshopby')) {
            foreach ($request->getParams() as $key => $values) {
                if ($key == 'amshopby') {
                    foreach ($values as $key => $item) {
                        $params[$key] = implode(",", $item);
                    }
                } else {
                    $params[$key] = $values;
                }
            }
        }

        return $params;
    }

    /**
     * @param RequestInterface $request
     * @param bool $brandRedirect
     * @return bool|\Magento\Framework\App\ActionInterface
     */
    protected function createSeoRedirect(RequestInterface $request, $brandRedirect = false)
    {
        $url = ($this->urlHelper->isSeoUrlEnabled() || $brandRedirect) ?
            $this->urlHelper->seofyUrl($request->getUri()->toString()) : $request->getUri()->toString();

        //Hardcoded fix for adding suffix to all-products page
        $shopbyPageUrl = $this->scopeConfig->getValue('amshopby_root/general/url', ScopeInterface::SCOPE_STORE);
        $identifier = rtrim(
            trim($request->getPathInfo(), '/'),
            $this->scopeConfig->getValue('catalog/seo/category_url_suffix', ScopeInterface::SCOPE_STORE)
        );
        if ($this->urlHelper->isAddSuffixToShopby() && $identifier == $shopbyPageUrl) {
            $suffix = $this->scopeConfig->getValue('catalog/seo/category_url_suffix', ScopeInterface::SCOPE_STORE);
            if (strpos($url, $suffix) === false) {
                if (strpos($url, '?') === false) {
                    $url .= $suffix;
                } else {
                    $url = str_replace('?', $suffix . '?', $url);
                }

            }
        }

        if (strcmp($url, $request->getUri()->toString()) === 0) {
            return false;
        }

        $this->_response->setRedirect($url, \Zend\Http\Response::STATUS_CODE_301);
        $request->setDispatched(true);

        return $this->actionFactory->create(\Magento\Framework\App\Action\Redirect::class);
    }
}
