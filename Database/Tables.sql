USE library_db;
DROP TABLE IF EXISTS borrowed_books;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS books;

CREATE TABLE users (
    id INT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    role ENUM('user', 'admin')
)

CREATE TABLE books (
    id INT PRIMARY KEY,
    title VARCHAR(100),
    author VARCHAR(100),
    ISBN VARCHAR(20),
    genre VARCHAR(50),
    year INT,
    quantity INT,
    available INT
);

CREATE TABLE borrowed_books (
    id INT PRIMARY KEY,
    title VARCHAR(100),
    author VARCHAR(100),
    year INT,
    due_date TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 14 DAYS),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
);
