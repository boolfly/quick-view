<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Quick View
 */
namespace Boolfly\QuickView\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package Boolfly\QuickView\Model
 */
class Config
{
    /**@#%
     * @const
     */
    const XML_PATH_QUICK_VIEW_ENABLE  = 'quickview/settings/enable';
    const XML_PATH_QUICK_VIEW_LABEL   = 'quickview/settings/label';
    const XML_PATH_SHOW_RELATED_BLOCK = 'quickview/settings/related';
    const XML_PATH_SHOW_UPSELL_BLOCK  = 'quickview/settings/upsell';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Configuration
     *
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Check Is Enable
     *
     * @param $path
     * @return boolean
     */
    protected function isEnableConfig($path)
    {
        return $this->scopeConfig->isSetFlag($path, ScopeInterface::SCOPE_STORES);
    }

    /**
     * Enable Quick View
     *
     * @return boolean
     */
    public function isEnable()
    {
        return $this->isEnableConfig(self::XML_PATH_QUICK_VIEW_ENABLE);
    }

    /**
     * Get Label
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->getConfig(self::XML_PATH_QUICK_VIEW_LABEL);
    }

    /**
     * Show Related Product
     *
     * @return boolean
     */
    public function isShowRelatedProducts()
    {
        return $this->isEnableConfig(self::XML_PATH_SHOW_RELATED_BLOCK);
    }

    /**
     * Show Upsell Block
     *
     * @return boolean
     */
    public function isShowUpsellProducts()
    {
        return $this->isEnableConfig(self::XML_PATH_SHOW_UPSELL_BLOCK);
    }
}
