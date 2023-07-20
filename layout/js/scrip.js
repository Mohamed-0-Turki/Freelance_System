console.log("TURKI");
// TODO: Start Script Button To Close The Message.
let itemMessage = document.querySelectorAll(".items-message");
let iconMessage = Array.from(document.querySelectorAll(".icon-mess-2"));
iconMessage.forEach((btn) => {
    btn.onclick = () => {
        let index = iconMessage.indexOf(btn);
        itemMessage[index].style.display = "none";
    }
});
// TODO: End Script Button To Close The Message.

// TODO: Start Validation Form Login.
function loginForm() {
    let inputFields = Array.from(document.querySelectorAll(".container-form-login-signup .items-container form .input-field")),
        inputs = Array.from(document.querySelectorAll(".container-form-login-signup .items-container form .input-field input")),
        submitBtn = document.querySelector(".container-form-login-signup .items-container form #submit-button");
    inputs.forEach(input => {
        input.addEventListener("keyup", () => {
            let index = inputs.indexOf(input);
            inputFields[index].style = input.value.length !== 0 ? "border: 2px solid rgb(0, 255, 0);" : "border: 2px solid red;";
        });
    });
    submitBtn.onclick = (event) => {
        inputs.forEach(input => {
            if (input.value.length === 0) {
                event.preventDefault();
            }
        });
    }
}
// TODO: End Validation Form Login.

function signupForm() {
    let inputFields = Array.from(document.querySelectorAll(".container-form-login-signup .items-container form .input-field")),
        inputs = Array.from(document.querySelectorAll(".container-form-login-signup .items-container form .input-field input")),
        passwordNots = document.querySelectorAll(".container-form-login-signup .items-container form .notes p"),
        submitBtn = document.querySelector(".container-form-login-signup .items-container form #submit-button"),
        radioInputs = Array.from(document.querySelectorAll(".container-form-login-signup .items-container form .check .radio-value")),
        phoneNumber = document.querySelector(".container-form-login-signup .items-container form #field-phoneNumber"),
        symbols = ("~!@#$%&*+=|?").split(""),
        symbolIndex = 0;

    inputFields.pop();
    inputs.pop();

    radioInputs.forEach(radioInput => {
        radioInput.addEventListener("click", () => {
            phoneNumber.style = radioInput.value === "Freelancer" ? "display: none;" : "dispaly: block;"
        })
    });

    inputs.forEach(input => {
        input.addEventListener("keyup", () => {
            let index = inputs.indexOf(input);

            if (input.getAttribute("id") === "password") {
                let password = input.value;
                passwordMethods(password);
                inputFields[index].style = password[0] !== undefined
                                        && password[0] === password[0].toUpperCase()
                                        && isNaN(password[0])
                                        && symbols.includes(password[symbolIndex])
                                        && password.length >= 8
                                        ? "border: 2px solid rgb(0, 255, 0);"
                                        : "border: 2px solid red;";
            }
            if (input.getAttribute("id") === "rePassword") {
                inputFields[index].style = input.value === inputs[index-1].value && input.value.length !== 0
                ? "border: 2px solid rgb(0, 255, 0);"
                : "border: 2px solid red;";
            }
            if (input.getAttribute("id") !== "password" && input.getAttribute("id") !== "rePassword") {
                inputFields[index].style = input.value.length !== 0 ? "border: 2px solid rgb(0, 255, 0);" : "border: 2px solid red;";
            }
        });
    });

    function passwordMethods(password) {
        if (symbols.includes(password[password.length-1])) {
            symbolIndex = password.length-1;
        }
        passwordNots[0].style =
                                password[0] === undefined
                                ? "color: RED;"
                                : password[0] === password[0].toUpperCase() && isNaN(password[0])
                                ?"color: rgb(0, 255, 0);" : "color: red;";
        passwordNots[1].style = symbols.includes(password[symbolIndex]) ? "color: rgb(0, 255, 0);" : "color: red;";
        passwordNots[2].style = password.length >= 8 ? "color: rgb(0, 255, 0);" : "color: red;";
    }

    submitBtn.onclick = (event) => {
        inputs.forEach(input => {
            if (input.value.length === 0) {
                event.preventDefault();
            }
        });
    }
}
// TODO: End Validation Form signup.
console.log("TURKI");
