<?php

namespace adapters;

use core\models\User as User;
use core\protocols\Config;
use core\protocols\DbGateway;
use PDO;

/*
 * Реализация интерфейса работы с БД MySQL с помощью PDO
 */
class MySqlGateway implements DbGateway {
    
    private PDO $dbh;
    
    public function __construct(Config $config) {
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        $this->dbh = new PDO($config->db_dsn, $config->db_user, $config->db_password, $options);
    }

    public function create_model(string $model): void
    {
        if (!empty($model::$create_table)) {
            $sql = $model::$create_table;
        }
        $this->dbh->query($sql);
    }

    public function create_user(User $user): int
    {
        $sth = $this->dbh->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $user->password = md5($user->password);
//        var_dump((array)$user);
        $params = (array)$user;
        unset($params['id']);
//        var_dump($params);
        $sth->execute($params);
        return $this->dbh->lastInsertId();
    }

    public function get_user_by_id(int $id): User | bool
    {
        $sth = $this->dbh->prepare("SELECT :class, users.* FROM users WHERE id = :user_id");
        $sth->bindValue("user_id", $id);
        $sth->bindValue("class", User::class);
        $sth->execute();
        return $sth->fetch();
    }

    public function get_user_by_email(string $email): User | bool
    {
        $sth = $this->dbh->prepare("SELECT :class, users.* FROM users WHERE email = :email");
        $sth->bindValue(":email", $email);
        $sth->bindValue(":class", User::class);
        $sth->execute();
        return $sth->fetch();
    }

    public function check_user(string $email, string $password): bool
    {
        $sth = $this->dbh->prepare("SELECT COUNT(id) FROM users WHERE email = :email, password = :password");
        $sth->execute(["email" => $email, "password" => md5($password)]);
        return $sth->fetchColumn() > 0;
    }

    public function update_user(User $user): void
    {
        $db_user = $this->get_user_by_id($user->id);
        $sth = $this->dbh->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        if ($user->password != $db_user->password) {
            $user->password = md5($user->password);
        }
        $sth->execute((array)$user);
    }

    public function delete_user(int $id): void
    {
        $sth = $this->dbh->prepare("DELETE FROM users WHERE id = :id");
        $sth->bindValue(":id", $id);
        $sth->execute();
    }

    public function list_users(): array
    {
        $class = User::class;
        return $this->dbh->query("SELECT '$class', users.* FROM users")->fetchAll();
    }
}