<?php

declare(strict_types=1);

namespace Model\ErrorMessage;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class MessageManagerHandler implements HandlerInterface
{
    private array $messageBag = [];

    public function __construct(
        private readonly MessageManagerInterface $messageManager
    ) {
    }

    public function handle(Exception $exception): void
    {
        $message = $exception->getMessage();

        if ($this->canShowMessage($exception) && !$this->isAlreadyDisplayed($message)) {
            $this->messageManager->addErrorMessage($message);
            $this->messageBag[] = $message;
        }
    }

    private function isAlreadyDisplayed(string $message): bool
    {
        return in_array($message, $this->messageBag);
    }

    private function canShowMessage(Exception $exception): bool
    {
        return $exception instanceof LocalizedException;
    }
}
