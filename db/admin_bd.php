<?php
// Database connection
include("db.php");
// Function to fetch submissions from the database
function fetchSubmissions()
{
    global $conn;
    $sql = "
        SELECT 
            e.name, 
            e.last_name, 
            d.filename, 
            d.filetype, 
            d.status, 
            d.date_de_Depot, 
            d.id_etud
        FROM documents d
        INNER JOIN etudiant e ON d.id_etud = e.matricule";

    $result = $conn->query($sql);

    // Check if the query failed
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    return $result;
}
?>

