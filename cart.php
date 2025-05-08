<?php
$pageTitle = "GradÉlan - Your Cart";
$stylecss = "css/cart.css";
$script = "src='js/cart.js'";
include 'head.php';
?>

<section class="cart-section container">
    <h2>Your Shopping Cart</h2>

    <div class="cart-container">
        <div class="cart-items" id="cartItems">
            <!-- Cart items will be loaded by JavaScript -->
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <div class="summary-details">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal">RM0.00</span>
                </div>
                <div class="summary-row">
                    <span>SST (6%)</span>
                    <span id="tax">RM0.00</span>
                </div>
                <div class="summary-row">
                    <span>Shipping Fees</span>
                    <span id="shipping">RM0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="total">RM0.00</span>
                </div>
            </div>
            <button id="checkoutBtn" class="btn-primary">Proceed to Checkout</button>
        </div>
    </div>
</section>

<?php include 'foot.php'; ?>