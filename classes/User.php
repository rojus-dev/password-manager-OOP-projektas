<?php

class User
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function register($username, $password, $encryptedKey)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = $this->connection->prepare(
            "INSERT INTO users (username, password_hash, encrypted_key) VALUES (?, ?, ?)"
        );

        return $query->execute([$username, $passwordHash, $encryptedKey]);
    }

    public function findByUsername($username)
    {
        $query = $this->connection->prepare(
            "SELECT * FROM users WHERE username = ?"
        );

        $query->execute([$username]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}