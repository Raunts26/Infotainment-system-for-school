<?php require($_SERVER['DOCUMENT_ROOT'] . "/telekas/inc/functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>INFOEKRAAN</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.10/css/weather-icons.css" rel="stylesheet" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Catamaran" rel="stylesheet">

    <!-- Vegas background -->
    <link rel="stylesheet" href="plugins/vegas/vegas.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <!--<div id="event-slider">

    </div>-->

    <iframe src="https://codeloops.ee/telekas/hidden.php" id='event-slider'>
      <p>See brauser ei toeta iframe.</p>
    </iframe>


    <div class="container-fluid">
      <div class="row">

        <div class="col-lg-3 event-column">
            <!--<div class="event-header">
              <h4>Üritused</h4>
            </div>-->
            <div class="event-background">
              <div class="logo-align">
                <img src="images/logo.png">
              </div>

            <ul id="event-data" class="event-list">


    				</ul>

            <div id="world_stats" class="color-white">
              <div id="weather" class="pull-right"></div>

              <div id="clock_me">
                <h1 id="clock"></h1>
              </div>

            </div>

          </div>

        </div>

        <div id="slider" class="col-lg-9">

        </div>

        <!--<div class="col-lg-8">



        </div>-->

      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="plugins/weather/jquery.simpleWeather.min.js"></script>
    <script src="plugins/vegas/vegas.min.js"></script>
    <script type="text/javascript" src="js/events.js?<?=time();?>"></script>
    <script>

      $(document).ready(function() {

        $('#event-slider').load(function() {
          var app = new App();
          document.getElementById('event-slider').contentWindow.callNow();
        });


        getWeather(); //Get the initial weather.
        setInterval(getWeather, 600000); //Update the weather every 10 minutes.
        startTime();

      });

      function startTime() {
          var today = new Date();
          var h = today.getHours();
          var m = today.getMinutes();

          m = checkTime(m);

          document.getElementById('clock').innerHTML =
          h + ":" + m;
          var t = setTimeout(startTime, 1000);
      }
      function checkTime(i) {
          if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
          return i;
      }

      function getWeather() {
        $.simpleWeather({
          location: 'Lagedi, Estonia',
          woeid: '',
          unit: 'c',
          success: function(weather) {
            html = '<h2><i class="wi wi-yahoo-'+weather.code+'"></i> '+weather.temp+'&deg;'+weather.units.temp+'</h2>';


            $("#weather").html(html);
          },
          error: function(error) {
            $("#weather").html('<p>'+error+'</p>');
          }
        });
      }

    </script>


  </body>
</html>
