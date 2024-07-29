<?php

namespace Plugin;

use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Swatches\Helper\Data as SwatchesHelper;

class AdaptSwatchesHelper
{
    public function afterGetProductMediaGallery(
        SwatchesHelper $subject,
        $result,
        ProductInterface $product
    ) {
        $pokemonData = $product->getExtensionAttributes()?->getPokemon();

        if (!$pokemonData) {
            return $result;
        }

        $baseImage = $pokemonData->getMainImageUrl();
        $gallery = [];

        $mediaGallery = $product->getMediaGalleryImages();
        /** @var ProductAttributeMediaGalleryEntryInterface $mediaEntry */
        foreach ($mediaGallery as $mediaEntry) {
           if ($mediaEntry->getDisabled()) {
               continue;
           }

            $gallery[$mediaEntry->getValueId()] = $this->collectImageData($mediaEntry->getFile(), $mediaEntry->getPosition());
        }

        if (!$baseImage) {
            return [];
        }

        $resultGallery = $this->collectImageData($baseImage, 0, true);
        $resultGallery['gallery'] = $gallery;

        return $resultGallery;
    }

    private function collectImageData(string $imageUrl, int $position, bool $isMain = false): array
    {
        $image = $this->getAllSizeImages($imageUrl);
        $image[ProductAttributeMediaGalleryEntryInterface::POSITION] = $position;
        $image['isMain'] = $isMain;
        return $image;
    }

    private function getAllSizeImages(string $imageUrl): array
    {
        return [
            'large' => $imageUrl,
            'medium' => $imageUrl,
            'small' => $imageUrl
        ];
    }
}
