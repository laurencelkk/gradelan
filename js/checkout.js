document.addEventListener("DOMContentLoaded", function () {
  // Get cart from localStorage
  function getCart() {
    return JSON.parse(localStorage.getItem("cart")) || [];
  }

  // Get selected payment method from localStorage
  function getSelectedPaymentMethod() {
    return localStorage.getItem("selectedPaymentMethod") || "credit";
  }

  // Save selected payment method to localStorage
  function saveSelectedPaymentMethod(method) {
    localStorage.setItem("selectedPaymentMethod", method);
  }

  // If cart is empty, redirect to products
  if (getCart().length === 0) {
    alert(
      "Your cart is empty! Please add items to your cart before proceeding to checkout."
    );
    window.location.href = "product.php";
  }

  // Render cart items in checkout page
  function renderCheckoutCart() {
    const cart = getCart();
    const cartContainer = document.getElementById("checkoutCartItems");
    const subtotalElement = document.getElementById("checkoutSubtotal");
    const taxElement = document.getElementById("checkoutTax");
    const shippingElement = document.getElementById("checkoutShipping");
    const totalElement = document.getElementById("checkoutTotal");

    let html = "";
    let subtotal = 0;

    cart.forEach((item) => {
      const itemTotal = item.price * item.quantity;
      subtotal += itemTotal;

      html += `
        <div class="checkout-item">
          <img src="${item.image}" alt="${item.name}">
          <div class="item-details">
            <h4>${item.name}</h4>
            <p>Product ID: ${item.id}</p>
          </div>
          <div class="item-meta">
            <div class="item-price">RM${item.price.toFixed(2)}</div>
            <div class="item-quantity">Qty: ${item.quantity}</div>
          </div>
        </div>
      `;
    });

    cartContainer.innerHTML = html;

    const tax = subtotal * 0.06;
    const shipping = subtotal > 250 ? 0 : 18;
    const total = subtotal + tax + shipping;

    subtotalElement.textContent = `RM${subtotal.toFixed(2)}`;
    taxElement.textContent = `RM${tax.toFixed(2)}`;
    shippingElement.textContent = `RM${shipping.toFixed(2)}`;
    totalElement.textContent = `RM${total.toFixed(2)}`;
  }

  // Initialize payment methods
  function initializePaymentMethods() {
    const selectedMethod = getSelectedPaymentMethod();
    const paymentOptions = document.querySelectorAll(".payment-option");
    const creditCardDetails = document.getElementById("creditCardDetails");
    const tngDetails = document.getElementById("tngDetails");
    const total = document.getElementById("checkoutTotal").textContent;

    document.querySelectorAll(".payment-details").forEach((detail) => {
      detail.style.display = "none";
    });

    paymentOptions.forEach((opt) => opt.classList.remove("active"));

    paymentOptions.forEach((option) => {
      if (option.dataset.method === selectedMethod) {
        option.classList.add("active");

        if (selectedMethod === "credit") {
          creditCardDetails.style.display = "block";
        } else if (selectedMethod === "tng") {
          tngDetails.style.display = "block";
          document.getElementById("tngAmount").textContent = total;
        }
      }
    });
  }

  // Payment method selection
  function setupPaymentMethodSelection() {
    const paymentOptions = document.querySelectorAll(".payment-option");
    const total = document.getElementById("checkoutTotal").textContent;

    paymentOptions.forEach((option) => {
      option.addEventListener("click", function () {
        const method = this.dataset.method;
        saveSelectedPaymentMethod(method);

        paymentOptions.forEach((opt) => opt.classList.remove("active"));
        this.classList.add("active");

        document.querySelectorAll(".payment-details").forEach((detail) => {
          detail.style.display = "none";
        });

        if (method === "credit") {
          document.getElementById("creditCardDetails").style.display = "block";
        } else if (method === "tng") {
          document.getElementById("tngDetails").style.display = "block";
          document.getElementById("tngAmount").textContent = total;
        }
      });
    });
  }

  // Validation functions
  function isValidName(name) {
    return /^[A-Za-z\s]+$/.test(name);
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function isValidPhone(phone) {
    return /^\+60\d{2}-\d{7,8}$/.test(phone);
  }

  function isValidCardNumber(cardNumber) {
    const cleaned = cardNumber.replace(/\s+/g, "");
    return /^\d{16}$/.test(cleaned);
  }

  function isValidExpiryDate(expiry) {
    return /^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry);
  }

  function isValidCVV(cvv) {
    return /^\d{3}$/.test(cvv);
  }

  // Auto-format expiry date
  function setupExpiryDateFormatting() {
    const expiryInput = document.getElementById("expiryDate");
    expiryInput.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\D/g, "");
      if (value.length >= 3) {
        value = value.slice(0, 2) + "/" + value.slice(2, 4);
      }
      e.target.value = value.slice(0, 5);
    });
  }

  function setupCardNumberFormatting() {
    const cardInput = document.getElementById("cardNumber");

    cardInput.addEventListener("input", function (e) {
      let value = e.target.value.replace(/\D/g, "").slice(0, 16); // Only digits, max 16
      let formatted = value.match(/.{1,4}/g); // Split every 4 digits
      e.target.value = formatted ? formatted.join(" ") : "";
    });
  }

  function setupCVVLimit() {
    const cvvInput = document.getElementById("cvv");

    cvvInput.addEventListener("input", function (e) {
      e.target.value = e.target.value.replace(/\D/g, "").slice(0, 3); // Only 3 digits
    });
  }

  function setupPhoneNumberFormatting() {
    const phoneInput = document.getElementById("phone");

    phoneInput.addEventListener("focus", function () {
      if (!phoneInput.value.startsWith("+60")) {
        phoneInput.value = "+60";
      }
    });

    phoneInput.addEventListener("input", function () {
      // Keep +60 and only allow digits and hyphen afterward
      let value = phoneInput.value.replace(/[^\d\-]/g, "");

      if (!value.startsWith("60")) {
        value = "60" + value.replace(/^60*/, "");
      }

      phoneInput.value = "+" + value;
    });
  }

  // Form validation and submission
  function setupFormSubmission() {
    const checkoutForm = document.getElementById("checkoutForm");

    checkoutForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const cart = getCart();
      if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
      }

      const form = e.target;

      const cartInput = document.createElement("input");
      cartInput.type = "hidden";
      cartInput.name = "cart";
      cartInput.value = JSON.stringify(cart);
      form.appendChild(cartInput);

      const paymentInput = document.createElement("input");
      paymentInput.type = "hidden";
      paymentInput.name = "paymentMethod";
      paymentInput.value = getSelectedPaymentMethod();
      form.appendChild(paymentInput);

      const fullName = document.getElementById("fullName").value.trim();
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();
      const address = document.getElementById("address").value.trim();
      const terms = document.getElementById("terms").checked;

      if (!fullName || !email || !phone || !address) {
        alert("Please fill in all required fields");
        return;
      }

      if (!terms) {
        alert("Please agree to the terms and conditions");
        return;
      }

      if (!isValidName(fullName)) {
        alert("Please enter a valid name (letters and spaces only)");
        return;
      }

      if (!isValidEmail(email)) {
        alert("Please enter a valid email address");
        return;
      }

      if (!isValidPhone(phone)) {
        alert(
          "Please enter a valid Malaysian phone number (e.g., +6012-3456789)"
        );
        return;
      }

      const activePayment = document.querySelector(".payment-option.active");
      const paymentMethod = activePayment ? activePayment.dataset.method : null;

      if (paymentMethod === "credit") {
        const cardNumber = document.getElementById("cardNumber").value.trim();
        const expiryDate = document.getElementById("expiryDate").value.trim();
        const cvv = document.getElementById("cvv").value.trim();

        if (!cardNumber || !expiryDate || !cvv) {
          alert("Please enter your credit card details");
          return;
        }

        if (!isValidCardNumber(cardNumber)) {
          alert("Card number must be exactly 16 digits.");
          return;
        }

        if (!isValidExpiryDate(expiryDate)) {
          alert("Expiry date must be in MM/YY format.");
          return;
        }

        if (!isValidCVV(cvv)) {
          alert("CVV must be exactly 3 digits.");
          return;
        }
      }

      // All validations passed, submit form
      checkoutForm.submit();
    });
  }

  // Run all initializers
  renderCheckoutCart();
  initializePaymentMethods();
  setupPaymentMethodSelection();
  setupFormSubmission();
  setupExpiryDateFormatting();
  setupCardNumberFormatting();
  setupCVVLimit();
  setupPhoneNumberFormatting();
});
