<?php
// Include the incident reporting process file
include_once("reportIncident_process.php");

// Check if the form is submitted and call the incident reporting function
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    reportIncidentToDatabase($_POST['incidentType'], $_POST['description'], $_POST['incidentStatus'], $_POST['timestamp'], $_POST['location']);
}
?>
