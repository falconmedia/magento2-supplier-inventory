<?php

namespace FalconMedia\SupplierInventory\Controller\Inventorystatus;

use Exception;
use FalconMedia\SupplierInventory\Model\Supplier;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action
{
    /** @var Product */
    protected $productModel;

    /** @var StockRegistryInterface */
    protected $stockRegistryInterface;

    /** @var Supplier */
    protected $supplier;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param Product $productModel
     * @param StockRegistryInterface $stockRegistryInterface
     * @param Supplier $supplier
     */
    public function __construct(
        Context $context,
        Product $productModel,
        StockRegistryInterface $stockRegistryInterface,
        Supplier $supplier
    ) {
        $this->productModel           = $productModel;
        $this->stockRegistryInterface = $stockRegistryInterface;
        $this->supplier               = $supplier;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        try {
            $productId    = $this->_request->getParam('product_id');
            $product      = $this->productModel->load($productId);
            $stockInfo    = $this->stockRegistryInterface->getStockItem($productId);
            $stockLevel   = (int) $stockInfo->getQty();
            $supplierName = $product->getAttributeText('supplier');
            $html         = '';

            if ($stockLevel < 1 && $product->getData('supplier_stock') > 0) {
                $this->supplier->load($supplierName, 'supplier_name');
                if (!empty($this->supplier->getData('supplier_shipping_days'))) {
                    $html = "<span class='product-view__stock-status--delivery__delay'>" .
                        __($this->supplier->getData('supplier_shipping_days')) . __(' Shipping Days') .
                        '</span>';
                }
            } else {
                $html = "<span class='product-view__stock-status--delivery__directly'>" .
                    __('Directly Available') .
                    '</span>';
            }

            $response = [
                'success'  => 'true',
                'response' => $html,
            ];
        } catch (Exception $e) {
            $html     = '';
            $response = [
                'success'  => 'false',
                'response' => $html,
            ];
        }

        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($response);

        return $resultJson;
    }
}
