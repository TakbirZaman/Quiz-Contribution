document.getElementById("regForm").addEventListener("submit", function (e) {
    e.preventDefault(); 
    let hasError = false;

    // Clear previous errors
    document.querySelectorAll('.error').forEach(element => {
        element.classList.remove('error');
    });

    document.querySelectorAll('.error-message').forEach(element => {
        element.innerText = '';
    });

    const name = document.getElementById("name").value.trim();
    if (/\d/.test(name)) {
        document.getElementById("name").classList.add('error');
        document.getElementById("nameError").innerText = "Name must not contain numbers.";
        hasError = true;
    }

    const email = document.getElementById("email").value.trim();
    const emailPattern = /^\d{2}-\d{5}-\d@student\.aiub\.edu$/;
    if (!emailPattern.test(email)) {
        document.getElementById("email").classList.add('error');
        document.getElementById("emailError").innerText = "Email must follow the format: XX-XXXXX-X@student.aiub.edu";
        hasError = true;
    }

    const password = document.getElementById("password").value;
    if (password.length < 6) {
        document.getElementById("password").classList.add('error');
        document.getElementById("passwordError").innerText = "Password must be at least 6 characters.";
        hasError = true;
    }

    const confirmPassword = document.getElementById("confirm_password").value;
    if (password !== confirmPassword) {
        document.getElementById("confirm_password").classList.add('error');
        document.getElementById("confirmPasswordError").innerText = "Passwords do not match.";
        hasError = true;
    }

    const dob = document.getElementById("dob").value;
    if (!dob) {
        document.getElementById("dob").classList.add('error');
        document.getElementById("dobError").innerText = "Date of birth is required.";
        hasError = true;
    } else {
        const birthDate = new Date(dob);
        const age = new Date().getFullYear() - birthDate.getFullYear();
        if (age < 18) {
            document.getElementById("dob").classList.add('error');
            document.getElementById("dobError").innerText = "You must be at least 18 years old.";
            hasError = true;
        }
    }

    const country = document.getElementById("country").value;
    if (!country) {
        document.getElementById("country").classList.add('error');
        document.getElementById("countryError").innerText = "Please select your country.";
        hasError = true;
    }

    const gender = document.querySelector('input[name="gender"]:checked');
    if (!gender) {
        document.querySelectorAll('input[name="gender"]').forEach(input => input.classList.add('error'));
        document.getElementById("genderError").innerText = "Please select a gender.";
        hasError = true;
    }

    const agree = document.getElementById("agreeCheckbox").checked;
    if (!agree) {
        document.getElementById("terms").classList.add('error');
        document.getElementById("termsError").innerText = "You must agree to the terms and conditions.";
        hasError = true;
    }

    if (hasError) {
        return;
    } else {
        // Show success message then submit after 2 seconds
        document.getElementById("successPopup").style.display = "block";
        setTimeout(() => {
            document.getElementById("regForm").submit(); // Assuming this is your form ID
        });
    }
}); // ✅ CLOSES the submit event listener function

// ✅ Now safe to have another event listener
document.getElementById('refreshGif').addEventListener('click', function() {
    var gif = document.getElementById('refreshGif');
    gif.src = "icon.gif"; 
    setTimeout(function() {
        gif.src = "icon.jpg";
    }, 2000);  
});
