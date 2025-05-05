<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : "" ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="<?= isset($stylecss) ? htmlspecialchars($stylecss) : "" ?>">
    <script <?= isset($script) ? htmlspecialchars($script) : "" ?>></script>
    <script src="https://kit.fontawesome.com/cf66887dbc.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <input type="text" placeholder="Search graduation products..." />
            </div>

            <div class="nav-right">
                <nav>
                    <a href="/">Home</a>
                    <a href="product.php">Products</a>
                    <a href="about.php">About</a>
                    <a href="contact.php">Contact</a>
                    <a href="manageproduct.php">Manage Product</a>
                </nav>
                <div class="icons">
                <a href="#"><i class="fas fa-shopping-cart icon"></i></a>
                </div>
            </div>
        </div>
    </header>
