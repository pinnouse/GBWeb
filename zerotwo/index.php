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
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="zerotwo/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <li>Music Streaming via YouTube</li>
            <li>League API (WIP)</li>
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

    //On ready handler
    $(document).ready(() => {

      Array.from(commands.keys()).forEach(cat => {
        $('#commands>ul.categories').append(
          `<li>
          <h2 class="command-category" data-category="${cat}">${cat.charAt(0).toUpperCase()+cat.slice(1)}</h2>
          </li>`);
      });

      let numServers = parseInt($("span.numServers").html()); //Get the total server count and store the value
      let duration = 1200; //Animation time in ms

      let updateInterval = (duration > numServers) ? duration / numServers : 17

      $("span.numServers").html("0");
      let i = 0; //Indexing
      var serverCounter = setInterval(() => {
        i += (updateInterval > 17) ? 1 : 17;
        
        let curServers = 0;
        if (duration >= numServers && i < numServers)
          curServers = i;
        else if (duration < numServers && i < duration)
          curServers = Math.ceil(i / duration * numServers);
        else {
          curServers = numServers;
          clearInterval(serverCounter);
        }

        let output = "";
        for(var j = 0; j < Math.floor(Math.log10(numServers)) - Math.floor(Math.log10(curServers)); j++)
          output += "&nbsp;";
          

        $("span.numServers").html(output + curServers);
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
            $('.view').removeClass('animate');
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
            $('.view').addClass('animate');
            if (hash != "#commands" || hash != "#features");
              $(hash + ">.categories>li").removeAttr("style");
          }
        }
      });

      $('a[href="#about"]').click();

      var table = $('.command-help>table');
      $('h2.command-category').on("click", event => {
        let cat = $(event.currentTarget).attr("data-category");
        $('.command-help>h1').html(`${cat.charAt(0).toUpperCase() + cat.slice(1)} Commands`);

        table.html(`<tr>${$('.command-help>table tr:first-child').html()}</tr>`);
        commands.get(cat).forEach(item => {
          let optArguments = "";
          if (item.optArgs && item.optArgs.length)
            optArguments = surround(item.optArgs, "&lbrack;", "&rbrack;","+");
          let reqArguments = "";
          if (item.reqArgs && item.reqArgs.length)
            reqArguments = surround(item.reqArgs, "&lang;", "&rang;","+");

          let usages = [];
          if (item.aliases && item.aliases.length)
            item.aliases.sort().forEach(alias => {
              usages.push(`
                <span data-tooltip="${commands['prefix']}${(item.superCmd && item.superCmd.length) ? item.superCmd[0]+" " : ""}${alias}` +
                ((optArguments !== "") ? " " + optArguments.split("+").join(" ") : "") +
                ((reqArguments !== "") ? " " + reqArguments.split("+").join(" ") : "") +
                `">${alias}</span>
              `);
            });
          table.append(`
          <tr>
            <td>${item.name}</td>
            <td>${item.description}</td>
            <td>${(item.permissions && item.permissions.length) ? surround(item.permissions.split("+"), "<span>", "</span>", " and ") : "<i>none</i>"}</td>
            <td>${(usages.length) ? surround(usages, "", "", " or ") : "<i>none..?</i>"}</td>
            <td>${(optArguments !== "" || reqArguments !== "") ?
              (optArguments !== "") ? surround(optArguments.split("+"), "<span data-tooltip=\"optional\">", "</span>"):"" +
              (reqArguments !== "") ? surround(reqArguments.split("+"), "<span data-tooltip=\"required\">", "</span>"):""
              : "<i>none</i>"}</td>
          </tr>`);
        });

        $('span[data-tooltip]').each((ind, elem) => {
          $(elem).append(`<div class="tooltip">${$(elem).data('tooltip').trim()}</div>`);
        });

        //Animate table
        $('.command-help').css({
          display: 'block',
          position: 'absolute'
          }).animate({
            top: '0vh'
        }, 460, 'easeOutCubic');

        $('.command-help>a').css('top', '-90px').delay(200).animate({
          top: '0'
        }, 360, 'easeOutCubic');
      });

      $('.command-help>a').on("click", event => {
        event.preventDefault();
        $('.command-help').css('position', 'fixed').animate({
          top: '100vh'
        }, 580, 'easeInQuart', () => {
          $('.command-help').removeAttr("style");
        });
        $('.command-help>a').animate({
          top: '-90px'
        }, 360, 'easeOutCubic');
      });
    });

    function surround(str, pre, suf, delim) {
      delim = delim || " ";
      return `${pre}${str.join(suf + delim + pre)}${suf}`;
    }
  </script>
</body>
</html>