{extends "layout.tpl"}

{block "title"}
  Who let the dogs out?
{/block}

{block "content"}
  <h3>Who let the dogs out?</h3>

  <form method="post">
    {foreach $durations as $i => $d}
      <div class="row voffset2">
        <button class="btn btn-block btn-default" name="durationIndex" value="{$i}">
          {include "bits/duration.tpl" duration=$d}
        </button>
      </div>
    {/foreach}
  </form>
{/block}
