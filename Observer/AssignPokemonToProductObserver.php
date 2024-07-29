<?php

declare(strict_types=1);

namespace Observer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Model\AssignExtensionAttributeToProduct;

class AssignPokemonToProductObserver implements ObserverInterface
{
    public function __construct(
        private readonly AssignExtensionAttributeToProduct $assignExtensionAttributeToProduct
    ) {
    }

    public function execute(EventObserver $observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product instanceof ProductInterface) {
            $this->assignExtensionAttributeToProduct->execute($product);
        }
    }
}
