<?php
namespace FalconMedia\SupplierInventory\Controller\Inventorystatus;

use FalconMedia\SupplierInventory\Model\Supplier;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    protected $_productModel;

    protected $_stockRegistryInterface;

    protected $_supplier;

    public function __construct(
        Context $context,
        Product $productModel,
        StockRegistryInterface $stockRegistryInterface,
        Supplier $supplier
    ) {
        $this->_productModel           = $productModel;
        $this->_stockRegistryInterface = $stockRegistryInterface;
        $this->_supplier               = $supplier;
        parent::__construct($context);
    }//end __construct()

    public function execute()
    {
        try {
            $productId = $this->_request->getParam('product_id');
            $product   = $this->_productModel->load($productId);

            $stockInfo  = $this->_stockRegistryInterface->getStockItem($productId);
            $stocklevel = (int) $stockInfo->getQty();

            $supplierName = $product->getAttributeText('supplier');
            $html         = '';
            if ($stocklevel < 1 && $product->getSupplierStock() > 0) {
                $this->_supplier->load($supplierName, 'supplier_name');
                if (!empty($this->_supplier->getSupplierShippingDays())) {
                    $html = "<span class='product-view__stock-status--delivery__delay'>" . __($this->_supplier->getSupplierShippingDays() . ' Shipping Days') . '.</span>';
                }
            } else {
                $html = "<span class='product-view__stock-status--delivery__directly'>" . __('Directly Available') . '.</span>';
            }

            $response = [
                'success'  => 'true',
                'response' => $html,
            ];
        } catch (\Exception $e) {
            $html     = '';
            $response = [
                'success'  => 'false',
                'response' => $html,
            ];
        }//end try

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($response);
        return $resultJson;
    }//end execute()
}//end class
