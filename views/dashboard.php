<?php

session_start();

require_once '../classes/PasswordGenerator.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$generatedPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $generator = new PasswordGenerator();

    $lower = (int)$_POST['lower'];
    $upper = (int)$_POST['upper'];
    $numbers = (int)$_POST['numbers'];
    $specials = (int)$_POST['specials'];

    $generatedPassword = $generator->generate($lower, $upper, $numbers, $specials);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Sveikas, <?php echo $_SESSION['username']; ?></h2>

<p>Slaptazodziu generatorius</p>

<form method="POST">
    <input type="number" name="lower" placeholder="Mazosios" required>
    <input type="number" name="upper" placeholder="Didziosios" required>
    <input type="number" name="numbers" placeholder="Skaiciai" required>
    <input type="number" name="specials" placeholder="Specialus simboliai" required>
    <button type="submit">Generuoti</button>
</form>

<p><?php echo $generatedPassword; ?></p>

<a href="logout.php">Atsijungti</a>

</body>
</html>