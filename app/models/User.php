<?php
/**
 * User Model (Admin/Staff)
 */
class User extends Model {
    protected $table = 'users';

    public function getByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND is_active = 1";
        return $this->fetchOne($sql, [':username' => $username]);
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND is_active = 1";
        return $this->fetchOne($sql, [':email' => $email]);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->fetchOne($sql, [':id' => $id]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (username, email, password_hash, full_name, role) 
                VALUES (:username, :email, :password_hash, :full_name, :role)";
        
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':full_name' => $data['full_name'],
            ':role' => $data['role'],
        ];

        return $this->insert($sql, $params);
    }

    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE {$this->table} SET password_hash = :password_hash, updated_at = NOW() WHERE id = :id";
        return $this->update($sql, [
            ':id' => $id,
            ':password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    public function getAll() {
        $sql = "SELECT id, username, email, full_name, role, is_active, created_at FROM {$this->table} ORDER BY created_at DESC";
        return $this->fetchAll($sql);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function getCommitteeMembers() {
        $sql = "SELECT id, full_name, role FROM {$this->table} WHERE is_active = 1 ORDER BY role, full_name";
        return $this->fetchAll($sql);
    }
}
