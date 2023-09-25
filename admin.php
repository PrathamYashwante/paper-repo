<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Question Paper Repository</title>
    <link rel="stylesheet" href="styles.css">
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
        <h2>Upload a Question Paper:</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file">
            <label for="academic_year">Academic Year:</label>
            <select name="academic_year" id="academic_year">
                <?php
                $currentYear = date("Y");
                for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>
            <label for="paper_date">Course Year:</label>
            <select name="paper_date" id="paper_date">
                <option value="BE">BE</option>
                <option value="TE">TE</option>
                <option value="SE">SE</option>
                <option value="FE">FE</option>
            </select>
            <label for="paper_branch">Paper Branch:</label>
            <select name="paper_branch" id="paper_branch">
                <option value="CE">CE</option>
                <option value="IT">IT</option>
                <option value="EXTC">EXTC</option>
                <option value="MECH">MECH</option>
                <option value="AIML">AIML</option>
                <option value="AIDS">AIDS</option>
                <option value="CSEIOT">CSEIOT</option>
            </select>
            <label for="paper_course">Paper Course:</label>
            <input type="text" name="paper_course" id="paper_course" placeholder="Enter Course Name">
            <label for="paper_semester">Paper Semester:</label>
            <select name="paper_semester" id="paper_semester">
                <?php
                for ($semester = 1; $semester <= 8; $semester++) {
                    echo "<option value='$semester'>$semester</option>";
                }
                ?>
            </select>
            <input type="submit" value="Upload">
        </form>
    </div>
</body>

</html>