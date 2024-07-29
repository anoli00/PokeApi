<?php

declare(strict_types=1);

namespace Api\Data;

interface PokemonDataInterface
{
    public const string NAME = 'name';
    public const string MAIN_IMAGE_URL = 'main_image_url';
    public const string ADDITIONAL_IMAGE_URLS = 'additional_image_urls';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return \Atma\PokemonIntegration\Api\Data\PokemonDataInterface
     */
    public function setName(string $name): PokemonDataInterface;

    /**
     * @return string
     */
    public function getMainImageUrl(): string;

    /**
     * @param string $mainImageUrl
     * @return \Atma\PokemonIntegration\Api\Data\PokemonDataInterface
     */
    public function setMainImageUrl(string $mainImageUrl): PokemonDataInterface;

    /**
     * @return string[]
     */
    public function getAdditionalImageUrls(): array;

    /**
     * @param string[] $additionalImageUrls
     * @return \Atma\PokemonIntegration\Api\Data\PokemonDataInterface
     */
    public function setAdditionalImageUrls(array $additionalImageUrls): PokemonDataInterface;

    /**
     * @return string[]
     */
    public function getAllImages(): array;

    /**
     * @return \Magento\Framework\Data\Collection
     */
    public function getAllImagesCollection(): \Magento\Framework\Data\Collection;
}