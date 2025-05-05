// Modal Functions
function openModal() {
    $("#modalTitle").text("Add New Product");
    $("#productModal form")[0].reset();
    $(".mustMsg").hide();
    $(".pHolder").removeClass("error success");
    $(".hiddens").hide();
    $("#imgPreview").hide();
    $("#npAvailable").show();
    $("#productModal").show();
    $("#isEditing").val("0");
    $("#prodImg").attr('required', true);
    isEditing = false;
}

function closeModal() {
    $("#productModal").hide();
}

// Image Preview
function previewImage() {
    const file = $("#prodImg")[0].files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            $("#imgPreview").attr("src", e.target.result).show();
            $("#npAvailable").hide();
        };
        reader.readAsDataURL(file);
    } else if (!isEditing) {
        $("#imgPreview").hide();
        $("#npAvailable").show();
    }
}

// Form Field Validations
function validateName() {
    const name = $("#prodName").val().trim();
    const $error = $("#prodNameError");

    if (!name) {
        $error.text("Product name is required").show();
        return false;
    }
    if (name.length < 3) {
        $error.text("Must be at least 3 characters").show();
        return false;
    }
    $error.hide();
    return true;
}

function validateDescription() {
    const desc = $("#prodDesc").val().trim();
    const $error = $("#prodDescError");

    if (!desc) {
        $error.text("Description is required").show();
        return false;
    }
    if (desc.length < 10) {
        $error.text("Must be at least 10 characters").show();
        return false;
    }
    $error.hide();
    return true;
}

function validatePrice() {
    const price = $("#prodPrice").val().trim();
    const $error = $("#prodPriceError");

    if (!price) {
        $error.text("Price is required").show();
        return false;
    }
    if (price.includes("e")) {
        $error.text("Use numbers only (e.g. 10.99)").show();
        return false;
    }
    if (!/^\d+\.\d{2}$/.test(price)) {
        $error.text("Must have exactly 2 decimal places").show();
        return false;
    }
    if (parseFloat(price) <= 0) {
        $error.text("Must be greater than 0").show();
        return false;
    }
    $error.hide();
    return true;
}

function validateImage() {
    const file = $("#prodImg")[0].files[0];
    const $error = $("#prodImgError");

    if (!isEditing && !file) {
        $error.text("Image is required").show();
        return false;
    }

    if (file) {
        const validTypes = ["image/jpeg", "image/png", "image/webp"];

        if (!validTypes.includes(file.type)) {
            $error.text("Only JPG/PNG/WEBP allowed").show();
            return false;
        }
        if (file.size > 2 * 1024 * 1024) {
            $error.text("Max size 2MB").show();
            return false;
        }
    }
    $error.hide();
    return true;
}

// Real-time validation
$("#prodName").on("input", function () {
    $(this).toggleClass("error", !validateName())
        .toggleClass("success", validateName());
});

$("#prodDesc").on("input", function () {
    $(this).toggleClass("error", !validateDescription())
        .toggleClass("success", validateDescription());
});

$("#prodPrice").on("input", function () {
    $(this).toggleClass("error", !validatePrice())
        .toggleClass("success", validatePrice());
}).on("keypress", function (e) {
    if (e.key === "e" || e.key === "E") e.preventDefault();
});

$("#prodImg").on("change", function () {
    const isValid = validateImage();
    $(this).toggleClass("error", !isValid)
        .toggleClass("success", isValid);
    previewImage();
});

// Form Submission
$("form").submit(function (e) {
    e.preventDefault();

    const isNameValid = validateName();
    const isDescValid = validateDescription();
    const isPriceValid = validatePrice();
    const isImageValid = validateImage();

    // Update UI states
    $("#prodName").toggleClass("success", isNameValid);
    $("#prodDesc").toggleClass("success", isDescValid);
    $("#prodPrice").toggleClass("success", isPriceValid);
    $("#prodImg").toggleClass("success", isImageValid);

    if (isNameValid && isDescValid && isPriceValid && isImageValid) {
        this.submit();
    } else {
        // Scroll to first error
        const firstError = $(".mustMsg:visible").first();
        if (firstError.length) {
            $("html, body").animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }
    }
});

// Initialize the page
$(document).ready(function() {
    // Set up event listeners for search and filter
    $('#searchBtn').click(function() {
        const search = $('#searchInput').val();
        const sort = $('#sortSelect').val();
        window.location.href = `?search=${encodeURIComponent(search)}&sort=${sort}`;
    });

    $('#sortSelect').change(function() {
        const search = $('#searchInput').val();
        const sort = $(this).val();
        window.location.href = `?search=${encodeURIComponent(search)}&sort=${sort}`;
    });

    $('#searchInput').keypress(function(e) {
        if (e.which === 13) { // Enter key
            $('#searchBtn').click();
        }
    });

    // Close alert messages
    $('.close-alert').click(function() {
        $(this).parent().hide();
    });
});