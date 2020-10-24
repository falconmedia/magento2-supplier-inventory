<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Api\Data;

interface SupplierSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Supplier list.
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface[]
     */
    public function getItems();

    /**
     * Set supplier_name list.
     * @param \FalconMedia\SupplierInventory\Api\Data\SupplierInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

