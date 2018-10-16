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
      echo '<h1>Currently serving: <span class="numServers">' . $numServers . "8765" . '</span> server(s)!</h1>';
    }
  ?>

  <script type="text/javascript">
    $(() => {
      //On ready handler
      let numServers = parseInt($("span.numServers").html()); //Get the total server count and store the value
      let duration = 800; //Animation time in ms

      let updateInterval = (duration > numServers) ? duration / numServers : 17

      $("span.numServers").html("0");
      console.log("update: " + numServers);
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

    });
  </script>
</body>
</html>