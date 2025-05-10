<?php
$pageTitle = "Contact Us - GradÉlan";
$stylecss = "css/contact.css";
$script = "src='js/contact.js'";
include 'head.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['name'])) {
  // Collect and sanitize form input
  $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
  $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
  $subject = htmlspecialchars(trim($_POST['subject']), ENT_QUOTES, 'UTF-8');
  $message = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');  

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  } else {
    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    if ($subject === "product") {
      $subject = "Product Inquiry";
    } elseif ($subject === "order") {
      $subject = "Order Status Inquiry";
    } elseif ($subject === "custom") {
      $subject = "Custom Order Request";
    } elseif ($subject === "wholesale") {
      $subject = "Wholesale Inquiry";
    } elseif ($subject === "other") {
      $subject = "Other Question";
    } else {
      $subject = "General Inquiry";
    }

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
      $mail->addAddress('laurencelkk-pm23@student.tarc.edu.my', 'GradElan CEO'); 

      // Email content
      $mail->isHTML(true);
      $mail->Subject = 'Contact Us Form Submission: ' . $subject;
      $mail->Body    = "<p><strong>Name:</strong> $name</p>
                              <p><strong>Email:</strong> $email</p>
                              <p><strong>Subject:</strong> $subject</p>
                              <p><strong>Message:</strong><br>$message</p>";
      // Send the email
      $mail->send();
      redirect('contact.php?success');
    } catch (Exception $e) {
      echo "<script>console.log('Mail Error: " . addslashes($mail->ErrorInfo) . "');</script>";
    }
  }
}
?>

<section class="contact-hero">
  <div class="container">
    <h1>Get in Touch</h1>
    <p>We're here to help you celebrate your academic achievements with our premium graduation products</p>
  </div>
</section>

<section class="contact-container">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-form">
        <h2>Send Us a Message</h2>
        <p>Have questions about our products or need assistance with your order? Fill out the form below and we'll get back to you within 24 hours.</p>

        <form class="gradelan-form" method="post" enctype="application/x-www-form-urlencoded" novalidate>
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>

          <div class="form-group">
            <label for="subject">Subject</label>
            <select id="subject" name="subject" required> 
              <option value="">Select a subject</option>
              <option value="product">Product Inquiry</option>
              <option value="order">Order Status</option>
              <option value="custom">Custom Order Request</option>
              <option value="wholesale">Wholesale Inquiry</option>
              <option value="other">Other Question</option>
            </select>
          </div>

          <div class="form-group">
            <label for="message">Your Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Type your message here..." required></textarea>
          </div>

          <button type="submit" class="btn-primary">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>

      <div class="contact-info">
        <div class="info-card">
          <h3>Contact Information</h3>
          <p>Reach out to us through any of these channels:</p>

          <div class="contact-method">
            <i class="fas fa-regular fa-clock"></i>
            <div>
              <h4>Operation Hours</h4>
              <p>Monday - Friday: 9 AM - 6 PM (UTC +8)</p>
            </div>
          </div>

          <div class="contact-method">
            <i class="fas fa-map-marker-alt"></i>
            <div>
              <h4>Our Location</h4>
              <a href="https://maps.app.goo.gl/giBMrWDRHVivGLHX6">
                <p>77, Lorong Lembah Permai 3<br>11200 Tanjung Bungah, Pulau Pinang<br>Malaysia</p>
              </a>
            </div>
          </div>

          <div class="contact-method">
            <i class="fas fa-phone-alt"></i>
            <div>
              <h4>Phone</h4>
              <a href="tel:+60182724715">
                <p>+6018-272 4715</p>
              </a>
            </div>
          </div>

          <div class="contact-method">
            <i class="fas fa-envelope"></i>
            <div>
              <h4>Email</h4>
              <a href="mailto:support@gradelen.com">
                <p>support@gradelan.laurencelkk.my</p>
              </a>
            </div>
          </div>

          <div class="social-links">
            <h4>Follow Us</h4>
            <div class="social-icons">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="map-section">
      <h2>Visit Our Showroom</h2>
      <p>Schedule an appointment to see our graduation products in person</p>
      <div class="map-container">
        <!-- Embedded Google Map -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d967.3437755569365!2d100.28429133675424!3d5.453156323123965!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ac2c0305a5483%3A0xfeb1c7560c785259!2sTAR%20UMT%20Penang%20Branch!5e0!3m2!1sen!2smy!4v1746398226002!5m2!1sen!2smy" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</section>

<?php include 'foot.php'; ?>