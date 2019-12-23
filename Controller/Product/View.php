<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Quick View
 */
namespace Boolfly\QuickView\Controller\Product;

use Boolfly\QuickView\Helper\Data;
use Magento\Catalog\Controller\Product;
use Magento\Catalog\Helper\Product\View as HelperProductView;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;

/**
 * Class View
 *
 * @package Boolfly\QuickView\Controller\Product
 */
class View extends Product
{
    /**
     * @var HelperProductView
     */
    protected $viewHelper;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Data
     */
    private $helperData;
    /**
     * @var Context
     */
    private $context;

    /**
     * View constructor.
     *
     * @param Context           $context
     * @param HelperProductView $viewHelper
     * @param ForwardFactory    $resultForwardFactory
     * @param LoggerInterface   $logger
     * @param Data              $helperData
     * @param PageFactory       $pageFactory
     */
    public function __construct(
        Context $context,
        HelperProductView $viewHelper,
        ForwardFactory $resultForwardFactory,
        LoggerInterface $logger,
        Data $helperData,
        PageFactory $pageFactory
    ) {
        $this->viewHelper           = $viewHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->pageFactory          = $pageFactory;
        parent::__construct($context);
        $this->logger     = $logger;
        $this->helperData = $helperData;
        $this->context    = $context;
    }

    /**
     * Index Action
     *
     * @return \Magento\Framework\View\Result\Page | \Magento\Framework\Controller\Result\Forward
     */
    public function execute()
    {
        try {
            $categoryId     = (int) $this->getRequest()->getParam('category', false);
            $productId      = (int) $this->getRequest()->getParam('id');
            $specifyOptions = $this->getRequest()->getParam('options');
            $params         = new DataObject();
            $params->setCategoryId($categoryId);
            $params->setSpecifyOptions($specifyOptions);
            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->pageFactory->create(false, [
                'isIsolated' => true,
                'template' => 'Boolfly_QuickView::root.phtml'
            ]);
            $resultPage->addDefaultHandle();
            $this->helperData->prepareLayout($resultPage);
            $this->viewHelper->prepareAndRender($resultPage, $productId, $this, $params);
            return $resultPage;
        } catch (NoSuchEntityException $e) {
            return $this->noProductRedirect();
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');

            return $resultForward;
        }
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\Result\Forward
     */
    protected function noProductRedirect()
    {
        $store = $this->getRequest()->getQuery('store');
        if (isset($store) && !$this->getResponse()->isRedirect()) {
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('');
        } elseif (!$this->getResponse()->isRedirect()) {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');

            return $resultForward;
        }
    }
}
