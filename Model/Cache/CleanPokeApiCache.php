<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Model\Cache;

use Magento\Framework\Cache\FrontendInterface;

class CleanPokeApiCache
{
    use CacheKeyGeneratorTrait;

    public function __construct(
        private readonly FrontendInterface $cache
    ) {}

    public function execute(string $pokemonName): bool
    {
        $cacheKey = $this->getCacheKey($pokemonName);
        return $this->cache->remove($cacheKey);
    }
}
