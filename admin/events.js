(function(){
  "use strict";

  var App = function() {

    if(App.instance) {
      return App.instance;
    }



    App.instance = this;

    this.init();
    };

    window.App = App;


    App.prototype = {
      init: function() {

        this.getEvents();
        this.getPictures();
        this.listenEvents();
        this.fillBefore();
      },

      listenEvents: function() {

        document.addEventListener('click', function(e) {

          if(e.target.parentElement.parentElement) {
            if(e.target.parentElement.parentElement.className === "me_event") {
              App.instance.getEventEdit(e.target.parentElement.parentElement.dataset.id);
            }
          }

          if(e.target.parentElement) {
            if(e.target.parentElement.className === "thumbnail") {
              App.instance.fullPicture(e.target.dataset.id, e.target.src);
            }
          }

          if(e.target.className === "thumbnail") {
            console.log(e.target.dataset.id);
            console.log(e.target.dataset.source);
            App.instance.fullVideo(e.target.dataset.id, e.target.dataset.source, e.target.dataset.type);
          }

        });

        $("#edit_del").click(function() {
          var c = confirm("Kas oled kindel, et soovid kustutada?");
          if(c) {
            App.instance.deleteEvent($("#edit_id").val());
          }
        });

        $("#edit_save").click(function() {
          App.instance.updateEvent();
        });

        $("#add_event").click(function() {
          App.instance.addEvent();
        });

        $("#delete-picture").click(function() {
          App.instance.deletePicture($('#full-picture').attr("data-id"));
        });

        $("#delete-all-pictures").click(function() {
          var c = confirm("Kas oled kindel, et soovid kustutada kõik pildid?");

          if(c) {
            App.instance.deleteAllPictures();
          }
        });

        $("#fake_picture").click(function() {
          $("#click_picture").click();
        });

        $("#fake_upload").click(function() {
          $("#click_upload").click();
        });

        $("#click_picture").change(function() {
          var files = document.querySelector("#click_picture").files;
          var names = document.querySelector("#file-list");
          names.innerHTML = "";

          for (var i = 0; i < files.length; i++) {
            names.innerHTML += files[i].name;
            if(i !== files.length - 1) {
              names.innerHTML += ", ";
            }
          }
        });

      },

      fullVideo: function(id, src, type) {
        $('#picturemodal').modal('show');
        $("#full-video").css("display", "block");
        $("#full-video").attr("src", src);
        $("#full-video").attr("type", type);
        $("#full-picture").attr("src", "");
        $("#full-picture").attr("data-id", "");
      },

      fullPicture: function(id, imgsrc) {
        $('#picturemodal').modal('show');
        $("#full-video").css("display", "none");
        $("#full-picture").attr("src", imgsrc);
        $("#full-picture").attr("data-id", id);
      },

      getPictures: function() {
        $.ajax({
            url: '/telekas/inc/ajax.php?allpictures=1',
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(result){
                console.log(result);
                App.instance.buildPictures(result);
            },
            error: function(result){
                console.log(result);
            }
        });
      },

      buildPictures: function(data) {
        var parent = document.querySelector("#picture-list");

        parent.innerHTML = "";

        for(var i = 0; i < data.length; i++) {

          var div = document.createElement("div");
          div.className = "col-xs-6";
          parent.appendChild(div);

          if(data[i].isVideo === "true") {

            var video = document.createElement("video");
            video.className = "thumbnail";
            video.style.width = "100%";
            video.style.height = "auto";
            video.dataset.id = data[i].id;
            video.dataset.source = data[i].url;
            div.appendChild(video);

            var source = document.createElement("source");
            source.src = data[i].url + "#t=0.5";
            source.type = "video/" + data[i].type;
            video.appendChild(source);

          } else {

            var a = document.createElement("a");
            a.className = "thumbnail";
            div.appendChild(a);

            var img = document.createElement("img");
            img.dataset.id = data[i].id;
            img.src = data[i].url;
            a.appendChild(img);

            /*

            <video width="320" height="240" controls>
              <source src="video.mp4#t=0.1" type="video/mp4">
            </video>

            */

          }


        }

        /*

        <div class="col-xs-6">
          <a class="thumbnail">
            <img data-id="<?=$img->id;?>" src="<?=$img->url;?>">
          </a>
        </div>

        */
      },

      deletePicture: function(id) {
        $.ajax({
            url: '/telekas/inc/ajax.php?deletepicture=' + id,
            type: 'get',
            success: function(result){
                console.log(result);
                $('#picturemodal').modal('hide');
                App.instance.getPictures();
            },
            error: function(result){
                console.log(result);
            }
        });
      },

      deleteAllPictures: function() {
        $.ajax({
            url: '/telekas/inc/ajax.php?deleteallpictures=true',
            type: 'get',
            success: function(result){
                console.log(result);
                App.instance.getPictures();
            },
            error: function(result){
                console.log(result);
            }
        });
      },

      fillBefore: function() {
        $("#add_year").val(localStorage.getItem("year"));
        $("#add_month").val(localStorage.getItem("month"));

      },

      addEvent: function() {
        var name = $("#add_name").val();
        var place = $("#add_place").val();
        var start = $("#add_start").val();
        var end = $("#add_end").val();
        var classn = $("#add_class").val();
        var day = $("#add_day").val();
        var month = $("#add_month").val();
        var year = $("#add_year").val();

        localStorage.setItem("month", month);
        localStorage.setItem("year", year);


        $.ajax({
            url: '/telekas/inc/ajax.php?addevent=true&name=' + name + "&place=" + place + "&start=" + start + "&end=" + end + "&class=" + classn + "&day=" + day + "&month=" + month + "&year=" + year,
            type: 'get',
            success: function(result){
                console.log(result);

                name.val("");
                place.val("");
                start.val("");
                end.val("");
                classn.val("");
                day.val("");

            },
            error: function(result){
                console.log(result);
            }
        });
      },

      updateEvent: function() {
        var id = $("#edit_id").val();
        var name = $("#edit_name").val();
        var place = $("#edit_place").val();
        var start = $("#edit_start").val();
        var end = $("#edit_end").val();
        var classn = $("#edit_class").val();
        var day = $("#edit_day").val();
        var month = $("#edit_month").val();
        var year = $("#edit_year").val();

        $.ajax({
            url: '/telekas/inc/ajax.php?updateevent=' + id + "&name=" + name + "&place=" + place + "&start=" + start + "&end=" + end + "&class=" + classn + "&day=" + day + "&month=" + month + "&year=" + year,
            type: 'get',
            success: function(result){
                console.log(result);
                $('#editmodal').modal('hide');
                App.instance.getEvents();

            },
            error: function(result){
                console.log(result);
            }
        });
      },

      deleteEvent: function(id) {
        $.ajax({
            url: '/telekas/inc/ajax.php?deleteevent=' + id,
            type: 'get',
            success: function(result){
                console.log(result);
                $('#editmodal').modal('hide');
                App.instance.getEvents();
            },
            error: function(result){
                console.log(result);
            }
        });
      },

      getEventEdit: function(id) {
        $.ajax({
            url: '/telekas/inc/ajax.php?eventdata=' + id,
            type: 'get',
            dataType: 'json',
            success: function(result){
                console.log(result);
                App.instance.fillModal(result);
            },
            error: function(result){
                console.log(result);
            }
        });
      },

      getEvents: function() {
        $.ajax({
            url: '/telekas/inc/ajax.php?allevents=1',
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(result){
                console.log(result);
                App.instance.buildEvents(result);
            },
            error: function(result){
                console.log(result);
            }
        });

      },

      fillModal: function(data) {
        var id = $("#edit_id").val(data.id);
        var name = $("#edit_name").val(data.name);
        var place = $("#edit_place").val(data.place);
        var start = $("#edit_start").val(data.start);
        var end = $("#edit_end").val(data.end);
        var classn = $("#edit_class").val(data.class);
        var day = $("#edit_day").val(data.day);
        var month = $("#edit_month").val(data.month);
        var year = $("#edit_year").val(data.year);
      },

      buildEvents: function(data) {
        var el = document.querySelector("#event-data");
        var months = ["Jaanuar", "Veebruar", "Märts", "Aprill", "Mai", "Juuni", "Juuli", "August", "September", "Oktoober", "November", "Detsember"];
        el.innerHTML = "";

        for(var i = 0; i < data.length; i++) {

          var li1 = document.createElement("li");
          li1.dataset.id = data[i].id;
          li1.className = "me_event";
          li1.dataset.toggle = "modal";
          li1.dataset.target = "#editmodal";
          el.appendChild(li1);

          var time = document.createElement("time");
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

          var div = document.createElement("div");
          div.className = "info";
          li1.appendChild(div);

          var h2 = document.createElement("h2");
          h2.className = "title";
          h2.innerHTML = data[i].name;
          div.appendChild(h2);

          var p = document.createElement("p");
          p.className = "desc";
          p.innerHTML = data[i].place;
          div.appendChild(p);

          var ul = document.createElement("ul");
          div.appendChild(ul);

          var li2 = document.createElement("li");
          li2.style.width = "50%";
          li2.innerHTML = "<span class='fa fa-users'></span> " + data[i].class;
          ul.appendChild(li2);


          var li3 = document.createElement("li");
          li3.style.width = "50%";
          li3.innerHTML = "<span class='fa fa-clock-o'></span> " + data[i].start + " - " + data[i].end;
          ul.appendChild(li3);


        }


      }






    };








}) ();
