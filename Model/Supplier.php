<?php

/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Model;

use FalconMedia\SupplierInventory\Api\Data\SupplierInterface;
use FalconMedia\SupplierInventory\Api\Data\SupplierInterfaceFactory;
use FalconMedia\SupplierInventory\Model\ResourceModel\Supplier\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Supplier extends AbstractModel
{
    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var SupplierInterfaceFactory */
    protected $supplierDataFactory;

    /** @var string */
    // phpcs:ignore
    protected $_eventPrefix = 'falconmedia_supplierinventory_supplier';

    /**
     * @param Context                  $context
     * @param Registry                 $registry
     * @param SupplierInterfaceFactory $supplierDataFactory
     * @param DataObjectHelper         $dataObjectHelper
     * @param ResourceModel\Supplier   $resource
     * @param Collection               $resourceCollection
     * @param array                    $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        SupplierInterfaceFactory $supplierDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\Supplier $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->supplierDataFactory = $supplierDataFactory;
        $this->dataObjectHelper    = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve supplier model with supplier data
     *
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
