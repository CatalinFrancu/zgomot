<!doctype html>
<html lang="ro">
  <head>
    <meta charset="UTF-8">
    <title>{block "title"}{/block}</title>
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
      {block "content"}{/block}
    </div>

  </body>
</html>
