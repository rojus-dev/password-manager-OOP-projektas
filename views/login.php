<?php

session_start();

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $connection = $database->connect();

    $user = new User($connection);

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $loggedUser = $user->login($username, $password);

    if ($loggedUser) {
        $_SESSION['user_id'] = $loggedUser['id'];
        $_SESSION['username'] = $loggedUser['username'];

        header('Location: dashboard.php');
        exit;
    } else {
        $message = 'Neteisingi prisijungimo duomenys';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prisijungimas</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<h2>Prisijungimas</h2>

<form method="POST">
    <input type="text" name="username" placeholder="Vartotojo vardas" required>
    <input type="password" name="password" placeholder="Slaptazodis" required>
    <button type="submit">Prisijungti</button>
</form>

<p><?php echo $message; ?></p>

<a href="register.php">Registracija</a>

</body>
</html>