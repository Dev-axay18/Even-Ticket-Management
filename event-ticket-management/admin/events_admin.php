<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}
// Handle add event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $stmt = $pdo->prepare('INSERT INTO events (title, description, event_date, event_time, location, total_tickets, available_tickets) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $_POST['title'], $_POST['description'], $_POST['event_date'], $_POST['event_time'], $_POST['location'], $_POST['total_tickets'], $_POST['total_tickets']
    ]);
    header('Location: events_admin.php');
    exit;
}
// Handle delete event
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM events WHERE id = ?');
    $stmt->execute([$_GET['delete']]);
    header('Location: events_admin.php');
    exit;
}
$events = $pdo->query('SELECT * FROM events ORDER BY event_date, event_time')->fetchAll();
include '../includes/header.php';
?>
<h2 class="mb-4">Admin: Manage Events</h2>
<div class="card p-4 mb-4">
  <h4>Add New Event</h4>
  <form method="post">
    <div class="row g-2 mb-2">
      <div class="col-md-4"><input type="text" name="title" class="form-control" placeholder="Title" required></div>
      <div class="col-md-4"><input type="text" name="location" class="form-control" placeholder="Location" required></div>
      <div class="col-md-2"><input type="date" name="event_date" class="form-control" required></div>
      <div class="col-md-2"><input type="time" name="event_time" class="form-control" required></div>
    </div>
    <div class="mb-2"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
    <div class="mb-2"><input type="number" name="total_tickets" class="form-control" placeholder="Total Tickets" min="1" required></div>
    <button type="submit" name="add_event" class="btn btn-primary">Add Event</button>
  </form>
</div>
<table class="table table-bordered table-striped">
  <thead><tr><th>Title</th><th>Date</th><th>Time</th><th>Location</th><th>Tickets</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($events as $event): ?>
      <tr>
        <td><?php echo htmlspecialchars($event['title']); ?></td>
        <td><?php echo $event['event_date']; ?></td>
        <td><?php echo $event['event_time']; ?></td>
        <td><?php echo htmlspecialchars($event['location']); ?></td>
        <td><?php echo $event['available_tickets'] . ' / ' . $event['total_tickets']; ?></td>
        <td><a href="?delete=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?')">Delete</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<a href="../index.php" class="btn btn-secondary mt-3">Back to Home</a>
<?php include '../includes/footer.php'; ?> 