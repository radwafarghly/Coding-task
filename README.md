## About Task

- Required to Create a simple MySQL DB.
- Required to design responsive form.
- Handle form submit and save to DB and make sure that the uploaded file is an image and is
not larger than 2MB.

## Run Project

-   git clone **(https://github.com/radwafarghly/Coding-task.git)**
-   CREATE DATABASE users;
-   USE users;
-   CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(255) NOT NULL,
  last_name VARCHAR(255) NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  deleted TINYINT(1) NOT NULL DEFAULT 0
);
-   **(http://localhost/Coding-task/)**
-   open it in browser
