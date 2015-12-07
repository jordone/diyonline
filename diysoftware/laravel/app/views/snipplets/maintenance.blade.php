<?php
/**
 * Created by PhpStorm.
 * User: bryan
 * Date: 12/1/2015
 * Time: 10:19 AM
 */?>
@if (defined('MAINTENANCE_ENABLED') && MAINTENANCE_ENABLED == 'yes')
    <div class="row-fluid" style="padding-bottom:0px;">
        <div class="col-sm-10 col-sm-offset-1" style="padding-bottom:0px;">
            <hr>

            <div class="alert btn-block alert-default" style="opacity:0.4;padding-bottom:0px;padding-top:0px;" onmouseover="jQuery(this).css('opacity',1)" onmouseout="jQuery(this).css('opacity',0.4);">
                <div class="row " style="padding-top:8px;padding-bottom:0px;">

                    <div class="col-sm-1 text-muted" style="font-size:40px;">!</div>
                    <div class="col-sm-11">
                        <p class="well well-sm text-muted" style="margin-bottom:0;">
                            <b>Maintenance</b> will start at {{ MAINTENANCE_START }} and will end at {{ MAINTENANCE_END }}
                        </p><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
