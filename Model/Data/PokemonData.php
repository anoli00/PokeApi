<?php

declare(strict_types=1);

namespace Atma\PokemonIntegratio\Model\Data;

use Atma\PokemonIntegration\Api\Data\PokemonDataInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\CollectionFactory as DataCollectionFactory;
use Magento\Framework\DataObject;

class PokemonData extends DataObject implements PokemonDataInterface
{
    public function __construct(
        protected readonly DataCollectionFactory $dataCollectionFactory,
        array $data = []
    ) {
        parent::__construct($data);
    }

    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    public function setName(?string $name): PokemonDataInterface
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    public function getMainImageUrl(): string
    {
        return (string)$this->getData(self::MAIN_IMAGE_URL);
    }

    public function setMainImageUrl(?string $mainImageUrl): PokemonDataInterface
    {
        $this->setData(self::MAIN_IMAGE_URL, $mainImageUrl);
        return $this;
    }

    public function getAdditionalImageUrls(): array
    {
        return (array)$this->getData(self::ADDITIONAL_IMAGE_URLS);
    }

    public function setAdditionalImageUrls(?array $additionalImageUrls): PokemonDataInterface
    {
        $this->setData(self::ADDITIONAL_IMAGE_URLS, $additionalImageUrls);
        return $this;
    }

    public function getAllImages(): array
    {
        return [$this->getMainImageUrl(), ...$this->getAdditionalImageUrls()];
    }

    public function getAllImagesCollection(string $imageLabel = ''): Collection
    {
        $images = $this->dataCollectionFactory->create();
        foreach ($this->getAllImages() as $imageUrl) {
            $imageId = uniqid();
            $image = [
                'file' => $imageUrl,
                'media_type' => 'image',
                'value_id' => $imageId,
                'row_id' => $imageId,
                'label' => $imageLabel,
                'label_default' => $imageLabel,
                'position' => 100,
                'position_default' => 100,
                'disabled' => 0,
                'url'  => $imageUrl,
                'path' => '',
                'small_image_url' => $imageUrl,
                'medium_image_url' => $imageUrl,
                'large_image_url' => $imageUrl
            ];
            $images->addItem(new DataObject($image));
        }

        return $images;
    }
}
