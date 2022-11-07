<?php

declare(strict_types=1);

namespace Lib;

use PDO;
use PDOException;

class Database
{
    public ?PDO $database = null;

    public function getConnection(): ?PDO
    {
        if ($this->database === null) {
            try {
                $this->database = new PDO(
                    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'],
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASSWORD']
                );
                $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
            }
        }
        return $this->database;
    }
}
