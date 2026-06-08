CREATE TABLE `authors` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255),
  `nickname` varchar(255) UNIQUE
);

CREATE TABLE `users` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(255) UNIQUE NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
);

CREATE TABLE `books` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal NOT NULL,
  `old_price` decimal,
  `year` integer,
  `author_id` integer NOT NULL
);

CREATE TABLE `genres` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

CREATE TABLE `book_genre` (
  `book_id` integer NOT NULL,
  `genre_id` integer NOT NULL
);

ALTER TABLE `authors` ADD FOREIGN KEY (`id`) REFERENCES `books` (`author_id`);

ALTER TABLE `book_genre` ADD FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

ALTER TABLE `book_genre` ADD FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);
