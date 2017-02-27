{$m=floor($duration/60)}
{$s=$duration%60}

{if $m >= 2}
  {$m} minute
{elseif $m}
  1 minut
{/if}

{if $s}
  {$s} secunde
{/if}
