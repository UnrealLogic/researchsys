<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function signup($username, $email, $password,$school) {
        try {
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password,school) VALUES (:username, :email, :password, :school)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hash_password);
            $stmt->bindparam (':school', $school);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Signup failed: " . $e->getMessage());
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }

    public function getUserById($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
    
    public function deleteUser($userId) {
    try {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error deleting user: " . $e->getMessage());
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
}
?>