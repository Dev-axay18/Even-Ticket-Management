<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}
$users = $pdo->query('SELECT id, username, email, is_admin, created_at FROM users ORDER BY created_at DESC')->fetchAll();
include '../includes/header.php';
?>
<h2 class="mb-4">Admin: Manage Users</h2>
<table class="table table-bordered table-striped">
  <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Admin</th><th>Registered</th></tr></thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
        <td><?php echo $user['created_at']; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<a href="../index.php" class="btn btn-secondary mt-3">Back to Home</a>
<?php include '../includes/footer.php'; ?> 