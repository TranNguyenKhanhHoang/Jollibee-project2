<?php
include("auth.inc");
require_once("settings.php");

$pageTitle = "Manage EOIs";
include("header.inc");
include("nav.inc");

function eoi_h($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$messages = [];
$errors = [];
$allowedStatuses = ["New", "Current", "Final"];
$allowedSortFields = [
    "EOInumber" => "EOInumber",
    "Job reference" => "jobref",
    "First name" => "firstname",
    "Last name" => "lastname",
    "Status" => "status"
];

// Update one EOI status. Only the three database-defined values are accepted.
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_status") {
    $eoiNumber = filter_var($_POST["EOInumber"] ?? null, FILTER_VALIDATE_INT);
    $newStatus = trim((string)($_POST["status"] ?? ""));

    if (!$eoiNumber || !in_array($newStatus, $allowedStatuses, true)) {
        $errors[] = "Select a valid EOI and status.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $newStatus, $eoiNumber);
            if (mysqli_stmt_execute($stmt)) {
                $messages[] = "EOI #" . $eoiNumber . " status updated.";
            } else {
                $errors[] = "The status could not be updated.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "The status update could not be prepared.";
        }
    }
}

// Delete all applications for a job reference after an explicit form confirmation.
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete_job") {
    $deleteJobref = strtoupper(trim((string)($_POST["delete_jobref"] ?? "")));
    $confirmed = ($_POST["confirm_delete"] ?? "") === "yes";

    if (!preg_match("/^[A-Za-z0-9]{5}$/", $deleteJobref)) {
        $errors[] = "Enter a valid 5-character job reference.";
    } elseif (!$confirmed) {
        $errors[] = "Tick the confirmation box before deleting these EOIs.";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE jobref = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $deleteJobref);
            if (mysqli_stmt_execute($stmt)) {
                $messages[] = mysqli_stmt_affected_rows($stmt) . " EOI(s) deleted for job reference " . $deleteJobref . ".";
            } else {
                $errors[] = "The EOIs could not be deleted.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "The delete request could not be prepared.";
        }
    }
}

// Read search filters from the query string.
$jobref = strtoupper(trim((string)($_GET["jobref"] ?? "")));
$firstName = trim((string)($_GET["firstname"] ?? ""));
$lastName = trim((string)($_GET["lastname"] ?? ""));
$sortKey = (string)($_GET["sort"] ?? "EOInumber");
$sortDirection = strtoupper((string)($_GET["direction"] ?? "ASC"));
if (!array_key_exists($sortKey, $allowedSortFields)) $sortKey = "EOInumber";
if (!in_array($sortDirection, ["ASC", "DESC"], true)) $sortDirection = "ASC";

$where = [];
$params = [];
$types = "";
if ($jobref !== "") {
    $where[] = "jobref = ?";
    $params[] = $jobref;
    $types .= "s";
}
if ($firstName !== "") {
    $where[] = "firstname LIKE ?";
    $params[] = "%" . $firstName . "%";
    $types .= "s";
}
if ($lastName !== "") {
    $where[] = "lastname LIKE ?";
    $params[] = "%" . $lastName . "%";
    $types .= "s";
}

$sql = "SELECT EOInumber, jobref, firstname, lastname, status FROM eoi";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY " . $allowedSortFields[$sortKey] . " " . $sortDirection;

$result = false;
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    if ($params) {
        $bindArgs = [$stmt, $types];
        foreach ($params as $index => $value) {
            $bindArgs[] = &$params[$index];
        }
        call_user_func_array("mysqli_stmt_bind_param", $bindArgs);
    }
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $errors[] = "The EOI search could not be completed.";
    }
} else {
    $errors[] = "The EOI search could not be prepared.";
}
?>

    <h2>Manage Applications</h2>

    <?php foreach ($messages as $message): ?>
        <p class="success-message"><?= eoi_h($message) ?></p>
    <?php endforeach; ?>
    <?php foreach ($errors as $error): ?>
        <p class="error-message"><?= eoi_h($error) ?></p>
    <?php endforeach; ?>

    <section>
        <h3>Search and sort EOIs</h3>
        <form method="get" action="manage.php">
            <label for="jobref">Job reference</label>
            <input id="jobref" name="jobref" type="text" maxlength="5" value="<?= eoi_h($jobref) ?>">

            <label for="firstname">Applicant first name</label>
            <input id="firstname" name="firstname" type="text" value="<?= eoi_h($firstName) ?>">

            <label for="lastname">Applicant last name</label>
            <input id="lastname" name="lastname" type="text" value="<?= eoi_h($lastName) ?>">

            <label for="sort">Sort by</label>
            <select id="sort" name="sort">
                <?php foreach ($allowedSortFields as $label => $column): ?>
                    <option value="<?= eoi_h($label) ?>" <?= $sortKey === $label ? "selected" : "" ?>><?= eoi_h($label) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="direction">Direction</label>
            <select id="direction" name="direction">
                <option value="ASC" <?= $sortDirection === "ASC" ? "selected" : "" ?>>Ascending</option>
                <option value="DESC" <?= $sortDirection === "DESC" ? "selected" : "" ?>>Descending</option>
            </select>

            <button type="submit">Search</button>
            <a href="manage.php">Clear filters</a>
        </form>
    </section>

    <section>
        <h3>Delete EOIs by job reference</h3>
        <form method="post" action="manage.php">
            <input type="hidden" name="action" value="delete_job">
            <label for="delete_jobref">Job reference</label>
            <input id="delete_jobref" name="delete_jobref" type="text" maxlength="5" required>
            <label>
                <input type="checkbox" name="confirm_delete" value="yes" required>
                I understand this will delete every EOI with this job reference.
            </label>
            <button type="submit">Delete matching EOIs</button>
        </form>
    </section>

    <section>
        <h3>EOI results</h3>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>EOI Number</th>
                        <th>Job Reference</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= eoi_h($row["EOInumber"]) ?></td>
                            <td><?= eoi_h($row["jobref"]) ?></td>
                            <td><?= eoi_h($row["firstname"]) ?></td>
                            <td><?= eoi_h($row["lastname"]) ?></td>
                            <td><?= eoi_h($row["status"]) ?></td>
                            <td>
                                <form method="post" action="manage.php">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="EOInumber" value="<?= eoi_h($row["EOInumber"]) ?>">
                                    <label class="visually-hidden" for="status-<?= eoi_h($row["EOInumber"]) ?>">New status for EOI <?= eoi_h($row["EOInumber"]) ?></label>
                                    <select id="status-<?= eoi_h($row["EOInumber"]) ?>" name="status">
                                        <?php foreach ($allowedStatuses as $status): ?>
                                            <option value="<?= eoi_h($status) ?>" <?= $row["status"] === $status ? "selected" : "" ?>><?= eoi_h($status) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif (!$errors): ?>
            <p>No EOIs matched your search.</p>
        <?php endif; ?>
    </section>
<?php
if ($result) mysqli_free_result($result);
if ($stmt) mysqli_stmt_close($stmt);
?>
<p><a href="logout.php">Logout</a></p>
<?php
include("footer.inc");
?>


