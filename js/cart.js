document.addEventListener("DOMContentLoaded", function () {
    // Helper functions
    function getCart() {
        return JSON.parse(localStorage.getItem("cart")) || [];
    }

    function saveCart(cart) {
        localStorage.setItem("cart", JSON.stringify(cart));
    }

    function updateCartCount() {
        const cart = getCart();
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        
        // Update all cart count elements
        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = totalItems;
        });
        
        return totalItems;
    }

    // DOM elements
    const cartItemsContainer = document.getElementById("cartItems");
    const subtotalElement = document.getElementById("subtotal");
    const taxElement = document.getElementById("tax");
    const shippingElement = document.getElementById("shipping");
    const totalElement = document.getElementById("total");
    const checkoutBtn = document.getElementById("checkoutBtn");

    // Main render function
    function renderCart() {
        const cart = getCart(); 
        
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tax = subtotal * 0.06;
        const shipping = subtotal > 250 ? 0 : 18; 
        const total = subtotal + tax + shipping;

        subtotalElement.textContent = `RM${subtotal.toFixed(2)}`;
        taxElement.textContent = `RM${tax.toFixed(2)}`;
        shippingElement.textContent = `RM${shipping.toFixed(2)}`;
        totalElement.textContent = `RM${total.toFixed(2)}`;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                    <a href="product.php" class="btn-primary">Continue Shopping</a>
                </div>
            `;
            updateCartCount();
            return;
        }

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

        html += `</tbody></table>`;
        cartItemsContainer.innerHTML = html;
        updateCartCount();
    }

    // Update quantity
    window.updateQuantity = function (index, change) {
        const cart = getCart();
        
        // Ensure index is valid
        if (index < 0 || index >= cart.length) return;
        
        cart[index].quantity += change;

        // Validate quantity
        if (cart[index].quantity < 1) {
            cart[index].quantity = 1;
            alert("Minimum quantity is 1");
        }

        if (cart[index].quantity > 10) {
            cart[index].quantity = 10;
            alert("Maximum quantity is 10");
        }

        saveCart(cart);
        renderCart();
    };

    // Remove item
    window.removeItem = function (index) {
        const cart = getCart();
        
        // Ensure index is valid
        if (index < 0 || index >= cart.length) return;
        
        cart.splice(index, 1);
        saveCart(cart);
        renderCart();
    };

    // Checkout button
    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", function () {
            const cart = getCart();
            if (cart.length === 0) {
                alert("Your cart is empty!");
                return;
            }
            window.location.href = "checkout.php";
        });
    }

    // Initial render
    renderCart();
});