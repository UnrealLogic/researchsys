<?php
class Conference {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function submitConference($userId, $title, $description, $date, $location) {
        if (empty($title) || empty($description) || empty($date) || empty($location)) {
            throw new Exception("All fields are required");
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO conferences (user_id, title,description, date, location) VALUES (:user_id, :title, :description, :date, :location)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':location', $location);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to submit conference: " . $e->getMessage());
        }
    }

    public function getConferencesByUser($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM conferences WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching conferences: " . $e->getMessage());
        }
    }
    public function updateConference($id, $title, $description, $date, $location) {
    try {
        $stmt = $this->conn->prepare("UPDATE conferences SET title = :title, description = :description, date = :date, location = :location WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':location', $location);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error updating conference: " . $e->getMessage());
    }
}

public function getConferenceById($id) {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM conferences WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching conference: " . $e->getMessage());
    }
}
    public function getAllConferences() {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM conferences");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching conferences: " . $e->getMessage());
    }
}
    
    public function countConferences() {
    try {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM conferences");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (PDOException $e) {
        throw new Exception("Error counting conferences: " . $e->getMessage());
    }
}
    
    
    
}
?>

