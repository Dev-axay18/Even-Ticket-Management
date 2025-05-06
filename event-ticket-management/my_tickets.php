<?php
session_start();
require 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get event ID from URL parameter and fetch the event details
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$event = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$event->execute([$event_id]);
$event = $event->fetch();

// Check if event exists and tickets are available
if (!$event || $event['available_tickets'] < 1) {
    die('Invalid or sold-out event.');
}

// Store event details in session for later use (payment success page)
$_SESSION['event_id'] = $event_id;
$_SESSION['ticket_price'] = $event['ticket_price']; // Stored as integer, Razorpay will handle it

// Include header
include 'includes/header.php';
?>

<div class="container mt-5">
  <h3>Book Ticket for: <?php echo htmlspecialchars($event['title']); ?></h3>
  <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?> | <strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
  <p><strong>Available Tickets:</strong> <?php echo $event['available_tickets']; ?></p>
  <p><strong>Ticket Price:</strong> ₹<?php echo number_format($event['ticket_price'], 0); ?></p>
  <hr>
  
  <!-- Razorpay payment form -->
  <form action="payment_success.php" method="POST">
    <script
      src="https://checkout.razorpay.com/v1/checkout.js"
      data-key="YOUR_PUBLIC_RAZORPAY_KEY"
      data-amount="<?php echo $event['ticket_price'] * 100; ?>"  <!-- Amount in paise -->
      data-currency="INR"
      data-name="Event Ticket"
      data-description="Booking for <?php echo htmlspecialchars($event['title']); ?>"
      data-image="https://example.com/logo.png"
      data-prefill.name="<?php echo htmlspecialchars($_SESSION['username']); ?>"
      data-prefill.email="<?php echo htmlspecialchars($_SESSION['email']); ?>"
      data-theme.color="#0f172a">
    </script>
    <input type="hidden" value="Hidden Element for Razorpay" name="hidden">
  </form>
</div>

<?php 
// Include footer
include 'includes/footer.php';
?>
