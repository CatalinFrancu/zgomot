<!doctype html>
<html lang="ro">
  <head>
    <meta charset="UTF-8">
    <title>Raport al lătraturilor</title>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/bootstrap-slider.min.js"></script>
    <script src="js/main.js"></script>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/css/bootstrap-slider.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div style="display: none">
      <span id="minHour">{$minHour}</span>
      <span id="maxHour">{$maxHour}</span>
      <span id="pageSize">{$pageSize}</span>
    </div>

    <div class="container" role="main">

      <h3>Raport al lătraturilor</h3>

      <div class="panel panel-default">
        <div class="panel-heading">Opțiuni</div>
        <div class="panel-body">

          <div class="row">
            <div class="col-md-2">
              <label>intervalul orar</label>
            </div>

            <div class="col-md-10">
              <input id="hourSlider"
                     type="text"
                     class="span2"
                     data-slider-value="[{$minHour}, {$maxHour}]"
                     >
              
            </div>
          </div>

          <div class="voffset3"></div>

          <div class="row">
            <div class="col-md-2">
              <label>zilele săptămânii</label>
            </div>

            <div class="col-md-2">
              <label>
                <input id="weekdayCheckbox" type="checkbox" {if $weekdays}checked{/if}>
                luni-vineri
              </label>
            </div>

            <div class="col-md-8">
              <label>
                <input id="weekendCheckbox" type="checkbox" {if $weekends}checked{/if}>
                sâmbătă-duminică
              </label>
            </div>
          </div>
        </div>
      </div>

      <div id="audioModal"
           class="modal"
           tabindex="-1"
           role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Clip audio</h4>
            </div>

            <div class="modal-body text-center">
              <audio controls>
                <source src="#" type="audio/mpeg">
              </audio>
            </div>

          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">Rezultate (cele mai recente primele)</div>

        <table id="clipTable" class="table">
          <tbody>
            <tr id="stemRow">
              <td>
                <span></span>
                <a href="#"
                   data-toggle="modal"
                   data-target="#audioModal"
                   >ascultă</a>
              </td>
            </tr>
            {foreach $data as $r}
              <tr>
                <td>
                  <span>{$r.date}</span>
                  <a href="#"
                     data-toggle="modal"
                     data-target="#audioModal"
                     data-clip="{$r.file}"
                     data-date="{$r.date}"
                     >ascultă</a>
                </td>
              </tr>
            {/foreach}
          </tbody>

          <tfoot>
            <tr>
              <td>
                <button id="loadMoreButton" class="btn btn-primary">
                  încarcă încă {$pageSize}
                </button>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

  </body>
</html>
