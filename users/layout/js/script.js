

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