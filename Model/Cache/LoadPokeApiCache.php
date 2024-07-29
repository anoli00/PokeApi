<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Model\Cache;

use Atma\PokemonIntegration\Model\Cache\Type\PokeApi;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\Serialize\SerializerInterface;

class LoadPokeApiCache
{
    use CacheKeyGeneratorTrait;

    public function __construct(
        private readonly FrontendInterface $cache,
        private readonly SerializerInterface $serializer,
        private readonly StateInterface $cacheState
    ) {}

    public function execute(string $pokemonName): ?array
    {
        if (!$this->cacheState->isEnabled(PokeApi::TYPE_IDENTIFIER)) {
            return null;
        }

        $cacheKey = $this->getCacheKey($pokemonName);
        $cachedData = $this->cache->load($cacheKey);

        if ($cachedData === false) {
            return null;
        }

        return $this->serializer->unserialize($cachedData);
    }
}
