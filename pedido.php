<?php 
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'includes/conexion.php';

    $user_id = intval($_SESSION['user_id']);
    $cart = $_SESSION['cart'] ?? [];
    $total_amount = floatval($_GET['total_amount'] ?? 0);
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $address = $user['address'];
    $sql = "insert into orders (user_id, order_date, total_amount, shipping_address, shipping_cost, status, payment_status)
            values (?, NOW(), ?, ?, 10, 'Processing', 'Pending')";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ids", $user_id, $total_amount, $address);
    $stmt->execute();
    $sql = "SELECT LAST_INSERT_ID() as order_id";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $order_id = $row['order_id'];
    foreach($cart as $book_id => $items) {
        $book_id = intval($book_id);
        $price_at_time = floatval($items['price_at_time']);
        $quantity = intval($items['quantity']);

        // Insertar en order_items
        $sql = "INSERT INTO order_items (order_id, book_id, quantity, price_at_time)
         VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiii", $order_id, $book_id, $quantity, $price_at_time);
        $stmt->execute();

        //Eliminamos de shooping_cart y de cart_items
       
        $sql = "DELETE FROM cart_items WHERE cart_id = ? AND book_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
    }
    $sql = "DELETE FROM shopping_carts WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $_SESSION['cart'] = [];
    header('Location: miPerfil.php?order=true');
    exit();
        ?>