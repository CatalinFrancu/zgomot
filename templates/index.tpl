<!doctype html>
<html lang="ro">
  <head>
    <meta charset="UTF-8">
    <title>Raport al lătraturilor</title>
    {if $local}
      <script src="js/third-party/jquery-2.2.4.min.js"></script>
      <script src="js/third-party/bootstrap.min.js"></script>
      <script src="js/third-party/bootstrap-datepicker.min.js"></script>
      <script src="js/third-party/bootstrap-datepicker.ro.min.js"></script>
      <link href="css/third-party/bootstrap.min.css" rel="stylesheet">
      <link href="css/third-party/bootstrap-datepicker.min.css" rel="stylesheet"></head>
    {else}
      <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.ro.min.js"></script>
      <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" rel="stylesheet"></head>
    {/if}
    <script src="js/main.js"></script>
    <link href="css/main.css" rel="stylesheet" type="text/css">
  </head>
  <body>
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

              <div class="col-md-10 form-inline">

                <select id="h1" name="h1" class="form-control">
                  {for $h=0 to 23}
                    <option value="{$h}" {if $h == $h1}selected{/if}>
                      {$h|string_format:"%02d:00"}
                    </option>
                  {/for}
                </select>

                &mdash;

                <select id="h2" name="h2" class="form-control">
                  {for $h=1 to 24}
                    <option value="{$h}" {if $h == $h2}selected{/if}>
                      {$h|string_format:"%02d:00"}
                    </option>
                  {/for}
                </select>

              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">
                datele
              </label>

              <div class="col-md-10 form-inline">
                <input type="text" id="d1" name="d1" class="form-control"
                       value="{$d1}">
                &mdash;
                <input type="text" id="d2" name="d2" class="form-control"
                       value="{$d2}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">
                zilele săptămânii
              </label>

              <div class="col-md-10">
                <select id="day" name="day" class="form-control">
                  <option value="0" {if $day == 0}selected{/if}>
                    toate
                  </option>
                  <option value="1" {if $day == 1}selected{/if}>
                    luni-vineri
                  </option>
                  <option value="2" {if $day == 2}selected{/if}>
                    sâmbătă-duminică
                  </option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 control-label">
                nivel de zgomot
              </label>

              <div class="col-md-10">
                <select id="amp" name="amp" class="form-control">
                  <option value="0" {if $amp == 0}selected{/if}>
                    oricare
                  </option>
                  <option value="1" {if $amp == 1}selected{/if}>
                    mare
                  </option>
                  <option value="2" {if $amp == 2}selected{/if}>
                    mediu
                  </option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-primary">
                  <i class="glyphicon glyphicon-refresh"></i>
                  reafișează
                </button>
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
                <button id="loadMoreButton"
                        class="btn btn-primary"
                        {if count($data) < $pageSize}disabled{/if}
                        >
                  <i class="glyphicon glyphicon-plus"></i>
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
