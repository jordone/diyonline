<?php
 $unique_step_id = 'stepid'.uniqid();
?>
<div id="{{ $unique_step_id }}" class="step-wrapper">
<div class="stepdef" @yield('step')>
	<div class="row">
	        <div class="col-lg-10 col-md-10 col-md-offset-1 col-xs-12 col-xs-offset-0">

	            <!--      Wizard container        -->
	            <div class="wizard-container2">

	                <div class="card2 wizard-card2 ct-wizard-red2" id="wizard2">

	                <!--        You can switch "ct-wizard-orange"  with one of the next bright colors: "ct-wizard-blue", "ct-wizard-green", "ct-wizard-orange", "ct-wizard-red"             -->
                        <div class="mywizardsteptext">
                        @if (isset($stepnav) && $stepnav)

                                @include('office.partial.steptext', ['navtitle' => @$__env->yieldContent('wizardtitle'), 'stepnav' => @$stepnav])
                                @else
								<h3 class="page-title page-downarrow" style="text-align:left;">&nbsp; &nbsp; @yield('wizardtitle')</h3>
                        @endif

                        </div>

	                        <div class="content wizard-content">

	                              <div class="row">
	                              	<div class="col-lg-12 col-xs-12">


                                        <div style="padding-left:10px;padding-right:10px;">
                                            @yield('wizardform_open')

                                            @yield('wizardcontent')

                                            @yield('wizardform_close')

                                        </div>

	                                  </div>
	                              </div>
							</div>

	                </div>
	            </div> <!-- wizard container -->
	        </div>
	</div><!-- end row -->
</div>
	<script type="text/javascript">
		setTimeout(function(){ updatephonenumbers('#{{ $unique_step_id }}'); }, 200);
	</script>
</div>
