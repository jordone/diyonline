<div class="stepdef" @yield('step')>
	<div class="row">
	        <div class="col-lg-10 col-md-offset-1 col-xs-12 col-xs-offset-0">
	           
	            <!--      Wizard container        -->   
	            <div class="wizard-container2"> 
	
	                <div class="card2 wizard-card2 ct-wizard-red2" id="wizard2">
	                
	                <!--        You can switch "ct-wizard-orange"  with one of the next bright colors: "ct-wizard-blue", "ct-wizard-green", "ct-wizard-orange", "ct-wizard-red"             -->
	                
							<div class="mywizardsteptext">
								<h3 class="page-title page-downarrow">@yield('wizardtitle')</h3>
							</div>		
	                
	                        @yield('wizardform_open')
	                        
	                        <div class="content wizard-content">
	                            <div class="" id="step1">
	                              <div class="row">
	                              	<div class="col-lg-12 col-lg-offset-0 col-xs-offset-0 col-xs-12">
	                                  <div style="padding-left:15px;padding-right:15px;">
	                              	 	@yield('wizardcontent')
	                              	 	</div>
	                                  <br/>
	                                  </div>
	                              </div>
	                            </div>
							</div>
	                        
	                        <div class="wizard-footer" style="display:none">
	                            	<div class="pull-right">
	                            	    @yield('wizardform_submit')
										<noscript>
											  <button type="submit" class="btn btn-next btn-fill btn-danger btn-wd btn-sm" name="action_submit">NEXT</button>
										</noscript>
									   
										<input type="submit" style="display:none;" class="btn btn-next btn-fill btn-danger btn-wd btn-sm" name="action_submit" value="next">
	                                </div>
	                                <div class="clearfix"></div>
	                        </div>
	                        
	                        @yield('wizardform_close')
	                        
	                </div>
	            </div> <!-- wizard container -->
	        </div>
	</div><!-- end row -->
</div>