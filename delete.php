<?php
$conn = new mysqli("localhost", "root", "", "inventory_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$table = $_POST['table_name'];
$id = (int)$_POST['record_id'];
$success = false;
$error_message = "";

// 1. Chỉ cho phép thao tác trên 4 bảng này để bảo mật
$allowed_tables = ['product', 'customer', 'supplier', 'transaction'];

if (in_array($table, $allowed_tables) && $id > 0) {
    
    // 2. Xác định tên cột Khóa chính (Primary Key) dựa trên tên bảng
    $primary_key = $table . "_id"; 

    // 3. Thực thi lệnh xóa an toàn
    $sql = "DELETE FROM `$table` WHERE `$primary_key` = $id";
    
    if ($conn->query($sql) === TRUE) {
        if ($conn->affected_rows > 0) {
            $success = true;
        } else {
            $error_message = "Không tìm thấy dữ liệu! ID #$id không tồn tại trong bảng $table.";
        }
    } else {
        $error_message = "Lỗi hệ thống: " . $conn->error;
    }
} else {
    $error_message = "Yêu cầu không hợp lệ.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Result</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #fdf8e7; text-align: center; padding: 50px; }
        .card { background: #fff; border: 2px solid #000; padding: 40px; display: inline-block; box-shadow: 8px 8px 0px #000; max-width: 600px;}
        .cta-btn { background-color: #f4c242; color: #000; border: 2px solid #000; padding: 15px 30px; font-weight: 700; text-transform: uppercase; text-decoration: none; display: inline-block; margin-top: 20px; }
        .error { color: #d32f2f; }
        .success { color: #2e7d32; }
    </style>
</head>
<body>
    <div class="card">
        <?php
        if ($success) {
            echo "<h1 class='success'>Đã Xóa Thành Công!</h1>";
            echo "<p>Bản ghi có ID <strong>#$id</strong> đã bị xóa vĩnh viễn khỏi bảng <strong>" . ucfirst($table) . "</strong>.</p>";
        } else {
            echo "<h1 class='error'>Không thể xóa</h1>";
            echo "<p>$error_message</p>";
        }
        $conn->close();
        ?>
        <br><a href="indexx.php" class="cta-btn">← Quay lại Dashboard</a>
    </div>
</body>
</html>