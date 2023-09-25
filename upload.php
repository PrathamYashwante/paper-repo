<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['paper_course']) && isset($_POST['academic_year'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];


    $course = $_POST['paper_course'];
    $year = $_POST['academic_year'];
    $date = $_POST['paper_date'];
    $branch = $_POST['paper_branch'];
    $semester = $_POST['paper_semester'];

    $title = $course . '_' .  $year . '_' . $branch . '_' . $date . '_' . $year;

    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = "{$title}.{$file_extension}";

    $upload_dir = 'uploads/';
    $destination = $upload_dir . $new_file_name;

    if (move_uploaded_file($file_tmp, $destination)) {
        // Insert paper info into the database
        $query = "INSERT INTO question_papers (title, file_name, academic_year, paper_date, paper_branch, paper_course, paper_semester) 
                  VALUES ('$title', '$new_file_name', '$year', '$date', '$branch', '$course', '$semester')";

        if ($conn->query($query) === TRUE) {
            header("Location: admin.php");
            exit;
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading the file.";
    }
} else {
    echo "Form data is incomplete.";
}
