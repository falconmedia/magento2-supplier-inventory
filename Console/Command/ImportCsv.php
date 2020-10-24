<?php declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Console\Command;

use FalconMedia\SupplierInventory\Model\SupplierRepository;
use FireGento\FastSimpleImport\Model\Adapters\NestedArrayAdapterFactory;
use FireGento\FastSimpleImport\Model\Importer;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\ImportExport\Model\Import;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ImportCsv extends Command
{
    const NAME_ARGUMENT = 'ID';

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var Importer
     */
    protected $importer;

    /**
     * @var NestedArrayAdapterFactory
     */
    protected $nestedArrayAdapterFactory;

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * @var StockItemRepository
     */
    private $stockItemRepository;

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
     * @var boolean|bool[]|null
     */
    private $delimiter;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var State
     */
    private $state;

    /**
     * @var SupplierRepository
     */
    private $supplierRepository;


    /**
     * Import constructor.
     *
     * @param DirectoryList              $directoryList
     * @param ReadFactory                $readFactory
     * @param ProductRepositoryInterface $productRepository
     * @param State                      $state
     * @param SupplierRepository         $supplierRepository
     * @param Filesystem                 $filesystem
     * @param ResourceConnection         $resourceConnection
     * @param StockItemRepository        $stockItemRepository
     * @param Product                    $product
     * @param Importer                   $importer
     * @param NestedArrayAdapterFactory  $nestedArrayAdapterFactory
     * @param Finder                     $finder
     */
    public function __construct(
        DirectoryList $directoryList,
        ReadFactory $readFactory,
        ProductRepositoryInterface $productRepository,
        State $state,
        SupplierRepository $supplierRepository,
        ResourceConnection $resourceConnection,
        StockItemRepository $stockItemRepository,
        Product $product,
        Importer $importer,
        NestedArrayAdapterFactory $nestedArrayAdapterFactory,
        Finder $finder
    ) {
        $this->directoryList       = $directoryList;
        $this->readFactory         = $readFactory;
        $this->productRepository   = $productRepository;
        $this->state               = $state;
        $this->supplierRepository  = $supplierRepository;
        $this->resourceConnection  = $resourceConnection;
        $this->stockItemRepository = $stockItemRepository;
        $this->product             = $product;
        $this->importer            = $importer;
        $this->nestedArrayAdapterFactory = $nestedArrayAdapterFactory;
        $this->finder                    = $finder;
        parent::__construct();

    }//end __construct()


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateSupplierStock($input->getArgument(self::NAME_ARGUMENT));
    }//end execute()


    /**
     * {@inheritdoc}
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

    }//end configure()


    protected function readCSVUrl($supplierId, $supplier)
    {
        $csvFile    = $supplier->getSupplierFeedUrl();
        $delimiter  = $supplier->getSupplierFeedSeparator();
        $csvContent = file_get_contents($csvFile);
        try {
            $csv = Reader::createFromString($csvContent);
            $csv->setDelimiter($delimiter);
            $csv->setHeaderOffset(0);
        } catch (Exception $e) {
        }

        $stmt = (new Statement());
        return $stmt->process($csv);

    }//end readCSVUrl()


    protected function getEntities($supplierId, $supplier)
    {
        $csvIterationObject = $this->readCSVUrl($supplierId, $supplier);
        $data               = [];
        foreach ($csvIterationObject as $row) {
            $sku           = $row[$supplier->getSupplierFeedSkuField()];
            if ($this->product->getIdBySku($sku)) {
                $sku           = $row[$supplier->getSupplierFeedSkuField()];
                $supplierStock = $row[$supplier->getSupplierFeedStockField()];
                $supplierName  = $supplier->getSupplierName();
                $data[]        = [
                    'sku'            => $sku,
                    'supplier_stock' => $supplierStock,
                    'supplier'       => $supplierName,
                ];
            }
        }

        return $data;

    }//end getEntities()


    protected function updateSupplierStock($supplierId)
    {
        $supplier      = $this->supplierRepository->get($supplierId);
        $supplierName  = $supplier->getSupplierName();
        $importerModel = $this->importer;
        $productsArray = $this->getEntities($supplierId, $supplier);
        $importerModel->setBehavior($this->getBehavior());
        $importerModel->setEntityCode($this->getEntityCode());
        $adapterFactory = $this->nestedArrayAdapterFactory;
        $importerModel->setImportAdapterFactory($adapterFactory);

        try {
            if ($productsArray) {
                $importerModel->processImport($productsArray);
            }
        } catch (\Exception $e) {
            print($e->getMessage());
        }

    }//end updateSupplierStock()


    public function getBehavior()
    {
        return $this->behavior;

    }//end getBehavior()


    /**
     * @param string $behavior
     */
    public function setBehavior($behavior)
    {
        $this->behavior = $behavior;

    }//end setBehavior()


    /**
     * @return string
     */
    public function getEntityCode()
    {
        return $this->entityCode;

    }//end getEntityCode()


    /**
     * @param string $entityCode
     */
    public function setEntityCode($entityCode)
    {
        $this->entityCode = $entityCode;

    }//end setEntityCode()


}//end class
