<?php

declare(strict_types=1);

namespace Model\ErrorMessage;

interface HandlerInterface
{
    public function handle(\Exception $exception);
}
