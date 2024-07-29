<?php

declare(strict_types=1);

namespace Model;

use Exception\PokeApiException;
use Exception\PokemonNotFoundException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class PokeApiClient
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly Curl           $curl,
        private readonly JsonSerializer $jsonSerializer
    ) {
    }

    /**
     * @throws PokeApiException
     * @throws PokemonNotFoundException
     */
    public function fetchPokemonData(string $pokemonName): array
    {
        $apiBaseUrl = $this->configProvider->getApiUrl();
        if (empty($apiBaseUrl)) {
            throw new PokeApiException(__('PokeApi URL is not configured'));
        }

        $pokemonDataApiUrl = sprintf('%s/%s/', $apiBaseUrl, urlencode(strtolower($pokemonName)));

        $this->curl->get($pokemonDataApiUrl);
        $response = $this->curl->getBody();
        $statusCode = $this->curl->getStatus();

        if ($statusCode === 404) {
            throw new PokemonNotFoundException(__('Pokemon "%1" not found', $pokemonName));
        }

        if ($statusCode !== 200) {
            throw new PokeApiException(__('Failed to fetch Pokemon data. Status code: %1', $statusCode));
        }

        if (empty($response)) {
            throw new PokeApiException(__('Empty response received from PokeApi'));
        }

        $decodedResponse = $this->jsonSerializer->unserialize($response);

        if (!is_array($decodedResponse)) {
            throw new PokeApiException(__('Invalid response format from PokeApi'));
        }

        return $decodedResponse;
    }
}
