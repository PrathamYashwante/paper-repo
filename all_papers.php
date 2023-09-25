<?php
require_once('config.php');

$query_branches = "SELECT DISTINCT paper_branch FROM question_papers ORDER BY paper_branch ASC";
$result_branches = $conn->query($query_branches);
$branches = [];

if ($result_branches->num_rows > 0) {
    while ($row = $result_branches->fetch_assoc()) {
        $branches[] = $row['paper_branch'];
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
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <span class="logo">SIES-GST Question Paper Repository</span>
            <ul class="nav-links">
                <li><a href="index.php">Filter Papers</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Available Question Papers:</h2>

        <?php
        foreach ($branches as $branch) {
            // Fetch papers for the current branch
            $query = "SELECT paper_course, academic_year, paper_branch, paper_semester, paper_date, file_name FROM question_papers WHERE paper_branch = '$branch'";
            $query .= " ORDER BY academic_year DESC, paper_date DESC, paper_semester ASC, paper_course ASC";

            $result = $conn->query($query);
            $papers = [];

            if ($result->num_rows > 0) {
                echo "<h3>$branch Branch</h3>";
                echo '<table class="papers-table">';
                echo '<tr><th>Year</th><th>Academic Year</th><th>Semester</th><th>Course</th><th>Download Link</th></tr>';

                while ($row = $result->fetch_assoc()) {

                    $year = $row['paper_date'];
                    $academicYear = $row['academic_year'];
                    $semester = $row['paper_semester'];
                    $course = $row['paper_course'];
                    $file_name = $row['file_name'];
                    $file_path = "uploads/$file_name";

                    echo "<tr>";
                    echo "<td>$year</td>";
                    echo "<td>$academicYear</td>";
                    echo "<td>$semester</td>";
                    echo "<td>$course</td>";
                    echo "<td><a class='paper-link' href='$file_path'>View</a></td>";
                    echo "</tr>";
                }

                echo '</table>';
            } else {
                echo "<p>No papers are available for $branch branch.</p>";
            }
        }
        ?>
    </div>
</body>

</html>