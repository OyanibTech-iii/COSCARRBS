
customersdb

+-----------------------+
| Tables_in_customersdb |
+-----------------------+
| orders                |
| products              |
| reservations          |
| users                 |
+-----------------------+

orders;
+-------------+---------------+------+-----+---------------------+----------------+
| Field       | Type          | Null | Key | Default             | Extra          |
+-------------+---------------+------+-----+---------------------+----------------+
| id          | int(11)       | NO   | PRI | NULL                | auto_increment |
| user_id     | int(11)       | YES  | MUL | NULL                |                |
| product_id  | int(11)       | YES  | MUL | NULL                |                |
| quantity    | int(11)       | YES  |     | NULL                |                |
| total_price | decimal(10,2) | YES  |     | NULL                |                |
| order_date  | timestamp     | NO   |     | current_timestamp() |                |
+-------------+---------------+------+-----+---------------------+----------------+

 products;
+-------------+---------------+------+-----+---------------------+----------------+
| Field       | Type          | Null | Key | Default             | Extra          |
+-------------+---------------+------+-----+---------------------+----------------+
| id          | int(11)       | NO   | PRI | NULL                | auto_increment |
| name        | varchar(100)  | NO   |     | NULL                |                |
| description | text          | YES  |     | NULL                |                |
| price       | decimal(10,2) | NO   |     | NULL                |                |
| image       | varchar(255)  | YES  |     | NULL                |                |
| created_at  | timestamp     | NO   |     | current_timestamp() |                |
+-------------+---------------+------+-----+---------------------+----------------+

reservations;
+------------------+--------------+------+-----+---------+----------------+
| Field            | Type         | Null | Key | Default | Extra          |
+------------------+--------------+------+-----+---------+----------------+
| id               | int(11)      | NO   | PRI | NULL    | auto_increment |
| username         | varchar(255) | NO   | MUL | NULL    |                |
| reservation_date | date         | NO   |     | NULL    |                |
+------------------+--------------+------+-----+---------+----------------+

users;
+------------+--------------+------+-----+---------------------+----------------+
| Field      | Type         | Null | Key | Default             | Extra          |
+------------+--------------+------+-----+---------------------+----------------+
| id         | int(11)      | NO   | PRI | NULL                | auto_increment |
| username   | varchar(50)  | NO   | UNI | NULL                |                |
| password   | varchar(255) | NO   |     | NULL                |                |
| created_at | timestamp    | NO   |     | current_timestamp() |                |
| email      | varchar(100) | NO   | UNI | NULL                |                |
+------------+--------------+------+-----+---------------------+----------------+