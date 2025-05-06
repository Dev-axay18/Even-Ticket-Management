<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';
$events = $pdo->query('SELECT * FROM events ORDER BY event_date, event_time')->fetchAll();
?>
<h2 class="mb-4">Upcoming Events</h2>
<div class="row">
<?php foreach ($events as $event): ?>
  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
        <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
        <p><strong>Date:</strong> <?php echo $event['event_date']; ?> <strong>Time:</strong> <?php echo $event['event_time']; ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p><strong>Available Tickets:</strong> <?php echo $event['available_tickets']; ?></p>
      </div>
      <div class="card-footer bg-white border-0">
        <?php if ($event['available_tickets'] > 0): ?>
          <a href="my_tickets.php?event_id=<?php echo $event['id']; ?>" class="btn btn-primary w-100">Book Ticket</a>

        <?php else: ?>
          <button class="btn btn-secondary w-100" disabled>Sold Out</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?> 