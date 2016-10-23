<!doctype html>
<html lang="ro">
  <head>
    <meta charset="UTF-8">
    <title>Raport al lătraturilor</title>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.ro.min.js"></script>
    <script src="js/main.js"></script>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/css/bootstrap-slider.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" rel="stylesheet"></head>
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

          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-md-2 control-label">
                intervalul orar
              </label>

              <div class="col-md-10">
                <input id="hourSlider"
                       type="text"
                       class="form-control"
                       data-slider-value="[{$minHour}, {$maxHour}]"
                       >
                
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">
                datele
              </label>

              <div class="col-md-10 form-inline">
                <input type="text" id="startDate" class="form-control" value="{$startDate}">
                &mdash;
                <input type="text" id="endDate" class="form-control" value="{$endDate}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">
                zilele săptămânii
              </label>

              <div class="col-md-10">
                <div class="checkbox">
                  <label>
                    <input id="weekdayCheckbox" type="checkbox" {if $weekdays}checked{/if}>
                    luni-vineri
                  </label>

                  <label>
                    <input id="weekendCheckbox" type="checkbox" {if $weekends}checked{/if}>
                    sâmbătă-duminică
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">
                nivel de zgomot
              </label>

              <div class="col-md-10">
                <div class="checkbox">
                  <label>
                    <input id="ampHiCheckbox" type="checkbox" {if $ampHi}checked{/if}>
                    mare
                  </label>

                  <label>
                    <input id="ampMedCheckbox" type="checkbox" {if $ampMed}checked{/if}>
                    mediu
                  </label>
                </div>
              </div>
            </div>

          </form>
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
          <thead>
            <tr>
              <th>data</th>
              <th>durata (secunde)</th>
              <th>zgomot</th>
            </tr>
          </thead>
          <tbody>
            <tr id="stemRow">
              <td>
                <a href="#"
                   data-toggle="modal"
                   data-target="#audioModal">
                  <i class="glyphicon glyphicon-volume-up"></i>
                  <span class="date"></span>
                </a>
              </td>
              <td class="duration"></td>
              <td class="amplitude"></td>
            </tr>

            {foreach $data as $r}
              <tr>
                <td>
                  <a href="#"
                     data-toggle="modal"
                     data-target="#audioModal"
                     data-clip="{$r.file}"
                     data-date="{$r.date}">
                    <i class="glyphicon glyphicon-volume-up"></i>
                    {$r.date}
                  </a>
                </td>
                <td>{$r.duration}</td>
                <td>{$r.amplitude}</td>
              </tr>
            {/foreach}
          </tbody>

          <tfoot>
            <tr>
              <td colspan="3">
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
