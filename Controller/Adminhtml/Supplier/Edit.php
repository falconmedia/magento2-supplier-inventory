<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;

use FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Supplier
{

    protected $resultPageFactory;


    /**
     * @param Context     $context
     * @param Registry    $coreRegistry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);

    }//end __construct()


    /**
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id    = $this->getRequest()->getParam('supplier_id');
        $model = $this->_objectManager->create(\FalconMedia\SupplierInventory\Model\Supplier::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Supplier no longer exists.'));
                /*
                    @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
                */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('falconmedia_supplierinventory_supplier', $model);

        // 3. Build edit form
        /*
            @var \Magento\Backend\Model\View\Result\Page $resultPage
        */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Supplier') : __('New Supplier'),
            $id ? __('Edit Supplier') : __('New Supplier')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Suppliers'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Supplier %1', $model->getId()) : __('New Supplier'));
        return $resultPage;

    }//end execute()


}//end class
