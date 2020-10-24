<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Api\Data;

interface SupplierInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const SUPPLIER_ID = 'supplier_id';
    const SUPPLIER_FEED_SKU_FIELD = 'supplier_feed_sku_field';
    const SUPPLIER_FEED_STOCK_FIELD = 'supplier_feed_stock_field';
    const SUPPLIER_NAME = 'supplier_name';
    const SUPPLIER_SHIPPING_DAYS = 'supplier_shipping_days';
    const SUPPLIER_FEED_SEPARATOR = 'supplier_feed_separator';
    const SUPPLIER_FEED_URL = 'supplier_feed_url';

    /**
     * Get supplier_id
     * @return string|null
     */
    public function getSupplierId();

    /**
     * Set supplier_id
     * @param string $supplierId
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierId($supplierId);

    /**
     * Get supplier_name
     * @return string|null
     */
    public function getSupplierName();

    /**
     * Set supplier_name
     * @param string $supplierName
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierName($supplierName);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \FalconMedia\SupplierInventory\Api\Data\SupplierExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \FalconMedia\SupplierInventory\Api\Data\SupplierExtensionInterface $extensionAttributes
    );

    /**
     * Get supplier_shipping_days
     * @return string|null
     */
    public function getSupplierShippingDays();

    /**
     * Set supplier_shipping_days
     * @param string $supplierShippingDays
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierShippingDays($supplierShippingDays);

    /**
     * Get supplier_feed_url
     * @return string|null
     */
    public function getSupplierFeedUrl();

    /**
     * Set supplier_feed_url
     * @param string $supplierFeedUrl
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedUrl($supplierFeedUrl);

    /**
     * Get supplier_feed_separator
     * @return string|null
     */
    public function getSupplierFeedSeparator();

    /**
     * Set supplier_feed_separator
     * @param string $supplierFeedSeparator
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedSeparator($supplierFeedSeparator);

    /**
     * Get supplier_feed_sku_field
     * @return string|null
     */
    public function getSupplierFeedSkuField();

    /**
     * Set supplier_feed_sku_field
     * @param string $supplierFeedSkuField
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedSkuField($supplierFeedSkuField);

    /**
     * Get supplier_feed_stock_field
     * @return string|null
     */
    public function getSupplierFeedStockField();

    /**
     * Set supplier_feed_stock_field
     * @param string $supplierFeedStockField
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedStockField($supplierFeedStockField);
}

