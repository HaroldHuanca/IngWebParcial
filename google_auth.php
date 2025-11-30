<?php
session_start();
require_once 'vendor/autoload.php';
include 'includes/conexion.php';
$config = include 'includes/social_config.php';

$client = new Google_Client();
$client->setClientId($config['google']['client_id']);
$client->setClientSecret($config['google']['client_secret']);
$client->setRedirectUri($config['google']['redirect_uri']);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if(isset($token['error'])){
             throw new Exception("Error fetching access token: " . $token['error']);
        }

        $client->setAccessToken($token['access_token']);

        // Get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        
        $email = $google_account_info->email;
        $name = $google_account_info->name;
        $google_id = $google_account_info->id;
        $picture = $google_account_info->picture;
        
        // Split name into first and last name
        $parts = explode(" ", $name);
        $last_name = array_pop($parts);
        $first_name = implode(" ", $parts);
        if(empty($first_name)) $first_name = $last_name; // Fallback

        // Check if user exists
        $sql = "SELECT * FROM users WHERE google_id = ? OR email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $google_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists
            $user = $result->fetch_assoc();
            
            // Update google_id if not set
            if (empty($user['google_id'])) {
                $update_sql = "UPDATE users SET google_id = ?, avatar = ? WHERE user_id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("ssi", $google_id, $picture, $user['user_id']);
                $update_stmt->execute();
            }
            
            // Login
            $_SESSION['usuario'] = $user['email'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
        } else {
            // Create new user
            $username = strstr($email, '@', true) . rand(1000, 9999); // Generate unique username
            $password_hash = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT); // Random password
            
            $default_phone = "000000";
            $default_address = "mi direccion";
            
            $insert_sql = "INSERT INTO users (first_name, last_name, username, email, password_hash, google_id, avatar, created_at, is_active, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1, ?, ?)";
            $insert_stmt = $con->prepare($insert_sql);
            $insert_stmt->bind_param("sssssssss", $first_name, $last_name, $username, $email, $password_hash, $google_id, $picture, $default_phone, $default_address);
            
            if ($insert_stmt->execute()) {
                $_SESSION['usuario'] = $email;
                $_SESSION['user_id'] = $con->insert_id;
                $_SESSION['is_admin'] = 0;
            } else {
                die("Error creando usuario: " . $con->error);
            }
        }
        
        // Handle Cart Logic (Copy from login.php)
        $user_id = $_SESSION['user_id'];
        
        // ... (Cart logic similar to login.php can be refactored into a function, but for now copying is safer to avoid breaking existing code structure if not refactoring everything)
        // Simplified Cart Logic for Social Login (Assuming basic cart merge or load)
         $sqlCart = "SELECT ci.* FROM cart_items ci JOIN shopping_carts sc ON ci.cart_id = sc.cart_id WHERE sc.user_id = $user_id;";
         $resCart = $con->query($sqlCart);
         
         if($resCart->num_rows == 0){
             if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                 // Create cart if not exists
                 $checkCart = $con->query("SELECT * FROM shopping_carts WHERE user_id = $user_id");
                 if($checkCart->num_rows == 0){
                     $con->query("INSERT INTO shopping_carts (cart_id, user_id, created_at) VALUES ($user_id, $user_id, NOW())");
                 }
                 
                 foreach ($_SESSION['cart'] as $bid => $item) {
                     $q = $item['quantity'];
                     $p = $item['price_at_time'];
                     $con->query("INSERT INTO cart_items (cart_id, book_id, quantity, price_at_time) VALUES ($user_id, $bid, $q, $p)");
                 }
             }
         } else {
             $_SESSION['cart'] = array();
             while ($row = $resCart->fetch_assoc()) {
                 $_SESSION['cart'][$row['book_id']] = ['quantity' => $row['quantity'], 'price_at_time' => $row['price_at_time']];
             }
         }

        header('Location: miPerfil.php');
        exit();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    // Generate URL and redirect
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
}
