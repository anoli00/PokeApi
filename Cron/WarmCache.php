<?php

declare(strict_types=1);

namespace Cron;

use Model\WarmCache as WarmCacheCommand;

class WarmCache
{
    public function __construct(
        private readonly WarmCacheCommand $warmCacheCommand
    ) {
    }

    public function execute(): void
    {
        $this->warmCacheCommand->execute();
    }
}
