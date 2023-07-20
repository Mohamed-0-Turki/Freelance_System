function writeWelcome() {
  let text = document.querySelector(".welcome-section .welcome-text h1"),
      arrayWord = text.innerHTML.split(""),
      index = 0;
  text.innerHTML = "";
  let write = setInterval(() => {
  if (index >= arrayWord.length) {
    clearInterval(write);
  } else {
    text.innerHTML = text.innerHTML + arrayWord[index];
    index++;
  }
  }, 150);
}


window.onload = () => {
  writeWelcome();
}