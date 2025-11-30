<?php
// edit.php
require_once 'config.php';
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) {
    echo "User not found";
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = trim($_POST['matric'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $accessLevel = $_POST['accessLevel'] ?? 'student';

    if ($matric === '' || $name === '') {
        $errors[] = "Matric and Name required.";
    } else {
        // check if matric used by another id
        $stmt = $pdo->prepare("SELECT id FROM users WHERE matric = ? AND id != ?");
        $stmt->execute([$matric, $id]);
        if ($stmt->fetch()) {
            $errors[] = "Matric already used.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET matric=?, name=?, email=?, accessLevel=? WHERE id=?");
        $stmt->execute([$matric, $name, $email, $accessLevel, $id]);
        $msg = "Updated successfully.";
        // refresh user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit User</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 700px; margin: 30px auto; }
    label { display:block; margin:8px 0; }
    input, select { width:100%; padding:8px; box-sizing:border-box; }
    .error { color: #b00020; }
    .success { color: #006400; }
  </style>
</head>
<body>
  <h2>Edit User</h2>

  <?php
  if (!empty($errors)) {
      echo '<div class="error">';
      foreach ($errors as $e) {
          echo '<div>' . htmlspecialchars($e) . '</div>';
      }
      echo '</div>';
  }
  if (!empty($msg)) {
      echo '<div class="success">' . htmlspecialchars($msg) . '</div>';
  }
  ?>

  <form method="post" action="">
    <label>Matric:
      <input type="text" name="matric" value="<?= htmlspecialchars($user['matric']) ?>">
    </label>
    <label>Name:
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">
    </label>
    <label>Email:
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
    </label>
    <label>Access Level:
      <select name="accessLevel">
        <option value="student" <?= ($user['accessLevel'] === 'student') ? 'selected' : '' ?>>Student</option>
        <option value="admin" <?= ($user['accessLevel'] === 'admin') ? 'selected' : '' ?>>Admin</option>
      </select>
    </label>
    <button type="submit" style="margin-top:10px;">Save</button>
  </form>

  <p><a href="users.php">Back to list</a></p>
</body>
</html>
