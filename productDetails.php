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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['prodName']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="product.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="product.js"></script>
</head>

<body>
    <div class="productDetailContainer">
        <div class="productDetailGrid">
            <div class="productGallery">
                <div class="mainImage">
                    <img src="img/products/<?= htmlspecialchars($product['prodImg']); ?>"
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

                    <button class="addToCartBtn" data-id="<?= $product['prodID']; ?>">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>

                    <button class="buyNowBtn" data-id="<?= $product['prodID']; ?>">
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
