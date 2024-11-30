<?php
// Function to fetch student requests from the database
function fetchStudentRequests($student_matricule) {
    include("db.php");
    // Prepare the SQL query to fetch the student requests
    $query = $conn->prepare("SELECT filename, filetype, status, date_de_Depot FROM documents WHERE id_etud = ?");
    if (!$query) {
        die("SQL query preparation failed: " . $conn->error);
    }

    // Bind the student matricule to the query (prevent SQL injection)
    $query->bind_param("i", $student_matricule);

    // Execute the query
    if (!$query->execute()) {
        die("Query execution failed: " . $query->error);
    }

    // Get the result set
    $result = $query->get_result();

    // Fetch all rows as an associative array
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }

    // Close the query and the database connection
    $query->close();
    $conn->close();

    // Return the list of requests
    return $requests;
}
function insertStudentRequest($student_matricule, $filename, $filetype, $id_admin) {
    // Connection string
    include("db.php");
    try {
        // Ensure the id_admin exists in the `admin` table
        $admin_check_query = "SELECT id FROM admin WHERE id = ?";
        $admin_check_stmt = $conn->prepare($admin_check_query);
        if (!$admin_check_stmt) {
            throw new Exception("SQL query preparation failed: " . $conn->error);
        }
        $admin_check_stmt->bind_param("i", $id_admin);
        $admin_check_stmt->execute();
        $admin_check_stmt->store_result();
        if ($admin_check_stmt->num_rows === 0) {
            throw new Exception("id_admin does not exist in the admin table.");
        }
        $admin_check_stmt->close();

        // Prepare the SQL statement
        $sql = "INSERT INTO documents (id_etud, filename, filetype, status, date_de_Depot, data, id_admin) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("SQL query preparation failed: " . $conn->error);
        }

        // Generate additional values
        $status = "En cours";
        $date_de_Depot = date('Y'); // Assuming 'date_de_Depot' is a YEAR field
        $data = file_get_contents(__DIR__ . '../assets/affiche2.pdf'); // Update the path to your file

        if ($data === false) {
            throw new Exception("Failed to read the file: " . __DIR__ . '../assets/affiche2.pdf');
        }

        // Bind parameters
        $stmt->bind_param("isssssi", $student_matricule, $filename, $filetype, $status, $date_de_Depot, $data, $id_admin);

        // Execute the prepared statement
        $stmt->execute();

        echo "New record created successfully";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the statement
        if (isset($stmt)) {
            $stmt->close();
        }

        // Close the connection
        $conn->close();
    }
}


?>





