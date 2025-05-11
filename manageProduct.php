<?php
$pageTitle = "Manage Product - GradÉlan";
$stylecss = "css/manageProduct.css";
$script = 'src="js/manageProduct.js" defer';
include 'head.php';

define('UPLOAD_DIR', 'img/products/');

if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

function generateProductID($conn)
{
    $result = $conn->query("SELECT MAX(CAST(SUBSTRING(prodID, 9) AS UNSIGNED)) AS maxNum FROM products");
    $row = $result->fetch_assoc();
    $maxID = $row['maxNum'];
    $next_number = $maxID ? $maxID + 1 : 1;
    return 'CA-PROD-' . str_pad($next_number, 4, '0', STR_PAD_LEFT);
}

$errors = [];
$successMsg = '';

// Pagination
$itemsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Search and Filter
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Determine sort order
switch ($sort) {
    case 'name_asc':
        $orderBy = "prodName ASC";
        break;
    case 'name_desc':
        $orderBy = "prodName DESC";
        break;
    case 'date_asc':
        $orderBy = "createdAt ASC";
        break;
    case 'date_desc':
        $orderBy = "createdAt DESC";
        break;
    default:
        $orderBy = "prodName ASC";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prodID = $_POST['prodID'] ?? '';
    $prodName = trim($_POST['prodName'] ?? '');
    $prodDesc = trim($_POST['prodDesc'] ?? '');
    $prodPrice = floatval($_POST['prodPrice'] ?? 0);
    $prodImg = $_FILES['prodImg'] ?? null;

    if (empty($prodName)) $errors[] = "Product name is required";
    if (empty($prodDesc)) $errors[] = "Product description is required";
    if ($prodPrice <= 0) $errors[] = "Product price must be greater than 0";

    $isNewProduct = empty($prodID);
    $imageRequired = $isNewProduct || !empty($prodImg['name']);
    $filename = '';

    if ($imageRequired) {
        if ($prodImg && $prodImg['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
            $max_size = 2 * 1024 * 1024;

            if (!in_array($prodImg['type'], $allowed_types)) {
                $errors[] = "Only JPG, JPEG, PNG, and WEBP files are allowed.";
            }

            if ($prodImg['size'] > $max_size) {
                $errors[] = "Image size must be less than 2MB";
            }

            $ext = pathinfo($prodImg['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $target_path = UPLOAD_DIR . $filename;
        } else {
            $errors[] = "Product image is required";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT prodID FROM products WHERE prodName = ? AND prodID != ?");
        $stmt->bind_param("ss", $prodName, $prodID);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "A product with this name already exists";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        if (!$isNewProduct && empty($prodImg['name'])) {
            $stmt = $conn->prepare("SELECT prodImg FROM products WHERE prodID = ?");
            $stmt->bind_param("s", $prodID);
            $stmt->execute();
            $result = $stmt->get_result();
            $filename = $result->fetch_assoc()['prodImg'];
            $stmt->close();
        }

        $fileMoved = true;
        if ($imageRequired && !empty($prodImg['name'])) {
            $fileMoved = move_uploaded_file($prodImg['tmp_name'], $target_path);
        }

        if ($fileMoved) {
            if ($isNewProduct) {
                $prodID = generateProductID($conn);
                $stmt = $conn->prepare("INSERT INTO products (prodID, prodName, prodDesc, prodPrice, prodImg) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssds", $prodID, $prodName, $prodDesc, $prodPrice, $filename);
                $success = $stmt->execute();
                $stmt->close();
            } else {
                if (!empty($prodImg['name'])) {
                    $stmt = $conn->prepare("SELECT prodImg FROM products WHERE prodID = ?");
                    $stmt->bind_param("s", $prodID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $oldImg = $result->fetch_assoc()['prodImg'];
                    $stmt->close();

                    if ($oldImg && file_exists(UPLOAD_DIR . $oldImg)) {
                        unlink(UPLOAD_DIR . $oldImg);
                    }
                }

                $stmt = $conn->prepare("UPDATE products SET prodName = ?, prodDesc = ?, prodPrice = ?, prodImg = ? WHERE prodID = ?");
                $stmt->bind_param("ssdss", $prodName, $prodDesc, $prodPrice, $filename, $prodID);
                $success = $stmt->execute();
                $stmt->close();
            }

            if ($success) {
                $successMsg = $isNewProduct ? "Product added successfully!" : "Product updated successfully!";
                header("Location: manageProduct.php?page=$page&search=" . urlencode($search) . "&sort=$sort");
                exit;
            } else {
                $errors[] = "Error saving product";
            }
        } else {
            $errors[] = "Failed to upload image";
        }
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $prodID = $_GET['delete'];
    $stmt = $conn->prepare("SELECT prodImg FROM products WHERE prodID = ?");
    $stmt->bind_param("s", $prodID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM products WHERE prodID = ?");
    $stmt->bind_param("s", $prodID);
    if ($stmt->execute()) {
        if ($product && file_exists(UPLOAD_DIR . $product['prodImg'])) {
            unlink(UPLOAD_DIR . $product['prodImg']);
        }
        $successMsg = "Product deleted successfully!";
        header("Location: manageProduct.php?page=$page&search=" . urlencode($search) . "&sort=$sort");
        exit;
    } else {
        $errors[] = "Error deleting product";
    }
    $stmt->close();
}

// Count total products
$countQuery = "SELECT COUNT(*) AS total FROM products WHERE prodName LIKE ? OR prodID LIKE ?";
$searchParam = "%$search%";
$stmt = $conn->prepare($countQuery);
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
$totalItems = $result->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch products
$query = "SELECT * FROM products WHERE prodName LIKE ? OR prodID LIKE ? ORDER BY $orderBy LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssii", $searchParam, $searchParam, $itemsPerPage, $offset);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="containers">
    <h1>Manage Products</h1>

    <?php if (!empty($successMsg)): ?>
        <div class="alert success">
            <?= htmlspecialchars($successMsg); ?>
            <span class="close-alert">&times;</span>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?= implode("<br>", array_map('htmlspecialchars', $errors)); ?>
            <span class="close-alert">&times;</span>
        </div>
    <?php endif; ?>

    <div class="toolbar">
        <div class="search-filter">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search products..." value="<?= htmlspecialchars($search); ?>">
                <button id="searchBtn">Search</button>
            </div>
            <div class="filter-box">
                <select id="sortSelect">
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                    <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                    <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
                    <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest First</option>
                </select>
            </div>
        </div>
        <button class="btn-open-modal" onclick="openModal()">Add New Product</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="no-data">No products found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?= UPLOAD_DIR . htmlspecialchars($product['prodImg']); ?>" class="product-img"
                                    alt="<?= htmlspecialchars($product['prodName']); ?>" onerror="this.src='img/default-product.png'">
                            </td>
                            <td><?= htmlspecialchars($product['prodID']); ?></td>
                            <td><?= htmlspecialchars($product['prodName']); ?></td>
                            <td><?= htmlspecialchars($product['prodDesc']); ?></td>
                            <td>RM<?= number_format($product['prodPrice'], 2); ?></td>
                            <td><?= date('M j, Y', strtotime($product['createdAt'])); ?></td>
                            <td class="action-btns">
                                <button class="btn-edit" onclick="editProduct('<?= $product['prodID']; ?>')">Edit</button>
                                <button class="btn-delete"
                                    onclick="deleteProduct('<?= $product['prodID']; ?>', '<?= addslashes($product['prodName']); ?>')">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>" class="page-link">&laquo; First</a>
                <a href="?page=<?= $page - 1; ?>&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>" class="page-link">&lsaquo; Prev</a>
            <?php endif; ?>

            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);

            if ($start > 1) echo '<span class="page-dots">...</span>';

            for ($i = $start; $i <= $end; $i++): ?>
                <a href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>" class="page-link <?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
            <?php endfor;

            if ($end < $totalPages) echo '<span class="page-dots">...</span>';
            ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1; ?>&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>" class="page-link">Next &rsaquo;</a>
                <a href="?page=<?= $totalPages; ?>&search=<?= urlencode($search); ?>&sort=<?= $sort; ?>" class="page-link">Last &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add New Product</h2>
            <form action="" method="POST" enctype="multipart/form-data" novalidate autocomplete="off">
                <label for="prodID" title="Required" class="hiddens">Product ID<span class="must">*</span></label>
                <input type="text" name="prodID" id="prodID" class="pHolder hiddens" value="" readonly>
                <input type="hidden" id="isEditing" name="isEditing" value="0">

                <label for="prodName" title="Required">Product Name<span class="must">*</span></label>
                <input type="text" name="prodName" id="prodName" placeholder="Enter Product Name" class="pHolder"
                    required />
                <div class="mustMsg" id="prodNameError"></div>

                <label for="prodDesc" title="Required">Product Description<span class="must">*</span></label>
                <textarea name="prodDesc" id="prodDesc" placeholder="Enter Product Description" class="pHolder"
                    required></textarea>
                <div class="mustMsg" id="prodDescError"></div>

                <label for="prodPrice" title="Required">Product Price<span class="must">*</span></label>
                <input type="number" name="prodPrice" id="prodPrice" placeholder="Enter Product Price" class="pHolder"
                    min="0.01" step="0.01" required />
                <div class="mustMsg" id="prodPriceError"></div>

                <label for="prodImg" title="Required">Product Image<span class="must">*</span></label>
                <input type="file" name="prodImg" id="prodImg" accept="image/*" class="pHolder" required />
                <div class="mustMsg" id="prodImgError"></div>

                <p>Product's Image Preview</p>
                <img id="imgPreview" src="" alt="Image Preview" />
                <p id="npAvailable">No preview image available</p>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const products = <?= json_encode($products); ?>;

    let isEditing = false;

    function editProduct(prodID) {
        const product = products.find((p) => p.prodID === prodID);
        if (product) {
            isEditing = true;
            $("#modalTitle").text("Edit Product");
            $("#prodID").val(product.prodID);
            $(".hiddens").show();
            $("#isEditing").val("1");
            $("#prodName").val(product.prodName);
            $("#prodDesc").val(product.prodDesc);
            $("#prodPrice").val(product.prodPrice);
            $("#imgPreview").attr("src", "<?= UPLOAD_DIR; ?>" + product.prodImg).show();
            $("#npAvailable").hide();
            $("#prodImg").val('').removeAttr('required');
            $("#prodName, #prodDesc, #prodPrice").addClass("success");
            $("#productModal").show();
        }
    }
</script>

<?php include 'foot.php'; ?>