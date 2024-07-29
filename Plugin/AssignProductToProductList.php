<?php

namespace Plugin;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Model\AssignExtensionAttributeToProduct;

class AssignProductToProductList
{
    public function __construct(
        private readonly AssignExtensionAttributeToProduct $assignExtensionAttributeToProduct
    ) {
    }

    public function afterGetLoadedProductCollection(
        ListProduct $subject,
        AbstractCollection $result
    )
    {
        if (!$result->isLoaded()) {
            return $result;
        }

        foreach ($result->getItems() as $product) {
            $this->assignExtensionAttributeToProduct->execute($product);
        }

        return $result;
    }
}
