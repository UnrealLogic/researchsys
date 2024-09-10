<?php
class Book {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function submitBook($userId, $title, $description, $publicationDate) {
        if (empty($title) || empty($description) || empty($publicationDate)) {
            throw new Exception("All fields are required");
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO books (user_id, title, description, publication_date) VALUES (:user_id, :title, :description, :publication_date)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':publication_date', $publicationDate);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to submit book: " . $e->getMessage());
        }
    }

    public function getBookByUser($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM books WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching books: " . $e->getMessage());
        }
    }
    public function updateBook($id, $title, $author, $publicationDate, $ISBN, $publisher) {
    try {
        $stmt = $this->conn->prepare("UPDATE books SET title = :title, author = :author, publication_date = :publication_date, ISBN = :ISBN, publisher = :publisher WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':publication_date', $publicationDate);
        $stmt->bindParam(':ISBN', $ISBN);
        $stmt->bindParam(':publisher', $publisher);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error updating book: " . $e->getMessage());
    }
}

public function getBookById($id) {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching book: " . $e->getMessage());
    }
}
    public function getAllBooks() {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM books");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching books: " . $e->getMessage());
    }
}
    public function countBooks() {
    try {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM books");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (PDOException $e) {
        throw new Exception("Error counting books: " . $e->getMessage());
    }
}
    
}
?>

