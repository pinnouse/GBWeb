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
  <title>Zero Two Bot</title>
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <link rel="stylesheet" type="text/css" href="zerotwo/style.css">
  <script src="zerotwo/index.js"></script>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans|Oxygen+Mono" rel="stylesheet">
</head>
<body>
  <?php
    if ($responseCode != 200) echo $response;
    else {
      $client = json_decode($response);
      $numServers = count($client->{'guilds'});
    }
  ?>

  <div id="background"></div>

  <div class="outer-container">
    <div class="tree">
      <div class="image-adjust">
        <img
          src=<?php
            echo "\"" . $client->{'avatar'} . "\""
          ?>
          alt="Zero Two Bot Avatar" />
      </div>
      <ul>
        <li><a href="#about" class="pageLink">About</a></li>
        <li><a href="#features" class="pageLink">Features</a></li>
        <li><a href="#commands" class="pageLink">Commands</a></li>
        <li><a href="#invite" class="pageLink">Invite</a></li>
        <li><a href="#source" class="pageLink">Source</a></li>
      </ul>
    </div>
    <div class="container">
      <div class="view">
        <div id="about">
          <h2>ねぇ ダーリン (Hey Darling)~</h2>
          <p>
            Hey there, I'm a lovely waifu Discord Bot based on ゼロツー (Zero Two) from ダーリン・イン・ザ・フランキス (Darling in the FranXX).
            Currently built and maintained by a certain intellectual individual.
            Operating on <span class="numServers"><?php echo $numServers; ?></span> servers.
            <br />
            <br />
            I was created with love and care.
          </p>
          <h6>&COPY; Nicholas Wong 2018. Rights of material used are attributed to their respective creators and owners.</h6>
        </div>
  
        <div id="features">
          <ul>
            <li>AniList API integration for searching anime</li>
            <li>ChatBot - just mention on a server using: <strong>@<?php
              echo $client->{'tag'}
            ?></strong></li>
            <li>Custom Prefixes</li>
            <li>Multi-Lingual (in development - only supports English for now)</li>
            <li>Music Streaming via YouTube</li>
            <li>NSFW - danbooru, rule 34, gelbooru, yandere, safebooru, etc.</li>
            <li>League of Legends (TBI - to be implemented)</li>
            <li>Osu! (TBI)</li>
          </ul>
        </div>
  
        <div id="commands">
          <ul class="categories">
          </ul>
        </div>
  
        <div id="invite">
          <h2>Invite link</h2>
          <p>
            Bring this amazing waifu to your server.
            <br />
            <strong><i>Note:</i></strong>
            Make sure you have 'Manage Server' permission to invite.
            <br />
            <br />
            <a href="https://discordapp.com/api/oauth2/authorize?client_id=456124032866320393&scope=bot" target="_blank">Link</a>
          </p>
        </div>
    
        <div id="source">
          <h2>View on GitHub</h2>
          <p>
            Have a little know-how with programming or some ideas you can bring to life?
            <br />
            Zero Two Bot (&TRADE;) is open source!
            <br />
            <br />
            <a href="https://github.com/pinnouse/ZeroTwoBot" target="_blank">Source</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="command-help">
    <a href="#"><span class="arrow"></span>Back</a>
    <h1>Name Commands</h1>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Permissions</th>
        <th>Usage</th>
        <th>Arguments</th>
      </tr>
    </table>
  </div>

  <script type="text/javascript">
    <?php
      echo 'var commands = new Map(' . json_encode($client->{'commands'}) . ');';
      echo 'commands["prefix"] = "' . $client->{'prefix'} . '";';
    ?>
  </script>
</body>
</html>