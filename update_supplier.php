<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = (int)$_POST['supplier_id'];
$updates = [];

if (!empty($_POST['name'])) $updates[] = "name = '" . $conn->real_escape_string($_POST['name']) . "'";
if (!empty($_POST['contact'])) $updates[] = "contact = '" . $conn->real_escape_string($_POST['contact']) . "'";
if (!empty($_POST['email'])) $updates[] = "email = '" . $conn->real_escape_string($_POST['email']) . "'";
if (!empty($_POST['type'])) $updates[] = "type = '" . $conn->real_escape_string($_POST['type']) . "'";
if (!empty($_POST['country'])) $updates[] = "country = '" . $conn->real_escape_string($_POST['country']) . "'";
if (!empty($_POST['address'])) $updates[] = "address = '" . $conn->real_escape_string($_POST['address']) . "'";
if (!empty($_POST['website'])) $updates[] = "website = '" . $conn->real_escape_string($_POST['website']) . "'";
if (!empty($_POST['description'])) $updates[] = "description = '" . $conn->real_escape_string($_POST['description']) . "'";
if (!empty($_POST['status'])) $updates[] = "status = '" . $conn->real_escape_string($_POST['status']) . "'";

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
if (count($updates) > 0) {
    $sql = "UPDATE supplier SET " . implode(", ", $updates) . " WHERE supplier_id = $id";
    if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
        echo "<h1>Success! Supplier #$id updated.</h1>";
    } else {
        echo "<h1>No Changes Made.</h1><p>Ensure the ID exists.</p>";
    }
} else {
    echo "<h1>No Changes Made.</h1><p>You didn't fill out any new information.</p>";
}
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>