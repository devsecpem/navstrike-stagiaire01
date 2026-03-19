<?php
/**
 * NavStrike - Target Model
 *
 * Handles hostile target database operations.
 */

class Target {
    /**
     * Search targets by designation
     */
    public function search($term) {
        global $conn;
        
        // SÉCURITÉ : Requête préparée pour le LIKE (J'ai sécurisé celle-ci aussi par précaution !)
        $stmt = $conn->prepare("SELECT * FROM targets WHERE designation LIKE ? OR zone LIKE ?");
        $searchTerm = '%' . $term . '%';
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    /**
     * Get active (non-neutralized) targets
     */
    public function getActive() {
        global $conn;
        $query = "SELECT * FROM targets WHERE status = 'ACTIVE' ORDER BY threat_level DESC";
        return mysqli_query($conn, $query);
    }

    /**
     * Add a new target to tracking
     */
    public function addTarget() {
        global $conn;
        $designation = $_POST['designation'] ?? '';
        $type = $_POST['type'] ?? '';
        $lat = $_POST['latitude'] ?? 0.0;
        $lng = $_POST['longitude'] ?? 0.0;
        $threat = $_POST['threat_level'] ?? '';
        
        // SÉCURITÉ : Requête préparée ("s" = string, "d" = double/float pour les coordonnées)
        $stmt = $conn->prepare("INSERT INTO targets (designation, type, latitude, longitude, threat_level, status) VALUES (?, ?, ?, ?, ?, 'ACTIVE')");
        $stmt->bind_param("ssdds", $designation, $type, $lat, $lng, $threat);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    /**
     * Get target by ID
     */
    public function findById() {
        global $conn;
        $targetId = $_GET['target_id'] ?? '';
        
        // SÉCURITÉ : Requête préparée
        $stmt = $conn->prepare("SELECT * FROM targets WHERE id = ?");
        $stmt->bind_param("s", $targetId); 
        $stmt->execute();
        
        return $stmt->get_result();
    }
}
