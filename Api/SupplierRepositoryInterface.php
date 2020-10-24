<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SupplierRepositoryInterface
{

    /**
     * Save Supplier
     * @param \FalconMedia\SupplierInventory\Api\Data\SupplierInterface $supplier
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \FalconMedia\SupplierInventory\Api\Data\SupplierInterface $supplier
    );

    /**
     * Retrieve Supplier
     * @param string $supplierId
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($supplierId);

    /**
     * Retrieve Supplier matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Supplier
     * @param \FalconMedia\SupplierInventory\Api\Data\SupplierInterface $supplier
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \FalconMedia\SupplierInventory\Api\Data\SupplierInterface $supplier
    );

    /**
     * Delete Supplier by ID
     * @param string $supplierId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($supplierId);
}

