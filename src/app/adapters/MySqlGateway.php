<?php

namespace adapters;

use core\models\User;
use core\protocols\Config;
use core\protocols\DbGateway;
use PDO;

class MySqlGateway implements DbGateway {
    
    private PDO $dbh;
    
    public function __construct(Config $config) {
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE
        ];
        $this->dbh = new PDO($config->db_dsn, $config->db_user, $config->db_password, $options);
    }

    public function create_user(User $user): void
    {
        $sth = $this->dbh->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $user->password = md5($user->password);
        $sth->execute((array)$user);
    }

    public function get_user(int $id): User
    {
        $sth = $this->dbh->prepare("SELECT User, * FROM users WHERE id = :user_id");
        $sth->bindValue(":user_id", $id);
        return $sth->fetch();
    }

    public function get_user_by_email(string $email): User
    {
        $sth = $this->dbh->prepare("SELECT User, * FROM users WHERE email = :email");
        $sth->bindValue(":email", $email);
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
        $db_user = $this->get_user($user->id);
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
        $sth = $this->dbh->prepare("SELECT * FROM users");
        $sth->setFetchMode(PDO::FETCH_CLASS, User::class);
        $sth->execute();
        $users = $sth->fetchAll();
//        var_dump($users);
        return $users;
    }
}