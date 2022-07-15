<?php

/**
 * Copyright © Falcon Media All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FalconMedia\SupplierInventory\Model\Product\Attribute\Source;

class Supplier extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => '', 'label' => __('Please Select')],
            ['value' => '1', 'label' => __('Supplier 1')],
            ['value' => '2', 'label' => __('Supplier 2')],
            ['value' => '3', 'label' => __('Supplier 3')],
            ['value' => '4', 'label' => __('Supplier 4')],
            ['value' => '5', 'label' => __('Supplier 5')],
            ['value' => '6', 'label' => __('Supplier 6')],
            ['value' => '7', 'label' => __('Supplier 7')],
            ['value' => '8', 'label' => __('Supplier 8')],
            ['value' => '9', 'label' => __('Supplier 9')],
            ['value' => '10', 'label' => __('Supplier 10')],
            ['value' => '11', 'label' => __('Supplier 11')]
        ];

        return $this->_options;
    }
}
