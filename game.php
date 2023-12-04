<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Guessing game</title>
    <link rel="stylesheet" href="./style/style.css" />
    <script > 
  const auth = () => {
  const authenticatedUser = "echo <?php echo $_SESSION["username"] ?>;";
  console.log(authenticatedUser);
  if (!authenticatedUser) {
    // window.location.href = 'login.html';
  } else {
    document.getElementById(
      'authName'
    ).innerText = `🗄 hi : ${authenticatedUser}`;
    updateHistory();
    clearHistoryBtn.className =
      gameHistory.filter(h => h.user == authenticatedUser).length > 0
        ? 'btn mx-md'
        : 'd-none';
    constractor();
  }
};</script>
  </head>

  <body onload="auth()">
    <div class="container">
      <form id="game">
        <h1 class="text-center fontsize-xl my-md">Guessing game</h1>
        <p class="text-center fontsize-lg my-md">
          Pick a number between 1 and 100
        </p>
        <p id="avatar" class="text-center my-md"></p>

        <p id="state" class="text-center my-md"></p>
        <div class="flex justify-content-center gap">
          <button class="btn mx-md" type="reset" onClick="constractor()">
            ♻️
          </button>
          <button class="btn mx-md" type="button" onclick="showResult()">
            🔍
          </button>
          <button class="btn mx-md" type="button" onclick="window.close();">
            👋
          </button>
        </div>

        <div class="flex column aligne-item-center my-md borderRaduis-md">
          <label for="guess">Guess</label>
          <input class="input my-md pointer" placeholder="Guess" id="guess" />
          <p id="guess-helper" class="fontsize-sm redColor d-none">
            Enter a number
          </p>
          <label for="support">Support</label>
          <input
            class="input my-md"
            id="support"
            disabled
            placeholder="Support"
          />
          <label for="tryNumber">Try</label>
          <input
            class="input my-md"
            id="tryNumber"
            disabled
            placeholder="Try"
          />
          <label for="result">Result</label>
          <input
            class="input my-md"
            id="result"
            disabled
            placeholder="Result"
          />
          <button class="btn mx-md fontsize-xl" type="submit">! Guess !</button>
        </div>

        <hr class="my-md" />
        <h1 class="text-center my-md" id="authName"></h1>
        <div class="flex justify-content-center">
          <button class="btn mx-md" type="button" onclick="switchAccount()">
            🔐
          </button>
          <button
            class="btn mx-md"
            id="clearHistoryBtn"
            type="button"
            onclick="clearHistory()"
          >
            🗑
          </button>
        </div>

        <div id="history"></div>
      </form>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        console.log(`<?php echo $_SESSION["username"] ?>`)
  auth();
});

var randomNumber;
var guessHelper = document.getElementById('guess-helper');
var support = document.getElementById('support');
var result = document.getElementById('result');
var game = document.getElementById('game');
var tryNumber = 0;
var htmlTryNumber = document.getElementById('tryNumber');
var historyHtml = document.getElementById('history');
var state = document.getElementById('state');
var avatar = document.getElementById('avatar');
var body = document.getElementsByTagName('body')[0];
var localUser ="<?php echo $_SESSION["username"] ?>";
console.log(localUser)
var gameHistory = localStorage.getItem('gameHistory')
  ? JSON.parse(localStorage.getItem('gameHistory'))
  : [];
var clearHistoryBtn = document.getElementById('clearHistoryBtn');
var autherName = document.getElementById('authName');
game.addEventListener('submit', e => {
  e.preventDefault();
  verifyResult();
});

const getRandomNumber = () => parseInt(Math.random() * 100);

const getLocalStorageItem = (key, defaultValue = '') => {
  const item = localStorage.getItem(key);
  return item ? item : defaultValue;
};

const setLocalStorageItem = (key, value) => localStorage.setItem(key, value);



const constractor = () => {
  tryNumber = 0;
  htmlTryNumber.value = '';
  result.value = '';
  support.value = '';
  guessHelper.className = 'd-none';
  randomNumber = getRandomNumber();
  console.log('The target is generated');
  updateHistory();
};

const formatDate = date => {
  if (!date) return '';
  date = new Date(date);
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  const seconds = String(date.getSeconds()).padStart(2, '0');

  return `🕰 <br/>${year}-${month}-${day} <br/> ${hours}:${minutes}:${seconds}`;
};

