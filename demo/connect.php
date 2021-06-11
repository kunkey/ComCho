<?php
$db = array(
    'host' => 'localhost',
    'user' => 'admin_qlsach',
    'pass' => '12341234',
    'db' => 'admin_qlsach'
);

// Tạo Kết Nối
$conn = mysqli_connect(
    $db['host'], 
    $db['user'], 
    $db['pass'],
    $db['db']
);

// Kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Change character set to utf8
mysqli_set_charset("utf8");

?>