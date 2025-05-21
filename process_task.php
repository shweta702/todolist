<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add task
    if (isset($_POST['add_task']) && isset($_SESSION['user_id'])) {
        $task = trim($_POST['task']);
        $user_id = $_SESSION['user_id'];

        if (!empty($task)) {
            $stmt = $conn->prepare("INSERT INTO tasks (task, user_id) VALUES (?, ?)");
            $stmt->bind_param("si", $task, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Mark as done
    if (isset($_POST['mark_done']) && isset($_SESSION['user_id'])) {
        $id = (int)$_POST['task_id'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete task
    if (isset($_POST['delete_task']) && isset($_SESSION['user_id'])) {
        $id = (int)$_POST['task_id'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: tasks.php");
exit();

?>
