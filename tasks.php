<?php
session_start();
include './includes/db.php';


// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$totalTasks = $conn->query("SELECT COUNT(*) AS total FROM tasks")->fetch_assoc()['total'];
$doneTasks = $conn->query("SELECT COUNT(*) AS done FROM tasks WHERE is_done = 1")->fetch_assoc()['done'];

$progress = ($totalTasks > 0) ? round(($doneTasks / $totalTasks) * 100) : 0;
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
   
    <div class="logout-container">
  <form action="logout.php" method="post">
    <button type="submit" class="logout-btn">Logout</button>
  </form>
</div>

    <p class="welcome">Hello, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Here are your tasks:</p>
    <div class="top-section">
  
  <div class="date-box">
    <p class="day"><?= date('l') ?></p>
    <div class="circle"><?= date('d') ?></div>
    <p class="month"><?= date('F') ?></p>
  </div>

  <div class="progress-box">
    <p class="label">Progression</p>
    <div class="progress-bar">
      <div class="progress-fill" style="width: <?= $progress ?>%;"></div>
    </div>
    <span><?= $progress ?>% completed</span>
  </div>
</div>

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

      while ($row = $result->fetch_assoc()):
      ?>

        <li class="<?= $row['is_done'] ? 'done' : '' ?>">
          <?= htmlspecialchars($row['task']) ?>

          <form action="process_task.php" method="post" class="task-actions">
            <input type="hidden" name="task_id" value="<?= $row['id'] ?>">

            <?php if (!$row['is_done']): ?>
              <button type="submit" name="mark_done">🍓done</button>
            <?php endif; ?>

            <button type="submit" name="delete_task">🗑</button>
          </form>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
</body>

</html>