<?php include 'includes/header.inc'; ?>
<?php
// Accept POST requests only; redirect direct access to the application form.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit;
}

// Normalize input here; escape values only when rendering HTML.
function clean_input($value): string { return trim((string)$value); }
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); }

$jobref = clean_input($_POST["jobref"] ?? "");
$position = clean_input($_POST["position"] ?? "");
$firstname = clean_input($_POST["firstname"] ?? "");
$lastname = clean_input($_POST["lastname"] ?? "");
$dobInput = clean_input($_POST["dob"] ?? "");
$gender = clean_input($_POST["gender"] ?? "");
$street = clean_input($_POST["street"] ?? "");
$suburb = clean_input($_POST["suburb"] ?? "");
$state = clean_input($_POST["state"] ?? "");
$postcode = clean_input($_POST["postcode"] ?? "");
$email = clean_input($_POST["email"] ?? "");
$phone = clean_input($_POST["phone"] ?? "");
$otherskills = clean_input($_POST["otherskills"] ?? "");
$skills = $_POST["skills"] ?? [];

$errors = [];
$positions = ["Front-End Web Developer", "UX/UI Designer", "Digital Marketing Specialist"];
$genders = ["Female", "Male", "Other", "Prefer not to say"];
$states = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
$allowedSkills = ["HTML5", "CSS3", "JavaScript", "Responsive design", "Accessibility", "Figma", "User research", "SEO/SEM", "Social media marketing", "Analytics"];

if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobref)) $errors[] = "Job reference must contain exactly 5 letters or numbers.";
if (!in_array($position, $positions, true)) $errors[] = "Select a valid position.";
if ($firstname === "" || strlen($firstname) > 20 || !preg_match("/^[A-Za-z]+$/", $firstname)) $errors[] = "First name is required and must contain up to 20 letters.";
if ($lastname === "" || strlen($lastname) > 20 || !preg_match("/^[A-Za-z]+$/", $lastname)) $errors[] = "Last name is required and must contain up to 20 letters.";
$date = DateTime::createFromFormat('!d/m/Y', $dobInput);
$dateErrors = DateTime::getLastErrors();
if (!$date || ($dateErrors !== false && ($dateErrors['warning_count'] || $dateErrors['error_count'])) || $date->format('d/m/Y') !== $dobInput) {
    $errors[] = "Enter a valid date of birth in dd/mm/yyyy format.";
} else { $dob = $date->format('Y-m-d'); }
if (!in_array($gender, $genders, true)) $errors[] = "Select a valid gender option.";
if ($street === "" || strlen($street) > 40) $errors[] = "Street address is required and must be at most 40 characters.";
if ($suburb === "" || strlen($suburb) > 40) $errors[] = "Suburb/town is required and must be at most 40 characters.";
if (!in_array($state, $states, true)) $errors[] = "Select a valid state.";
if (!preg_match("/^[0-9]{4}$/", $postcode)) $errors[] = "Postcode must contain exactly 4 digits.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) $errors[] = "Enter a valid email address (maximum 254 characters).";
if (!preg_match("/^[0-9]{8,12}$/", $phone)) $errors[] = "Phone number must contain 8 to 12 digits.";
if (!is_array($skills)) { $skills = []; $errors[] = "Invalid skills selection."; }
else {
    $skills = array_values(array_unique(array_map('clean_input', $skills)));
    if (!$skills) $errors[] = "Select at least one skill.";
    foreach ($skills as $skill) if (!in_array($skill, $allowedSkills, true)) { $errors[] = "One or more selected skills are invalid."; break; }
}
if (strlen($otherskills) > 5000) $errors[] = "Other skills must be at most 5000 characters.";

