<?php
require_once __DIR__ . '/../Database.php';

class Book {
    private $db;

    public function __construct() {
        $config = require __DIR__ . '/../config.php';
        $this->db = new Database($config['database']);
    }

    public function fetchBooks($search = '', $category = '', $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM books WHERE 1";
        $params = [];
    
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR author LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($category)) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }
    
        // ✅ FIX: Use direct integers for LIMIT and OFFSET
        $sql .= " ORDER BY title ASC LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
    
        return $this->db->fetchAll($sql, $params);
    }
    

    public function countBooks($search = '', $category = '') {
        $sql = "SELECT COUNT(*) AS total FROM books WHERE 1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR author LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($category)) {
            $sql .= " AND category = :category";
            $params[':category'] = $category;
        }

        return $this->db->fetch($sql, $params)['total'];
    }

    public function findBookById($id) {
        return $this->db->fetch("SELECT * FROM books WHERE id = :id", [':id' => $id]);
    }
    
}
