$(function() {
  var audio = null;
  var stemRow = null;
  var pagesLoaded = 1;

  function init() {
    $('#h1, #h2').change(adjustOtherHour);

    $('#d1, #d2').datepicker({
      autoclose: true,
      daysOfWeekHighlighted: '0,6',
      format: 'dd-mm-yyyy',
      language: 'ro',
      todayBtn: 'linked',
      todayHighlight: true,
    });

    stemRow = $('#stemRow').detach().removeAttr('id');

    audio = $('audio');
    $('#audioModal').on('show.bs.modal', modalShown);
    $('#audioModal').on('hide.bs.modal', modalHidden);

    $('#loadMoreButton').click(loadData);

    if (window.location.hash) {
      var matches = window.location.hash.match(/^#(\d+):(\d+):(\d):(\d)$/);
      if (matches) {
      }
    }
  }

  function adjustOtherHour() {
    var h1 = Number($('#h1').val());
    var h2 = Number($('#h2').val());
    if (h1 >= h2) {
      if ($(this).attr('id') == 'h1') {
        $('#h2').val(h1 + 1);
      } else {
        $('#h1').val(h2 - 1);
      }
    }
  }

  function loadData() {
    $.ajax({
      url: 'ajax/getPage.php',
      data: {
        h1: $('#h1').val(),
        h2: $('#h2').val(),
        d1: $('#d1').val(),
        d2: $('#d2').val(),
        day: $('#day').val(),
        amp: $('#amp').val(),
        page: pagesLoaded,
      },
    }).done(function(data) {

      $.each(data, function(k, v) {
        var r = stemRow.clone(true);
        r.find('.date').html(v.date);
        r.find('.duration').html(v.duration);
        r.find('.amplitude').html(v.amplitude);
        r.find('a').data('clip', v.file).data('date', v.date);
        $('#clipTable > tbody').append(r);
      });

      pagesLoaded++;

      if (data.length < 100) {
        $('#loadMoreButton').prop('disabled', true);
      }
    });
  }

  function modalShown(event) {
    var link = $(event.relatedTarget);
    var clip = link.data('clip');
    var date = link.data('date');

    audio.find('source').attr('src', clip);
    audio.get(0).load();
    audio.get(0).play();
    $('.modal-title').text(date);
  }

  function modalHidden() {
    audio.get(0).pause();
  }

  init();

});
