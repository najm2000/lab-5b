<?php
// users.php
require_once 'config.php';

// require login
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// fetch users
$stmt = $pdo->query("SELECT id, matric, name, accessLevel FROM users ORDER BY name");
$users = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Users</title></head>
<body>
  <h2>Welcome <?= htmlspecialchars($_SESSION['name']) ?> (<?= htmlspecialchars($_SESSION['accessLevel']) ?>)</h2>
  <p><a href="register.php">Add New User</a> | <a href="logout.php">Logout</a></p>

  <table border="1" cellpadding="6" cellspacing="0">
    <thead>
      <tr><th>Matric</th><th>Name</th><th>Access Level</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['matric']) ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['accessLevel']) ?></td>
          <td>
            <a href="edit.php?id=<?= $u['id'] ?>">Update</a> |
            <a href="delete.php?id=<?= $u['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
