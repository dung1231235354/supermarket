<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name = $conn->real_escape_string($_POST['name']);
$contact = $conn->real_escape_string($_POST['contact']);
$email = $conn->real_escape_string($_POST['email']);
$type = $conn->real_escape_string($_POST['type']);
$country = $conn->real_escape_string($_POST['country']);
$address = $conn->real_escape_string($_POST['address']);
$website = $conn->real_escape_string($_POST['website']);
$status = $conn->real_escape_string($_POST['status']);
$description = $conn->real_escape_string($_POST['description']);

$sql = "INSERT INTO supplier (name, contact, email, type, country, address, website, status, description) 
        VALUES ('$name', '$contact', '$email', '$type', '$country', '$address', '$website', '$status', '$description')";

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
if ($conn->query($sql) === TRUE) {
    echo "<h1>Success! Supplier Added.</h1>";
} else {
    echo "<h1 style='color:red;'>Error</h1><p>" . $conn->error . "</p>";
}
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>