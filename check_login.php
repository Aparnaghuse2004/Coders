<?php
require_once "database_login_values.php";
$table_name = "post";
$email = $_POST['email']; 
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "SELECT * FROM $table_name WHERE username = '$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $cookie_name = "username";
        $cookie_value = $username;
        $cookie_expiry = time() + (86400 * 30); 
        setcookie($cookie_name, $cookie_value, $cookie_expiry, "/");
        header("Location: ./home.html");
      
        exit; 
    } else {
        echo "Incorrect password!";
        
    }
} else {
    echo "Username not found!";

}
$conn->close();
?>
