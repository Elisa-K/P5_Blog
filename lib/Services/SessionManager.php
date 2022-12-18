<?php

declare(strict_types=1);

namespace Lib\Services;

use Lib\Services\SessionInterface;

class SessionManager implements SessionInterface
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(['secure' => false, 'httponly' => true, 'samesite' => 'lax']);
            session_start();
        }
    }

    public function regenerateId(): void
    {
        session_regenerate_id();
    }

    public function get(string $key): mixed
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function set(string $key, mixed $value): SessionInterface
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function clear(): void
    {
        session_unset();
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }
}
