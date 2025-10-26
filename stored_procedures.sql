USE bookport_db;

DELIMITER //

-- =============================================
-- Procedimientos para Users
-- =============================================

CREATE PROCEDURE sp_CreateUser(
    IN p_username VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_password_hash VARCHAR(255),
    IN p_first_name VARCHAR(50),
    IN p_last_name VARCHAR(50),
    IN p_phone VARCHAR(15),
    IN p_address TEXT
)
BEGIN
    INSERT INTO users (username, email, password_hash, first_name, last_name, phone, address)
    VALUES (p_username, p_email, p_password_hash, p_first_name, p_last_name, p_phone, p_address);
    SELECT LAST_INSERT_ID() as user_id;
END //

CREATE PROCEDURE sp_GetUser(
    IN p_user_id INT
)
BEGIN
    SELECT * FROM users WHERE user_id = p_user_id;
END //

CREATE PROCEDURE sp_UpdateUser(
    IN p_user_id INT,
    IN p_username VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_first_name VARCHAR(50),
    IN p_last_name VARCHAR(50),
    IN p_phone VARCHAR(15),
    IN p_address TEXT
)
BEGIN
    UPDATE users 
    SET username = p_username,
        email = p_email,
        first_name = p_first_name,
        last_name = p_last_name,
        phone = p_phone,
        address = p_address
    WHERE user_id = p_user_id;
END //

CREATE PROCEDURE sp_DeleteUser(
    IN p_user_id INT
)
BEGIN
    UPDATE users SET is_active = FALSE WHERE user_id = p_user_id;
END //

-- =============================================
-- Procedimientos para Books
-- =============================================

CREATE PROCEDURE sp_CreateBook(
    IN p_title VARCHAR(255),
    IN p_isbn VARCHAR(13),
    IN p_description TEXT,
    IN p_price DECIMAL(10,2),
    IN p_stock INT,
    IN p_cover_image_url VARCHAR(255),
    IN p_publication_date DATE,
    IN p_publisher VARCHAR(100),
    IN p_language VARCHAR(50),
    IN p_page_count INT,
    IN p_format VARCHAR(50)
)
BEGIN
    INSERT INTO books (title, isbn, description, price, stock, cover_image_url, 
                      publication_date, publisher, language, page_count, format)
    VALUES (p_title, p_isbn, p_description, p_price, p_stock, p_cover_image_url,
            p_publication_date, p_publisher, p_language, p_page_count, p_format);
    SELECT LAST_INSERT_ID() as book_id;
END //

CREATE PROCEDURE sp_GetBook(
    IN p_book_id INT
)
BEGIN
    SELECT b.*, 
           GROUP_CONCAT(DISTINCT CONCAT(a.first_name, ' ', a.last_name)) as authors,
           GROUP_CONCAT(DISTINCT c.name) as categories
    FROM books b
    LEFT JOIN book_authors ba ON b.book_id = ba.book_id
    LEFT JOIN authors a ON ba.author_id = a.author_id
    LEFT JOIN book_categories bc ON b.book_id = bc.book_id
    LEFT JOIN categories c ON bc.category_id = c.category_id
    WHERE b.book_id = p_book_id
    GROUP BY b.book_id;
END //

CREATE PROCEDURE sp_UpdateBook(
    IN p_book_id INT,
    IN p_title VARCHAR(255),
    IN p_isbn VARCHAR(13),
    IN p_description TEXT,
    IN p_price DECIMAL(10,2),
    IN p_stock INT,
    IN p_cover_image_url VARCHAR(255),
    IN p_publication_date DATE,
    IN p_publisher VARCHAR(100),
    IN p_language VARCHAR(50),
    IN p_page_count INT,
    IN p_format VARCHAR(50)
)
BEGIN
    UPDATE books 
    SET title = p_title,
        isbn = p_isbn,
        description = p_description,
        price = p_price,
        stock = p_stock,
        cover_image_url = p_cover_image_url,
        publication_date = p_publication_date,
        publisher = p_publisher,
        language = p_language,
        page_count = p_page_count,
        format = p_format
    WHERE book_id = p_book_id;
END //

CREATE PROCEDURE sp_DeleteBook(
    IN p_book_id INT
)
BEGIN
    DELETE FROM book_authors WHERE book_id = p_book_id;
    DELETE FROM book_categories WHERE book_id = p_book_id;
    DELETE FROM books WHERE book_id = p_book_id;
END //

-- =============================================
-- Procedimientos para Shopping Cart
-- =============================================

