<?php require($_SERVER['DOCUMENT_ROOT'] . "/telekas/inc/functions.php"); ?>
<?php
if(!isset($_SESSION['user_session'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>INFOEKRAAN ADMIN</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.10/css/weather-icons.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>




    <div class="container-fluid">
      <div class="row">

        <?php if(isset($_SESSION['error_msg'])): ?>
          <div class="col-sm-12">
            <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-warning"></i> Tähelepanu!</h4>
              <?=$_SESSION['error_msg'];?>
              <?php
              if(count($error) > 0) {
                for($i = 0; $i < count($error); $i++) {
                  if($i == count($error) - 1) {
                    echo $error[$i];
                  } else {
                    echo $error[$i] . ", ";
                  }
                }
              }
              ?>
            </div>
          </div>
        <?php $_SESSION['msg_seen'] = true; endif; ?>

        <?php if(isset($_SESSION['success_msg'])): ?>
          <div class="col-sm-12">
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> Salvestatud!</h4>
              <?=$_SESSION['success_msg'];?>
            </div>
          </div>
        <?php $_SESSION['msg_seen'] = true; endif; ?>

        <div class="col-lg-4">



          <form>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Ürituse lisamine</h3>
              </div>
              <div class="panel-body">



                  <div class="col-sm-12">

                    <div class="form-group">
                      <label>Nimi</label>
                      <input class="form-control" id="add_name" placeholder="Lõbus vahetund" required>
                    </div>

                  </div>

                  <div class="col-sm-6">


                    <div class="form-group">
                      <label>Asukoht</label>
                      <input class="form-control" id="add_place" placeholder="Aula" required>
                    </div>

                    <div class="form-group">
                      <label>Klassid</label>
                      <input class="form-control" id="add_class" placeholder="1.-9. klass" required>
                    </div>

                  </div>

                  <div class="col-sm-6">

                    <div class="form-group">
                      <label>Alguskell</label>
                      <input class="form-control" id="add_start" placeholder="12:30" required>
                    </div>

                    <div class="form-group">
                      <label>Lõppkell</label>
                      <input class="form-control" id="add_end" placeholder="13:15" required>
                    </div>

                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Päev</label>
                      <input class="form-control" id="add_day" placeholder="3" required>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Kuu</label>
                      <select id="add_month" class="form-control">
                        <option value="1">Jaanuar</option>
                        <option value="2">Veebruar</option>
                        <option value="3">Märts</option>
                        <option value="4">Aprill</option>
                        <option value="5">Mai</option>
                        <option value="6">Juuni</option>
                        <option value="7">Juuli</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">Oktoober</option>
                        <option value="11">November</option>
                        <option value="12">Detsember</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Aasta</label>
                      <input class="form-control" id="add_year" placeholder="2017" required>
                    </div>
                  </div>


              </div>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-12">
                    <button id="add_event" class="btn btn-success pull-right">Lisa</button>
                  </div>
                </div>
              </div>
            </div>
          </form>


        </div>

        <div class="col-lg-4">

          <ul id="event-data" class="event-list">


  				</ul>

        </div>

        <div class="col-lg-4">

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Meedia haldamine</h3>
            </div>
            <div class="panel-body">
              <div class="btn-group btn-group-justified" role="group">
                <div class="btn-group">
                  <button id="fake_picture" class="btn btn-default">Vali meedia(d)</button>
                </div>
                <div class="btn-group">
                  <button id="fake_upload" class="btn btn-default">Lae üles</button>
                </div>
              </div>
              <input id="file-durations">
              <span id="file-list"></span>
              <br>
              <button id="delete-all-pictures" class="btn btn-danger btn-block">Kustuta kogu meedia</button>
              <form action="../inc/upload.php" method="post" enctype="multipart/form-data" style="display: none;">
                <input id="click_picture" type="file" name="my_files[]" accept=".png, .jpg, .jpeg, .mp4, .mpeg4, .wmv" multiple>
                <input id="click_upload" type="submit" value="lae" name="submit">
              </form>
            </div>
          </div>

          <div id="picture-list" class="row">



          </div>


        </div>



      </div>
    </div>

    <!-- Pildi Modal -->
    <div class="modal fade" id="picturemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="row">

              <video id="full-video" class="img-responsive" data-id="" style="display: none" controls>
                <source id="full-source" src="" type="">
              </video>

              <img id="full-picture" class="img-responsive" data-id="" src="">

            </div>
          </div>
          <div class="modal-footer">
            <button id="delete-picture" type="button" class="btn btn-danger pull-left">Kustuta</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Ürituse Modal -->
    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Ürituse muutmine</h4>
          </div>
          <div class="modal-body">

            <div class="row">
              <form>

                <input id="edit_id" type="hidden">

                <div class="col-sm-12">

                  <div class="form-group">
                    <label>Nimi</label>
                    <input class="form-control" id="edit_name" placeholder="Lõbus vahetund" required>
                  </div>

                </div>

                <div class="col-sm-6">


                  <div class="form-group">
                    <label>Asukoht</label>
                    <input class="form-control" id="edit_place" placeholder="Aula" required>
                  </div>

                  <div class="form-group">
                    <label>Alguskell</label>
                    <input class="form-control" id="edit_start" placeholder="12:30" required>
                  </div>

                </div>

                <div class="col-sm-6">

                  <div class="form-group">
                    <label>Klassid</label>
                    <input class="form-control" id="edit_class" placeholder="1.-9. klass" required>
                  </div>

                  <div class="form-group">
                    <label>Lõppkell</label>
                    <input class="form-control" id="edit_end" placeholder="13:15" required>
                  </div>

                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Päev</label>
                    <input class="form-control" id="edit_day" placeholder="3" required>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Kuu</label>
                    <select id="edit_month" class="form-control">
                      <option value="1">Jaanuar</option>
                      <option value="2">Veebruar</option>
                      <option value="3">Märts</option>
                      <option value="4">Aprill</option>
                      <option value="5">Mai</option>
                      <option value="6">Juuni</option>
                      <option value="7">Juuli</option>
                      <option value="8">August</option>
                      <option value="9">September</option>
                      <option value="10">Oktoober</option>
                      <option value="11">November</option>
                      <option value="12">Detsember</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Aasta</label>
                    <input class="form-control" id="edit_year" placeholder="2017" required>
                  </div>
                </div>


              </form>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" id="edit_del" class="btn btn-danger pull-left">Kustuta</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Sulge</button>
            <button type="button" id="edit_save" class="btn btn-primary">Salvesta</button>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../plugins/weather/jquery.simpleWeather.min.js"></script>
    <script type="text/javascript" src="events.js?<?=time();?>"></script>
    <script>

      $(document).ready(function() {

        var app = new App();

      });

    </script>


  </body>

  <?php
    unset($_SESSION['success_msg']);
    unset($_SESSION['error_msg']);
  ?>

  <?php
  if(isset($_SESSION['upload_id'])) {
    unset($_SESSION['upload_id']);
  }

  ?>

</html>
