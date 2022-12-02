<?php

declare(strict_types=1);

namespace Lib\Services;

use Lib\Services\SessionInterface;

class SessionManager implements SessionInterface
{
	public function __construct(?int $cacheExpire = null, ?string $cacheLimiter = null)
	{
		if (session_status() === PHP_SESSION_NONE) {

			if ($cacheLimiter !== null) {
				session_cache_limiter($cacheLimiter);
			}

			if ($cacheExpire !== null) {
				session_cache_expire($cacheExpire);
			}

			session_start();
		}
	}

	public function get(string $key): mixed
	{
		if ($this->has($key)) {
			return $_SESSION[$key];
		}

		return null;
	}

	public function set(string $key, $value): SessionInterface
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