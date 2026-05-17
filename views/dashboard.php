<?php

session_start();

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Encryptor.php';
require_once '../classes/PasswordGenerator.php';
require_once '../classes/PasswordEntry.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$connection = $database->connect();

$userClass = new User($connection);
$passwordEntry = new PasswordEntry($connection);
$encryptor = new Encryptor();

$currentUser = $userClass->findByUsername($_SESSION['username']);

$generatedPassword = '';
$message = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate'])) {
        $generator = new PasswordGenerator();

        $lower = (int)$_POST['lower'];
        $upper = (int)$_POST['upper'];
        $numbers = (int)$_POST['numbers'];
        $specials = (int)$_POST['specials'];

        $total = $lower + $upper + $numbers + $specials;

        if ($lower < 0 || $upper < 0 || $numbers < 0 || $specials < 0) {
            $message = 'Negalima ivesti neigiamu skaiciu';
        } elseif ($total === 0) {
            $message = 'Bent vienas simbolis turi buti pasirinktas';
        } else {
            $generatedPassword = $generator->generate($lower, $upper, $numbers, $specials);
        }
    }

    if (isset($_POST['save'])) {
        $title = trim($_POST['title']);
        $password = trim($_POST['password']);
        $loginPassword = $_POST['login_password'];

        if (empty($title) || empty($password)) {
            $message = 'Uzpildyk visus laukus';
        } else {
        $plainKey = $encryptor->decrypt($currentUser['encrypted_key'], $loginPassword);

        if ($plainKey) {
            $encryptedPassword = $encryptor->encrypt($password, $plainKey);
            $passwordEntry->save($_SESSION['user_id'], $title, $encryptedPassword);
            $_SESSION['message'] = 'Slaptazodis issaugotas';
            header('Location: dashboard.php');
            exit;
        } else {
            $message = 'Neteisingas paskyros slaptazodis';
        }
        }
    }
}

$savedPasswords = $passwordEntry->getByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<h2>Sveikas, <?php echo $_SESSION['username']; ?></h2>

<p>Slaptazodziu generatorius</p>

<form method="POST">
    <input type="number" name="lower" placeholder="Mazosios" required>
    <input type="number" name="upper" placeholder="Didziosios" required>
    <input type="number" name="numbers" placeholder="Skaiciai" required>
    <input type="number" name="specials" placeholder="Specialus simboliai" required>
    <button type="submit" name="generate">Generuoti</button>
</form>

<p><?php echo $generatedPassword; ?></p>

<h3>Issaugoti slaptazodi</h3>

<form method="POST">
    <input type="text" name="title" placeholder="Pavadinimas" required>
    <input type="text" name="password" placeholder="Slaptazodis" value="<?php echo $generatedPassword; ?>" required>
    <input type="password" name="login_password" placeholder="Paskyros slaptazodis" required>
    <button type="submit" name="save">Issaugoti</button>
</form>

<p><?php echo $message; ?></p>

<h3>Mano irasai</h3>

<table border="1">
    <tr>
        <th>Pavadinimas</th>
        <th>Data</th>
        <th>Uzkoduotas slaptazodis</th>
    </tr>

    <?php foreach ($savedPasswords as $item): ?>
        <tr>
            <td><?php echo $item['title']; ?></td>
            <td><?php echo $item['created_at']; ?></td>
            <td><?php echo $item['encrypted_password']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>

<a href="dashboard.php">Pradzia</a>
<a href="logout.php">Atsijungti</a>

</body>
</html>