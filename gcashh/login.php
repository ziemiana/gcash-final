<?php
session_start();
include('database/connection.php');

if (isset($_POST['login'])) {
    $phone_number = $_POST['phone_number'];
    $mpin = $_POST['mpin'];

    $sql = "SELECT * FROM users WHERE phone_number = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify MPIN
            if (password_verify($mpin, $user['mpin'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['phone_number'] = $user['phone_number'];
                header('Location: home.php');
                exit();
            } else {
                echo "Invalid MPIN!";
            }
        } else {
            echo "User not found!";
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
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        Phone Number: <input type="text" name="phone_number" required><br>
        MPIN: <input type="password" name="mpin" required><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
