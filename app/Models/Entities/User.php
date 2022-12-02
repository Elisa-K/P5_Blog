<?php

declare(strict_types=1);

namespace App\Models\Entities;

class User
{
	public int $id;
	public string $username;
	public string $firstname;
	public string $lastname;
	public string $email;
	public string $password;
	public string $createdAt;
	public int $isAdmin;
}