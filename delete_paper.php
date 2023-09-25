<?php
require_once('config.php');

// Function to delete a paper by its ID from the database and uploads folder
function deletePaper($paperId, $file_name)
{
    global $conn;

    // Delete the paper record from the database
    $deleteQuery = "DELETE FROM question_papers WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $paperId);

    if ($stmt->execute()) {
        // Delete the paper file from the uploads folder
        $uploadsDirectory = "uploads/";
        $filePath = $uploadsDirectory . $file_name;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return true;
    } else {
        return false;
    }
}


if (isset($_GET['id']) && isset($_GET['file_name'])) {
    $paperId = $_GET['id'];
    $file_name = $_GET['file_name'];


    $deletionResult = deletePaper($paperId, $file_name);

    if ($deletionResult) {

        echo '<script>alert("Paper deleted successfully.");</script>';

        echo '<script>window.location.href = "delete_paper.php";</script>';
    } else {
        # error handling 
        echo '<script>alert("Failed to delete paper. Please try again later.");</script>';

        echo '<script>window.location.href = "delete_paper.php";</script>';
    }
}



// Fetch all papers without filters
$query = "SELECT id, paper_course, academic_year, paper_branch, paper_semester, paper_date, file_name FROM question_papers";
$query .= " ORDER BY paper_branch ASC, academic_year DESC, paper_date DESC, paper_semester ASC, paper_course ASC";

$result = $conn->query($query);
$papers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $papers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Paper Repository</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Define CSS styles for the table */
        .papers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .papers-table th,
        .papers-table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .papers-table th {
            background-color: #f2f2f2;
        }

        .papers-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .papers-table tr:hover {
            background-color: #ddd;
        }

        .delete-button {
            background-color: #ff6666;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #ff0000;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <span class="logo">SIES GST Question Paper Repository</span>
            <ul class="nav-links">
                <li><a href="admin.php">Add Paper</a></li>
                <li><a href="index.php">Filter Papers</a></li>
                <li><a href="delete_paper.php">Delete Papers</a></li>
                <li><a href="all_papers.php">All Papers</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Available Question Papers:</h2>

        <?php
        if (count($papers) > 0) {
            echo '<table class="papers-table">';
            echo '<tr><th>Branch</th><th>Year</th><th>Academic Year</th><th>Semester</th><th>Course</th><th>Download Link</th><th>Delete</th></tr>';

            foreach ($papers as $paper) {

                $id = $paper['id'];
                $branch = $paper['paper_branch'];
                $year = $paper['academic_year'];
                $academicYear = $paper['paper_date'];
                $semester = $paper['paper_semester'];
                $course = $paper['paper_course'];
                $file_name = $paper['file_name'];
                $file_path = "uploads/$file_name";

                echo "<tr>";
                echo "<td>$branch</td>";
                echo "<td>$year</td>";
                echo "<td>$academicYear</td>";
                echo "<td>$semester</td>";
                echo "<td>$course</td>";
                echo "<td><a class='paper-link' href='$file_path' target='_blank'>Download</a></td>";
                echo "<td><button class='delete-button' onclick=\"deletePaper($id, '$file_name')\">Delete</button></td>";
                echo "</tr>";
            }

            echo '</table>';
        } else {
            echo "<p>No papers are available.</p>";
        }
        ?>
    </div>

    <script>
        // Delete Paper
        function deletePaper(paperId, fileName) {
            if (confirm("Are you sure you want to delete this paper?")) {
                window.location.href = `delete_paper.php?id=${paperId}&file_name=${fileName}`;
            }
        }
    </script>
</body>

</html>