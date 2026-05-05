<?php
// Database connection
require_once "database_login_values.php";

// Table name
$table_name = "post";

// Fetch input values
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
$country = isset($_POST['country']) ? $_POST['country'] : '';
$terms_agreement = isset($_POST['terms_agreement']) ? $_POST['terms_agreement'] : '';

// Check if all fields are filled
if (empty($full_name) || empty($username) || empty($gender) || empty($email) || empty($password) || empty($confirm_password) || empty($date_of_birth) || empty($country) || empty($terms_agreement)) {
    echo "Please fill in all fields.";
    exit();
}

// Check if passwords match
if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit();
}

// Hash the password before storing it in the database (for security)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM $table_name WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email already exists
    echo "Email already exists.";
} else {
    // Email doesn't exist, insert the user into the database
    $stmt = $conn->prepare("INSERT INTO $table_name (full_name, username, gender, email, password, date_of_birth, country, terms_agreement) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $full_name, $username, $gender, $email, $hashed_password, $date_of_birth, $country, $terms_agreement);
    if ($stmt->execute()) {
        // Registration successful
        header("Location: ./welcome.html");
        exit;

    } else {
        // Error occurred while registering the user
        echo "Error: " . $stmt->error;
    }
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();
?>
