<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Quick View
 */
namespace Boolfly\QuickView\Plugin\Catalog\Block;

use Boolfly\QuickView\Model\Config;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\UrlInterface;

/**
 * Class ProductPlugin
 *
 * @package Boolfly\QuickView\Plugin\Catalog\Block\Product
 * @see \Magento\Catalog\Block\Product\AbstractProduct
 */
class ProductPlugin
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Config
     */
    private $config;

    /**
     * ProductPlugin constructor.
     *
     * @param UrlInterface $urlBuilder
     * @param Config       $config
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Config $config
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->config     = $config;
    }

    /**
     * Adding Quick View Url
     *
     * @param $subject
     * @param $result
     * @param Product $product
     * @return string
     */
    public function afterGetProductDetailsHtml(
        AbstractProduct $subject,
        $result,
        Product $product
    ) {
        if (is_string($result) && $this->config->isEnable()) {
            return $result . $this->getQuickViewHtml($product);
        }

        return $result;
    }

    /**
     * Get Quick View Html
     *
     * @param Product $product
     * @return string
     */
    protected function getQuickViewHtml(Product $product)
    {
        return '<span data-role="quick-view" data-url="'. $this->getQuickViewUrl($product) .'"></span>';
    }

    /**
     * Get Quick View Url
     *
     * @param Product $product
     * @return string
     */
    protected function getQuickViewUrl(Product $product)
    {
        return $this->urlBuilder->getUrl('quickview/product/view', ['id' => $product->getId()]);
    }
}
