

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


// TODO: End Validation Form Add New Member.
function addNewMember() {
    let inputFields = Array.from(document.querySelectorAll(".container-all-forms .items-container form .input-field")),
        inputs = Array.from(document.querySelectorAll(".container-all-forms .items-container form .input-field input")),
        passwordNots = document.querySelectorAll(".container-all-forms .items-container form .notes p"),
        submitBtn = document.querySelector(".container-all-forms .items-container form #submit-button"),
        radioInputs = Array.from(document.querySelectorAll(".container-all-forms .items-container form .check .radio-value")),
        phoneNumber = document.querySelector(".container-all-forms .items-container form #field-phoneNumber"),
        showPassword = document.querySelector(".container-all-forms .items-container form .input-field .icon-show-password"),
        symbols = ("~!@#$%&*+=|?").split(""),
        symbolIndex = 0;
    inputFields.pop();
    inputs.pop();
    showPassword.addEventListener("click", () => {
        inputs.forEach(input => {
            if (input.getAttribute("id") === "password") {
                if (input.getAttribute("type") === "password") {
                    input.setAttribute("type", "text");
                }
                else {
                    input.setAttribute("type", "password");
                }
            }
        });
    });
    radioInputs.forEach(radioInput => {
        radioInput.addEventListener("click", () => {
            phoneNumber.style = radioInput.value === "Client" || radioInput.value === "Admin" ? "display: none;" : "dispaly: block;"
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
// TODO: End Validation Form Add New Member.

// TODO: Start Validation Edit Form.
function editMember() {
    let inputFields = Array.from(document.querySelectorAll(".container-all-forms .items-container form .input-field")),
        inputs = Array.from(document.querySelectorAll(".container-all-forms .items-container form .input-field input")),
        showPassword = document.querySelector(".container-all-forms .items-container form .input-field .icon-show-password");

    inputFields.pop();
    inputs.splice(2, 1);
    inputs.pop();

    inputs.forEach(input => {
        input.addEventListener("keyup", () => {
            let index = inputs.indexOf(input);
            inputFields[index].style = input.value.length !== 0 ? "border: 2px solid rgb(0, 255, 0);" : "border: 2px solid red;";
        });
    });
    showPassword.addEventListener("click", () => {
        inputs.forEach(input => {
            if (input.getAttribute("id") === "password") {
                if (input.getAttribute("type") === "password") {
                    input.setAttribute("type", "text");
                }
                else {
                    input.setAttribute("type", "password");
                }
            }
        });
    });
}
// TODO: End Validation Edit Form.