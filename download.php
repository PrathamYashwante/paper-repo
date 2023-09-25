<?php
require_once('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve file information from the database
    $query = "SELECT * FROM question_papers WHERE id=$id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $file_name = $row['file_name'];
        $file_path = 'uploads/' . $file_name;

        // Send the file for download
        if (file_exists($file_path)) {
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            readfile($file_path);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "Question paper not found.";
    }
}
?>
