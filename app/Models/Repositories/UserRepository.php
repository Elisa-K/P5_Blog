<?php

declare(strict_types=1);

namespace App\Models\Repositories;

use PDO;
use App\Models\Entities\User;

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

	public function getUserByEmail(string $email, string $password): User|string
	{
		$stmt = $this->dbConnect->prepare("SELECT id, username, firstname, lastname, email, password, DATE_FORMAT(createdAt, '%d/%m/%Y à %Hh%i') as french_createdAt, isAdmin FROM user WHERE email = :email");

		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();
			if (password_verify($password, $row['password'])) {
				$user = new User();
				$user->username = $row['username'];
				$user->firstname = $row['firstname'];
				$user->lastname = $row['lastname'];
				$user->email = $row['email'];
				$user->createdAt = $row['french_createdAt'];
				$user->isAdmin = $row['isAdmin'];
				return $user;
			} else {
				return "Le mot de passe est invalide";
			}
		} else {
			return "Aucun compte ne correspond à cette adresse email";
		}
	}
}