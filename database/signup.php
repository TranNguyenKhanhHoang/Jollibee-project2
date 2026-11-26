cd /Applications/XAMPP/htdocs/Jollibee-project2 && cat > signup.php << 'EOF'
<?php
$pageTitle = "NovaCart | Sign Up";
require_once "settings.php";
mysqli_report(MYSQLI_REPORT_OFF);

$errors   = [];
$success  = false;
$username = "";
$email    = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm"] ?? "";

    if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
        $errors[] = "Username must be 3-20 characters (letters, numbers, underscore).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (!$errors) {
        $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

        if (!$conn) {
            $errors[] = "Database connection failed. Please try again later.";
        } else {
            mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(20) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ?");
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = "Username or email is already registered.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins  = mysqli_prepare($conn, "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($ins, "sss", $username, $email, $hash);

                if (mysqli_stmt_execute($ins)) {
                    $success  = true;
                    $username = "";
                    $email    = "";
                } else {
                    $errors[] = "Could not create account. Please try again.";
                }
                mysqli_stmt_close($ins);
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }
}

include "include/header.inc";
include "include/nav.inc";
?>

<main>
    <section class="container">

        <h1>Create an Account</h1>

        <?php if ($success): ?>
            <p>Account created successfully. <a href="login.php">Log in here</a>.</p>
        <?php endif; ?>

        <?php if ($errors): ?>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="signup.php" novalidate>

            <p>
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username" maxlength="20" required
                       value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">
            </p>

            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" maxlength="100" required
                       value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
            </p>

            <p>
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" minlength="8" required>
            </p>

            <p>
                <label for="confirm">Confirm Password</label><br>
                <input type="password" id="confirm" name="confirm" minlength="8" required>
            </p>

            <p>
                <button type="submit" class="btn">Sign Up</button>
            </p>

        </form>

        <p>Already have an account? <a href="login.php">Log in</a></p>

    </section>
</main>

<?php include "include/footer.inc"; ?>
EOF
git add -A && git commit -m "Add signup page" && git pull --rebase && git push
