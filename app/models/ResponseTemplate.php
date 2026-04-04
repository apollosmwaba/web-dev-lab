<?php
/**
 * ResponseTemplate Model
 */
class ResponseTemplate extends Model {
    protected $table = 'response_templates';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY category, template_name";
        return $this->fetchAll($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->fetchOne($sql, [':id' => $id]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (template_name, template_content, category) 
                VALUES (:template_name, :template_content, :category)";
        
        $params = [
            ':template_name' => $data['template_name'],
            ':template_content' => $data['template_content'],
            ':category' => $data['category'] ?? null,
        ];

        return $this->insert($sql, $params);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET template_name = :template_name, template_content = :template_content, category = :category 
                WHERE id = :id";
        
        $params = [
            ':id' => $id,
            ':template_name' => $data['template_name'],
            ':template_content' => $data['template_content'],
            ':category' => $data['category'] ?? null,
        ];

        return $this->update($sql, $params);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->query($sql, [':id' => $id]);
    }

    public function getByCategory($category) {
        $sql = "SELECT * FROM {$this->table} WHERE category = :category ORDER BY template_name";
        return $this->fetchAll($sql, [':category' => $category]);
    }
}
