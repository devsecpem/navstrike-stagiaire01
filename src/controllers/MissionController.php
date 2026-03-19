<?php
/**
 * NavStrike - Mission Controller
 *
 * Handles mission planning, briefings, crew management, and exports.
 */

require_once __DIR__ . '/../models/CrewMember.php';
require_once __DIR__ . '/../helpers/ReportHelper.php';

class MissionController {
    /**
     * List active missions
     */
    public function list() {
        global $conn;
        $query = "SELECT * FROM missions ORDER BY priority DESC";
        $results = mysqli_query($conn, $query);
        include __DIR__ . '/../../templates/missions.php';
    }

/**
     * Load mission briefing document
     */
    public function loadBriefing() {
        // 1. On récupère la variable en évitant les erreurs si elle est absente
        $briefingFile = $_GET['file'] ?? '';

        // 2. SÉCURITÉ : basename() extrait uniquement le nom du fichier et supprime les dossiers (ex: ../)
        $safeFileName = basename($briefingFile);

        // 3. On reconstruit le chemin de manière sécurisée
        $path = "briefings/" . $safeFileName;

        // 4. SÉCURITÉ : On vérifie que le fichier existe avant d'essayer de le lire
        if (!empty($safeFileName) && file_exists($path)) {
            $content = file_get_contents($path);
            
            echo "<h2>Briefing de mission</h2>";
            // BONUS SÉCURITÉ : On protège contre les failles XSS avec htmlspecialchars
            echo "<div>" . htmlspecialchars($content) . "</div>";
        } else {
            // On ne donne pas d'indications techniques à l'attaquant
            echo "<h2>Briefing de mission</h2>";
            echo "<div>Erreur : Document introuvable ou accès refusé.</div>";
        }
    }

    /**
     * Export mission report
     */
    public function export() {
        $missionId = $_GET['mission_id'] ?? '';
        $format = $_GET['format'] ?? 'pdf';
        $report = new ReportHelper();
        $report->exportReport($missionId, $format);
    }

    /**
     * Display crew assignments
     */
    public function crew() {
        $crew = new CrewMember();
        $results = $crew->getAssigned();
        include __DIR__ . '/../../templates/crew.php';
    }
}
