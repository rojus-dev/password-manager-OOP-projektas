<?php

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Encryptor.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $connection = $database->connect();

    $user = new User($connection);
    $encryptor = new Encryptor();

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $key = $encryptor->generateKey();
    $encryptedKey = $encryptor->encrypt($key, $password);

    if (empty($username) || empty($password)) {
        $message = 'Uzpildyk visus laukus';
    } elseif ($user->findByUsername($username)) {
        $message = 'Toks vartotojas jau egzistuoja';
    } else {
        $user->register($username, $password, $encryptedKey);
        $message = 'Registracija sekminga';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registracija</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<h2>Registracija</h2>

<form method="POST">
    <input type="text" name="username" placeholder="Vartotojo vardas" required>
    <input type="password" name="password" placeholder="Slaptazodis" required>
    <button type="submit">Registruotis</button>
</form>

<p><?php echo $message; ?></p>

<a href="login.php">Prisijungimas</a>

</body>
</html>