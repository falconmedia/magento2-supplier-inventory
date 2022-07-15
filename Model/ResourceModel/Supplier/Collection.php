<?php

/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Model\ResourceModel\Supplier;

use FalconMedia\SupplierInventory\Model\ResourceModel\Supplier;
use FalconMedia\SupplierInventory\Model\Supplier as SupplierModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    // phpcs:ignore
    protected $_idFieldName = 'supplier_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(SupplierModel::class, Supplier::class);
    }
}
