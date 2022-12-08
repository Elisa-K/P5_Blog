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

	public function getUserByEmail(string $email): ? User
	{
		$stmt = $this->dbConnect->prepare("SELECT id, username, firstname, lastname, email, password, createdAt, isAdmin FROM user WHERE email = :email");

		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();
			$user = new User();
			$user->id = $row['id'];
			$user->username = $row['username'];
			$user->firstname = $row['firstname'];
			$user->lastname = $row['lastname'];
			$user->email = $row['email'];
			$user->password = $row['password'];
			$user->createdAt = $row['createdAt'];
			$user->isAdmin = $row['isAdmin'];
			return $user;
		} else {
			return null;
		}
	}

	public function getAllUser(int $id): array
	{
		$stmt = $this->dbConnect->query("SELECT id, username, firstname, lastname, email, DATE_FORMAT(createdAt, '%d/%m/%Y à %Hh%i') as french_createdAt, isAdmin FROM user WHERE id != $id ORDER BY isAdmin DESC");

		$users = [];

		while ($row = $stmt->fetch()) {
			$user = new User();
			$user->id = $row['id'];
			$user->username = $row['username'];
			$user->firstname = $row['firstname'];
			$user->lastname = $row['lastname'];
			$user->email = $row['email'];
			$user->createdAt = $row['french_createdAt'];
			$user->isAdmin = $row['isAdmin'];
			$users[] = $user;
		}
		return $users;
	}

	public function getUserById(int $id): User
	{
		$stmt = $this->dbConnect->prepare("SELECT firstname, lastname FROM user WHERE id = :id");

		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch();
		$user = new User();
		$user->firstname = $row['firstname'];
		$user->lastname = $row['lastname'];
		return $user;

	}

	public function setPermissionUser(int $id, bool $isAdmin): bool
	{
		$stmt = $this->dbConnect->prepare("UPDATE user SET isAdmin=:isAdmin WHERE id = :id");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->bindParam(':isAdmin', $isAdmin, PDO::PARAM_BOOL);
		$affectedLines = $stmt->execute();

		return ($affectedLines > 0);
	}

	public function getNbUser(): int
	{
		$stmt = $this->dbConnect->query("SELECT count(id) as nb_user FROM user");
		$row = $stmt->fetch();
		$nbUser = $row['nb_user'];
		return $nbUser;
	}

	public function getNbAdmin(): int
	{
		$stmt = $this->dbConnect->query("SELECT count(id) as nb_admin FROM user WHERE isAdmin = TRUE");
		$row = $stmt->fetch();
		$nbAdmin = $row['nb_admin'];
		return $nbAdmin;
	}
}