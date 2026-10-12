<?php
require "db.php";

$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tasks</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        .task { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .task h3 { margin: 0 0 5px 0; color: #2c3e50; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 3px; font-size: 12px; }
        .pending { background: #f39c12; color: white; }
        .in_progress { background: #3498db; color: white; }
        .done { background: #27ae60; color: white; }
        a { color: #3498db; text-decoration: none; margin-right: 10px; }
        .btn-add { background: #27ae60; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>My Tasks</h1>
    <a href="create.php" class="btn-add">+ Add Task</a>
    
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="task">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <span class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span><br><br>
            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this task?');">Delete</a>
        </div>
    <?php endwhile; ?>
    
</body>
</html>
<?php $conn->close(); ?>