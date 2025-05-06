<?php
include 'base.php';

$prodID = $_GET['prodID'] ?? null;

if (!$prodID) {
    redirect("product.php");
}

// Fetch the product from database
$stmt = $conn->prepare("SELECT * FROM products WHERE prodID = ?");
$stmt->bind_param("s", $prodID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    redirect("product.php");
}

// Fetch related products
$relatedStmt = $conn->prepare("SELECT * FROM products WHERE prodID != ? LIMIT 4");
$relatedStmt->bind_param("s", $prodID);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();
$relatedProducts = [];
while ($row = $relatedResult->fetch_assoc()) {
    $relatedProducts[] = $row;
}
$relatedStmt->close();
?>

<?php
$pageTitle = "Product Details - " . htmlspecialchars($product['prodName']);
$stylecss = "css/productDetails.css";
$script = "src='js/productDetails.js'";
include 'head.php';
?>

<div class="productCard" data-id="<?= htmlspecialchars($product['prodID']); ?>">
    <div class="productDetailContainer">
        <div class="productDetailGrid">
            <div class="productGallery">
                <div class="mainImage">
                    <img class="productImg" src="img/products/<?= htmlspecialchars($product['prodImg']); ?>"
                        alt="<?= htmlspecialchars($product['prodName']); ?>">
                </div>
            </div>

            <div class="productInfo">
                <h1><?= htmlspecialchars($product['prodName']); ?></h1>

                <p class="productIDs"><?= htmlspecialchars($product['prodID']); ?></p>

                <div class="pricesContainer">
                    <span class="price">RM<?= number_format($product['prodPrice'], 2); ?></span>
                </div>

                <div class="productDescription">
                    <p><?= nl2br(htmlspecialchars($product['prodDesc'])); ?></p>
                </div>

                <div class="productActions">
                    <div class="quantitySelector">
                        <button class="quantityBtn minus"><i class="fas fa-minus"></i></button>
                        <input type="number" value="1" min="1" max="10" class="quantityInput" readonly>
                        <button class="quantityBtn plus"><i class="fas fa-plus"></i></button>
                    </div>

                    <button class="addToCartBtn">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>

                    <button class="buyNowBtn">
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products Section -->
<section class="relatedProducts">
    <h2>You May Also Like</h2>
    <div class="relatedGrid">
        <?php foreach ($relatedProducts as $related): ?>
            <div class="productCard">
                <a href="productDetails.php?prodID=<?= htmlspecialchars($related['prodID']); ?>">
                    <div class="productImage">
                        <img src="img/products/<?= htmlspecialchars($related['prodImg']); ?>"
                            alt="<?= htmlspecialchars($related['prodName']); ?>">
                    </div>
                    <h3><?= htmlspecialchars($related['prodName']); ?></h3>
                    <p class="productPrice">RM<?= number_format($related['prodPrice'], 2); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<?php include 'foot.php'; ?>