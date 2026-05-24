<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name = $conn->real_escape_string($_POST['name']);
$phone = $conn->real_escape_string($_POST['phone']);
$email = $conn->real_escape_string($_POST['email']);
$address = $conn->real_escape_string($_POST['address']);
$dob = empty($_POST['date_of_birth']) ? "NULL" : "'" . $conn->real_escape_string($_POST['date_of_birth']) . "'";
$reg_date = empty($_POST['registration_date']) ? "CURRENT_DATE" : "'" . $conn->real_escape_string($_POST['registration_date']) . "'";
$gender = $conn->real_escape_string($_POST['gender']);
$status = $conn->real_escape_string($_POST['status']);

$sql = "INSERT INTO customer (name, phone, email, address, date_of_birth, registration_date, gender, points, status) 
        VALUES ('$name', '$phone', '$email', '$address', $dob, $reg_date, '$gender', 0, '$status')";

echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
if ($conn->query($sql) === TRUE) {
    echo "<h1>Success! Customer Registered.</h1>";
} else {
    echo "<h1 style='color:red;'>Error</h1><p>" . $conn->error . "</p>";
}
echo "<br><a href='indexx.php' style='padding: 10px 20px; background: #f4c242; color: #000; text-decoration: none; font-weight: bold; border: 2px solid #000;'>← Back to Dashboard</a></div>";
$conn->close();
?>