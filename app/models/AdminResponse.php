<?php
/**
 * AdminResponse Model
 */
class AdminResponse extends Model {
    protected $table = 'admin_responses';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (suggestion_id, user_id, response_text, is_anonymous, is_internal_note) 
                VALUES (:suggestion_id, :user_id, :response_text, :is_anonymous, :is_internal_note)";
        
        $params = [
            ':suggestion_id' => $data['suggestion_id'],
            ':user_id' => $data['user_id'],
            ':response_text' => $data['response_text'],
            ':is_anonymous' => $data['is_anonymous'] ?? false,
            ':is_internal_note' => $data['is_internal_note'] ?? false,
        ];

        return $this->insert($sql, $params);
    }

    public function getBySuggestionId($suggestionId, $includeInternal = false) {
        $sql = "SELECT ar.*, u.full_name, u.role 
                FROM {$this->table} ar 
                LEFT JOIN users u ON ar.user_id = u.id 
                WHERE ar.suggestion_id = :suggestion_id";
        
        if (!$includeInternal) {
            $sql .= " AND ar.is_internal_note = 0";
        }
        
        $sql .= " ORDER BY ar.created_at ASC";
        
        return $this->fetchAll($sql, [':suggestion_id' => $suggestionId]);
    }

    public function getInternalNotes($suggestionId) {
        $sql = "SELECT ar.*, u.full_name 
                FROM {$this->table} ar 
                LEFT JOIN users u ON ar.user_id = u.id 
                WHERE ar.suggestion_id = :suggestion_id AND ar.is_internal_note = 1 
                ORDER BY ar.created_at ASC";
        
        return $this->fetchAll($sql, [':suggestion_id' => $suggestionId]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET response_text = :response_text, updated_at = NOW() WHERE id = :id";
        return $this->query($sql, [
            ':id' => $id,
            ':response_text' => $data['response_text']
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->query($sql, [':id' => $id]);
    }
}
