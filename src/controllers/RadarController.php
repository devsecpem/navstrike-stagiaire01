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
        $sensorUrl = $_GET['sensor_url'];
        $data = file_get_contents($sensorUrl);
        echo "<h2>Donnees capteur distant</h2>";
        echo "<pre>" . $data . "</pre>";
    }
}
