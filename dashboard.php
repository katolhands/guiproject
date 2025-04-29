<?php
session_start();
include(__DIR__ . '/includes/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['google_link'])) {
    $link = trim($_POST['google_link']);
    $name = trim($_POST['file_name']);

    if (!empty($link) && !empty($name)) {
        $stmt = $conn->prepare("INSERT INTO files (name, link) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $link);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}

// Fetch files
$result = $conn->query("SELECT * FROM files ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | GUI Project</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php" class="logout-btn">Logout</a>

        <h2>Add Google Drive File</h2>
        <form method="post" class="add-form">
            <input type="text" name="file_name" placeholder="File Name" required><br>
            <input type="url" name="google_link" placeholder="Google Drive Link" required><br>
            <button type="submit">Add File</button>
        </form>

        <h2>Your Files</h2>
        <div class="file-list">
            <?php if ($result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="file-item">
                            <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this file?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No files added yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
