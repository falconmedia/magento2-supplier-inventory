<?php

/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Model;

use Exception;
use FalconMedia\SupplierInventory\Api\Data\SupplierInterface;
use FalconMedia\SupplierInventory\Api\Data\SupplierInterfaceFactory;
use FalconMedia\SupplierInventory\Api\Data\SupplierSearchResultsInterface;
use FalconMedia\SupplierInventory\Api\Data\SupplierSearchResultsInterfaceFactory;
use FalconMedia\SupplierInventory\Api\SupplierRepositoryInterface;
use FalconMedia\SupplierInventory\Model\ResourceModel\Supplier as ResourceSupplier;
use FalconMedia\SupplierInventory\Model\ResourceModel\Supplier\CollectionFactory as SupplierCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    /** @var SupplierFactory */
    protected $supplierFactory;

    /** @var SupplierCollectionFactory */
    protected $supplierCollectionFactory;

    /** @var SupplierSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var ResourceSupplier */
    protected $resource;

    /** @var ExtensibleDataObjectConverter */
    protected $extensibleDataObjectConverter;

    /** @var DataObjectProcessor */
    protected $dataObjectProcessor;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var JoinProcessorInterface */
    protected $extensionAttributesJoinProcessor;

    /** @var SupplierInterfaceFactory */
    protected $dataSupplierFactory;

    /**
     * @param ResourceSupplier                      $resource
     * @param SupplierFactory                       $supplierFactory
     * @param SupplierInterfaceFactory              $dataSupplierFactory
     * @param SupplierCollectionFactory             $supplierCollectionFactory
     * @param SupplierSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper                      $dataObjectHelper
     * @param DataObjectProcessor                   $dataObjectProcessor
     * @param StoreManagerInterface                 $storeManager
     * @param CollectionProcessorInterface          $collectionProcessor
     * @param JoinProcessorInterface                $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter         $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceSupplier $resource,
        SupplierFactory $supplierFactory,
        SupplierInterfaceFactory $dataSupplierFactory,
        SupplierCollectionFactory $supplierCollectionFactory,
        SupplierSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource                         = $resource;
        $this->supplierFactory                  = $supplierFactory;
        $this->supplierCollectionFactory        = $supplierCollectionFactory;
        $this->searchResultsFactory             = $searchResultsFactory;
        $this->dataObjectHelper                 = $dataObjectHelper;
        $this->dataSupplierFactory              = $dataSupplierFactory;
        $this->dataObjectProcessor              = $dataObjectProcessor;
        $this->storeManager                     = $storeManager;
        $this->collectionProcessor              = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter    = $extensibleDataObjectConverter;
    }

    /**
     * @param SupplierInterface $supplier
     *
     * @return SupplierInterface
     * @throws CouldNotSaveException
     */
    public function save(
        SupplierInterface $supplier
    ) {
        $supplierData = $this->extensibleDataObjectConverter->toNestedArray(
            $supplier,
            [],
            SupplierInterface::class
        );

        $supplierModel = $this->supplierFactory->create()->setData($supplierData);

        try {
            $this->resource->save($supplierModel);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the supplier: %1',
                    $exception->getMessage()
                )
            );
        }

        return $supplierModel->getDataModel();
    }

    /**
     * @param string $supplierId
     *
     * @return SupplierInterface
     * @throws NoSuchEntityException
     */
    public function get($supplierId)
    {
        $supplier = $this->supplierFactory->create();
        $this->resource->load($supplier, $supplierId);
        if (!$supplier->getId()) {
            throw new NoSuchEntityException(__('Supplier with id "%1" does not exist.', $supplierId));
        }

        return $supplier->getDataModel();
    }

    /**
     * @param SearchCriteriaInterface $criteria
     *
     * @return SupplierSearchResultsInterface
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->supplierCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            SupplierInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param SupplierInterface $supplier
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(
        SupplierInterface $supplier
    ) {
        try {
            $supplierModel = $this->supplierFactory->create();
            $this->resource->load($supplierModel, $supplier->getSupplierId());
            $this->resource->delete($supplierModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the Supplier: %1',
                    $exception->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * @param string $supplierId
     *
     * @return bool
     */
    public function deleteById($supplierId)
    {
        return $this->delete($this->get($supplierId));
    }
}
