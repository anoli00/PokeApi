<?php

declare(strict_types=1);

namespace Model\Cache\Type;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;

class PokeApi extends TagScope
{
    public const TYPE_IDENTIFIER = 'pokeapi';
    public const CACHE_TAG = 'POKEAPI';

    public function __construct(FrontendPool $cacheFrontendPool)
    {
        parent::__construct(
            $cacheFrontendPool->get(self::TYPE_IDENTIFIER),
            self::CACHE_TAG
        );
    }
}
