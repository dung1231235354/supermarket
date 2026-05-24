<?php

$product_id = $_POST["product_id"];
$price = $_POST["price"];
$stock = $_POST["stock"];
$status = $_POST["status"];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "UPDATE product SET price='$price', stock='$stock', status='$status' WHERE product_id='$product_id'";

if ($conn->query($sql) === TRUE) {
    if ($conn->affected_rows > 0) {
        echo "<h3>Product ID $product_id updated successfully!</h3>";
        
        
        $verify_sql = "SELECT product_id, name, category, price, stock, status FROM product WHERE product_id='$product_id'";
        $result = $conn->query($verify_sql);
        
        if ($result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr style='background-color: #f2f2f2;'><th style='padding: 8px;'>ID</th><th style='padding: 8px;'>Name</th><th style='padding: 8px;'>Category</th><th style='padding: 8px;'>Price</th><th style='padding: 8px;'>Stock</th><th style='padding: 8px;'>Status</th></tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='padding: 8px;'>" . $row["product_id"]. "</td>";
                echo "<td style='padding: 8px;'>" . $row["name"]. "</td>";
                echo "<td style='padding: 8px;'>" . $row["category"]. "</td>";
                echo "<td style='padding: 8px;'>" . $row["price"]. "</td>";
                echo "<td style='padding: 8px;'>" . $row["stock"]. "</td>";
                echo "<td style='padding: 8px;'>" . $row["status"]. "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "No record found with that ID.";
    }
} else {
    echo "Error updating record: " . $conn->error;
}
$conn->close();
?>