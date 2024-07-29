<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Plugin;

use Closure;
use Magento\Catalog\Block\Product\Image as ImageBlock;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Image\ParamsBuilder;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\ConfigInterface;

class AdaptImageFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ConfigInterface $presentationConfig,
        private readonly ParamsBuilder $imageParamsBuilder
    ) { }

    public function aroundCreate(
        ImageFactory $subject,
        Closure $proceed,
        Product $product,
        string $imageId,
        array $attributes = null
    ) {
        $pokemonData = $product->getExtensionAttributes()?->getPokemon();

        if (!$pokemonData) {
            return $proceed($product, $imageId, $attributes);
        }

        $viewImageConfig = $this->presentationConfig->getViewConfig()->getMediaAttributes(
            'Magento_Catalog',
            ImageHelper::MEDIA_TYPE_CONFIG_NODE,
            $imageId
        );

        $imageMiscParams = $this->imageParamsBuilder->build($viewImageConfig);
        $data = [
            'data' => [
                'template' => 'Magento_Catalog::product/image_with_borders.phtml',
                'image_url' => $pokemonData->getMainImageUrl(),
                'width' => $imageMiscParams['image_width'],
                'height' => $imageMiscParams['image_height'],
                'label' => $product->getName(),
                'ratio' => $this->getRatio($imageMiscParams['image_width'] ?? 0, $imageMiscParams['image_height'] ?? 0),
                'custom_attributes' => $this->filterCustomAttributes($attributes),
                'class' => $this->getClass($attributes),
                'product_id' => $product->getId()
            ],
        ];

        return $this->objectManager->create(ImageBlock::class, $data);
    }

    private function getRatio(int $width, int $height): float
    {
        if ($width && $height) {
            return $height / $width;
        }
        return 1.0;
    }

    private function filterCustomAttributes(array $attributes): array
    {
        if (isset($attributes['class'])) {
            unset($attributes['class']);
        }
        return $attributes;
    }

    private function getClass(array $attributes): string
    {
        return $attributes['class'] ?? 'product-image-photo';
    }
}