CREATE PROCEDURE sp_CreateCart(
    IN p_user_id INT
)
BEGIN
    INSERT INTO shopping_carts (user_id) VALUES (p_user_id);
    SELECT LAST_INSERT_ID() as cart_id;
END //

CREATE PROCEDURE sp_AddToCart(
    IN p_cart_id INT,
    IN p_book_id INT,
    IN p_quantity INT
)
BEGIN
    INSERT INTO cart_items (cart_id, book_id, quantity, price_at_time)
    SELECT p_cart_id, p_book_id, p_quantity, price
    FROM books WHERE book_id = p_book_id
    ON DUPLICATE KEY UPDATE quantity = quantity + p_quantity;
END //

CREATE PROCEDURE sp_UpdateCartItem(
    IN p_cart_id INT,
    IN p_book_id INT,
    IN p_quantity INT
)
BEGIN
    IF p_quantity > 0 THEN
        UPDATE cart_items 
        SET quantity = p_quantity
        WHERE cart_id = p_cart_id AND book_id = p_book_id;
    ELSE
        DELETE FROM cart_items 
        WHERE cart_id = p_cart_id AND book_id = p_book_id;
    END IF;
END //

CREATE PROCEDURE sp_GetCart(
    IN p_cart_id INT
)
BEGIN
    SELECT ci.*, b.title, b.cover_image_url
    FROM cart_items ci
    JOIN books b ON ci.book_id = b.book_id
    WHERE ci.cart_id = p_cart_id;
END //

-- =============================================
-- Procedimientos para Orders
-- =============================================

CREATE PROCEDURE sp_CreateOrder(
    IN p_user_id INT,
    IN p_shipping_address TEXT,
    IN p_shipping_cost DECIMAL(10,2)
)
BEGIN
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_cart_id INT;
    
    -- Obtener el carrito del usuario
    SELECT cart_id INTO v_cart_id 
    FROM shopping_carts 
    WHERE user_id = p_user_id 
    ORDER BY created_at DESC LIMIT 1;
    
    -- Calcular el total
    SELECT SUM(quantity * price_at_time) INTO v_total
    FROM cart_items
    WHERE cart_id = v_cart_id;
    
    -- Crear el pedido
    INSERT INTO orders (user_id, total_amount, shipping_address, shipping_cost)
    VALUES (p_user_id, v_total + p_shipping_cost, p_shipping_address, p_shipping_cost);
    
    -- Obtener el ID del pedido
    SET @order_id = LAST_INSERT_ID();
    
    -- Copiar items del carrito al pedido
    INSERT INTO order_items (order_id, book_id, quantity, price_at_time)
    SELECT @order_id, book_id, quantity, price_at_time
    FROM cart_items
    WHERE cart_id = v_cart_id;
    
    -- Actualizar stock
    UPDATE books b
    JOIN cart_items ci ON b.book_id = ci.book_id
    SET b.stock = b.stock - ci.quantity
    WHERE ci.cart_id = v_cart_id;
    
    -- Limpiar el carrito
    DELETE FROM cart_items WHERE cart_id = v_cart_id;
    
    -- Retornar el ID del pedido
    SELECT @order_id as order_id;
END //

CREATE PROCEDURE sp_GetOrder(
    IN p_order_id INT
)
BEGIN
    SELECT o.*, 
           oi.book_id, 
           b.title,
           oi.quantity,
           oi.price_at_time
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN books b ON oi.book_id = b.book_id
    WHERE o.order_id = p_order_id;
END //

CREATE PROCEDURE sp_UpdateOrderStatus(
    IN p_order_id INT,
    IN p_status VARCHAR(20)
)
BEGIN
    UPDATE orders 
    SET status = p_status
    WHERE order_id = p_order_id;
END //

-- =============================================
-- Procedimientos para Reviews
-- =============================================

CREATE PROCEDURE sp_CreateReview(
    IN p_user_id INT,
    IN p_book_id INT,
    IN p_rating INT,
    IN p_comment TEXT
)
BEGIN
    INSERT INTO reviews (user_id, book_id, rating, comment)
    VALUES (p_user_id, p_book_id, p_rating, p_comment);
    SELECT LAST_INSERT_ID() as review_id;
END //

CREATE PROCEDURE sp_GetBookReviews(
    IN p_book_id INT
)
BEGIN
    SELECT r.*, u.username, u.first_name, u.last_name
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.book_id = p_book_id
    ORDER BY r.created_at DESC;
END //

DELIMITER ;