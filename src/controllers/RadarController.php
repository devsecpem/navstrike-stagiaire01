<?php
/**
 * NavStrike - Radar Controller
 *
 * Handles radar operations, contact tracking, and remote sensor feeds.
 */

class RadarController {
    /**
     * Display local radar contacts
     */
    public function scan() {
        global $conn;
        $sector = $_GET['sector'] ?? 'all';        
        $stmt = $conn->prepare("SELECT * FROM radar_contacts WHERE sector = ? ORDER BY distance ASC");
	$stmt->bind_param("ss", $sector);
	$stmt->execute();
	$result = $stmt->get_result();
        
        include __DIR__ . '/../../templates/radar.php';
    }

/**
     * Fetch remote sensor data from allied vessel
     */
    public function scanRemote() {
        $sensorUrl = $_GET['sensor_url'] ?? '';

        // 1. Vérification de base : Est-ce bien une URL formattée correctement ?
        if (!filter_var($sensorUrl, FILTER_VALIDATE_URL)) {
            die("Erreur : URL invalide.");
        }

        // 2. On extrait les composants de l'URL
        $parsedUrl = parse_url($sensorUrl);
        $host = $parsedUrl['host'] ?? '';
        $scheme = $parsedUrl['scheme'] ?? '';

        // 3. SÉCURITÉ (Liste blanche) : On définit les seuls domaines autorisés
        $allowedDomains = [
            'sensor.allied-fleet.mil',
            'radar.coalition.net'
        ];

        // 4. On vérifie que le domaine et le protocole sont autorisés (pas de "file://")
        if (!in_array($host, $allowedDomains) || !in_array($scheme, ['http', 'https'])) {
            die("Erreur : Source non autorisée ou protocole invalide.");
        }

        // 5. Récupération silencieuse (le @ cache les avertissements PHP si le capteur est hors ligne)
        $data = @file_get_contents($sensorUrl);

        echo "<h2>Donnees capteur distant</h2>";
        
        if ($data !== false) {
            // SÉCURITÉ : On protège l'affichage contre les failles XSS
            echo "<pre>" . htmlspecialchars($data) . "</pre>";
        } else {
            echo "<pre>Erreur de communication avec le capteur.</pre>";
        }
    }
}
