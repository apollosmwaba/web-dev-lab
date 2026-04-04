<?php
/**
 * Draft Model
 */
class Draft extends Model {
    protected $table = 'drafts';

    public function save($sessionId, $data) {
        // Check if draft exists for this session
        $existing = $this->getBySessionId($sessionId);
        
        if ($existing) {
            $sql = "UPDATE {$this->table} 
                    SET title = :title, description = :description, category = :category, 
                        urgency_level = :urgency_level, student_name = :student_name, 
                        student_email = :student_email, updated_at = NOW() 
                    WHERE session_id = :session_id";
            
            $params = [
                ':session_id' => $sessionId,
                ':title' => $data['title'] ?? null,
                ':description' => $data['description'] ?? null,
                ':category' => $data['category'] ?? null,
                ':urgency_level' => $data['urgency_level'] ?? 'medium',
                ':student_name' => $data['student_name'] ?? null,
                ':student_email' => $data['student_email'] ?? null,
            ];
            
            return $this->update($sql, $params);
        } else {
            $sql = "INSERT INTO {$this->table} 
                    (session_id, title, description, category, urgency_level, student_name, student_email) 
                    VALUES (:session_id, :title, :description, :category, :urgency_level, :student_name, :student_email)";
            
            $params = [
                ':session_id' => $sessionId,
                ':title' => $data['title'] ?? null,
                ':description' => $data['description'] ?? null,
                ':category' => $data['category'] ?? null,
                ':urgency_level' => $data['urgency_level'] ?? 'medium',
                ':student_name' => $data['student_name'] ?? null,
                ':student_email' => $data['student_email'] ?? null,
            ];
            
            return $this->insert($sql, $params);
        }
    }

    public function getBySessionId($sessionId) {
        $sql = "SELECT * FROM {$this->table} WHERE session_id = :session_id";
        return $this->fetchOne($sql, [':session_id' => $sessionId]);
    }

    public function delete($sessionId) {
        $sql = "DELETE FROM {$this->table} WHERE session_id = :session_id";
        return $this->query($sql, [':session_id' => $sessionId]);
    }

    public function cleanupOld($days = 7) {
        $sql = "DELETE FROM {$this->table} WHERE updated_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        return $this->query($sql, [':days' => (int)$days]);
    }
}
