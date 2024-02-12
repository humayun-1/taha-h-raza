<?php

// Connect to the database (replace with your credentials)
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'Contact';

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die('Database connection failed: ' . mysqli_error($conn));

// Sanitize form data to prevent SQL injection
$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$subject = mysqli_real_escape_string($conn, $_POST['subject']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email format.';
    exit;
}

// Prepare and execute the INSERT query
$sql = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql) or die('Failed to prepare statement: ' . mysqli_error($conn));
mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message) or die('Failed to bind parameters: ' . mysqli_error($conn));
$stmt->execute() or die('Failed to insert data: ' . mysqli_error($conn));

// Send email notification (optional)
if (empty($_POST['noreply'])) {
    $to = $myemail; // Replace with your recipient address
    $email_subject = "$subject";
    $email_body = "\n Name: $name \n Email: $email \n Subject: $subject \n Message: \n $message";
    $headers = "From: $email";

    mail($to, $email_subject, $email_body, $headers);
}

// Close the database connection
mysqli_close($conn);

// Thank the user and provide feedback
echo 'Your message has been sent successfully!';

?>
