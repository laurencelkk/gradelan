$(document).ready(function () {
  // Initialize cart if not exists
  if (localStorage.getItem("cart") === null) {
    localStorage.setItem("cart", JSON.stringify([]));
  }

  // Search functionality
  $("#searchBtn").click(function () {
    const search = $("#searchInput").val();
    const sort = $("#sortSelect").val();
    window.location.href = `product.php?search=${encodeURIComponent(
      search
    )}&sort=${sort}`;
  });

  // Sort functionality
  $("#sortSelect").change(function () {
    const search = $("#searchInput").val();
    const sort = $(this).val();
    window.location.href = `product.php?search=${encodeURIComponent(
      search
    )}&sort=${sort}`;
  });

  // Press Enter in search input
  $("#searchInput").keypress(function (e) {
    if (e.which === 13) {
      // Enter key
      $("#searchBtn").click();
    }
  });

  // Add to cart functionality
  $(".addToCartBtn").click(function () {
    const productCard = $(this).closest(".productCard");
    const productId = productCard.data("id");
    const productName = productCard.find("h3").text();
    const productPrice = parseFloat(
      productCard.find(".price").text().replace("RM", "")
    );
    const productImage = productCard.find(".productImg").attr("src");

    let cart = JSON.parse(localStorage.getItem("cart"));
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

    localStorage.setItem("cart", JSON.stringify(cart));

    // Show success message
    alert(`${productName} (x1) has been added to your cart!`);

    // Update cart count in header if exists
    updateCartCount();
  });

  // Function to update cart count in header
  function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart"));
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);

    if ($("#cartCount").length) {
      $("#cartCount").text(totalItems);
    } else {
      // Create cart count element if not exists
      $("header").append(`<div id="cartCount">${totalItems}</div>`);
    }
  }

  // Initialize cart count on page load
  updateCartCount();
});
