<?php
session_start(); // Start a session to track logged-in users

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
include("db.php");
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_admin'])) {
        // Admin login
        $admin_id = $conn->real_escape_string($_POST['admin_id']);
        $admin_password = $conn->real_escape_string($_POST['admin_password']);

        // Query to check admin credentials
        $query = "SELECT * FROM admin WHERE id = '$admin_id'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if ($admin_password === $admin['password']) {
                // Set session for the admin
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['role'] = 'admin';
                header("Location: ../pages/admin.php");
                exit();
            } else {
                echo "Invalid Admin Password.";
            }
        } else {
            echo "Admin ID not found.";
        }
    } elseif (isset($_POST['login_student'])) {
        // Student login
        $student_matricule = $conn->real_escape_string($_POST['student_matricule']);
        $student_password = $conn->real_escape_string($_POST['student_password']);

        // Query to check student credentials
        $query = "SELECT * FROM etudiant WHERE matricule = '$student_matricule'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            if ($student_password === $student['password']) {
                // Set session for the student
                $_SESSION['student_matricule'] = $student['matricule'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['role'] = 'student';
                header("Location: ../pages/etudiant.php");
                exit();
            } else {
                echo "Invalid Student Password.";
            }
        } else {
            echo "Matricule not found.";
        }
    } else {
        echo "No login type specified.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>

