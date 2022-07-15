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
    public const ADMIN_RESOURCE = 'FalconMedia_SupplierInventory::top_level';

    /** @var Registry */
    protected $coreRegistry;

    /**
     * @param Context  $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param  Page $resultPage
     *
     * @return Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(
                __('FalconMedia'),
                __('FalconMedia')
            )->addBreadcrumb(
                __('Supplier'),
                __('Supplier')
            );

        return $resultPage;
    }
}
