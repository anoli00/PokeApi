<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Api;

use Atma\PokemonIntegration\Api\Data\PokemonDataInterface;
use Magento\Framework\Exception\LocalizedException;

interface PokemonDataProviderInterface
{
    /**
     * @param string $pokemonName
     * @return \Api\Data\PokemonDataInterface|null
     * @throws LocalizedException
     */
    public function getPokemonData(string $pokemonName): ?PokemonDataInterface;
}
