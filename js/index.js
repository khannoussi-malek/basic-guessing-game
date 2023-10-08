var randomNumber;
var guessHelper = document.getElementById("guess-helper");
var support = document.getElementById("support");
var result = document.getElementById("result");
var game = document.getElementById("game");
var tryNumber = 0;
var htmlTryNumber = document.getElementById("tryNumber");
var historyHtml = document.getElementById("history");
var state = document.getElementById("state");
var avatar = document.getElementById("avatar");
var body = document.getElementsByTagName("body")[0];
var gameHistory = localStorage.getItem("gameHistory")
  ? JSON.parse(localStorage.getItem("gameHistory"))
  : [];
var clearHistoryBtn = document.getElementById("clearHistoryBtn");
game.addEventListener("submit", e => {
  e.preventDefault();
  verifyResult();
});

var user = localStorage.getItem("user") ? localStorage.getItem("user") : "";
const auth = () => {
  var user = localStorage.getItem("user") ? localStorage.getItem("user") : "";
  if (!user) {
    user = prompt("Enter your name");
    localStorage.setItem("user", user);
  }
  console.log(user);
  updateHistory();
  clearHistoryBtn.className =
    gameHistory.filter(h => h.user == user).length > 0 ? "btn mx-md" : "d-none";
  constractor();
};
const constractor = () => {
  tryNumber = 0;
  htmlTryNumber.value = "";
  result.value = "";
  support.value = "";
  guessHelper.className = "d-none";
  randomNumber = parseInt(Math.random() * 100);
  console.log("The target is generated");
  updateHistory();
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

  return `🕰 <br/>${year}-${month}-${day} <br/> ${hours}:${minutes}:${seconds}`;
};

const updateHistory = () => {
  gameHistory = localStorage.getItem("gameHistory")
    ? JSON.parse(localStorage.getItem("gameHistory"))
    : [];
  let historyHtmlContent = "<ul>";
  gameHistory
    .filter(h => h.user == user)
    .map(({ tryNumber = "", randomNumber = "", date = "", user = "" }) => {
      historyHtmlContent += `<li><span>Try number:<br/> ${tryNumber}</span>  <span>🎯 <br/>${randomNumber}</span>  <span> ${formatDate(
        date
      )}</span><span> 👤<br/> ${user}</span> </li>`;
    });
  historyHtmlContent += "</ul>";
  historyHtml.innerHTML = "";
  historyHtml.innerHTML = historyHtmlContent;
  console.log({ historyHtml, gameHistory });
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
    user,
  };
  gameHistory.unshift(gameResult);
  localStorage.setItem("gameHistory", JSON.stringify(gameHistory));
  constractor();
  support.value = "";
  body.style.backgroundImage = 'url("../style/win.jpg")';
  result.value = "You won";
};

const verifyResult = () => {
  body.style.backgroundImage = 'url("../style/bg.gif")';
  avatar.innerHTML = "";
  let guess = document.getElementById("guess").value;
  result.value = "";
  if (isNaN(guess)) {
    state.innerText = "🤦‍♂️🤦‍♀️";
    console.log("Please enter a number");
    guessHelper.className = "fontsize-sm redColor ";
  } else {
    let guessInt = parseInt(guess);
    tryNumber++;
    if (!(guessInt > 100 || guessInt < 0)) {
      htmlTryNumber.value = "Number of tries: " + tryNumber;
    } else {
      state.innerText = "🤦";
    }

    guessHelper.className = "d-none";

    if (guessInt > 100 || guessInt < 0) {
      state.innerText = "↔️";
      console.log("This number is out of range");
      support.value = "This number is out of range";
    } else if (guessInt > randomNumber) {
      state.innerText = "⬇️";
      console.log("The number is too big");
      support.value = "The number is too big";
    } else if (guessInt < randomNumber) {
      state.innerText = "⬆️";
      console.log("The number is too small");
      support.value = "The number is too small";
    } else if (guessInt === randomNumber) {
      state.innerText = "✨";
      avatar.innerText = "🎉";

      win();
    }
    if (tryNumber >= 150) {
      avatar.innerHTML = '<img src="./style/wa7ech.gif" />';
      body.style.backgroundImage = 'url("../style/hell-v2.gif")';
      alert("Someone is call 911 please, we will need them soon :)");
    } else if (tryNumber >= 100) {
      body.style.backgroundImage = 'url("../style/hell-v1.gif")';
      avatar.innerHTML = `<img src="./style/tounsi-mti9er-tunisia.gif" alt="tounsi-mti9er-tunisia" width="80%" border="0"/>`;
    } else if (tryNumber > 30) {
      avatar.innerHTML = "🙏";
    } else if (tryNumber > 25) {
      avatar.innerHTML = "👻";
    } else if (tryNumber > 20) {
      avatar.innerHTML = "⚰️";
    } else if (tryNumber > 15) {
      avatar.innerText = "☠️";
    } else if (tryNumber > 10) {
      avatar.innerText = "⚠️";
    } else if (tryNumber > 8) {
      avatar.innerText = "🤯";
    } else if (tryNumber > 6) {
      avatar.innerText = "😱";
    } else if (tryNumber > 4) {
      avatar.innerHTML = "😨";
    }
  }
};

const showResult = () => {
  console.log("The number was " + randomNumber);
  support.value = "";
  tryNumber = 0;
  htmlTryNumber.value = "";
  result.value = "The number was " + randomNumber;
};

const switchAccount = () => {
  console.log("switchAccount");
  localStorage.removeItem("user");
  user = null;
  auth();
};
