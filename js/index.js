var randomNumber;
var guessHelper = document.getElementById("guess-helper");
var support = document.getElementById("support");
var result = document.getElementById("result");
var game = document.getElementById("game");
var tryNumber = 0;
var htmlTryNumber = document.getElementById("tryNumber");
var historyHtml = document.getElementById("history");

game.addEventListener("submit", e => {
  e.preventDefault();
  verifyResult();
  //This will prevent the form from reloading
  //Because you are preventing the default
});
const constractor = () => {
  tryNumber = 0;

  htmlTryNumber.value = "Number of tries: " + tryNumber;
  result.value = "";
  support.value = "";
  guessHelper.className = "d-none";
  randomNumber = parseInt(Math.random() * 100);
  console.log("The target is generated");
  updateHistory();
  return randomNumber;
};
const formatDate = date => {
  if (!date) return "";
  date = new Date(date);
  const year = date.getFullYear();

  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  const hours = String(date.getHours()).padStart(2, "0");
  const minutes = String(date.getMinutes()).padStart(2, "0");
  const seconds = String(date.getSeconds()).padStart(2, "0");

  return `date : ${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};
const updateHistory = () => {
  const gameHistory = localStorage.getItem("gameHistory")
    ? JSON.parse(localStorage.getItem("gameHistory"))
    : [];
  let historyHtmlContent = "<ul>";
  gameHistory.map(gameResult => {
    historyHtmlContent += `<li>Try number: ${
      gameResult.tryNumber
    } - Random number: ${gameResult.randomNumber} ${formatDate(
      gameResult.date
    )} </li>`;
  });
  historyHtmlContent += "</ul>";
  historyHtml.innerHTML = historyHtmlContent;
};
const clearHistory = () => {
  localStorage.removeItem("gameHistory");
  updateHistory();
};
const win = () => {
  console.log("You won");
  const gameHistory = localStorage.getItem("gameHistory")
    ? JSON.parse(localStorage.getItem("gameHistory"))
    : [];
  console.log(gameHistory);
  const gameResult = {
    tryNumber: tryNumber,
    randomNumber: randomNumber,
    date: new Date(),
  };
  gameHistory.unshift(gameResult);
  localStorage.setItem("gameHistory", JSON.stringify(gameHistory));
  constractor();
  support.value = "";
  result.value = "You won";
};

const verifyResult = () => {
  // get the value of the input guess in the form "game"
  let guess = document.getElementById("guess").value;
  result.value = "";
  //   result.value = "";
  if (isNaN(guess)) {
    console.log("Please enter a number");
    guessHelper.className = "fontsize-sm redColor ";
  } else {
    let guessInt = parseInt(guess);
    tryNumber++;
    if (!(guessInt > 100 || guessInt < 0)) {
      htmlTryNumber.value = "Number of tries: " + tryNumber;
    }
    guessHelper.className = "d-none";
    if (guessInt > 100 || guessInt < 0) {
      console.log("This number is out of range");
      support.value = "This number is out of range";
    } else if (guessInt > randomNumber) {
      console.log("The number is too big");
      support.value = "The number is too big";
    } else if (guessInt < randomNumber) {
      console.log("The number is too small");
      support.value = "The number is too small";
    } else if (guessInt === randomNumber) {
      win();
    }
  }
};

const showResult = () => {
  console.log("The number was " + randomNumber);
  support.value = "";
  result.value = "The number was " + randomNumber;
};
