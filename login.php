<?php
// login.php
require_once 'config.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = trim($_POST['matric'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($matric === '' || $password === '') {
        $err = "Enter matric and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE matric = ?");
        $stmt->execute([$matric]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['matric'] = $user['matric'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['accessLevel'] = $user['accessLevel'];

            header('Location: users.php');
            exit;
        } else {
            $err = "Invalid matric or password.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
  <h2>Login</h2>
  <?php if ($err) echo "<div style='color:red;'>".htmlspecialchars($err)."</div>"; ?>
  <form method="post" action="login.php">
    <label>Matric
      <input type="text" name="matric" required>
    </label>
    <label>Password
      <input type="password" name="password" required>
    </label>
    <button type="submit">Login</button>
  </form>
  <p><a href="register.php">Register</a></p>
</body>
</html>
