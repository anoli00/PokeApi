<?php

namespace Plugin;

use Closure;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Helper\Data as ConfigurableProductHelper;

class AdaptConfigurableProductHelper
{
    public function aroundGetGalleryImages(
        ConfigurableProductHelper $subject,
        Closure $proceed,
        ProductInterface $product
    ) {
        $pokemonData = $product->getExtensionAttributes()?->getPokemon();

        if (!$pokemonData) {
            return $proceed($product);
        }

        return $product->getMediaGalleryImages();
    }
}
