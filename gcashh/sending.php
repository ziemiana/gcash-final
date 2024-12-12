<?php
session_start();
include('database/connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['send_money'])) {
    $receiver_phone = $_POST['receiver_phone'];
    $amount = $_POST['amount'];
    $sender_id = $_SESSION['id'];

    // Get sender's balance
    $sql = "SELECT balance FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sender = $result->fetch_assoc();
    $sender_balance = $sender['balance'];

    // Ensure sufficient funds
    if ($sender_balance >= $amount) {
        // Get receiver's ID
        $sql = "SELECT id FROM users WHERE phone_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $receiver_phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $receiver = $result->fetch_assoc();
            $receiver_id = $receiver['id'];

            // Deduct from sender and add to receiver
            $sql = "UPDATE users SET balance = balance - ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $sender_id);
            $stmt->execute();

            $sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $receiver_id);
            $stmt->execute();

            // Insert transaction record
            $sql = "INSERT INTO transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iid", $sender_id, $receiver_id, $amount);
            $stmt->execute();

            echo "Transaction successful!";
        } else {
            echo "Receiver not found!";
        }
    } else {
        echo "Insufficient funds!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Money</title>
</head>
<body>
    <h2>Send Money</h2>
    <form method="post" action="">
        Receiver's Phone Number: <input type="text" name="receiver_phone" required><br>
        Amount: <input type="number" name="amount" required><br>
        <button type="submit" name="send_money">Send</button>
    </form>
</body>
</html>
