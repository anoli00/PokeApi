<?php

declare(strict_types=1);

namespace Plugin;

use Magento\Catalog\Block\Product\View\Gallery;

class AddImagesToGalleryBlock
{
    public function afterGetGalleryImages(Gallery $subject, $result) {
        $product = $subject->getProduct();
        $pokemonData = $product->getExtensionAttributes()?->getPokemon();

        if (!$pokemonData) {
            return $result;
        }

        return $pokemonData->getAllImagesCollection($product->getName());
    }

}
