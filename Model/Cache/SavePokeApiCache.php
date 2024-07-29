<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Model\Cache;

use Atma\PokemonIntegration\Model\Cache\Type\PokeApi;
use Atma\PokemonIntegration\Model\ConfigProvider;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\Serialize\SerializerInterface;

class SavePokeApiCache
{
    use CacheKeyGeneratorTrait;

    public function __construct(
        private readonly FrontendInterface $cache,
        private readonly SerializerInterface $serializer,
        private readonly StateInterface $cacheState,
        private readonly ConfigProvider $configProvider
    ) {}

    public function execute(string $pokemonName, array $data): bool
    {
        if (!$this->cacheState->isEnabled(PokeApi::TYPE_IDENTIFIER)) {
            return false;
        }

        $cacheLifeTime = $this->configProvider->getCacheLifetime();
        $cacheKey = $this->getCacheKey($pokemonName);
        $serializedData = $this->serializer->serialize($data);

        return $this->cache->save($serializedData, $cacheKey, [PokeApi::CACHE_TAG], $cacheLifeTime);
    }
}
