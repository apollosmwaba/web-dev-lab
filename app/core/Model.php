<?php
/**
 * Base Model Class
 */
class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    protected function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    protected function insert($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $this->db->lastInsertId();
    }

    protected function update($sql, $params = []) {
        return $this->query($sql, $params);
    }

    protected function delete($sql, $params = []) {
        return $this->query($sql, $params);
    }
}
