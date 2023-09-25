<?php
require_once('config.php');

// Fetch available academic years
$query_years = "SELECT DISTINCT academic_year FROM question_papers ORDER BY academic_year DESC";
$result_years = $conn->query($query_years);
$academic_years = [];

if ($result_years->num_rows > 0) {
    while ($row = $result_years->fetch_assoc()) {
        $academic_years[] = $row['academic_year'];
    }
}


$branches = ["CE", "IT", "EXTC", "MECH", "AIML", "AIDS", "CSEIOT"];


$semesters = ["1", "2", "3", "4", "5", "6", "7", "8"];


$selected_year = "";
$selected_branch = "";
$selected_semester = "";

$filters_applied = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selected_year = $_POST["year"];
    $selected_branch = $_POST["branch"];
    $selected_semester = $_POST["semester"];

    if (!empty($selected_year) || !empty($selected_branch) || !empty($selected_semester)) {
        $filters_applied = true;
    }
}


$query = "SELECT paper_course, academic_year, paper_branch, paper_semester, file_name FROM question_papers";
if ($filters_applied) {
    $query .= " WHERE 1";

    if (!empty($selected_year)) {
        $query .= " AND academic_year = '$selected_year'";
    }
    if (!empty($selected_branch)) {
        $query .= " AND paper_branch = '$selected_branch'";
    }
    if (!empty($selected_semester)) {
        $query .= " AND paper_semester = '$selected_semester'";
    }
}
$query .= " ORDER BY academic_year DESC, paper_branch ASC, paper_course ASC, paper_semester ASC";

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
        .paper-link {
            text-decoration: none;
            color: #007bff;
        }

        .paper-link:hover {
            text-decoration: underline;
        }

        .filter-form {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .filter-label {
            font-weight: bold;
        }

        .filter-select {
            margin-right: 10px;
        }

        .filter-button {
            margin-top: 10px;
        }

        .clear-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        .clear-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <span class="logo">SIES-GST Question Paper Repository</span>
            <ul class="nav-links">
                <li><a href="all_papers.php">All Papers</a></li>
            </ul>
        </nav>
    </header>


    <div class="container">
        <h2>Available Question Papers:</h2>

        <!-- Filter form -->
        <form class="filter-form" method="POST">
            <label class="filter-label" for="year">Academic Year:</label>
            <select class="filter-select" name="year" id="year">
                <option value="">Select Year</option>
                <?php
                foreach ($academic_years as $year) {
                    $selected = ($selected_year == $year) ? 'selected' : '';
                    echo "<option value='$year' $selected>$year</option>";
                }
                ?>
            </select>

            <label class="filter-label" for="branch">Branch:</label>
            <select class="filter-select" name="branch" id="branch">
                <option value="">Select Branch</option>
                <?php
                foreach ($branches as $branch) {
                    $selected = ($selected_branch == $branch) ? 'selected' : '';
                    echo "<option value='$branch' $selected>$branch</option>";
                }
                ?>
            </select>

            <label class="filter-label" for="semester">Semester:</label>
            <select class="filter-select" name="semester" id="semester">
                <option value="">Select Semester</option>
                <?php
                foreach ($semesters as $semester) {
                    $selected = ($selected_semester == $semester) ? 'selected' : '';
                    echo "<option value='$semester' $selected>$semester</option>";
                }
                ?>
            </select>

            <input class="filter-button" type="submit" value="Apply Filter">
            <button class="clear-button" onclick="clearFilters()">Clear Filters</button>
        </form>
        <!-- End of filter form -->

        <?php
        if ($filters_applied) {
            echo "<h3>Papers:</h3>";
            if (count($papers) > 0) {
                echo "<div class='filter-form'>";

                foreach ($papers as $paper) {
                    $title = $paper['paper_course'] . '_' . $paper['academic_year'];
                    $file_name = $paper['file_name'];
                    $file_path = "uploads/$file_name";

                    echo "<p><a class='paper-link' href='$file_path'>$title</a></p>";
                }
                echo "</div>";
            } else {
                echo "<p>No papers are available based on the selected filters.</p>";
            }
        }
        ?>
    </div>

    <script>
        function clearFilters() {
            document.getElementById("year").value = "";
            document.getElementById("branch").value = "";
            document.getElementById("semester").value = "";
        }
    </script>
</body>

</html>