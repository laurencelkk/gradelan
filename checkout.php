<?php
$pageTitle = "Checkout | GradÉlan";
$stylecss = "css/checkout.css";
include 'head.php';

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fullName'])) {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $cart = json_decode($_POST['cart'], true);
    $paymentMethod = $_POST['paymentMethod'] ?? '';

    $mail = new PHPMailer(true);

    if ($paymentMethod === 'credit') {
        $paymentMethod = 'Credit Card';
    } elseif ($paymentMethod === 'tng') {
        $paymentMethod = 'Touch \'n Go eWallet';
    } else {
        $paymentMethod = 'Unknown Payment Method';
    }

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'csstarumtpg@gmail.com';
        $mail->Password = 'khebbtdvzgbzpacq';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('support@gradelan.laurencelkk.my', 'GradElan');
        $mail->addAddress($email, $fullName);

        $mail->isHTML(true);
        $mail->Subject = 'Your GradElan Order Confirmation';
        $mail->Body = "<h2>Thank you for your order, $fullName!</h2>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Shipping Address:</strong><br>$address</p>
            <p><strong>Payment Method:</strong> " . ucfirst($paymentMethod) . "</p>
            <h3>Order Summary:</h3>";

        $total = 0;
        foreach ($cart as $item) {
            $line = "{$item['name']} (Qty: {$item['quantity']}) - RM" . number_format($item['price'], 2);
            $total += $item['price'] * $item['quantity'];
            $mail->Body .= "<p>$line</p>";
        }

        $sst = $total * 0.06;
        $shipping = $total > 250 ? 0 : 18;
        $grandTotal = $total + $sst + $shipping;

        $mail->Body .= "<hr><p><strong>Subtotal:</strong> RM" . number_format($total, 2) . "</p>";
        $mail->Body .= "<p><strong>SST (6%):</strong> RM" . number_format($sst, 2) . "</p>";
        $mail->Body .= "<p><strong>Shipping Fees:</strong> RM" . number_format($shipping, 2) . "</p>";
        $mail->Body .= "<p><strong>Total:</strong> RM" . number_format($grandTotal, 2) . "</p>";

        $mail->send();

        echo "<script>
          localStorage.removeItem('cart');
          localStorage.removeItem('selectedPaymentMethod');
          window.location.href = 'thankyou.php';
        </script>";
        exit;
    } catch (Exception $e) {
        echo "<script>console.log('Mail Error: " . addslashes($mail->ErrorInfo) . "');</script>";
    }
}
?>

<section class="checkout-section container">
    <h1>Complete Your Purchase</h1>

    <div class="checkout-grid">
        <!-- Left Column - Order Summary -->
        <div class="order-summary">
            <h2><i class="fas fa-shopping-bag"></i> Your Order</h2>

            <div class="cart-items" id="checkoutCartItems">
                <!-- Cart items will be loaded by JavaScript -->
            </div>

            <div class="order-totals">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span id="checkoutSubtotal">RM0.00</span>
                </div>
                <div class="total-row">
                    <span>SST (6%)</span>
                    <span id="checkoutTax">RM0.00</span>
                </div>
                <div class="total-row">
                    <span>Shipping Fees</span>
                    <span id="checkoutShipping">RM0.00</span>
                </div>
                <div class="total-row grand-total">
                    <span>Total</span>
                    <span id="checkoutTotal">RM0.00</span>
                </div>
            </div>

            <div class="secure-checkout">
                <i class="fas fa-lock"></i>
                <span>Secure SSL Encrypted Checkout</span>
            </div>
        </div>

        <!-- Right Column - Customer & Payment -->
        <div class="customer-details">
            <form id="checkoutForm" method="POST">
                <h2><i class="fas fa-user"></i> Customer Information</h2>

                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="address">Shipping Address</label>
                    <textarea id="address" name="address" rows="6" required></textarea>
                </div>

                <h2><i class="fas fa-credit-card"></i> Payment Method</h2>

                <div class="payment-methods">
                    <div class="payment-option active" data-method="credit">
                        <i class="fab fa-cc-visa"></i>
                        <span>Credit/Debit Card</span>
                    </div>
                    <div class="payment-option" data-method="tng">
                        <i class="fas fa-wallet"></i>
                        <span>Touch 'n Go eWallet</span>
                    </div>
                </div>

                <div class="payment-details" id="creditCardDetails">
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiryDate">Expiry Date</label>
                            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123">
                        </div>
                    </div>
                </div>

                <div class="payment-details" id="tngDetails">
                    <div class="tng-instructions">
                        <p>Scan the QR code below using your Touch 'n Go eWallet app to complete payment:</p>
                        <div class="tng-qr-container">
                            <img src="img/tng-qr.png" alt="Touch 'n Go eWallet QR Code" class="tng-qr-code">
                            <div class="tng-amount">
                                <span>Amount to pay:</span>
                                <span id="tngAmount">RM0.00</span>
                            </div>
                        </div>
                        <p class="tng-note"><i class="fas fa-info-circle"></i> Payment will be verified automatically within 2 minutes</p>
                    </div>                </div>


                <div class="form-group terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I agree to the <a href="tos.php">Terms & Conditions</a> and <a href="privacy.php">Privacy Policy</a></label>
                </div>

                <button type="submit" class="btn-primary" id="placeOrderBtn">
                    <i class="fas fa-lock"></i> Place Order
                </button>
            </form>
        </div>
    </div>
</section>

<script src="js/checkout.js"></script>

<?php include 'foot.php'; ?>