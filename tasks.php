<?php
session_start();
include './includes/db.php';


// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MY TO DO LIST</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="dark-theme">
  <div class="container">
    <h1>MY TO DO LIST</h1>
       
    <p class="welcome">Hello, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Here are your tasks:</p>


    <form action="logout.php" method="post" style="text-align: right;">
      <button type="submit" name="logout">Logout</button>
    </form>

    <form action="process_task.php" method="post" class="add-form">
      <input type="text" name="task" placeholder="Add a new task..." required>
      <button type="submit" name="add_task">Add</button>
    </form>
  
    <ul class="task-list">
      <?php
        $user_id = $_SESSION['user_id']; // Get the currently logged-in user's ID
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()):
      ?>

        <li class="<?= $row['is_done'] ? 'done' : '' ?>">
          <?= htmlspecialchars($row['task']) ?>
          
          <form action="process_task.php" method="post" class="task-actions">
            <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
            
            <?php if (!$row['is_done']): ?>
              <button type="submit" name="mark_done">✅</button>
            <?php endif; ?>
            
            <button type="submit" name="delete_task">🗑</button>
          </form>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
</body>
</html>
