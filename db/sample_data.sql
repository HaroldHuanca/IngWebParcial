USE bookport_db;

-- Insertar usuarios de muestra (contraseña hash es 'password123' en todos los casos)
INSERT INTO users (username, email, password_hash, first_name, last_name, phone, address) VALUES
('juan_perez', 'juan.perez@email.com', '123456', 'Juan', 'Pérez', '999888777', 'Av. Lima 123'),
('maria_garcia', 'maria.garcia@email.com', '123456', 'María', 'García', '999888776', 'Jr. Arequipa 456'),
('carlos_rodriguez', 'carlos.rodriguez@email.com', '123456', 'Carlos', 'Rodríguez', '999888775', 'Calle Tacna 789'),
('ana_martinez', 'ana.martinez@email.com', '123456', 'Ana', 'Martínez', '999888774', 'Av. Cusco 321'),
('pedro_lopez', 'pedro.lopez@email.com', '123456', 'Pedro', 'López', '999888773', 'Jr. Puno 654'),
('lucia_torres', 'lucia.torres@email.com', '123456', 'Lucía', 'Torres', '999888772', 'Av. Trujillo 987'),
('diego_flores', 'diego.flores@email.com', '123456', 'Diego', 'Flores', '999888771', 'Calle Piura 147'),
('sofia_diaz', 'sofia.diaz@email.com', '123456', 'Sofía', 'Díaz', '999888770', 'Jr. Ica 258'),
('miguel_castro', 'miguel.castro@email.com', '123456', 'Miguel', 'Castro', '999888769', 'Av. Callao 369'),
('carmen_ruiz', 'carmen.ruiz@email.com', '123456', 'Carmen', 'Ruiz', '999888768', 'Calle Tumbes 741');

-- Insertar categorías
INSERT INTO categories (name, description, parent_category_id) VALUES
('Ficción', 'Libros de ficción en general', NULL),
('No Ficción', 'Libros basados en hechos reales', NULL),
('Literatura Juvenil', 'Libros para jóvenes lectores', NULL),
('Ciencia Ficción', 'Historias futuristas y científicas', 1),
('Fantasía', 'Mundos mágicos y criaturas fantásticas', 1),
('Romance', 'Historias de amor y relaciones', 1),
('Historia', 'Libros sobre eventos históricos', 2),
('Ciencia', 'Libros sobre descubrimientos científicos', 2),
('Biografías', 'Historias de vida de personajes importantes', 2),
('Aventura', 'Historias llenas de acción y descubrimiento', 1);

-- Insertar autores
INSERT INTO authors (first_name, last_name, biography, photo_url) VALUES
('Gabriel', 'García Márquez', 'Premio Nobel de Literatura 1982', 'authors/garcia_marquez.jpg'),
('Isabel', 'Allende', 'Reconocida escritora chilena', 'authors/allende.jpg'),
('Mario', 'Vargas Llosa', 'Premio Nobel de Literatura 2010', 'authors/vargas_llosa.jpg'),
('Jorge Luis', 'Borges', 'Célebre escritor argentino', 'authors/borges.jpg'),
('Julio', 'Cortázar', 'Maestro del cuento corto', 'authors/cortazar.jpg'),
('Pablo', 'Neruda', 'Premio Nobel de Literatura 1971', 'authors/neruda.jpg'),
('Octavio', 'Paz', 'Premio Nobel de Literatura 1990', 'authors/paz.jpg'),
('Carlos', 'Fuentes', 'Renovador de la literatura mexicana', 'authors/fuentes.jpg'),
('César', 'Vallejo', 'Poeta peruano universal', 'authors/vallejo.jpg'),
('Miguel', 'Ángel Asturias', 'Premio Nobel de Literatura 1967', 'authors/asturias.jpg'),
('Alfonsina', 'Storni', 'Poetisa argentina moderna', 'authors/storni.jpg'),
('Juan', 'Rulfo', 'Maestro del realismo mágico', 'authors/rulfo.jpg'),
('Gabriela', 'Mistral', 'Primera latinoamericana Premio Nobel', 'authors/mistral.jpg'),
('José', 'Donoso', 'Novelista chileno del boom', 'authors/donoso.jpg'),
('Alejo', 'Carpentier', 'Precursor del realismo mágico', 'authors/carpentier.jpg'),
('Roberto', 'Bolaño', 'Renovador de la literatura contemporánea', 'authors/bolano.jpg'),
('Juan Carlos', 'Onetti', 'Narrador uruguayo fundamental', 'authors/onetti.jpg'),
('Elena', 'Poniatowska', 'Cronista de la realidad mexicana', 'authors/poniatowska.jpg'),
('Augusto', 'Roa Bastos', 'Narrador paraguayo esencial', 'authors/roa_bastos.jpg'),
('Cristina', 'Peri Rossi', 'Escritora uruguaya contemporánea', 'authors/peri_rossi.jpg');

