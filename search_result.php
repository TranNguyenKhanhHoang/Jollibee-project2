<?php
require_once "settings.php";
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['job_title'])) {
    $job_title = mysqli_real_escape_string($conn, $_GET['job_title']);
    $sql = "SELECT * FROM jobs WHERE title LIKE '%$job_title%'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<h2>Search Results for: " . htmlspecialchars($_GET['job_title']) . "</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr>
                <th>Job ID</th>
                <th>Title</th>
                <th>Reference Number</th>
                <th>Description</th>
                <th>Salary</th>
                <th>Reporting Line</th>
              </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['job_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['reference_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
            echo "<td>" . htmlspecialchars($row['reporting_line']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><a href='jobs.php'>← Back to Jobs</a>";
    } else {
        echo "<p>No matching jobs found.</p>";
        echo "<br><a href='jobs.php'>← Back to Jobs</a>";
    }
} else {
    echo "Please enter a job title to search.";
    echo "<br><a href='jobs.php'>← Back to Jobs</a>";
}

mysqli_close($conn);
?>