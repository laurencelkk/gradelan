<?php
include 'base.php';

// Include PHPMailer files (you may need to adjust the paths based on where PHPMailer is installed)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload Composer dependencies if PHPMailer is installed via Composer
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['newsMail'])) {
    $email = filter_var(trim($_POST['newsMail']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    } else {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'csstarumtpg@gmail.com';
            $mail->Password   = 'khebbtdvzgbzpacq';   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587; 

            // Sender and recipient
            $mail->setFrom('support@gradelan.laurencelkk.my', 'GradElan');
            $mail->addAddress($email); 

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Thank you for subscribing!';
            $mail->Body    = '<p>Dear Subscriber,</p><p>Thank you for subscribing to GradÉlan\'s newsletter! We will keep you updated with exclusive offers and graduation tips.</p><p>Best regards,<br>GradÉlan Team</p>';
            
            // Send the email
            $mail->send();
            $currentPage = basename($_SERVER['PHP_SELF']);
            redirect($currentPage);
        } catch (Exception $e) {
            echo "<script>console.log('Mail Error: " . addslashes($mail->ErrorInfo) . "');</script>";
        }
    }
}
?>

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
                <form action="product.php" method="get" class="search-form" id="searchForm">
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