$eoiNumber = null;
if (!$errors) {
    // Update these settings to match the database account used by your project.
    $dbHost = "localhost";
    $dbName = "jollibee_careers";
    $dbUser = "root";
    $dbPassword = "";
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPassword, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $sql = "INSERT INTO eoi (jobref, position, firstname, lastname, dob, gender, street, suburb, state, postcode, email, phone, skills, otherskills)
                VALUES (:jobref, :position, :firstname, :lastname, :dob, :gender, :street, :suburb, :state, :postcode, :email, :phone, :skills, :otherskills)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':jobref' => $jobref, ':position' => $position, ':firstname' => $firstname, ':lastname' => $lastname,
            ':dob' => $dob, ':gender' => $gender, ':street' => $street, ':suburb' => $suburb, ':state' => $state,
            ':postcode' => $postcode, ':email' => $email, ':phone' => $phone,
            ':skills' => json_encode($skills, JSON_UNESCAPED_UNICODE), ':otherskills' => $otherskills
        ]);
        $eoiNumber = (int)$pdo->lastInsertId();
    } catch (PDOException $exception) {
        error_log("EOI insert failed: " . $exception->getMessage());
        $errors[] = "We could not save your application right now. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application details | NovaCart Careers</title>
    <link rel="stylesheet" href="Styles/styles.css">
    <link rel="stylesheet" href="Styles/applystyle.css">
    <style>
        .confirmation { width: min(90%, 900px); margin: 40px auto; }
        .confirmation .application-card { width: 100%; padding: 0; overflow: hidden; border-radius: 18px; background: white; box-shadow: 0 12px 32px rgba(30, 57, 50, .12); }
        .application-top { padding: 32px 38px; color: white; background: linear-gradient(120deg, #004d35, #006241 65%, #168264); }
        .application-top h2 { margin: 0 0 6px; color: white; font-size: 2rem; }
        .application-top p { margin: 0; color: #e4f1eb; }
        .application-columns { display: grid; grid-template-columns: 1fr 1.6fr; }
        .application-side, .application-content { padding: 28px 32px; }
        .application-side { background: #f4f7f5; }
        .application-group { margin: 0 0 24px; padding: 0; }
        .application-group:last-child { margin-bottom: 0; }
        .application-group h3 { margin: 0 0 12px; padding: 0 0 8px; border-bottom: 2px solid #d4e9e2; color: #006241; font-size: 1rem; }
        .application-item { margin-bottom: 12px; overflow-wrap: anywhere; }
        .application-item strong { display: block; color: #687b74; font-size: .8rem; }
        .application-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .application-tags span { padding: 7px 11px; border-radius: 20px; color: #006241; background: #d4e9e2; }
        .actions { display: flex; align-items: center; gap: 12px; margin-top: 24px; }
        .actions a { padding: 12px 22px; border-radius: 30px; color: white; background: #006241; text-decoration: none; }
        .print-note { margin: 0; color: #006241; font-style: italic; }
        .error { padding: 16px; border-radius: 8px; color: #842029; background: #f8d7da; }

        @media (max-width: 680px) {
            .application-columns { grid-template-columns: 1fr; }
            .application-top { padding: 26px 24px; }
            .application-side, .application-content { padding: 24px; }
            .actions { flex-direction: column; }
            .actions a { text-align: center; }
        }

        @page {
            size: A4;
            margin: 9mm;
        }

        @media print {
            html, body {
                background: white !important;
                font-size: 9pt;
            }

            header {
                padding: 0 0 10px !important;
                color: #1e3932 !important;
                background: white !important;
                text-align: center;
            }

            header::after {
                display: none !important;
            }

            header h1 {
                font-size: 20pt !important;
            }

            header p {
                color: #555 !important;
                font-size: 10pt !important;
            }

            .confirmation {
                width: 100% !important;
                margin: 8px auto !important;
            }

            .confirmation > h2 {
                margin: 0 0 4px !important;
                font-size: 15pt !important;
            }

            .confirmation > p {
                margin: 0 0 10px !important;
            }

            .confirmation .application-card {
                border: 1px solid #dce7e1;
                box-shadow: none;
                break-inside: avoid;
            }

            .application-top {
                padding: 14px 22px;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .application-top h2 {
                font-size: 19pt;
            }

            .application-columns {
                grid-template-columns: 1fr 1.6fr;
            }

            .application-side, .application-content {
                padding: 14px 20px;
            }

            .application-side {
                background: #f4f7f5 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .application-group {
                margin-bottom: 12px;
                break-inside: avoid;
            }

            .application-group h3 {
                margin-bottom: 6px;
                padding-bottom: 4px;
                font-size: 9pt;
            }

            .application-item {
                margin-bottom: 6px;
                font-size: 9pt;
            }

            .application-item strong {
                font-size: 7.5pt;
            }

            .application-tags {
                gap: 5px;
            }

            .application-tags span {
                padding: 4px 8px;
                background: #d4e9e2 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .actions, footer {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>NovaCart Careers</h1>
    <p>Application details</p>
</header>

<main class="confirmation">
<?php if ($errors): ?>
    <h2>Please check your information</h2>
    <div class="error">
        <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= e($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="actions">
        <a href="apply.php">Return to form</a>
    </div>
<?php else: ?>
    <h2>Application submitted successfully</h2>
    <p>Your application has been saved. Your EOI number is <strong><?= e($eoiNumber) ?></strong>.</p>

    <div class="application-card">
        <div class="application-top">
            <h2><?= e($firstname) ?> <?= e($lastname) ?></h2>
            <p><?= e($position) ?> · Reference <?= e($jobref) ?></p>
        </div>

        <div class="application-columns">
            <div class="application-side">
                <section class="application-group">
                    <h3>Contact</h3>
                    <div class="application-item"><strong>Email</strong><?= e($email) ?></div>
                    <div class="application-item"><strong>Phone</strong><?= e($phone) ?></div>
                </section>

                <section class="application-group">
                    <h3>Personal details</h3>
                    <div class="application-item"><strong>Date of birth</strong><?= e($dobInput) ?></div>
                    <div class="application-item"><strong>Gender</strong><?= e($gender) ?></div>
                </section>

                <section class="application-group">
                    <h3>Address</h3>
                    <div class="application-item"><strong>Street</strong><?= e($street) ?></div>
                    <div class="application-item"><strong>Suburb / Town</strong><?= e($suburb) ?></div>
                    <div class="application-item"><strong>State / Postcode</strong><?= e($state) ?> <?= e($postcode) ?></div>
                </section>
            </div>

            <div class="application-content">
                <section class="application-group">
                    <h3>Position</h3>
                    <div class="application-item"><strong>Applied for</strong><?= e($position) ?></div>
                    <div class="application-item"><strong>Job reference</strong><?= e($jobref) ?></div>
                </section>

                <section class="application-group">
                    <h3>Skills</h3>
                    <div class="application-tags">
                    <?php foreach ($skills as $skill): ?>
                        <span><?= e($skill) ?></span>
                    <?php endforeach; ?>
                    </div>
                </section>

                <?php if ($otherskills !== ""): ?>
                    <section class="application-group">
                        <h3>Other skills</h3>
                        <p><?= nl2br(e($otherskills)) ?></p>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="actions">
        <a href="apply.php">Go back and edit</a>
        <p class="print-note">*To print this page, press Ctrl + P.</p>
    </div>
<?php endif; ?>
</main>

<footer>&copy; 2026 NovaCart. All rights reserved.</footer>
</body>
</html>
<?php include 'includes/footer.inc'; ?>