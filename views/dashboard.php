<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Sveikas, <?php echo $_SESSION['username']; ?></h2>

<p>Prisijungimas sekmingas</p>

</body>
</html>