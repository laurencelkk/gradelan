<?php
$pageTitle = "GradÉlan - Your Cart";
$stylecss = "css/cart.css";
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
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="total">RM0.00</span>
                </div>
            </div>
            <button id="checkoutBtn" class="btn-primary">Proceed to Checkout</button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function getCart() {
            return JSON.parse(localStorage.getItem('cart')) || [];
        }

        const cart = getCart();
        const cartItemsContainer = document.getElementById('cartItems');
        const subtotalElement = document.getElementById('subtotal');
        const taxElement = document.getElementById('tax');
        const totalElement = document.getElementById('total');

        // Render cart items
        function renderCart() {
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                    <a href="product.php" class="btn-primary">Continue Shopping</a>
                </div>
            `;
                return;
            }

            let subtotal = 0;
            let html = `
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
        `;

            cart.forEach((item, index) => {
                const itemSubtotal = item.price * item.quantity;
                subtotal += itemSubtotal;

                console.log(item.image);
                console.log(item.name);
                console.log(item.id);
                console.log(item.price);
                console.log(item.quantity);

                html += `
                <tr>
                    <td class="product-info">
                        <img src="${item.image}" alt="${item.name}">
                        <div>
                            <h4>${item.name}</h4>
                            <p>Product ID: ${item.id}</p>
                        </div>
                    </td>
                    <td class="price">RM${item.price.toFixed(2)}</td>
                    <td class="quantity">
                        <button class="qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                    </td>
                    <td class="subtotal">RM${itemSubtotal.toFixed(2)}</td>
                    <td class="remove">
                        <button onclick="removeItem(${index})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            });

            html += `</tbody>\n</table>`;
            cartItemsContainer.innerHTML = html;

            // Calculate totals
            const tax = subtotal * 0.06;
            const total = subtotal + tax;

            subtotalElement.textContent = `RM${subtotal.toFixed(2)}`;
            taxElement.textContent = `RM${tax.toFixed(2)}`;
            totalElement.textContent = `RM${total.toFixed(2)}`;
        }

        // Update quantity
        window.updateQuantity = function(index, change) {
            cart[index].quantity += change;

            if (cart[index].quantity < 1) {
                cart[index].quantity = 1;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        // Remove item
        window.removeItem = function(index) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        // Checkout button
        document.getElementById('checkoutBtn').addEventListener('click', function() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            window.location.href = 'checkout.php';
        });

        // Initial render
        renderCart();
    });
</script>

<?php include 'foot.php'; ?>