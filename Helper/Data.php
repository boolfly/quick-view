<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Quick View
 */
namespace Boolfly\QuickView\Helper;

use Boolfly\QuickView\Model\Config;

/**
 * Class Data
 *
 * @package Boolfly\QuickView\Helper
 */
class Data
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Data constructor.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param \Magento\Framework\View\Result\Page $resultPage
     */
    public function prepareLayout($resultPage)
    {
        $layoutXml = $this->getLayoutXml();
        if ($layoutXml) {
            $resultPage->addUpdate($layoutXml);
        }
    }

    /**
     * @return string
     */
    protected function getLayoutXml()
    {
        if (!empty($layoutConfig = $this->getLayoutConfig())) {
            return  '<body>' . implode("\n", $layoutConfig) . '</body>';
        }

        return '';
    }

    /**
     * Get Layout Config
     *
     * @return array
     */
    protected function getLayoutConfig()
    {
        $layout = [];
        if (!$this->config->isShowRelatedProducts()) {
            $layout[] = '<referenceBlock name="catalog.product.related" remove="true"/>';
        }
        if (!$this->config->isShowUpsellProducts()) {
            $layout[] = '<referenceBlock name="product.info.upsell" remove="true"/>';
        }

        return $layout;
    }
}
