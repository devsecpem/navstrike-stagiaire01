<?php
/**
 * NavStrike - Missile Model
 *
 * Handles missile inventory and launch status operations.
 */

class Missile {
    /**
     * Get full missile inventory
     */
    public function getInventory() {
        global $conn;
        $query = "SELECT * FROM missiles ORDER BY type ASC, status DESC";
        return mysqli_query($conn, $query);
    }

    /**
     * Find missiles by type
     */
    public function findByType() {
        global $conn;
        $type = $_GET['type'] ?? '';
        
        // SÉCURITÉ : Requête préparée
        $stmt = $conn->prepare("SELECT * FROM missiles WHERE type = ? AND status = 'READY'");
        $stmt->bind_param("s", $type);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    /**
     * Update missile status for launch sequence
     */
    public function updateStatus() {
        global $conn;
        $missileId = $_POST['missile_id'] ?? 0;
        $newStatus = $_POST['status'] ?? '';
        
        // SÉCURITÉ : Requête préparée ("s" pour string status, "i" pour integer id)
        $stmt = $conn->prepare("UPDATE missiles SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $missileId);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    /**
     * Get launch-ready count by type
     */
    public function getReadyCount() {
        global $conn;
        $query = "SELECT type, COUNT(*) as count FROM missiles WHERE status = 'READY' GROUP BY type";
        return mysqli_query($conn, $query);
    }
}
