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
        $sortInput = $_GET['sort'] ?? 'rank';
        
        // SÉCURITÉ : Rupture totale de la chaîne de corruption.
        // On n'insère JAMAIS l'entrée utilisateur. On assigne une valeur en dur.
        switch ($sortInput) {
            case 'id':      $sortBy = 'id'; break;
            case 'name':    $sortBy = 'name'; break;
            case 'role':    $sortBy = 'role'; break;
            case 'station': $sortBy = 'station'; break;
            default:        $sortBy = 'rank'; break;
        }
        
        $query = "SELECT * FROM crew ORDER BY " . $sortBy . " ASC";
        return mysqli_query($conn, $query);
    }
}
