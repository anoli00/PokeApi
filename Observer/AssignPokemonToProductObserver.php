<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Observer;

use Atma\PokemonIntegration\Model\AssignExtensionAttributeToProduct;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

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
