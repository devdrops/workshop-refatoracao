# PHP: Session and Cookies

A simple login/logout demo.

Credits to [@MohammedElagha](https://github.com/MohammedElagha) for his [original source code](https://github.com/MohammedElagha/php_session_and_cookies).

## How to Execute?

You'll need:

- Docker
- Docker-Compose (version 3)

Just run the command on your terminal:

```bash
docker-compose up -d
```

The application will be available at `http://0.0.0.0:80/`.

## How to Stop Execution?

Run this command at the project's source dir on your terminal:

```bash
docker-compose down
```

## Database Requisites

You'll need the current environment up and running before prepare your database - see [How to Execute](#how-to-execute) instructions.

This project uses a MySQL database to manage user access. In order to do that, make sure to follow the next steps:

1. From the source dir, open a bash communication with the current Docker service:

```bash
docker-compose exec mysql bash
```

2. Now, import the SQL file into the target database:

```
root@06055479a3d9:/# mysql -u developer -p login < /tmp/tables/login.sql
Enter password:
```

3. Now, check if the table is OK:

```
root@06055479a3d9:/# mysql -u developer -p login
Enter password:
mysql> show tables;

    +-----------------+
    | Tables_in_login |
    +-----------------+
    | user_logins     |
    +-----------------+
    1 row in set (0.00 sec)

    mysql> describe user_logins;
    +----------+-------------+------+-----+---------+----------------+
    | Field    | Type        | Null | Key | Default | Extra          |
    +----------+-------------+------+-----+---------+----------------+
    | id       | int(11)     | NO   | PRI | NULL    | auto_increment |
    | username | varchar(32) | NO   |     | NULL    |                |
    | password | varchar(16) | NO   |     | NULL    |                |
    +----------+-------------+------+-----+---------+----------------+
    3 rows in set (0.00 sec)
```

If you have the same output as above, you're good to go! :wink:
