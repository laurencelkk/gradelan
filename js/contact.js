document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".gradelan-form").addEventListener("submit", function (e) {
        e.preventDefault(); 

        let name = document.getElementById("name").value;
        let email = document.getElementById("email").value;
        let subject = document.getElementById("subject").value;
        let message = document.getElementById("message").value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let isValidEmail = emailPattern.test(email);


        if (name === "" || email === "" || subject === "" || message === "") {
            alert("Please fill in all fields.");
            return;
        } else if (!isValidEmail) {
            alert("Please enter a valid email address.");
            return;
        } else if (message.length < 10) {
            alert("Message must be at least 10 characters long.");
            return;
        } else {
            alert("Thank you for your message! We will get back to you soon.");
            this.submit(); 
        }
    });
});