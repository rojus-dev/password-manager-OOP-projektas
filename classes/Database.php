<?php

class Database
{
    private $connection;

    public function connect()
    {
        if ($this->connection === null) {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->connection;
    }
}