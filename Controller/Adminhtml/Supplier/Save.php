<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;

use FalconMedia\SupplierInventory\Model\Supplier;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{

    protected $dataPersistor;


    /**
     * @param Context                $context
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);

    }//end __construct()


    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /*
            @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
        */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data           = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('supplier_id');

            $model = $this->_objectManager->create(Supplier::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Supplier no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Supplier.'));
                $this->dataPersistor->clear('falconmedia_supplierinventory_supplier');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['supplier_id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Supplier.'));
            }

            $this->dataPersistor->set('falconmedia_supplierinventory_supplier', $data);
            return $resultRedirect->setPath('*/*/edit', ['supplier_id' => $this->getRequest()->getParam('supplier_id')]);
        }//end if

        return $resultRedirect->setPath('*/*/');

    }//end execute()


}//end class
