 -- test/seed.sql
-- Inserta 1 admin, 1 cliente y 2 libros si no existen

INSERT INTO users (nombre, apellidos, email, password_hash, rol)
SELECT 'Admin', 'Test', 'admin_test@demo.com', '$2y$10$w7l3v1uQv4zWgXWvBq3rUe6mC2yY2kqQp3q2q9xBqQ7m8bGmQp1mG', 'admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='admin_test@demo.com');

INSERT INTO users (nombre, apellidos, email, password_hash, rol)
SELECT 'Cliente', 'Test', 'cliente_test@demo.com', '$2y$10$w7l3v1uQv4zWgXWvBq3rUe6mC2yY2kqQp3q2q9xBqQ7m8bGmQp1mG', 'cliente'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='cliente_test@demo.com');

INSERT INTO books (titulo, descripcion, autor, precio, imagen, genero_literario, stock)
SELECT 'Libro Test 1', 'Descripción test', 'Autor Test', 9.99, 'test1.png', 'Fantasía', 10
WHERE NOT EXISTS (SELECT 1 FROM books WHERE titulo='Libro Test 1' AND autor='Autor Test');

INSERT INTO books (titulo, descripcion, autor, precio, imagen, genero_literario, stock)
SELECT 'Libro Test 2', 'Descripción test', 'Autor Test', 12.99, 'test2.png', 'Thriller', 5
WHERE NOT EXISTS (SELECT 1 FROM books WHERE titulo='Libro Test 2' AND autor='Autor Test');
