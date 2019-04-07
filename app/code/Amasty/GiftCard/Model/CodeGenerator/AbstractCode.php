<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\CodeGenerator;

use Magento\Framework\Exception\LocalizedException;

abstract class AbstractCode
{
    const MAX_ATTEMPTS = 5;
    const MAX_QTY = 10000;

    protected $_mask;
    protected $_template = '';

    protected $_countMasksInTemplate;
    protected $_countValues;

    protected $_maxQty;
    private $listMaskInTemplate;

    const DIGITS = [2, 3, 4, 5, 6, 7, 8, 9];

    const LETTERS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M',
            'N', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    public function __construct()
    {
        $this->_mask = [
            // no "0" and "1" as they are confusing
            '{D}'  => self::DIGITS,
            // no I, Q and O as they are confusing
            '{L}'  => self::LETTERS,
        ];
    }

    /**
     * @param $qty
     * @throws LocalizedException
     */
    public function generate($qty)
    {
        $this->_validate($qty);
        for ($i = 0; $i<$qty; $i++) {
            $attempts = 0;
            do {
                $code = $this->_generateCode();
                $attempts++;
            } while ($this->exist($code) && $attempts < self::MAX_ATTEMPTS);
            if ($attempts == self::MAX_ATTEMPTS) {
                throw new LocalizedException(
                    __(
                        "Maximum number of code combinations for the current template achieved is %1",
                        self::MAX_ATTEMPTS
                    )
                );
            }
            $this->_preprocessCode($code);
        }

        $this->_afterGenerate();
    }

    /**
     * @param $qty
     * @throws LocalizedException
     */
    protected function _validate($qty)
    {
        if (false === strpos($this->_template, '{L}') && false === strpos($this->_template, '{D}')) {
            $msg = __('Please add {L} or {D} placeholders into the template "%1"', $this->_template);
            throw new LocalizedException($msg);
        }

        if ($qty > $this->getMaxQty()) {
            throw new LocalizedException(__('Maximum number of code combinations for the current template is %1, 
            please update Quantity field accordingly.', $this->getMaxQty()));
        }

        if ($qty > self::MAX_QTY) {
            throw new LocalizedException(__('Over time, you can generate no more than %1 codes.', self::MAX_QTY));
        }
    }

    /**
     * @return string
     */
    protected function _generateCode()
    {
        $code = $this->_template;
        foreach ($this->listMaskInTemplate as $j => $maskSymbol) {
            $count = count($this->_mask[$maskSymbol]);
            if (!$count) {
                $this->setMask($maskSymbol);
            }
            $arrayWithCodes = $this->_mask[$maskSymbol];
            $key = array_rand($arrayWithCodes);
            $code = preg_replace('/' . preg_quote($maskSymbol, '/') . '/', $arrayWithCodes[$key], $code, 1);
            unset($arrayWithCodes[$key]);
            $this->_mask[$maskSymbol] = $arrayWithCodes;
        }

        return $code;
    }

    /**
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->_template = $template;

        $listMask = [];

        foreach ($this->_mask as $placeholder => $values) {
            $this->_countMasksInTemplate[$placeholder] = substr_count($this->_template, $placeholder);
            $this->_countValues[$placeholder] = count($values);
            $listMask[] = preg_quote($placeholder, '/');
        }

        $listMaskInTemplate = [];
        $regExpTemplate = implode('|', $listMask);
        if (preg_match_all('/'. $regExpTemplate . '/', $this->_template, $matches)) {
            $listMaskInTemplate = $matches[0];
        }

        $this->listMaskInTemplate = $listMaskInTemplate;

        $this->_calcMaxQty();

        return $this;
    }

    protected function _calcMaxQty()
    {
        $maxQtyByTemplate   = $this->_maxQtyByTemplate();
        $existQtyByTemplate = $this->_getExistQtyByTemplate();
        $this->_maxQty      = $maxQtyByTemplate - $existQtyByTemplate;
    }

    /**
     * @return int|number
     */
    protected function _maxQtyByTemplate()
    {
        $maxQty = 1;
        foreach ($this->_mask as $placeholder => $values) {
            $maxQty *= pow($this->_countValues[$placeholder], $this->_countMasksInTemplate[$placeholder]);
        }

        return $maxQty;
    }

    protected function _getExistQtyByTemplate()
    {
        return 0;
    }

    protected function _preprocessCode(&$code)
    {

    }

    protected function _afterGenerate()
    {

    }

    public function getMaxQty()
    {
        return $this->_maxQty;
    }

    public function exist($code)
    {
        return false;
    }

    private function setMask($type)
    {
        if ($type === '{L}') {
            $this->_mask[$type] = self::LETTERS;
        } else {
            $this->_mask[$type] = self::DIGITS;
        }
    }
}
