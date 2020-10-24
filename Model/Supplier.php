<?php
/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Model;

use FalconMedia\SupplierInventory\Api\Data\SupplierInterface;
use FalconMedia\SupplierInventory\Api\Data\SupplierInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Supplier extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $supplierDataFactory;

    protected $_eventPrefix = 'falconmedia_supplierinventory_supplier';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param SupplierInterfaceFactory $supplierDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \FalconMedia\SupplierInventory\Model\ResourceModel\Supplier $resource
     * @param \FalconMedia\SupplierInventory\Model\ResourceModel\Supplier\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        SupplierInterfaceFactory $supplierDataFactory,
        DataObjectHelper $dataObjectHelper,
        \FalconMedia\SupplierInventory\Model\ResourceModel\Supplier $resource,
        \FalconMedia\SupplierInventory\Model\ResourceModel\Supplier\Collection $resourceCollection,
        array $data = []
    ) {
        $this->supplierDataFactory = $supplierDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve supplier model with supplier data
     * @return SupplierInterface
     */
    public function getDataModel()
    {
        $supplierData = $this->getData();
        
        $supplierDataObject = $this->supplierDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $supplierDataObject,
            $supplierData,
            SupplierInterface::class
        );
        
        return $supplierDataObject;
    }
}

