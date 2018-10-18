<?php

$config = include('config.php');

$postData = array(
  'key' => md5($config['key'])
);

$ch = curl_init($config['url']);
curl_setopt($ch, CURLOPT_POST, 1);
#curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

$responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

curl_close($ch);

?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="shortcut icon" type="image/x-icon" href="http://gnowbros.com/favicon.ico">
  <link rel="stylesheet" type="text/css" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
</head>
<body>
  <?php
    if ($responseCode != 200) echo $response;
    else {
      $client = json_decode($response);
      var_dump($client);
      $numServers = count($client->{'guilds'});
    }
  ?>

  <div id="background"></div>

  <header>
    <h1>Welcome to the Zero Two (002) Discord Bot home page!</h1>
  </header>

  <div class="tree">
    <div class="image-adjust">
      <img src="https://cdn.discordapp.com/avatars/456124032866320393/67b2fb0201da7de661be0d7166ecc2fa.png?size=2048" alt="Zero Two Bot Avatar" />
    </div>
    <ul>
      <li><a href="#features" class="pageLink">Features</a></li>
      <li><a href="#commands" class="pageLink">Commands</a></li>
      <li><a href="#invite" class="pageLink">Invite</a></li>
      <li><a href="#source" class="pageLink">Source</a></li>
    </ul>
  </div>
  <div class="container">
    <div class="view">
      <div id="features">
        <ul>
          <li>Serving on <span class="numServers">
            <?php
            echo $numServers;
            ?>
          </span> server(s)!</li>
          <li>AniList API integration for searching anime</li>
          <li>ChatBot - just use <strong>@ZeroTwo#5534</strong></li>
          <li>Music Streaming via YouTube</li>
          <li>League API (WIP)</li>
        </ul>
      </div>

      <div id="commands">
        <ul class="categories">
          <li>
            <h2 class="command-category">Anime</h2>
          </li>
          <li>
            <h2 class="command-category">General</h2>
          </li>
          <li>
            <h2 class="command-category">Voice</h2>
          </li>
        </ul>
      </div>

      <div id="invite">
        <h2>Invite link: <a href="https://inviteLink">link</a></h2>
      </div>

      <div id="source">
        <h2>View on GitHub: <a href="https://github.com/pinnouse/ZeroTwoBot">Source</a></h2>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(() => {
      //On ready handler
      let numServers = parseInt($("span.numServers").html()); //Get the total server count and store the value
      let duration = 1800; //Animation time in ms

      let updateInterval = (duration > numServers) ? duration / numServers : 17

      $("span.numServers").html("0");
      let i = 0; //Indexing
      var serverCounter = setInterval(() => {
        i += (updateInterval > 17) ? 1 : 17;
        if (duration >= numServers && i < numServers)
          $("span.numServers").html(i);
        else if (duration < numServers && i < duration)
          $("span.numServers").html(Math.ceil(i / duration * numServers));
        else {
          $("span.numServers").html(numServers);
          clearInterval(serverCounter);
        }
      }, updateInterval);

      $('a.pageLink').on("click", event => {
        event.preventDefault();

        if (!$(event.currentTarget).hasClass("active")) {
          $('a.pageLink').not($(event.currentTarget)).removeClass("active")
          $(event.currentTarget).addClass("active")

          var hash = $(event.target).prop("hash");
          var target = $(hash);

          $("[id]").not(hash).removeClass("active");
          target.addClass("active");

          if (hash == "#commands" || hash == "#features") {
            $(hash+">ul>li").each((ind, elem) => {
              let trans = "400ms";
              $(elem).css({
                transition: "0ms",
                left: "50vw",
                padding: "0 18px"
              });
              $(elem).stop().delay(60*ind).animate({
                left: 0,
                padding: "18px"
              }, 650, "easeOutExpo", () => {
                $(elem).css("transition", trans);
              });
            });
          } else {
            if (hash != "#commands")
              $("#commands>.categories>li").removeAttr("style");
            if (hash != "#features")
              $("#features>.categories>li").removeAttr("style");
          }
        }
      });

      $('a[href="#features"]').click();
    });
  </script>
</body>
</html>