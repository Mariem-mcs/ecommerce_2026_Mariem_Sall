<?php
require "db.php";

$id = intval($_GET["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $status = $_POST["status"];
    $post_id = intval($_POST["id"]);

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $description, $status, $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$task) { die("Task not found."); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; }
        input, textarea, select { width: 100%; padding: 8px; margin: 5px 0 15px 0; box-sizing: border-box; }
        button { background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Edit Task</h2>
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
        
        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        
        <label>Description</label>
        <textarea name="description" rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>
        
        <label>Status</label>
        <select name="status">
            <option value="pending" <?php echo $task['status']==='pending'?'selected':''; ?>>Pending</option>
            <option value="in_progress" <?php echo $task['status']==='in_progress'?'selected':''; ?>>In Progress</option>
            <option value="done" <?php echo $task['status']==='done'?'selected':''; ?>>Done</option>
        </select>
        
        <button type="submit">Update Task</button>
    </form>
    <br>
    <a href="index.php">Back</a>
</body>
</html>