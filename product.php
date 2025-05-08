<?php
$pageTitle = "Products | GradÉlan";
$stylecss = "css/product.css";
$script = "src=js/product.js";
include 'head.php'; 

// Pagination
$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$searchCondition = '';
$searchParams = [];

if ($search !== '') {
    $searchCondition = "WHERE prodName LIKE ? OR prodID LIKE ?";
    $searchParams[] = '%' . $search . '%';
    $searchParams[] = '%' . $search . '%';
}

// Sorting functionality
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
$orderBy = '';

switch ($sort) {
    case 'name_asc': $orderBy = "prodName ASC"; break;
    case 'name_desc': $orderBy = "prodName DESC"; break;
    case 'price_asc': $orderBy = "prodPrice ASC"; break;
    case 'price_desc': $orderBy = "prodPrice DESC"; break;
    case 'date_asc': $orderBy = "createdAt ASC"; break;
    case 'date_desc': $orderBy = "createdAt DESC"; break;
    default: $orderBy = "prodName ASC";
}

// Fetch products with pagination
$sql = "SELECT * FROM products $searchCondition ORDER BY $orderBy LIMIT ?, ?";
$stmt = $conn->prepare($sql);

// Bind parameters
$paramTypes = '';
$bindParams = [];

if ($search !== '') {
    $paramTypes .= 'ss';
    $bindParams = array_merge($bindParams, $searchParams);
}

$paramTypes .= 'ii';
$bindParams[] = $start;
$bindParams[] = $limit;

$stmt->bind_param($paramTypes, ...$bindParams);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get total number of products for pagination
$countSql = "SELECT COUNT(*) as total FROM products $searchCondition";
$countStmt = $conn->prepare($countSql);

if ($search !== '') {
    $countStmt->bind_param('ss', ...$searchParams);
}

$countStmt->execute();
$countResult = $countStmt->get_result();
$totalProducts = $countResult->fetch_assoc()['total'] ?? 0;
$totalPages = ceil($totalProducts / $limit);
$countStmt->close();
?>

<body>
    <div class="mainContainer">
        <h1 class="heading">🛍️ Welcome to Our Shop!</h1>
        
        <div class="toolbar">
            <div class="search-filter">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search products..." 
                           value="<?= htmlspecialchars($search); ?>">
                    <button id="searchBtn"><i class="fas fa-search"></i></button>
                </div>
                <div class="filter-box">
                    <select id="sortSelect">
                        <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                        <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
                        <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
                        <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest First</option>
                    </select>
                </div>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="noResults">
                <i class="fas fa-search"></i>
                <h3>No products found</h3>
                <p>Try adjusting your search or filter to find what you're looking for.</p>
            </div>
        <?php else: ?>
            <div class="productList">
                <?php foreach ($products as $product): ?>
                    <div class="productCard" data-id="<?= $product['prodID']; ?>">
                        <div class="productImgContainer">
                            <img src="img/products/<?= htmlspecialchars($product['prodImg']); ?>"
                                alt="<?= htmlspecialchars($product['prodName']); ?>" class="productImg">
                            <div class="productOverlay">
                                <button class="quickViewBtn" onclick="location.href='productDetails.php?prodID=<?= $product['prodID']; ?>'">
                                    Quick View
                                </button>
                            </div>
                        </div>
                        <h3><?= htmlspecialchars($product['prodName']); ?></h3>
                        <p class="productID"><?= htmlspecialchars($product['prodID']); ?></p>
                        <p class="price">RM<?= number_format($product['prodPrice'], 2); ?></p>
                        <div class="productActions">
                            <button class="addToCartBtn">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <button class="buyNowBtn">
                                <i class="fas fa-bolt"></i> Buy Now
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination Links -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="product.php?page=<?= $page - 1; ?>&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>" class="paginationArrow">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);

                    if ($startPage > 1) {
                        echo '<a href="product.php?page=1&search=' . urlencode($search) . '&sort=' . $sort . '">1</a>';
                        if ($startPage > 2) echo '<span class="paginationDots">...</span>';
                    }

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="product.php?page=<?= $i; ?>&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>"
                            class="<?= ($i == $page) ? 'active' : ''; ?>">
                            <?= $i; ?>
                        </a>
                    <?php endfor;

                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) echo '<span class="paginationDots">...</span>';
                        echo '<a href="product.php?page=' . $totalPages . '&search=' . urlencode($search) . '&sort=' . $sort . '">' . $totalPages . '</a>';
                    }
                    ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="product.php?page=<?= $page + 1; ?>&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>" class="paginationArrow">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include 'foot.php'; ?>