<?php
// delete.php
require_once 'config.php';
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id) {
 
    if ($id == $_SESSION['user_id']) {
    
        header('Location: users.php?msg=cannot_delete_self'); exit;
    }
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: users.php');
exit;
