<?php
include('database/connection.php');

if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone_number = $_POST['phone_number'];
    $mpin = password_hash($_POST['mpin'], PASSWORD_DEFAULT); // Hash the MPIN for security

    // Insert into the users table
    $sql = "INSERT INTO users (firstname, lastname, phone_number, mpin) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $firstname, $lastname, $phone_number, $mpin);
        if ($stmt->execute()) {
            echo "Registration successful!";
            header('Location: login.php');  // Redirect to login after successful registration
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Create Account</h2>
    <form method="post" action="">
        First Name: <input type="text" name="firstname" required><br>
        Last Name: <input type="text" name="lastname" required><br>
        Phone Number: <input type="text" name="phone_number" required><br>
        MPIN: <input type="password" name="mpin" required><br>
        <button type="submit" name="register">Register</button>
    </form>
</body>
</html> 