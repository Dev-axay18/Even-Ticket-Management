<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['event_id'])) {
    die('Session expired.');
}

$event_id = $_SESSION['event_id'];
$stmt = $pdo->prepare('SELECT * FROM events WHERE id = ?');
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) die('Event not found.');

// Reduce available tickets
$pdo->prepare('UPDATE events SET available_tickets = available_tickets - 1 WHERE id = ?')->execute([$event_id]);

include 'includes/header.php';
?>
<div class="container mt-5">
  <div class="ticket p-4 rounded shadow-lg text-white" style="background: linear-gradient(135deg, #1f1f2f, #3f3f5f); max-width: 500px; margin: auto;">
    <h2 class="text-center mb-3"><?php echo htmlspecialchars($event['title']); ?> 🎟️</h2>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
    <p><strong>Booked by:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <p><strong>Amount Paid:</strong> ₹<?php echo htmlspecialchars($event['ticket_price']); ?></p>
    <div class="text-center mt-4">
      <a href="events.php" class="btn btn-light">Back to Events</a>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
