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
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

class InlineEdit extends Action
{
    /** @var JsonFactory */
    protected $jsonFactory;

    /**
     * @param Context     $context
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Inline edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error      = false;
        $messages   = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error      = true;
            } else {
                foreach (array_keys($postItems) as $modelid) {
                    /*
                        @var \FalconMedia\SupplierInventory\Model\Supplier $model
                    */
                    $model = $this->_objectManager->create(Supplier::class)->load($modelid);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$modelid]));
                        $model->save();
                    } catch (Exception $e) {
                        $messages[] = "[Supplier ID: {$modelid}]  {$e->getMessage()}";
                        $error      = true;
                    }
                }
            }
        }

        return $resultJson->setData(
            [
                'messages' => $messages,
                'error'    => $error,
            ]
        );
    }
}
