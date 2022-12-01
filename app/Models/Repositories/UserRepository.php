<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use PDO;

class UserRepository
{

	private ? PDO $dbConnect;

	public function __construct($dbConnect)
	{
		$this->dbConnect = $dbConnect;
	}

	public function registerUser(string $username, string $firstname, string $lastname, string $email, string $password): bool
	{
		$stmt = $this->dbConnect->prepare("INSERT INTO user (username, firstname, lastname, email, password, createdAt, isAdmin) VALUES(:username, :firstname, :lastname, :email, :password, NOW(), 0)");

		$password = password_hash($password, PASSWORD_BCRYPT);
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
		$stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);

		$affectedLines = $stmt->execute();

		return ($affectedLines > 0);
	}
}