<?php
$pageTitle = "GradÉlan - Graduation Essentials";
$stylecss = "css/index.css";
include 'head.php';

$query = "SELECT * FROM products ORDER BY createdAt DESC LIMIT 3";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!-- Hero Banner -->
<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content container">
    <h1>Celebrate Your Achievement in Style</h1>
    <p>Premium graduation essentials designed to make your milestone moment unforgettable</p>
    <div class="hero-cta">
      <button onclick="location.href='#featured-products'" class="btn-primary btn">Shop Collection</button>
      <button onclick="location.href='#why-us'" class="btn-secondary btn">Learn More <i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
  <div class="hero-scroll">
    <a href="#featured-products"><i class="fas fa-chevron-down"></i></a>
  </div>
</section>

<!-- Featured Products -->
<section id="featured-products" class="container products-section">
  <div class="section-header" id="featured-products-header">
    <h2>Featured Graduation Essentials</h2>
    <p>Curated collection for your special day</p>
    <button onclick="location.href='product.php'" class="btn-link">View All Products <i class="fas fa-arrow-right"></i></button>
  </div>
  <div class="product-grid">
    <?php foreach ($products as $product): ?>
      <div class="product-card productCard" data-id="<?= $product['prodID'] ?>">
        <div class="product-badge">NEW</div>
        <div class="product-image">
          <img src="img/products/<?php echo htmlspecialchars($product['prodImg']); ?>" alt="<?php echo htmlspecialchars($product['prodName']); ?>" class="productImg" loading="eager"/>
          <div class="quick-view-overlay">
            <button class="btn-quickview" onclick="location.href='productDetails.php?prodID=<?php echo $product['prodID']; ?>'">
              <i class="fas fa-eye"></i> Quick View
            </button>
          </div>
        </div>
        <div class="product-details">
          <h3><?php echo htmlspecialchars($product['prodName']); ?></h3>
          <div class="product-meta">
            <span class="price">RM<?php echo number_format($product['prodPrice'], 2); ?></span>
          </div>
          <button class="btn-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
          <button class="buyNowBtn"><i class="fas fa-bolt"></i> Buy Now
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Value Proposition -->
<section class="value-prop">
  <div class="container">
    <div class="value-grid">
      <div class="value-image">
        <img src="img/graduationcelebration.png" alt="Happy graduates celebrating" loading="lazy">
      </div>
      <div class="value-content">
        <h2>More Than Just Graduation Attire</h2>
        <p>At GradÉlan, we understand that graduation is a once-in-a-lifetime milestone. Our carefully crafted products are designed to help you celebrate this achievement with the elegance and dignity it deserves.</p>
        <ul class="value-list">
          <li><i class="fas fa-check-circle"></i> Premium materials for unmatched comfort</li>
          <li><i class="fas fa-check-circle"></i> Tailored fit for all body types</li>
          <li><i class="fas fa-check-circle"></i> Eco-friendly production processes</li>
          <li><i class="fas fa-check-circle"></i> Customization options to make it uniquely yours</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Why Shop With Us -->
<section id="why-us" class="trust-section">
  <div class="container">
    <div class="section-header">
      <h2>Why Choose GradÉlan?</h2>
      <p>We go above and beyond to make your graduation perfect</p>
    </div>
    <div class="trust-grid">
      <div class="trust-item">
        <div class="trust-icon">
          <i class="fas fa-medal"></i>
        </div>
        <h4>Premium Quality</h4>
        <p>Our graduation essentials are crafted from high-quality materials that drape beautifully and last.</p>
      </div>
      <div class="trust-item">
        <div class="trust-icon">
          <i class="fas fa-truck"></i>
        </div>
        <h4>Fast & Reliable Shipping</h4>
        <p>Get your order in 3-5 business days with our expedited shipping options.</p>
      </div>
      <div class="trust-item">
        <div class="trust-icon">
          <i class="fas fa-user-graduate"></i>
        </div>
        <h4>Graduate Approved</h4>
        <p>Loved by thousands of graduates nationwide with 98% satisfaction rate.</p>
      </div>
      <div class="trust-item">
        <div class="trust-icon">
          <i class="fas fa-headset"></i>
        </div>
        <h4>Dedicated Support</h4>
        <p>Our graduation experts are available 24/7 to answer all your questions.</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="testimonials">
  <div class="container">
    <div class="section-header">
      <h2>Graduate Stories</h2>
      <p>Hear from those who celebrated with GradÉlan</p>
    </div>
    <div class="testimonial-grid">
      <div class="testimonial-card">
        <div class="testimonial-content">
          <p>"The quality of my graduation gown was exceptional! I felt so confident walking across that stage."</p>
        </div>
        <div class="testimonial-author">
          <div>
            <h5>Sarah Liew</h5>
            <span>University of Malaya, 2025</span>
          </div>
        </div>
      </div>
      <div class="testimonial-card">
        <div class="testimonial-content">
          <p>"The customization options allowed me to add personal touches that made my graduation even more special."</p>
        </div>
        <div class="testimonial-author">
          <div>
            <h5>Michael Ng</h5>
            <span>University Sains Malaysia, 2025</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
  <div class="container">
    <div class="cta-content">
      <h2>Ready for Your Big Day?</h2>
      <p>Shop our collection today and receive 10% off your first order</p>
      <button onclick="location.href='product.php'" class="btn-primary btn">Shop Now</button>
    </div>
  </div>
</section>

<?php include 'foot.php'; ?>