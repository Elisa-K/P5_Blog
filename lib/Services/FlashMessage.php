<?php

declare(strict_types=1);

namespace Lib\Services;

use Iterator;
use Countable;

class FlashMessage implements Countable, Iterator
{
    public array $messages = [];
    private int $key = 0;

    public function __construct(array &$messages)
    {
        $this->messages = & $messages;
    }

    public function count(): int
    {
        return count($this->messages);
    }

    public function current(): mixed
    {
        $message = current($this->messages);
        unset($this->messages[$this->key]);
        return $message;
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->key++;
    }

    public function rewind(): void
    {
        $this->key = 0;
    }

    public function valid(): bool
    {
        return isset($this->messages[$this->key]);
    }

    public function add(mixed $value): void
    {
        array_push($this->messages, $value);
    }

}