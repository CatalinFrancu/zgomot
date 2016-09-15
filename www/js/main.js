$(function() {
  var audio = null;
  var stemRow = null;
  var timer = null;
  var minHour = null;
  var maxHour = null;
  var weekdays = null;
  var weekends = null;
  var pageSize = null;
  var pagesLoaded = 1;

  function init() {
    $('#hourSlider').slider({
      min: 0,
      max: 24,
      ticks: range(0, 24),
      ticks_labels: range(0, 24),
      tooltip: 'always',
      tooltip_split: true,
    }).on('change', optionsChanged);

    $('#weekdayCheckbox, #weekendCheckbox').change(optionsChanged);

    stemRow = $('#stemRow').detach().removeAttr('id');

    minHour = $('#minHour').text();
    maxHour = $('#maxHour').text();
    weekdays = $('#weekdayCheckbox').is(':checked');
    weekends = $('#weekendCheckbox').is(':checked');
    pageSize = $('#pageSize').text();

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

  function optionsChanged() {
    var parts = $('#hourSlider').val().split(',');
    var newMinHour = parts[0];
    var newMaxHour = parts[1];
    var newWeekdays = $('#weekdayCheckbox').is(':checked');
    var newWeekends = $('#weekendCheckbox').is(':checked');

    if ((newMinHour != minHour) ||
        (newMaxHour != maxHour) ||
        (newWeekdays != weekdays) ||
        (newWeekends != weekends)) {
      minHour = newMinHour;
      maxHour = newMaxHour;
      weekdays = newWeekdays;
      weekends = newWeekends;

      if (timer) {
        clearTimeout(timer);
      }
      timer = setTimeout(wipeAndReload, 1000);
    }
  }

  function wipeAndReload() {
    window.location.search = $.param({
      h1: minHour,
      h2: maxHour,
      wd: +weekdays,
      we: +weekends,
    });
  }

  function loadData() {
    // console.log('reloading data for ' + minHour + ', ' + maxHour + ', ' +
    //             weekdays + ', ' + weekends);
    $.ajax({
      url: 'ajax/getPage.php',
      data: {
        minHour: minHour,
        maxHour: maxHour,
        weekdays: weekdays,
        weekends: weekends,
        page: pagesLoaded,
      },
    }).done(function(data) {

      $.each(data, function(k, v) {
        var r = stemRow.clone(true);
        r.find('span').html(v.date);
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

  // inclusive range
  function range(x, y) {
    var r = [];
    for (var i = x; i <= y; i++) {
      r.push(i);
    }
    return r;
  }

  init();

});