const updateHistory = () => {
  gameHistory = JSON.parse(getLocalStorageItem('gameHistory', '[]'));
  let historyHtmlContent = '<ul>';
  gameHistory
    .filter(h => h.user == localUser)
    .sort((a, b) => a.tryNumber - b.tryNumber)
    .map(({ tryNumber = '', randomNumber = '', date = '', user = '' }) => {
      historyHtmlContent += `<li><span>Try number:<br/> ${tryNumber}</span>  <span>🎯 <br/>${randomNumber}</span>  <span> ${formatDate(
        date
      )}</span><span> 👤<br/> ${user}</span> </li>`;
    });
  historyHtmlContent += '</ul>';
  historyHtml.innerHTML = historyHtmlContent;
};

const clearHistory = () => {
  localStorage.removeItem('gameHistory');
  updateHistory();
};
const win = () => {
  console.log('You won');
  const gameHistory = localStorage.getItem('gameHistory')
    ? JSON.parse(localStorage.getItem('gameHistory'))
    : [];
  console.log(gameHistory);
  const gameResult = {
    tryNumber: tryNumber,
    randomNumber: randomNumber,
    date: new Date(),
    user: localUser,
  };
  gameHistory.unshift(gameResult);
  localStorage.setItem('gameHistory', JSON.stringify(gameHistory));
  constractor();
  support.value = '';
  body.style.backgroundImage = 'url("../style/win.jpg")';
  result.value = 'You won';
};

const verifyResult = () => {
  if (!localUser) {
    alert('Please enter your name');
    auth();
  }
  body.style.backgroundImage = 'url("../style/bg.gif")';
  avatar.innerHTML = '';
  let guess = document.getElementById('guess').value;
  result.value = '';
  if (isNaN(guess)) {
    state.innerText = '🤦‍♂️🤦‍♀️';
    console.log('Please enter a number');
    guessHelper.className = 'fontsize-sm redColor ';
  } else {
    let guessInt = parseInt(guess);
    tryNumber++;
    if (!(guessInt > 100 || guessInt < 0)) {
      htmlTryNumber.value = 'Number of tries: ' + tryNumber;
    } else {
      state.innerText = '🤦';
    }

    guessHelper.className = 'd-none';

    if (guessInt > 100 || guessInt < 0) {
      state.innerText = '↔️';
      console.log('This number is out of range');
      support.value = 'This number is out of range';
    } else if (guessInt > randomNumber) {
      state.innerText = '⬇️';
      console.log('The number is too big');
      support.value = 'The number is too big';
    } else if (guessInt < randomNumber) {
      state.innerText = '⬆️';
      console.log('The number is too small');
      support.value = 'The number is too small';
    } else if (guessInt === randomNumber) {
      state.innerText = '✨';
      avatar.innerText = '🎉';

      win();
    }
    if (tryNumber >= 150) {
      avatar.innerHTML = '<img src="./style/wa7ech.gif" />';
      body.style.backgroundImage = 'url("../style/hell-v2.gif")';
      alert('Someone is call 911 please, we will need them soon :)');
    } else if (tryNumber >= 100) {
      body.style.backgroundImage = 'url("../style/hell-v1.gif")';
      avatar.innerHTML = `<img src="./style/tounsi-mti9er-tunisia.gif" alt="tounsi-mti9er-tunisia" width="80%" border="0"/>`;
    } else if (tryNumber > 30) {
      avatar.innerHTML = '🙏';
    } else if (tryNumber > 25) {
      avatar.innerHTML = '👻';
    } else if (tryNumber > 20) {
      avatar.innerHTML = '⚰️';
    } else if (tryNumber > 15) {
      avatar.innerText = '☠️';
    } else if (tryNumber > 10) {
      avatar.innerText = '⚠️';
    } else if (tryNumber > 8) {
      avatar.innerText = '🤯';
    } else if (tryNumber > 6) {
      avatar.innerText = '😱';
    } else if (tryNumber > 4) {
      avatar.innerHTML = '😨';
    }
  }
};

const showResult = () => {
  console.log('The number was ' + randomNumber);
  support.value = '';
  tryNumber = 0;
  htmlTryNumber.value = '';
  result.value = 'The number was ' + randomNumber;
};

const switchAccount = () => {
  console.log('switchAccount');
  localStorage.removeItem('user');
  user = null;
  auth();
};

document.addEventListener('DOMContentLoaded', () => {
  auth();
});

function authenticateUser(username) {
  fetch('/auth.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `username=${username}&password=example-password`,
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('authName').innerText = `🗄 hi : ${username}`;
        updateHistory();
        clearHistoryBtn.className =
          gameHistory.filter(h => h.user == username).length > 0
            ? 'btn mx-md'
            : 'd-none';
        constractor();
        localStorage.setItem('user', username);
      } else {
        alert('Authentication failed. Please try again.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

    </script>
  </body>
</html>
