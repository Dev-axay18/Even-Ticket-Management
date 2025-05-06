<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}
$tickets = $pdo->query('SELECT t.id, u.username, e.title, t.booking_time FROM tickets t JOIN users u ON t.user_id = u.id JOIN events e ON t.event_id = e.id ORDER BY t.booking_time DESC')->fetchAll();
include '../includes/header.php';
?>
<h2 class="mb-4">Admin: All Tickets</h2>
<table class="table table-bordered table-striped">
  <thead><tr><th>ID</th><th>User</th><th>Event</th><th>Booked At</th></tr></thead>
  <tbody>
    <?php foreach ($tickets as $ticket): ?>
      <tr>
        <td><?php echo $ticket['id']; ?></td>
        <td><?php echo htmlspecialchars($ticket['username']); ?></td>
        <td><?php echo htmlspecialchars($ticket['title']); ?></td>
        <td><?php echo $ticket['booking_time']; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<a href="../index.php" class="btn btn-secondary mt-3">Back to Home</a>
<?php include '../includes/footer.php'; ?> 