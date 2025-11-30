<?php
// register.php
require_once 'config.php';

// If the form posted:
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = trim($_POST['matric'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $accessLevel = $_POST['accessLevel'] ?? 'student';

    if ($matric === '' || $name === '' || $password === '') {
        $errors[] = "Matric, Name and Password are required.";
    }

    // check duplicate matric
    $stmt = $pdo->prepare("SELECT id FROM users WHERE matric = ?");
    $stmt->execute([$matric]);
    if ($stmt->fetch()) {
        $errors[] = "Matric already registered.";
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (matric, name, email, password, accessLevel) VALUES (?,?,?,?,?)");
        $stmt->execute([$matric, $name, $email, $hash, $accessLevel]);
        $success = "Registration successful. You may <a href='login.php'>login</a>.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <style>
    /* minimal styling */
    body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; }
    label { display:block; margin-top:10px; }
    input, select { width:100%; padding:8px; box-sizing:border-box; }
    .err { color: red; }
    .ok { color: green; }
  </style>
</head>
<body>
  <h2>Registration</h2>

  <?php if (!empty($errors)): ?>
    <div class="err">
      <?php foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="ok"><?= $success ?></div>
  <?php endif; ?>

  <form method="post" action="register.php">
    <label>Matric / ID
      <input type="text" name="matric" value="<?= htmlspecialchars($_POST['matric'] ?? '') ?>" required>
    </label>
    <label>Name
      <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
    </label>
    <label>Email
      <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label>
    <label>Password
      <input type="password" name="password" required>
    </label>
    <label>Access Level
      <select name="accessLevel">
        <option value="student">Student</option>
        <option value="lecturer">Lecturer</option>
      </select>
    </label>
    <button type="submit" style="margin-top:10px;">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>
