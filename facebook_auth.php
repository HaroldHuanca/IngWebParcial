<?php
session_start();
require_once 'vendor/autoload.php';
include 'includes/conexion.php';
$config = include 'includes/social_config.php';

require_once 'includes/CustomFacebookCurlHttpClient.php';

/**
 * Descarga y guarda la foto de perfil de redes sociales
 * @param string $picture_url URL de la imagen
 * @param string $provider 'google' o 'facebook'
 * @param int $user_id ID del usuario
 * @return string Ruta relativa de la imagen guardada
 */
function downloadProfilePicture($picture_url, $provider, $user_id) {
    $img_dir = __DIR__ . '/img/';
    
    // Crear directorio si no existe
    if (!is_dir($img_dir)) {
        mkdir($img_dir, 0755, true);
    }
    
    // Determinar extensión
    $extension = 'jpg'; // Por defecto JPG
    
    // Descargar la imagen
    $image_content = @file_get_contents($picture_url);
    if ($image_content === false) {
        return null; // Si falla la descarga, retornar null
    }
    
    // Generar nombre del archivo
    $filename = $provider . '_' . $user_id . '.' . $extension;
    $filepath = $img_dir . $filename;
    
    // Guardar archivo
    if (file_put_contents($filepath, $image_content)) {
        return 'img/' . $filename; // Retornar ruta relativa
    }
    
    return null;
}

$fb = new \Facebook\Facebook([
  'app_id' => $config['facebook']['app_id'],
  'app_secret' => $config['facebook']['app_secret'],
  'default_graph_version' => $config['facebook']['default_graph_version'],
  'http_client_handler' => new \Facebook\HttpClients\CustomFacebookCurlHttpClient(),
]);

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['code'])) {
    try {
        $accessToken = $helper->getAccessToken();
        
        if (! isset($accessToken)) {
             throw new Exception("Error fetching access token");
        }
        
        $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
        $userNode = $response->getGraphUser();
        
        $facebook_id = $userNode->getId();
        $name = $userNode->getName();
        $email = $userNode->getEmail();
        $picture = $userNode->getPicture()->getUrl();
        
        // If email is missing (sometimes FB doesn't return it), generate a dummy one or handle error
        if (empty($email)) {
            $email = $facebook_id . "@facebook.com"; 
        }

        $parts = explode(" ", $name);
        $last_name = array_pop($parts);
        $first_name = implode(" ", $parts);
        if(empty($first_name)) $first_name = $last_name;

        // Check if user exists
        $sql = "SELECT * FROM users WHERE facebook_id = ? OR email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $facebook_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (empty($user['facebook_id'])) {
                // Download and save profile picture
                $avatar_path = downloadProfilePicture($picture, 'facebook', $user['user_id']);
                
                $update_sql = "UPDATE users SET facebook_id = ?, avatar = ? WHERE user_id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("ssi", $facebook_id, $avatar_path, $user['user_id']);
                $update_stmt->execute();
            }
            
            $_SESSION['usuario'] = $user['email'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
        } else {
            $username = strstr($email, '@', true) . rand(1000, 9999);
            $password_hash = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
            
            $default_phone = "000000";
            $default_address = "mi direccion";
            
            $insert_sql = "INSERT INTO users (first_name, last_name, username, email, password_hash, facebook_id, created_at, is_active, phone, address) VALUES (?, ?, ?, ?, ?, ?, NOW(), 1, ?, ?)";
            $insert_stmt = $con->prepare($insert_sql);
            $insert_stmt->bind_param("ssssssss", $first_name, $last_name, $username, $email, $password_hash, $facebook_id, $default_phone, $default_address);
            
            if ($insert_stmt->execute()) {
                $new_user_id = $con->insert_id;
                
                // Download and save profile picture
                $avatar_path = downloadProfilePicture($picture, 'facebook', $new_user_id);
                
                // Update avatar path
                $update_sql = "UPDATE users SET avatar = ? WHERE user_id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("si", $avatar_path, $new_user_id);
                $update_stmt->execute();
                
                $_SESSION['usuario'] = $email;
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['is_admin'] = 0;
            } else {
                die("Error creando usuario: " . $con->error);
            }
        }

        // Cart Logic
        $user_id = $_SESSION['user_id'];
         $sqlCart = "SELECT ci.* FROM cart_items ci JOIN shopping_carts sc ON ci.cart_id = sc.cart_id WHERE sc.user_id = $user_id;";
         $resCart = $con->query($sqlCart);
         
         if($resCart->num_rows == 0){
             if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
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

    } catch(Exception $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    }
} else {
    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl($config['facebook']['redirect_uri'], $permissions);
    header('Location: ' . $loginUrl);
    exit();
}
