<?php

declare(strict_types=1);

namespace Model;

use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\Exception\LocalizedException;
use Model\Cache\Type\PokeApi;
use Psr\Log\LoggerInterface;

class WarmCache
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly PokemonDataProvider $pokemonDataProvider,
        private readonly LoggerInterface $logger,
        private readonly StateInterface $cacheState,
        private readonly ProductCollectionFactory $productCollectionFactory
    ) {
    }

    public function execute(): void
    {
        if (!$this->configProvider->isCacheWarmerEnabled()) {
            return;
        }

        $this->logger->info('PokeApi cache warmer process started');

        if (!$this->cacheState->isEnabled(PokeApi::TYPE_IDENTIFIER)) {
            $this->logger->info('PokeApi cache is disabled, skipping');
            return;
        }


        $pokemonNames = $this->getUsedPokemonNames();
        foreach ($pokemonNames as $pokemonName) {
            $this->logger->info('Warming ' . $pokemonName);
            try {
                $this->pokemonDataProvider->getPokemonData($pokemonName, true);
            } catch (LocalizedException $e) {
                $this->logger->error(sprintf('Could not warm cache for %s, skipping', $pokemonName));
            }
        }

        $this->logger->info('PokeApi cache warmer process ended');
    }

    private function getUsedPokemonNames(): array
    {
        $products = $this->productCollectionFactory->create();
        $products->addAttributeToFilter('status', Status::STATUS_ENABLED)
            ->addFieldToFilter('pokemon_name', ['notnull' => true])
            ->groupByAttribute('pokemon_name');

        return $products->getColumnValues('pokemon_name');
    }
}
