<!DOCTYPE html>
<html lang="uz">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mister Food</title>
  <style>img[alt="www.000webhost.com"]{display:none}</style>
</head>
<body>
<h1>Hello!!!</h1>
<div id="theme"></div>
<script src="https://telegram.org/js/telegram-web-app.js?0"></script>
<!--suppress JSUnresolvedVariable-->
<script>
let App = window.Telegram.WebApp
App.MainButton.show()
document.querySelector("#theme").innerHTML = JSON.stringify(App.themeParams)
</script>
</body>
</html>