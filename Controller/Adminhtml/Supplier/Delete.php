<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;

use FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;
use Magento\Framework\Controller\ResultInterface;

class Delete extends Supplier
{


    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /*
            @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
        */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('supplier_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\FalconMedia\SupplierInventory\Model\Supplier::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Supplier.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['supplier_id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Supplier to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');

    }//end execute()


}//end class
