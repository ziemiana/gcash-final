<?php
session_start();
include('database/connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['id'];

// Get user data
$sql = "SELECT * FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $balance = $user['balance'];
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash - Home</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($firstname); ?>!</h2>
    <p>Your Balance: ₱<?php echo number_format($balance, 2); ?></p>

    <h3>Actions</h3>
    <a href="sending.php">Send Money</a><br>
    <a href="logout.php">Logout</a>

</body>
</html>
