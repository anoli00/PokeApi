<?php

declare(strict_types=1);

namespace Plugin;

use Closure;
use Magento\Catalog\Model\Product;

class CatalogProductPlugin
{
    public function afterGetName(Product $product, $result)
    {
        $pokemonData = $product->getExtensionAttributes()?->getPokemon();

        if (!$pokemonData) {
            return $result;
        }

        return $pokemonData->getName();
    }

    public function aroundGetMediaGalleryImages(Product $product, Closure $proceed) {
        $pokemonData = $product->getExtensionAttributes()?->getPokemon();

        if (!$pokemonData) {
            return $proceed();
        }

        return $pokemonData->getAllImagesCollection($product->getName());
    }
}
