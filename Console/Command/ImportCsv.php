<?php

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Console\Command;

use FalconMedia\SupplierInventory\Api\Data\SupplierInterface;
use FalconMedia\SupplierInventory\Model\SupplierRepository;
use FireGento\FastSimpleImport\Model\Adapters\NestedArrayAdapterFactory;
use FireGento\FastSimpleImport\Model\Importer;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\ImportExport\Model\Import;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCsv extends Command
{
    private const NAME_ARGUMENT = 'ID';

    /**
     * @var Importer
     */
    protected $importer;

    /**
     * @var NestedArrayAdapterFactory
     */
    protected $nestedArrayAdapterFactory;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var string
     */
    protected $behavior;

    /**
     * @var string
     */
    protected $entityCode;

    /**
     * @var SupplierRepository
     */
    private $supplierRepository;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * Import constructor.
     *
     * @param SupplierRepository        $supplierRepository
     * @param Product                   $product
     * @param Importer                  $importer
     * @param NestedArrayAdapterFactory $nestedArrayAdapterFactory
     * @param StockRegistryInterface    $stockRegistry
     */
    public function __construct(
        SupplierRepository $supplierRepository,
        Product $product,
        Importer $importer,
        NestedArrayAdapterFactory $nestedArrayAdapterFactory,
        StockRegistryInterface $stockRegistry
    ) {
        $this->supplierRepository        = $supplierRepository;
        $this->product                   = $product;
        $this->importer                  = $importer;
        $this->nestedArrayAdapterFactory = $nestedArrayAdapterFactory;
        $this->stockRegistry             = $stockRegistry;

        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateSupplierStock($input->getArgument(self::NAME_ARGUMENT));
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('falconmedia:supplierstock:import');
        $this->setDescription('Import supplier stock per SKU from csv');
        $this->setBehavior(Import::BEHAVIOR_ADD_UPDATE);
        $this->setDefinition(
            [
                new InputArgument(
                    self::NAME_ARGUMENT,
                    InputArgument::REQUIRED,
                    'Supplier ID'
                ),
            ]
        );
        $this->setEntityCode('catalog_product');
        parent::configure();
    }

    /**
     * @param string            $supplierId
     * @param SupplierInterface $supplier
     *
     * @return TabularDataReader|null
     */
    // phpcs:ignore
    protected function readCSVUrl(string $supplierId, SupplierInterface $supplier): ?TabularDataReader
    {
        $csvFile    = $supplier->getSupplierFeedUrl() . '?' . date("YmdHis");
        $delimiter  = $supplier->getSupplierFeedSeparator();
        $csvContent = file_get_contents($csvFile);

        try {
            $csv = Reader::createFromString($csvContent);
            $csv->setDelimiter($delimiter);
            $csv->setHeaderOffset(0);
        } catch (Exception $e) {
            return null;
        }

        return (new Statement())->process($csv);
    }

    /**
     * @param string            $supplierId
     * @param SupplierInterface $supplier
     *
     * @return array
     */
    protected function getEntities(string $supplierId, SupplierInterface $supplier)
    {
        $csvIterationObject = $this->readCSVUrl($supplierId, $supplier);
        $data               = [];

        foreach ($csvIterationObject as $row) {
            $sku       = $row[$supplier->getSupplierFeedSkuField()];
            $productId = (int) $this->product->getIdBySku($sku);

            if ($productId) {
                $supplierStock    = $row[$supplier->getSupplierFeedStockField()];
                $isProductInStock = $this->isProductInStock($supplier, $productId, $supplierStock);
                $allowBackorders  = $this->supplierHasStock($supplier, $supplierStock);
                $data[]           = [
                    'sku'              => trim($sku),
                    'supplier_stock'   => $supplierStock,
                    'supplier'         => $supplier->getSupplierName(),
                    'is_in_stock'      => $isProductInStock,
                    'allow_backorders' => $allowBackorders,
                    'product_type'     => 'simple',
                    '_store'           => 0
                ];
            }
        }

        return $data;
    }

    /**
     * @param string $supplierId
     *
     * @return void
     */
    protected function updateSupplierStock(string $supplierId): void
    {
        try {
            $supplier       = $this->supplierRepository->get($supplierId);
            $importerModel  = $this->importer;
            $productsArray  = $this->getEntities($supplierId, $supplier);
            $adapterFactory = $this->nestedArrayAdapterFactory;

            if ($productsArray) {
                $importerModel->setBehavior($this->getBehavior());
                $importerModel->setEntityCode($this->getEntityCode());
                $importerModel->setImportAdapterFactory($adapterFactory);
                $importerModel->processImport($productsArray);
            }
        } catch (LocalizedException $e) {
            print($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getBehavior(): string
    {
        return $this->behavior;
    }


    /**
     * @param string $behavior
     *
     * @return void
     */
    public function setBehavior(string $behavior): void
    {
        $this->behavior = $behavior;
    }


    /**
     * @return string
     */
    public function getEntityCode(): string
    {
        return $this->entityCode;
    }


    /**
     * @param string $entityCode
     *
     * @return void
     */
    public function setEntityCode(string $entityCode): void
    {
        $this->entityCode = $entityCode;
    }

    /**
     * Check if the product supplied by the supplier is in stock in Magento.
     *
     * @param SupplierInterface $supplier
     * @param int               $productId
     * @param string            $supplierStock
     *
     * @return bool
     */
    private function isProductInStock(SupplierInterface $supplier, int $productId, string $supplierStock): bool
    {
        $stockItem = $this->stockRegistry->getStockItem($productId);

        // First of all, check if there is actual stock for the product itself
        if ($stockItem->getQty() > 0) {
            return true;
        }

        return $this->supplierHasStock($supplier, $supplierStock);
    }

    /**
     * @param SupplierInterface $supplier
     * @param string            $supplierStock
     *
     * @return bool
     */
    private function supplierHasStock(SupplierInterface $supplier, string $supplierStock): bool
    {
        if (stristr($supplierStock, '-')) {
            // If there is a range for the stock, we should keep the minimum as a base here.
            $stockParts    = explode('-', $supplierStock);
            $supplierStock = (int)reset($stockParts);
        } elseif (substr($supplierStock, -1) === '+') {
            // If the is a minimum set (i.e. 50+), we should use that minimum as a base here.
            $stockParts    = explode('+', $supplierStock);
            $supplierStock = (int)reset($stockParts);
        }

        return $supplierStock >= (int) $supplier->getSupplierFeedMinStock();
    }
}
