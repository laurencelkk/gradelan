$(document).ready(function () {
  // Initialize cart if not exists
  if (localStorage.getItem("cart") === null) {
    localStorage.setItem("cart", JSON.stringify([]));
  }

  // Add to cart functionality
  $(".addToCartBtn").click(function () {
    const productCard = $(this).closest(".productCard");
    const productId = productCard.data("id");
    const productName = productCard.find("h1, h3").first().text();
    const productPrice = parseFloat(
      productCard.find(".price").text().replace("RM", "")
    );
    const productImage = productCard.find(".productImg").attr("src");
    const quantity = parseInt(productCard.find(".quantityInput").val()) || 1;

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existingItem = cart.find((item) => item.id === productId);

    if (existingItem) {
      existingItem.quantity += quantity;
    } else {
      cart.push({
        id: productId,
        name: productName,
        price: productPrice,
        image: productImage,
        quantity: quantity,
      });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    alert(`${productName} (x${quantity}) has been added to your cart!`);
    updateCartCount();
  });

  // Buy now functionality
  $(".buyNowBtn").click(function () {
    const productCard = $(this).closest(".productCard");
    const productId = productCard.data("id");
    const productName = productCard.find("h1, h3").first().text();
    const productPrice = parseFloat(
      productCard.find(".price").text().replace("RM", "")
    );
    const productImage = productCard.find(".productImg").attr("src");
    const quantity = parseInt(productCard.find(".quantityInput").val()) || 1;

    const cart = [
      {
        id: productId,
        name: productName,
        price: productPrice,
        image: productImage,
        quantity: quantity,
      },
    ];

    localStorage.setItem("cart", JSON.stringify(cart));
    window.location.href = "checkout.php";
  });

  // Quantity increase/decrease buttons
  $(".quantityBtn.plus").click(function () {
    let input = $(this).siblings(".quantityInput");
    let current = parseInt(input.val());
    if (current < 10) input.val(current + 1);
  });

  $(".quantityBtn.minus").click(function () {
    let input = $(this).siblings(".quantityInput");
    let current = parseInt(input.val());
    if (current > 1) input.val(current - 1);
  });

  // Function to update cart count
  function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    $("#cartCount").text(totalItems);
  }

  // Initialize cart count on load
  updateCartCount();
});
