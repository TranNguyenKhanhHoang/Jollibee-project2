<?php

session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username == "admin" && $password == "admin") {

        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;

        header("Location: manage.php");
        exit();
    }

    $error = "Invalid username or password.";

}
?>

<?php
$pageTitle = "Admin Login";
include("includes/header.inc");
include("includes/nav.inc");
?>

<h2>Administrator Login</h2>

<form action="login.php" method="post">

    <label>Username</label>

    <input type="text" name="username" required>

    <label>Password</label>

    <input type="password" name="password" required>

    <button type="submit">Login</button>

</form>

<p style="color:red;">
<?= $error ?>
</p>

<?php
include("includes/footer.inc");
?>