<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;

use FalconMedia\SupplierInventory\Controller\Adminhtml\Supplier;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Registry;

class NewAction extends Supplier
{

    protected $resultForwardFactory;


    /**
     * @param Context        $context
     * @param Registry       $coreRegistry
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context, $coreRegistry);

    }//end __construct()


    /**
     * New action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /*
            @var \Magento\Framework\Controller\Result\Forward $resultForward
        */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');

    }//end execute()


}//end class