-- Insertar libros
INSERT INTO books (title, isbn, description, price, stock, cover_image_url, publication_date, publisher, language, page_count, format, is_featured) VALUES
('Cien años de soledad', '9780307474728', 'La obra maestra del realismo mágico', 59.90, 100, 'books/cien_anos.jpg', '1967-05-30', 'Editorial Sudamericana', 'Español', 432, 'Tapa blanda', TRUE),
('La casa de los espíritus', '9780525433477', 'Saga familiar en Chile', 49.90, 75, 'books/casa_espiritus.jpg', '1982-01-01', 'Plaza & Janés', 'Español', 368, 'Tapa dura', TRUE),
('La ciudad y los perros', '9788420471839', 'La vida en un colegio militar', 45.90, 50, 'books/ciudad_perros.jpg', '1963-10-10', 'Alfaguara', 'Español', 448, 'Tapa blanda', FALSE),
('Ficciones', '9780802130303', 'Colección de cuentos fundamentales', 39.90, 60, 'books/ficciones.jpg', '1944-01-01', 'Sur', 'Español', 288, 'Tapa blanda', TRUE),
('Rayuela', '9788437604572', 'Novela experimental revolucionaria', 54.90, 40, 'books/rayuela.jpg', '1963-06-28', 'Editorial Sudamericana', 'Español', 635, 'Tapa dura', FALSE),
('Veinte poemas de amor', '9780307474689', 'Poesía romántica esencial', 29.90, 120, 'books/veinte_poemas.jpg', '1924-01-01', 'Editorial Nascimento', 'Español', 56, 'Tapa blanda', TRUE),
('El laberinto de la soledad', '9780802150646', 'Ensayo sobre la identidad mexicana', 44.90, 30, 'books/laberinto.jpg', '1950-01-01', 'Cuadernos Americanos', 'Español', 351, 'Tapa blanda', FALSE),
('La muerte de Artemio Cruz', '9788420422290', 'La revolución mexicana', 49.90, 45, 'books/artemio_cruz.jpg', '1962-01-01', 'Fondo de Cultura Económica', 'Español', 315, 'Tapa blanda', FALSE),
('Los heraldos negros', '9788437604589', 'Poesía peruana moderna', 34.90, 25, 'books/heraldos.jpg', '1919-01-01', 'Editorial Sur', 'Español', 154, 'Tapa blanda', FALSE),
('El señor presidente', '9788437604596', 'Novela sobre la dictadura', 47.90, 35, 'books/sr_presidente.jpg', '1946-01-01', 'Editorial Losada', 'Español', 288, 'Tapa blanda', TRUE);

