<?php

/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Block\Adminhtml\Supplier\Edit;

use Magento\Backend\Block\Widget\Context;

abstract class GenericButton
{
    /** @var Context */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Return model ID
     *
     * @return int|null
     */
    public function getModelId()
    {
        return $this->context->getRequest()->getParam('supplier_id');
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array  $params
     *
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
