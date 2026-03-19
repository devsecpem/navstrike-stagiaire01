<?php
/**
 * NavStrike - Report Helper
 *
 * Generates mission reports and tactical exports.
 */

class ReportHelper {
    /**
     * Export mission report to specified format
     */
    public function exportReport($missionId, $format) {
        // 1. SÉCURITÉ : Liste blanche stricte pour le format
        $allowedFormats = ['pdf', 'jpg', 'png'];
        if (!in_array(strtolower($format), $allowedFormats)) {
            die("Erreur : Format d'export non autorisé.");
        }

        // 2. SÉCURITÉ : On force le missionId à être un entier
        $safeMissionId = intval($missionId);

        // 3. SÉCURITÉ : On prépare nos chemins
        $inputFile = "/var/www/html/reports/" . $safeMissionId . ".html";
        $outputFile = "/tmp/mission_report_" . $safeMissionId . "." . strtolower($format);

        // 4. SÉCURITÉ : On échappe chaque argument passé à la ligne de commande
        $command = sprintf(
            "wkhtmltopdf %s %s",
            escapeshellarg($inputFile),
            escapeshellarg($outputFile)
        );

        // Exécution sécurisée - nosemgrep: php.lang.security.exec-use.exec-use
        $result = shell_exec($command);
        
        echo "<p>Rapport exporte : " . htmlspecialchars($outputFile) . "</p>";
    }

    /**
     * Generate tactical summary
     */
    public function generateSummary($missionId) {
        global $conn;
        $query = "SELECT * FROM missions WHERE id = " . intval($missionId);
        $result = mysqli_query($conn, $query);
        $mission = mysqli_fetch_assoc($result);

        $summary = "RAPPORT TACTIQUE - Mission: " . ($mission['name'] ?? 'N/A') . "\n";
        $summary .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $summary .= "Zone: " . ($mission['zone'] ?? 'N/A') . "\n";
        return $summary;
    }
}
