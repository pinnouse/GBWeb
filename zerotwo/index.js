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
        <td>${item.name || "<i>none</i>"}</td>
        <td>${item.description || "<i>none</i>"}</td>
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