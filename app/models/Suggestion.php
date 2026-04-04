<?php
/**
 * Suggestion Model
 */
class Suggestion extends Model {
    protected $table = 'suggestions';

    public function generateTicketCode() {
        return 'SUG-' . strtoupper(substr(uniqid(), -6)) . '-' . rand(1000, 9999);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (ticket_code, title, description, category, urgency_level, is_anonymous, student_name, student_email, file_path, file_original_name) 
                VALUES (:ticket_code, :title, :description, :category, :urgency_level, :is_anonymous, :student_name, :student_email, :file_path, :file_original_name)";
        
        $params = [
            ':ticket_code' => $this->generateTicketCode(),
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':category' => $data['category'] ?? null,
            ':urgency_level' => $data['urgency_level'] ?? 'medium',
            ':is_anonymous' => $data['is_anonymous'] ?? true,
            ':student_name' => $data['student_name'] ?? null,
            ':student_email' => $data['student_email'] ?? null,
            ':file_path' => $data['file_path'] ?? null,
            ':file_original_name' => $data['file_original_name'] ?? null,
        ];

        return $this->insert($sql, $params);
    }

    public function getAll($status = null, $orderBy = 'created_at DESC') {
        $sql = "SELECT s.*, u.full_name as resolved_by_name 
                FROM {$this->table} s 
                LEFT JOIN users u ON s.resolved_by = u.id";
        
        $params = [];
        
        if ($status) {
            $sql .= " WHERE s.status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY {$orderBy}";
        
        return $this->fetchAll($sql, $params);
    }

    public function getById($id) {
        $sql = "SELECT s.*, u.full_name as resolved_by_name 
                FROM {$this->table} s 
                LEFT JOIN users u ON s.resolved_by = u.id 
                WHERE s.id = :id";
        
        return $this->fetchOne($sql, [':id' => $id]);
    }

    public function getByTicketCode($ticketCode) {
        $sql = "SELECT * FROM {$this->table} WHERE ticket_code = :ticket_code";
        return $this->fetchOne($sql, [':ticket_code' => $ticketCode]);
    }

    public function updateStatus($id, $status, $userId = null) {
        $sql = "UPDATE {$this->table} SET status = :status, updated_at = NOW()";
        $params = [':id' => $id, ':status' => $status];
        
        if ($status === 'resolved' || $status === 'actioned') {
            $sql .= ", resolved_at = NOW()";
            if ($userId) {
                $sql .= ", resolved_by = :user_id";
                $params[':user_id'] = $userId;
            }
        }
        
        $sql .= " WHERE id = :id";
        
        return $this->update($sql, $params);
    }

    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_count,
                SUM(CASE WHEN status = 'in_review' THEN 1 ELSE 0 END) as in_review_count,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_count,
                SUM(CASE WHEN status = 'actioned' THEN 1 ELSE 0 END) as actioned_count
                FROM {$this->table}";
        
        return $this->fetchOne($sql);
    }

    public function getRecentPublic($limit = 5) {
        $sql = "SELECT ticket_code, title, category, urgency_level, status, created_at 
                FROM {$this->table} 
                WHERE status IN ('resolved', 'actioned') 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        return $this->fetchAll($sql, [':limit' => (int)$limit]);
    }

    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE title LIKE :keyword OR description LIKE :keyword OR ticket_code LIKE :keyword 
                ORDER BY created_at DESC";
        
        return $this->fetchAll($sql, [':keyword' => "%{$keyword}%"]);
    }
}
