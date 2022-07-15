<?php

/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;

use Exception;
use FalconMedia\SupplierInventory\Model\Supplier;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class Save extends Action
{
    /** @var DataPersistorInterface */
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
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var Request $request */
        $request = $this->getRequest();
        $data    = $request->getPostValue();

        if ($data) {
            $id = $request->getParam('supplier_id');

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

                if ($request->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['supplier_id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Supplier.'));
            }

            $this->dataPersistor->set('falconmedia_supplierinventory_supplier', $data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['supplier_id' => $request->getParam('supplier_id')]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
