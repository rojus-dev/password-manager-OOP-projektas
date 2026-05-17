<?php

class PasswordEntry
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function save($userId, $title, $encryptedPassword)
    {
        $query = $this->connection->prepare(
            "INSERT INTO passwords (user_id, title, encrypted_password) VALUES (?, ?, ?)"
        );

        return $query->execute([$userId, $title, $encryptedPassword]);
    }

    public function getByUser($userId)
    {
        $query = $this->connection->prepare(
            "SELECT * FROM passwords WHERE user_id = ? ORDER BY created_at DESC"
        );

        $query->execute([$userId]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}