<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbySeo
 */


namespace Amasty\ShopbySeo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class UrlParser extends AbstractHelper
{
    /**
     * @var  Data
     */
    protected $seoHelper;

    /**
     * @var string
     */
    protected $aliasDelimiter;

    public function __construct(
        Context $context,
        Data $seoHelper
    ) {
        parent::__construct($context);
        $this->seoHelper = $seoHelper;
        $this->aliasDelimiter = $context->getScopeConfig()->getValue('amasty_shopby_seo/url/option_separator');
    }

    /**
     * @param $seoPart
     * @return array
     */
    public function parseSeoPart($seoPart)
    {
        $seoPart = str_replace('/', $this->aliasDelimiter, $seoPart);
        if ($this->aliasDelimiter == $this->seoHelper->getSpecialChar()) {
            $aliases = $this->parseAliasesRecursive($seoPart);
            return $this->parseAliasesOldAlgorythn($aliases, $seoPart);
        }

        $aliases = $this->getAliases($seoPart);
        return $this->parseAliases($aliases);
    }

    /**
     * @param $seoPart
     * @return array
     */
    public function getAliases($seoPart)
    {
        $aliases = explode($this->aliasDelimiter, $seoPart);
        return $aliases;
    }

    /**
     * @param $seoPart
     * @return array
     */
    private function parseAliasesRecursive($seoPart)
    {
        if (!is_array($seoPart)) {
            $seoPart = explode($this->aliasDelimiter, $seoPart);
        }
        $aliases = [];
        $aliasGroup = [];
        if (empty($seoPart)) {
            return $aliases;
        }
        for ($i = count($seoPart) - 1; $i >= 0 ; $i--) {
            $aliasGroup[] = implode($this->aliasDelimiter, array_slice($seoPart, 0, $i + 1));
        }
        $aliases[] = $aliasGroup;
        array_shift($seoPart);
        return array_merge($aliases, $this->parseAliasesRecursive($seoPart));
    }

    /**
     * @param array $aliases
     * @return array
     */
    protected function parseAliases($aliases)
    {
        $attributeOptionsData = $this->seoHelper->getOptionsSeoData();
        $filterWord = $this->seoHelper->getFilterWord();
        $paramsCount = 0;
        $params = [];
        $parsedAliases = [];
        foreach ($aliases as $key => $currentAlias) {
            if (in_array($currentAlias, array_keys($attributeOptionsData)) || $currentAlias == $filterWord) {
                unset($aliases[$key]);
                continue;
            }
            foreach ($attributeOptionsData as $attributeCode => $optionsData) {
                foreach ($optionsData as $optionId => $alias) {
                    if ($alias === $currentAlias) {
                        $parsedAliases[] = $currentAlias;
                        $params = $this->addParsedOptionToParams($optionId, $attributeCode, $params);
                        $paramsCount++;
                        continue 3;
                    }
                }
            }
        }

        return $paramsCount == count($aliases) ? $params : [];
    }

    /**
     * @param array $aliases
     * @param string $seoPart
     * @return array
     */
    protected function parseAliasesOldAlgorythn($aliases, $seoPart)
    {
        $attributeOptionsData = $this->seoHelper->getOptionsSeoData();
        $filterWord = $this->seoHelper->getFilterWord();
        $paramsCount = 0;
        $params = [];
        $parsedAliases = [];
        foreach ($aliases as $groupKey => $aliasGroup) {
            foreach ($aliasGroup as $key => $currentAlias){
                foreach ($attributeOptionsData as $attributeCode => $optionsData) {
                    foreach ($optionsData as $optionId => $alias) {
                        if ($alias === $currentAlias) {
                            $parsedAliases[] = $currentAlias;
                            $params = $this->addParsedOptionToParams($optionId, $attributeCode, $params);
                            $paramsCount++;
                            continue 4;
                        }
                    }
                }

            }
        }

        $requestedAliases = explode($this->aliasDelimiter, $seoPart);
        foreach($requestedAliases as $key => $alias) {
            if (in_array($alias, array_keys($attributeOptionsData)) || $alias == $filterWord) {
                unset($requestedAliases[$key]);
                continue;
            }
        }
        return implode($this->aliasDelimiter, $requestedAliases)  == implode($this->aliasDelimiter, $parsedAliases)
            ? $params : [];
    }

    /**
     * @param $value
     * @param $paramName
     * @param $params
     * @return mixed
     */
    protected function addParsedOptionToParams($value, $paramName, $params)
    {
        if (array_key_exists($paramName, $params)) {
            $params[$paramName] .= ',' . $value;
        } else {
            $params[$paramName] = '' . $value;
        }

        return $params;
    }
}
