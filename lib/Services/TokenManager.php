<?php

declare(strict_types=1);

namespace Lib\Services;

use Lib\Services\SessionManager;

class TokenManager
{

	private int $lifetime = 5;
	private SessionManager $session;

	public function __construct()
	{
		$this->session = new SessionManager();
	}

	public function generate(): string
	{
		$token = bin2hex(random_bytes(16));
		$this->session->set('csrf_token', ['token' => $token, 'time' => time()]);
		return $token;
	}

	public function checkToken(string $tokenForm): bool
	{
		if (!$this->isExist() || $this->isExpired() || !$this->isValid($tokenForm))
			return false;
		else
			$this->unset();
		return true;
	}

	private function unset(): void
	{
		$this->session->remove('csrf_token');
	}

	private function isExpired(): bool
	{
		$max_time = $this->lifetime * 60;
		return (($this->session->get('csrf_token')['time'] + $max_time) < time());
	}

	private function isExist(): bool
	{
		return $this->session->has('csrf_token');
	}

	private function isValid(string $tokenForm): bool
	{
		return $tokenForm === $this->session->get('csrf_token')['token'];
	}
}