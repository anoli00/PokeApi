<?php

declare(strict_types=1);

namespace Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider
{
    public const XML_PATH_API_URL = 'pokemon_integration/general/api_url';
    public const XML_PATH_MAIN_IMAGE = 'pokemon_integration/general/main_image';
    public const XML_PATH_ADDITIONAL_IMAGES = 'pokemon_integration/general/additional_images';
    public const XML_PATH_CACHE_LIFETIME = 'pokemon_integration/cache/lifetime';
    public const XML_PATH_CACHE_WARMER_ENABLED = 'pokemon_integration/cache/warmer_enabled';
    public const XML_PATH_CACHE_WARMER_TIME = 'pokemon_integration/cache/warmer_time';
    public const XML_PATH_CACHE_WARMER_FREQUENCY = 'pokemon_integration/cache/warmer_frequency';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getApiUrl(): ?string
    {
        $configValue = $this->scopeConfig->getValue(
            self::XML_PATH_API_URL,
            ScopeInterface::SCOPE_STORE
        );

        return $configValue !== null ? rtrim($configValue, '/') . '/' : null;
    }

    public function getMainImageSpriteName(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAIN_IMAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getAdditionalImageSpriteNames(): array
    {
        $configValue = $this->scopeConfig->getValue(
            self::XML_PATH_ADDITIONAL_IMAGES,
            ScopeInterface::SCOPE_STORE
        );

        return $configValue ? array_map('trim', explode(',', $configValue)) : [];
    }

    public function getCacheLifetime(): int
    {
        $configValue = $this->scopeConfig->getValue(self::XML_PATH_CACHE_LIFETIME);
        return $configValue ? (int)$configValue : 0;
    }

    public function isCacheWarmerEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_CACHE_WARMER_ENABLED);
    }
}
