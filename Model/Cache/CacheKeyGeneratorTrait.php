<?php

declare(strict_types=1);

namespace Model\Cache;

use Model\Cache\Type\PokeApi;

trait CacheKeyGeneratorTrait
{
    private function getCacheKey(string $pokemonName): string
    {
        return PokeApi::TYPE_IDENTIFIER . '_' . $pokemonName;
    }
}