-- Relacionar libros con autores
INSERT INTO book_authors (book_id, author_id) VALUES
(1, 1), -- Cien años de soledad - García Márquez
(2, 2), -- La casa de los espíritus - Allende
(3, 3), -- La ciudad y los perros - Vargas Llosa
(4, 4), -- Ficciones - Borges
(5, 5), -- Rayuela - Cortázar
(6, 6), -- Veinte poemas de amor - Neruda
(7, 7), -- El laberinto de la soledad - Paz
(8, 8), -- La muerte de Artemio Cruz - Fuentes
(9, 9), -- Los heraldos negros - Vallejo
(10, 10); -- El señor presidente - Asturias

-- Relacionar libros con categorías
INSERT INTO book_categories (book_id, category_id) VALUES
(1, 1), (1, 5), -- Cien años de soledad - Ficción, Fantasía
(2, 1), (2, 6), -- La casa de los espíritus - Ficción, Romance
(3, 1), (3, 10), -- La ciudad y los perros - Ficción, Aventura
(4, 1), (4, 5), -- Ficciones - Ficción, Fantasía
(5, 1), -- Rayuela - Ficción
(6, 1), (6, 6), -- Veinte poemas de amor - Ficción, Romance
(7, 2), -- El laberinto de la soledad - No Ficción
(8, 1), (8, 7), -- La muerte de Artemio Cruz - Ficción, Historia
(9, 1), -- Los heraldos negros - Ficción
(10, 1), (10, 7); -- El señor presidente - Ficción, Historia

-- Insertar algunos favoritos
INSERT INTO favorites (user_id, book_id) VALUES
(1, 1), (1, 4), (1, 6),
(2, 2), (2, 5),
(3, 3), (3, 7), (3, 9),
(4, 1), (4, 2), (4, 3),
(5, 5), (5, 8);

-- Crear carritos de compra
INSERT INTO shopping_carts (user_id) VALUES
(1), (2), (3), (4), (5);

-- Añadir items a los carritos
INSERT INTO cart_items (cart_id, book_id, quantity, price_at_time) VALUES
(1, 1, 2, 59.90),
(1, 4, 1, 39.90),
(2, 2, 1, 49.90),
(3, 3, 1, 45.90),
(3, 6, 2, 29.90),
(4, 5, 1, 54.90),
(5, 7, 1, 44.90);

-- Crear algunos pedidos
INSERT INTO orders (user_id, total_amount, shipping_address, shipping_cost, status, payment_status) VALUES
(1, 169.70, 'Av. Lima 123', 10.00, 'completed', 'paid'),
(2, 59.90, 'Jr. Arequipa 456', 10.00, 'completed', 'paid'),
(3, 115.70, 'Calle Tacna 789', 10.00, 'processing', 'pending'),
(4, 64.90, 'Av. Cusco 321', 10.00, 'shipped', 'paid');

-- Añadir items a los pedidos
INSERT INTO order_items (order_id, book_id, quantity, price_at_time) VALUES
(1, 1, 2, 59.90),
(1, 4, 1, 39.90),
(2, 2, 1, 49.90),
(3, 3, 1, 45.90),
(3, 6, 2, 29.90),
(4, 5, 1, 54.90);

-- Insertar algunas reseñas
INSERT INTO reviews (user_id, book_id, rating, comment) VALUES
(1, 1, 5, '¡Una obra maestra! Definitivamente el mejor libro que he leído.'),
(2, 1, 4, 'Fascinante historia familiar, aunque algo compleja de seguir.'),
(3, 2, 5, 'Isabel Allende en su mejor momento. No pude dejar de leer.'),
(4, 3, 4, 'Una historia cruda y real sobre la vida militar.'),
(5, 4, 5, 'Borges demuestra su genialidad en cada página.'),
(1, 5, 4, 'Una estructura narrativa innovadora y desafiante.'),
(2, 6, 5, 'Poesía que llega al alma. Neruda es insuperable.'),
(3, 7, 4, 'Un análisis profundo de la identidad mexicana.'),
(4, 8, 5, 'Una perspectiva única de la Revolución Mexicana.'),
(5, 9, 4, 'Poesía peruana en su máxima expresión.');