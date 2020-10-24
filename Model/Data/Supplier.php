<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Model\Data;

use FalconMedia\SupplierInventory\Api\Data\SupplierInterface;

class Supplier extends \Magento\Framework\Api\AbstractExtensibleObject implements SupplierInterface
{

    /**
     * Get supplier_id
     * @return string|null
     */
    public function getSupplierId()
    {
        return $this->_get(self::SUPPLIER_ID);
    }

    /**
     * Set supplier_id
     * @param string $supplierId
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierId($supplierId)
    {
        return $this->setData(self::SUPPLIER_ID, $supplierId);
    }

    /**
     * Get supplier_name
     * @return string|null
     */
    public function getSupplierName()
    {
        return $this->_get(self::SUPPLIER_NAME);
    }

    /**
     * Set supplier_name
     * @param string $supplierName
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierName($supplierName)
    {
        return $this->setData(self::SUPPLIER_NAME, $supplierName);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \FalconMedia\SupplierInventory\Api\Data\SupplierExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \FalconMedia\SupplierInventory\Api\Data\SupplierExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get supplier_shipping_days
     * @return string|null
     */
    public function getSupplierShippingDays()
    {
        return $this->_get(self::SUPPLIER_SHIPPING_DAYS);
    }

    /**
     * Set supplier_shipping_days
     * @param string $supplierShippingDays
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierShippingDays($supplierShippingDays)
    {
        return $this->setData(self::SUPPLIER_SHIPPING_DAYS, $supplierShippingDays);
    }

    /**
     * Get supplier_feed_url
     * @return string|null
     */
    public function getSupplierFeedUrl()
    {
        return $this->_get(self::SUPPLIER_FEED_URL);
    }

    /**
     * Set supplier_feed_url
     * @param string $supplierFeedUrl
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedUrl($supplierFeedUrl)
    {
        return $this->setData(self::SUPPLIER_FEED_URL, $supplierFeedUrl);
    }

    /**
     * Get supplier_feed_separator
     * @return string|null
     */
    public function getSupplierFeedSeparator()
    {
        return $this->_get(self::SUPPLIER_FEED_SEPARATOR);
    }

    /**
     * Set supplier_feed_separator
     * @param string $supplierFeedSeparator
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedSeparator($supplierFeedSeparator)
    {
        return $this->setData(self::SUPPLIER_FEED_SEPARATOR, $supplierFeedSeparator);
    }

    /**
     * Get supplier_feed_sku_field
     * @return string|null
     */
    public function getSupplierFeedSkuField()
    {
        return $this->_get(self::SUPPLIER_FEED_SKU_FIELD);
    }

    /**
     * Set supplier_feed_sku_field
     * @param string $supplierFeedSkuField
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedSkuField($supplierFeedSkuField)
    {
        return $this->setData(self::SUPPLIER_FEED_SKU_FIELD, $supplierFeedSkuField);
    }

    /**
     * Get supplier_feed_stock_field
     * @return string|null
     */
    public function getSupplierFeedStockField()
    {
        return $this->_get(self::SUPPLIER_FEED_STOCK_FIELD);
    }

    /**
     * Set supplier_feed_stock_field
     * @param string $supplierFeedStockField
     * @return \FalconMedia\SupplierInventory\Api\Data\SupplierInterface
     */
    public function setSupplierFeedStockField($supplierFeedStockField)
    {
        return $this->setData(self::SUPPLIER_FEED_STOCK_FIELD, $supplierFeedStockField);
    }
}

