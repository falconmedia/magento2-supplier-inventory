<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;

abstract class Supplier extends Action
{

    protected $_coreRegistry;
    const ADMIN_RESOURCE = 'FalconMedia_SupplierInventory::top_level';


    /**
     * @param Context  $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);

    }//end __construct()


    /**
     * Init page
     *
     * @param  Page $resultPage
     * @return Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)->addBreadcrumb(__('FalconMedia'), __('FalconMedia'))->addBreadcrumb(__('Supplier'), __('Supplier'));
        return $resultPage;

    }//end initPage()


}//end class
