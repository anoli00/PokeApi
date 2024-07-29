<?php

declare(strict_types=1);

namespace Atma\PokemonIntegration\Model\ErrorMessage;

use Exception;

interface HandlerInterface
{
    public function handle(Exception $exception);
}
