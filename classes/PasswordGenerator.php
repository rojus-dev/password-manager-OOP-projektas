<?php

class PasswordGenerator
{
    private $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    private $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $numbers = '0123456789';
    private $specials = '!@#$%^&*()_+-=[]{}<>?';

    public function generate($lowerCount, $upperCount, $numberCount, $specialCount)
    {
        $password = '';

        for ($i = 0; $i < $lowerCount; $i++) {
            $password .= $this->lowercase[rand(0, strlen($this->lowercase) - 1)];
        }

        for ($i = 0; $i < $upperCount; $i++) {
            $password .= $this->uppercase[rand(0, strlen($this->uppercase) - 1)];
        }

        for ($i = 0; $i < $numberCount; $i++) {
            $password .= $this->numbers[rand(0, strlen($this->numbers) - 1)];
        }

        for ($i = 0; $i < $specialCount; $i++) {
            $password .= $this->specials[rand(0, strlen($this->specials) - 1)];
        }

        return str_shuffle($password);
    }
}