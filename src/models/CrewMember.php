<?php
/**
 * NavStrike - Crew Member Model
 *
 * Handles crew assignment and combat station operations.
 */

class CrewMember {
    /**
     * Get crew assigned to combat stations
     */
    public function getAssigned() {
        global $conn;
        $query = "SELECT * FROM crew WHERE station IS NOT NULL ORDER BY rank DESC";
        return mysqli_query($conn, $query);
    }

    /**
     * Find crew by role
     */
    public function findByRole() {
        global $conn;
        $role = $_GET['role'] ?? '';
        
        // SÉCURITÉ : Requête préparée
        $stmt = $conn->prepare("SELECT * FROM crew WHERE role = ? ORDER BY rank DESC");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    /**
     * List crew with sorting
     */
    public function listCrew() {
        global $conn;
        $sortBy = $_GET['sort'] ?? 'rank';
        
        // SÉCURITÉ : Liste blanche car on ne peut pas préparer un ORDER BY
        $allowedSortColumns = ['id', 'name', 'rank', 'role', 'station'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'rank'; // Valeur par défaut si falsification
        }
        
        $query = "SELECT * FROM crew ORDER BY " . $sortBy . " ASC";
        return mysqli_query($conn, $query);
    }
}
