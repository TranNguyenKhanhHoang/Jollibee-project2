<?php

session_start();
mysqli_report(MYSQLI_REPORT_OFF);
require_once "settings.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Administrator account (access to manage.php)
    if ($username == "admin" && $password == "admin") {

        session_regenerate_id(true);
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;

        header("Location: manage.php");
        exit();
    }

    // Registered users (no access to manage.php)
    $stmt = mysqli_prepare($conn, "SELECT password_hash FROM users WHERE username = ?");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hash);

        if (mysqli_stmt_fetch($stmt) && password_verify($password, $hash)) {
            mysqli_stmt_close($stmt);

            session_regenerate_id(true);
            $_SESSION["user"] = $username;

            header("Location: index.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    }

    $error = "Invalid username or password.";

}
?>

<?php
$pageTitle = "Login";
include("include/header.inc");
include("include/nav.inc");
?>

<h2>Login</h2>

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

<p>No account yet? <a href="signup.php">Sign up</a></p>

<?php
include("include/footer.inc");
?>
