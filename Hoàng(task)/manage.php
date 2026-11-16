<?php

include("includes/auth.inc");

require_once("settings.php");

$pageTitle = "Manage EOIs";

include("includes/header.inc");
include("includes/nav.inc");

$sql = "SELECT * FROM eoi";

$result = mysqli_query($conn, $sql);

?>

<h2>Manage Applications</h2>

<table border="1">

<tr>

<th>EOI Number</th>
<th>First Name</th>
<th>Last Name</th>
<th>Status</th>

</tr>

<?php

while($row = mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?= $row["EOInumber"] ?></td>

<td><?= $row["FirstName"] ?></td>

<td><?= $row["LastName"] ?></td>

<td><?= $row["Status"] ?></td>

</tr>

<?php

}

?>

</table>

<p>

<a href="logout.php">Logout</a>

</p>

<?php

include("includes/footer.inc");

?>