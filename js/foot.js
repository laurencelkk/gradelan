document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector(".newsletter-form")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      const email = document.getElementById("newsMail").value.trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const isValidEmail = emailPattern.test(email);

      if (email === "") {
        alert("Please enter your email address.");
        return;
      } else if (!isValidEmail) {
        alert("Please enter a valid email address.");
        return;
      } else {
        alert("Thank you for subscribing to our newsletter!");
        this.submit();
      }
    });

  const year = new Date().getFullYear();
  document.getElementById("copy").innerHTML = `&copy; ${year} GradÉlan. All rights reserved.`;
});
