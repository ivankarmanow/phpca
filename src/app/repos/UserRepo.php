<?php

namespace repos;

use core\exceptions\ValueError;
use core\models\User;
use core\exceptions\EmailExists;

class UserRepo extends StubRepo
{
    public function is_email_unique(string $email): bool
    {
        $user = $this->db->get_user_by_email($email);
        return empty($user);
    }

    public function create_user(string $name, string $email, string $password): void
    {
        if (!$this->is_email_unique($email)) {
            throw new EmailExists();
        } else {
            $user = new User(
                $name,
                $email,
                $password
            );
            $this->db->create_user($user);
        }
    }

    public function list_users()
    {
        $list = $this->db->list_users();
        return $list;
    }

    public function get_user(int $id = null, string $email = null): User
    {
        if (!empty($id)) {
            $user = $this->db->get_user($id);
        } else if (!empty($email)) {
            $user = $this->db->get_user_by_email($email);
        } else {
            throw new ValueError();
        }
        return $user;
    }

    public function auth_user(string $email, string $password): bool
    {
        $res = $this->db->check_user($email, $password);
        return $res;
    }

    public function update_user(int $id, string $name = null, string $email = null, string $password = null): void
    {
        $user = $this->db->get_user($id);
        if (!empty($name)) {
            $user->name = $name;
        }
        if (!empty($email)) {
            $user->email = $email;
        }
        if (!empty($password)) {
            $user->password = $password;
        }
        $this->db->update_user($user);
    }

    public function delete_user(int $id): void
    {
        $this->db->delete_user($id);
    }
}