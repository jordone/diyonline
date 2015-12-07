<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/22/2015
 * Time: 1:05 PM
 */
if (isset($step) && $step && defined($step.'_DISPLAY_TEXT'))
{
   $step_text = constant($step.'_DISPLAY_TEXT');
}
else {
    $step_text = "";
}
?>
@if ($step_text)
    <div class="bs-callout bs-callout-info"><b>{{$step_text}}</b></div>
@endif

@if(defined('STEP_DISPLAY_TEXT') && STEP_DISPLAY_TEXT)
<h4 class="autolinktext displaytextonsteps">{{ STEP_DISPLAY_TEXT }}</h4>
@endif
