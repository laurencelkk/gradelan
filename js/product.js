$(document).ready(function() {
    // Add to cart functionality
    $('.addToCartBtn').on('click', function() {
        alert('Product added to cart!');
        });
    
    // Buy now functionality
    $('.buyNowBtn').on('click', function() {
        window.open('checkout.php', '_self');
    });
    
    // Quantity buttons
    $('.quantityBtn.plus').on('click', function() {
        const input = $(this).siblings('.quantityInput');
        if (parseInt(input.val()) < 10) {
            input.val(parseInt(input.val()) + 1);
        }
    });
    
    $('.quantityBtn.minus').on('click', function() {
        const input = $(this).siblings('.quantityInput');
        if (parseInt(input.val()) > 1) {
            input.val(parseInt(input.val()) - 1);
        }
    });
});