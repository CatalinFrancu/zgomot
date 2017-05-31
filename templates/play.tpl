{extends "layout.tpl"}

{block "title"}
  Who let the dogs out?
{/block}

{block "content"}
  <h3>Who let the dogs out?</h3>

  <form method="post">
    {foreach $durations as $d}
      <div class="row voffset2">
        <button class="btn btn-block btn-default" name="duration" value="{$d}">
          {include "bits/duration.tpl" duration=$d}
        </button>
      </div>
    {/foreach}

    <div class="row voffset5">
      <button class="btn btn-block btn-danger" name="stop" value="1">
        <i class="glyphicon glyphicon-volume-off"></i>
        oprește
      </button>
    </div>
  </form>
{/block}
