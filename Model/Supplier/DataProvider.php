<?php

/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Model\Supplier;

use FalconMedia\SupplierInventory\Model\ResourceModel\Supplier\Collection;
use FalconMedia\SupplierInventory\Model\ResourceModel\Supplier\CollectionFactory;
use FalconMedia\SupplierInventory\Model\Supplier;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /** @var Collection */
    protected $collection;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var array|null */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string                 $name
     * @param string                 $primaryFieldName
     * @param string                 $requestFieldName
     * @param CollectionFactory      $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array                  $meta
     * @param array                  $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var Supplier[] $items */
        $items = $this->collection->getItems();

        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
        }

        $data = $this->dataPersistor->get('falconmedia_supplierinventory_supplier');

        if (!empty($data)) {
            /** @var Supplier $model */
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('falconmedia_supplierinventory_supplier');
        }

        return $this->loadedData;
    }
}
