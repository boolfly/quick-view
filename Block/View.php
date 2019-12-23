<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Quick View
 */
namespace Boolfly\QuickView\Block;

use Boolfly\QuickView\Model\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * View block
 *
 * @api
 * @since 100.0.2
 */
class View extends Template
{
    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var Config
     */
    private $config;

    /**
     * View constructor.
     *
     * @param Template\Context $context
     * @param Json             $serializer
     * @param Config           $config
     * @param array            $data
     */
    public function __construct(
        Template\Context $context,
        Json $serializer,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->serializer = $serializer;
        $this->config     = $config;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->config->isEnable()) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * Get Config
     *
     * @return boolean|string
     */
    public function getJsonData()
    {
        return $this->serializer->serialize([
            'quickViewText' => $this->config->getLabel(),
            'loadingHtml' => $this->getLoadingHtml()
        ]);
    }

    /**
     * Get loading html
     *
     * @return string
     */
    public function getLoadingHtml()
    {
        return '<img src="' . $this->getViewFileUrl('images/loader-1.gif')
            . '" atl="' . __('Loading...') .'"/>';
    }
}
