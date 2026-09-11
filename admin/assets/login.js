//SHOW HIDDEN PASSWORD

// Select the password input and the eye icon
// Add a click event to the eye icon
// On click — check if input type is password
// If yes → change it to text and swap icon to eye-off
// If no → change it back to password and swap icon back to eye

const password = document.getElementById('password');
const eyeBtn = document.getElementById('togglePassword');

eyeBtn.addEventListener('click', () => {
    if (password.type == "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
});

//Empty Field Validation

// Add a click event to the login button
// Prevent the form from submitting normally
// Check if email field value is empty
// If empty → add red border to email input + show "Email is required" below it
// Check if password field value is empty
// If empty → add red border to password input + show "Password is required" below it
// Add input event on each field → when user starts typing, remove the red border and error message

const login = document.querySelector('.lgnBtn');
const email = document.getElementById('email');


const emailErr = document.getElementById('emailErr');
const passwordErr = document.getElementById('passwordErr');

//LOGIN ERROR WHEN EMAIL FIELD OR PASSWORD FIELD ARE EMPTY
login.addEventListener('click', async function (e) {
    e.preventDefault();
    let loginSuccess = true;
    if (email.value === "") {
        emailErr.textContent = "Email Field is Required";
        email.style.border = "1.5px solid red";
        loginSuccess = false;
    }

    if (password.value === "") {
      password.type = "password";
      passwordErr.textContent = "Password Field is Required";
      password.style.border = "1.5px solid red";
        loginSuccess = false;  
    }

    const btnText = document.querySelector(".btnText");
    const btnSpinner = document.querySelector(".btnSpinner");
    const errMsg = document.getElementById("mainErr");
    if (loginSuccess) {
        try {
          btnSpinner.style.display = "inline-block";
          btnText.style.display = "none";
          const form = document.getElementById("logInForm");

          const formData = new FormData(form);

          const loginResponse = await fetch("controllers/login_process.php", {
            method: "POST",
            body: formData,
          });

          //converting response from json to javascript object
          const jsonConverted = await loginResponse.json();

          if (jsonConverted.status == "success") {
            window.location.href = "views/dashboard.php";
          } else {
            errMsg.textContent = jsonConverted.message;
            btnSpinner.style.display = "none";
            btnText.style.display = "inline-block";
          }
        } catch (e) {
          errMsg.textContent = "Login Failed";
          btnSpinner.style.display = "none";
          btnText.style.display = "inline-block";
        }

    }
});

//CHANGING BORDER AND ERROR MESSAGE WHEN TYPING INTO LOGIN FORM
email.addEventListener('input', () => {
    emailErr.textContent = '';

    //CHECKING CORRECT EMAIL FORMAT
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        login.disabled = true;
        emailErr.textContent = "Wrong Email Format";
        email.style.border = "1.5px solid red !important";
    } else {
        login.disabled = false;
    }   
});

password.addEventListener('input', () => {
    password.style.border = "1.5px solid #E85D04";
    passwordErr.textContent = "";
});

