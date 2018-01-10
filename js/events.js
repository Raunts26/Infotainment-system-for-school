(function(){
  "use strict";

  var App = function() {

    if(App.instance) {
      return App.instance;
    }

    this.bgdata = [{src:"images/lagedi.jpg"}];
    this.fakebgdata = [];
    this.realbgdata = [{src:"images/lagedi.jpg"}];

    App.instance = this;

    this.init();

    };

    window.App = App;


    App.prototype = {
      init: function() {
        this.getEvents();

        setInterval(function() {
          App.instance.getBackgrounds();
          //document.querySelectorAll(".vegas-slide")[1].style.filter = "blur(10px)";
          App.instance.getEvents();
        }, 6000); // ära unusta 30000 peale tagasi panna, 30 sek, kui keegi peaks kunagi muutma midagi - 30 sek on, et ei koormaks kooli veebilehe serverit üle

        //setInterval(function() {
          //console.log($("#slider").vegas('current'));
          //console.log($("#event-slider").vegas('current'));
        //}, 1000);


        this.startBackground();
      },

      getBackgrounds: function() {
        var videos = [];
        var durations = [];
        var images = [];
        $.ajax({
            url: '/telekas/inc/ajax.php?allpictures=1',
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(result){
                console.log(result);


                App.instance.fakebgdata = [];

                if(result.length > 0) {
                  for(var i = 0; i < result.length; i++) {
                    console.log(result[i]);

                    if(result[i].isVideo === "true") {
                      videos.push("telekas/" + result[i].url);
                      durations.push(result[i].duration);
                    } else {
                      images.push("telekas/" + result[i].url);
                    }




                  }
                  //App.instance.fakebgdata.push({src: images[j], video: {src: videos[i]}, delay: 30000});
                  var minus = 0;
                  for(var j = 0; j < images.length; j++) {
                    App.instance.fakebgdata.push({src: images[j]});
                  }

                  for(var k = 0; k < videos.length; k++) {
                    App.instance.fakebgdata.push({src: "images/lagedi.jpg", video: {src: videos[k]}, delay: (durations[k] * 1000)});
                  }

                } else {
                  App.instance.fakebgdata.push({src:"images/lagedi.jpg"});
                }
                App.instance.checkBackgrounds();



            },
            error: function(result){
                console.log(result);
            }
        });
      },

      checkBackgrounds: function() {
        console.log(this.bgdata);
        if(this.bgdata.length === this.fakebgdata.length) {
          for(var i = 0; i < this.bgdata.length; i++) {
            if(this.bgdata[i].src !== this.fakebgdata[i].src) {
              console.log(this.bgdata[i]);
              console.log(this.fakebgdata[i]);
              this.bgdata = this.fakebgdata;
              if($("#slider").vegas()) {
                $("#slider").vegas('destroy');
              }
              if($("#event-slider").vegas()) {
                $("#event-slider").vegas('destroy');
              }
              this.startBackground();
            }
          }
        } else {
          console.log("2");
          this.bgdata = this.fakebgdata;
          if($("#slider").vegas()) {
            $("#slider").vegas('destroy');
          }
          if($("#event-slider").vegas()) {
            $("#event-slider").vegas('destroy');
          }
          this.startBackground();
        }
      },

      startBackground: function() {

        if(this.bgdata.length === 0) {
          this.bgdata = [{src:"images/lagedi.jpg"}];
        }
        $("#slider").vegas({
          delay: 10000,
          timer: true,
          slides: App.instance.bgdata,
          animation: 'kenburns'
        });
        /*$("#event-slider").vegas({
          delay: 10000,
          timer: false,
          slides: App.instance.bgdata,
          animation: 'kenburns'
        });*/

        //here you have the control over the body of the iframe document
        //var iBody = $("#event-slider").contents().find("body");

        //here you have the control over any element (#myContent)
        //var myContent = iBody.find("#slider-me");

        //var iFrameDOM = $("iframe#event-slider").contents();
      	/*$("#event-slider").contents().find("body").vegas({
          delay: 10000,
          timer: false,
          slides: App.instance.bgdata,
          animation: 'kenburns'
        });*/
        //console.log(iFrameDOM.find("#slider"));




      },

      getEvents: function() {
        $.ajax({
            url: '/telekas/inc/ajax.php?allevents=1',
            type: 'get',
            dataType: 'json',
            success: function(result){
                console.log(result);
                App.instance.buildEvents(result);
            },
            error: function(result){
                console.log(result);
            }
        });

      },

      buildEvents: function(data) {
        var el = document.querySelector("#event-data");
        var months = ["Jaanuar", "Veebruar", "Märts", "Aprill", "Mai", "Juuni", "Juuli", "August", "September", "Oktoober", "November", "Detsember"];
        //var colors = ["#c52c66", "#c52c2c", "#a8c52c", "#45c52c", "#2cc5a7", "#2c80c5", "#2d2cc5", "#732cc5", "#de7a0f", "#f5c436"];
        el.innerHTML = "";

        for(var i = 0; i < data.length; i++) {
          var li1 = document.createElement("li");
          el.appendChild(li1);

          var time = document.createElement("time");
          if(i === 0) {
            time.style.background = data[i].color;
          } else {
            time.style.borderLeft = "10px solid " + data[i].color;
          }
          li1.appendChild(time);

          var span1 = document.createElement("span");
          span1.className = "day";
          span1.innerHTML = data[i].day;
          time.appendChild(span1);
          var span2 = document.createElement("span");
          span2.className = "month";
          span2.innerHTML = months[data[i].month - 1];
          time.appendChild(span2);
          var span3 = document.createElement("span");
          span3.className = "year";
          span3.innerHTML = data[i].year;
          time.appendChild(span3);
          var span4 = document.createElement("span");
          span4.className = "time";
          span4.innerHTML = "ALL DAY";
          time.appendChild(span4);

          var span5 = document.createElement("span");
          span5.className = "event-time";
          span5.innerHTML = data[i].start + " - " + data[i].end;
          time.appendChild(span5);


          var div = document.createElement("div");
          div.className = "info";
          li1.appendChild(div);

          var h2 = document.createElement("h2");
          h2.className = "title";
          h2.innerHTML = data[i].name;
          div.appendChild(h2);


          var ul = document.createElement("ul");
          div.appendChild(ul);

          var p = document.createElement("li");
          p.className = "desc";
          p.innerHTML = data[i].place;
          ul.appendChild(p);

          var li2 = document.createElement("li");
          li2.style.width = "50%";
          li2.innerHTML = data[i].class;
          ul.appendChild(li2);

        }


      }






    };








}) ();
