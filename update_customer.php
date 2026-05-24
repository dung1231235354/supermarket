<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = (int)$_POST['customer_id'];
$updates = [];

if (!empty($_POST['name'])) $updates[] = "name = '" . $conn->real_escape_string($_POST['name']) . "'";
if (!empty($_POST['phone'])) $updates[] = "phone = '" . $conn->real_escape_string($_POST['phone']) . "'";
if (!empty($_POST['email'])) $updates[] = "email = '" . $conn->real_escape_string($_POST['email']) . "'";
if (!empty($_POST['address'])) $updates[] = "address = '" . $conn->real_escape_string($_POST['address']) . "'";
if (!empty($_POST['gender'])) $updates[] = "gender = '" . $conn->real_escape_string($_POST['gender']) . "'";
if ($_POST['points'] !== "") $updates[] = "points = " . (int)$_POST['points'];
if (!empty($_POST['status'])) $updates[] = "status = '" . $conn->real_escape_string($_POST['status']) . "'";

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
if (count($updates) > 0) {
    $sql = "UPDATE customer SET " . implode(", ", $updates) . " WHERE customer_id = $id";
    if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
        echo "<h1>Success! Customer #$id updated.</h1>";
    } else {
        echo "<h1>No Changes Made.</h1><p>Ensure the ID exists.</p>";
    }
} else {
    echo "<h1>No Changes Made.</h1><p>You didn't fill out any new information.</p>";
}
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>