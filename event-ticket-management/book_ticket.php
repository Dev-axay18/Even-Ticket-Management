<?php
session_start();
require 'includes/db.php';
require 'vendor/autoload.php'; // Razorpay SDK
use Razorpay\Api\Api;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$message = '';

if ($event_id) {
    $event = $pdo->prepare('SELECT * FROM events WHERE id = ?');
    $event->execute([$event_id]);
    $event = $event->fetch();
    
    if (!$event) {
        $message = 'Event not found.';
    } elseif ($event['available_tickets'] < 1) {
        $message = 'No tickets available.';
    } else {
        // Create Razorpay payment order
        $api = new Api('YOUR_RAZORPAY_KEY', 'YOUR_RAZORPAY_SECRET');
        $paymentData = [
            'amount' => $event['ticket_price'] * 100, // Price in paise (100 paise = 1 INR)
            'currency' => 'INR',
            'receipt' => 'receipt#' . rand(1000, 9999),
            'notes' => [
                'event_id' => $event_id
            ]
        ];
        
        $order = $api->order->create($paymentData);
        
        // Generate the Razorpay payment form
        $orderId = $order->id;
        $_SESSION['order_id'] = $orderId; // Store order ID to verify payment later
        
        $message = '
        <form action="payment_success.php" method="POST" id="razorpay-form">
            <script
                src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="YOUR_RAZORPAY_KEY"
                data-amount="' . $paymentData['amount'] . '"
                data-currency="' . $paymentData['currency'] . '"
                data-order_id="' . $orderId . '"
                data-buttontext="Pay Now"
                data-name="Event Ticket Management"
                data-description="Payment for Event"
                data-image="https://example.com/logo.png"
                data-prefill.name="' . htmlspecialchars($_SESSION['username']) . '"
                data-prefill.email="' . htmlspecialchars($_SESSION['email']) . '"
                data-theme.color="#F37254">
            </script>
        </form>';
    }
} else {
    $message = 'Invalid event.';
}

include 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4 mt-5">
      <h2 class="mb-4">Book Ticket</h2>
      <?php if ($message): ?><div class="alert alert-info"><?php echo $message; ?></div><?php endif; ?>
      <a href="events.php" class="btn btn-secondary mt-3">Back to Events</a>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
