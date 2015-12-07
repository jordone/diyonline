<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/17/2015
 * Time: 2:32 AM
 * Uh stuff.. pass the bitwise for the steps that are loaded.
 */
?>

@if ( !($steps & step1 ) )
    @if (!isset($BrowserCSL) || !$BrowserCSL)
       <span class="label label-danger">S#1</span>
    @endif
@else
    <span class="label label-success">S#1</span>
@endif

@if ( !($steps & step2 ))
    @if (!isset($BrowserCSL) || !$BrowserCSL)
       <span class="label label-danger">S#2</span>
    @endif
@else
    <span class="label label-success">S#2</span>
@endif

@if ( !($steps & step3 ) )
    @if (!isset($BrowserCSL) || !$BrowserCSL)
        <span class="label label-danger">S#3</span>
    @endif
@else
    <span class="label label-success">S#3</span>
@endif
@if ( !($steps & step4 ) )
    @if (!isset($BrowserCSL) || !$BrowserCSL)
        <span class="label label-danger">S#4</span>
    @endif
@else
    <span class="label label-success">S#4</span>
@endif

@if ( !($steps & step5 ) )
    @if (!isset($BrowserCSL) || !$BrowserCSL)
        <span class="label label-danger">S#5</span>
    @endif
@else
    <span class="label label-success">S#5</span>
@endif

@if ( !($steps & step6 ) )
    @if (!isset($BrowserCSL) || !$BrowserCSL)
        <span class="label label-danger">S#6</span>
    @endif
@else
    <span class="label label-success">S#6</span>
@endif

@if ( !($steps & step7 ) )
    @if (!isset($BrowserCSL) || !$BrowserCSL)
        <span class="label label-danger">S#7</span>
    @endif
@else
    <span class="label label-success">S#7</span>
@endif
