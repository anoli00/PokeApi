<?php
declare(strict_types=1);

namespace Model;

use Api\Data\PokemonDataInterface;
use Api\PokemonDataProviderInterface;
use Atma\PokemonIntegration\Api\Data\PokemonDataInterfaceFactory;
use Exception;
use Exception\PokeApiException;
use Exception\PokemonNotFoundException;
use Magento\Framework\Exception\LocalizedException;
use Model\Cache\LoadPokeApiCache;
use Model\Cache\SavePokeApiCache;
use Psr\Log\LoggerInterface;

class PokemonDataProvider implements PokemonDataProviderInterface
{
    public function __construct(
        private readonly LoadPokeApiCache $loadPokeApiCache,
        private readonly SavePokeApiCache $savePokeApiCache,
        private readonly PokeApiClient $pokeApiClient,
        private readonly PokemonDataInterfaceFactory $pokemonDataFactory,
        private readonly ConfigProvider $configProvider,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getPokemonData(string $pokemonName, bool $forceReload = false): ?PokemonDataInterface
    {
        try {
            $data = $forceReload
                ? $this->fetchAndCacheData($pokemonName)
                : ($this->loadPokeApiCache->execute($pokemonName) ?? $this->fetchAndCacheData($pokemonName));

            return $this->createPokemonData($data);
        } catch (PokemonNotFoundException $e) {
            $this->logError($e, $pokemonName);
            return null;
        } catch (PokeApiException $e) {
            $this->logError($e, $pokemonName);
            throw new LocalizedException(__('An error occurred while fetching product data.'));
        }
    }


    /**
     * @throws PokeApiException
     * @throws PokemonNotFoundException
     */
    private function fetchAndCacheData(string $pokemonName): array
    {
        $data = $this->pokeApiClient->fetchPokemonData($pokemonName);
        if (!empty($data)) {
            $this->savePokeApiCache->execute($pokemonName, $data);
        }
        return $data;
    }

    private function createPokemonData(array $data): PokemonDataInterface
    {
        $mainImageSpriteName = $this->configProvider->getMainImageSpriteName();
        $mainImage = $data['sprites'][$mainImageSpriteName] ?? '';
        $additionalImages = $this->getAdditionalImages($data['sprites']);

        return $this->pokemonDataFactory->create([
            'data' => [
                PokemonDataInterface::NAME => $data['name'],
                PokemonDataInterface::MAIN_IMAGE_URL => $mainImage,
                PokemonDataInterface::ADDITIONAL_IMAGE_URLS => $additionalImages
            ]
        ]);
    }

    private function getAdditionalImages(array $sprites): array
    {
        $additionalImageSpriteNames = $this->configProvider->getAdditionalImageSpriteNames();
        return array_filter(
            $sprites,
            fn($sprite, $key) => is_string($sprite) && in_array($key, $additionalImageSpriteNames),
            ARRAY_FILTER_USE_BOTH
        );
    }

    private function logError(Exception $e, string $pokemonName): void
    {
        $this->logger->error(
            'PokeAPI error occurred while fetching data',
            [
                'exception' => $e,
                'pokemonName' => $pokemonName
            ]
        );
    }
}
