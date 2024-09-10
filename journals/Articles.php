<?php
class Article {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function submitArticle($userId, $journal, $article, $publicationDate, $school, $ISSNNumber) {
        if (empty($journal) || empty($article) || empty($publicationDate) || empty($school) || empty($ISSNNumber)) {
            throw new Exception("All fields are required.");
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO articles (user_id, journal, article, publication_date, school, ISSN_number) VALUES (:user_id, :journal, :article, :publication_date, :school, :ISSN_number)");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':journal', $journal, PDO::PARAM_STR);
            $stmt->bindParam(':article', $article, PDO::PARAM_STR);
            $stmt->bindParam(':publication_date', $publicationDate, PDO::PARAM_STR);
            $stmt->bindParam(':school', $school, PDO::PARAM_STR);
            $stmt->bindParam(':ISSN_number', $ISSNNumber, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to submit article: " . $e->getMessage());
        }
    }

    public function getArticlesByUser($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM articles WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching articles: " . $e->getMessage());
        }
    }
    
    public function getconferencesByUser($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM conference WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching conference: " . $e->getMessage());
        }
    }
    
    public function getAllArticles() {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM articles");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching articles: " . $e->getMessage());
    }
}

   
    public function getAllUsers() {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching users: " . $e->getMessage());
    }
}
    public function deleteArticle($articleId) {
    try {
        $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error deleting article: " . $e->getMessage());
    }
          
    }
    public function updateArticle($id, $title, $article, $publicationDate, $school, $ISSN) {
    try {
        $stmt = $this->conn->prepare("UPDATE articles SET journal = :journal, article = :article, publication_date = :publication_date, school = :school, ISSN_number = :ISSN_number WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':journal', $journal);
        $stmt->bindParam(':article', $article);
        $stmt->bindParam(':publication_date', $publicationDate);
        $stmt->bindParam(':school', $school);
        $stmt->bindParam(':ISSN_number', $ISSN);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error updating article: " . $e->getMessage());
    }
}
        public function getArticleById($id) {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching article: " . $e->getMessage());
    }
}
    public function countArticles() {
    try {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM articles");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (PDOException $e) {
        throw new Exception("Error counting articles: " . $e->getMessage());
    }
}
    
    
}

?>
