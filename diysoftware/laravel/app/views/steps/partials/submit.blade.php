<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/21/2015
 * Time: 3:55 AM
 */

$unique_id = 'cb'.uniqid();
$unique_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

?>
<style>
    .form-group input[type="checkbox"] + label::before {
        color: #b40000 !important;
    }
</style>
<div class="form-group" style="padding-top: 15px;">
    <input style="color: {{ $unique_color }} !important;" type="checkbox" class="formsubmitter checkbox-inline" id="{{$unique_id}}" name="action_submit"><label for="{{$unique_id}}" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;" >Check the box if all of the information is correct &amp; continue to the next step.</label>
</div>
