<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/18/2015
 * Time: 1:15 AM haha, rip.
 *
{{-- * I wrote this so that we can show more information and a cleaner bar. The one you've asked for looks like poop. * --}}
* to use this:  include('office.partial.steptext',['step_id' => $step_id, 'client' => $client, ''] );
 */
$steptest_step_id = uniqid('step');

 if (isset($step)) $navtitle = (is_int($step) ? "Step ".$step."" : $step);

?>
<nav class="navbar <?php echo isset($class) ? $class : '' ?>" style="margin:0px;">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">{{ $navtitle }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">{{ $__env->yieldContent('wizardtitle') }}</a>
        </div>

        @if (isset($stepnav) && is_array($stepnav))
        <div id="{{ $steptest_step_id ? $steptest_step_id : 0 }}">

                 </div><!--/.nav-collapse -->
        @endif

    </div><!--/.container-fluid -->
</nav>
