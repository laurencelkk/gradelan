<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : "GradÉlan" ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="<?= isset($stylecss) ? htmlspecialchars($stylecss) : "" ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script <?= isset($script) ? $script : "" ?>></script>
    <script src="js/head.js"></script>
    <script src="js/foot.js" defer></script>
    <script src="https://kit.fontawesome.com/cf66887dbc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body>
    <header>
        <div class="container nav-container">
            <a href="/" target="_self" class="logo-area">
                <img src="logo.gif" alt="GradÉlan Logo" class="logo" />
                <h1>GradÉlan</h1>
            </a>

            <div class="search-area">
                <form action="product.php" method="get" class="search-form">
                    <input type="text" name="search" placeholder="Search graduation products..."
                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="nav-right">
                <nav>
                    <a href="/">Home</a>
                    <a href="product.php">Products</a>
                    <a href="about.php">About</a>
                    <a href="contact.php">Contact</a>
                    <a href="manageproduct.php">Manage Product</a>
                </nav>
                <div class="cart-icon" id="cartIcon">
                    <i class="fas fa-shopping-cart icon"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </div>
            </div>
        </div>
    </header>