// Cart functionality
document.addEventListener("DOMContentLoaded", function () {
  // Initialize cart from localStorage
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  // Update cart count display
  function updateCartCount() {
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    document.getElementById("cartCount").textContent = totalItems;
  }

  // Add to cart functionality
  document.querySelectorAll(".btn-cart").forEach((button) => {
    button.addEventListener("click", function () {
      const productCard = this.closest(".product-card");
      const productId = productCard
        .querySelector(".btn-quickview")
        .getAttribute("onclick")
        .match(/prodID=([\w-]+)/)[1];
      const productName = productCard.querySelector("h3").textContent;
      const productPrice = parseFloat(
        productCard.querySelector(".price").textContent.replace("RM", "")
      );
      const productImage = productCard.querySelector("img").src;

      // Check if product already in cart
      const existingItem = cart.find((item) => item.id === productId);

      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({
          id: productId,
          name: productName,
          price: productPrice,
          image: productImage,
          quantity: 1,
        });
      }

      // Save to localStorage and update UI
      localStorage.setItem("cart", JSON.stringify(cart));
      updateCartCount();

      // Show added to cart feedback
      const originalText = this.innerHTML;
      this.innerHTML = '<i class="fas fa-check"></i> Added!';
      this.style.backgroundColor = "#4CAF50";

      setTimeout(() => {
        this.innerHTML = originalText;
        this.style.backgroundColor = "#8a4fff";
      }, 1500);
    });
  });

  // Cart icon click handler
  document.getElementById("cartIcon").addEventListener("click", function (e) {
    e.preventDefault();
    window.location.href = "cart.php";
  });

  // Initialize cart count
  updateCartCount();
});
