@if (OFFICE_LOGIN_STYLE == 'dropdown' && Session::get('auth_office_use') !== true)
<div class="col-sm-3 pull-right">
	<ul class="nav">   
	<li class="dropdown active">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-lock" style="font-size:25px;"></i> Office Use</a>
            <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px; width:420px;">
            	<div class="form-group">
              		<label  class="control-label col-sm-4">Office&nbsp;Password:</label>  
		       		<div class="col-sm-5"><input type="password" id="officepasswd" class="form-control"></div>
		       		<div class="col-sm-2"><button type="button" id="verify_office_pw" data-loading-text="Logging in" id="verify_office_pw" class="btn btn-primary">Login</button>
		       		</div>
		       	</div>
		       	<div class="text-muted">This is for office use only</div>     
            </div>
</ul>
</div>

@elseif (OFFICE_LOGIN_STYLE == 'dialog' && !Session::get('auth_office_use'))
<div class="col-sm-3 pull-right">
					<a href="#" onclick=" $('#office_login_window').modal('show');  return false" class="btn btn-success"><i class="glyphicon glyphicon-lock"></i> Office Use</a>
<!--		Login Form Model (It needs to be hidden -->

<!-- Modal -->
<div class="modal" id="office_login_window" tabindex="-1" role="dialog" aria-labelledby="office_login_window">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><small><?php echo date('l'); ?></small> &times;</span></button>
        <h4 class="modal-title text-left" id="myModalLabel">For Authorized Office Use</h4>
      </div>
      <div class="modal-body">
      <div class="container-fluid">
      	<div class="text-left"> 
      	<p class="muted">You'll need to enter your office password and press Login.</p>
      	</div>
      	<br>
 
       	<div class="row">
       		<div class="col-sm-3">Password</div>
		 	<div class="col-sm-5"><input type="password" id="officepasswd" class="form-control"></div>
		 	<div class="col-sm-2"><button type="button" id="verify_office_pw" data-loading-text="Logging in" id="verify_office_pw" class="btn btn-primary">Login</button>      		
       	</div>
		<br>
		<br>
		<br>

       	</div>
      </div>

    </div>
  </div>
</div>		
</div>		
					
					
@elseif (!Session::get('auth_office_use'))
<!--Show page -->
<div class="col-sm-3 pull-right">
					<a href="#" onclick="LoadStep('step7?np=login'); return false;" class="btn btn-success"><i class="glyphicon glyphicon-lock"></i> Office Use</a>
</div>					
@endif
 

<!--Style 1: dropdown-->
@if (OFFICE_LINK_STYLE == 'dropdown' && Session::get('auth_office_use'))
<div class="col-sm-3 pull-right">
<ul class="nav">
<!--          <li><a href="#">Office Use</a></li>-->
          <li class="divider-vertical"></li>
          <li class="dropdown active">          

<!--            They are logged in show them the links to this stuff yaaa-->
            <a id="officeusemenuxxx" class="dropdown-toggle" href="#" data-toggle="dropdown">Office Use <strong class="caret"></strong></a>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			  	<li class="dropdown-header">Offer Split Payments</li>
			  	@if (OFFICE_LINK_A_ENABLED == 'yes')
			    <li><a href="#" onclick="LoadStep('step7?np=2'); return false">{{ OFFICE_LINK_A_NAME }} </a></li>
			    @endif
			    
			    
			    @if (OFFICE_LINK_B_ENABLED == 'yes')
			    <li><a href="#" onclick="LoadStep('step7?np=3');return false;">{{ OFFICE_LINK_B_NAME }}</a></li>
				@endif

				@if (OFFICE_LINK_NOPAYMENT_ENALBED == 'yes')
			    <li><a href="#" onclick="LoadStep('step7?np=1');return false;">{{ OFFICE_LINK_NOPAYMENT_NAME }}</a></li>
			    @endif
			    
			    				
				@if ('no' == 'yes')
			    <li><a href="#" onclick="LoadStep('step7?np=1');return false;">{{ OFFICE_LINK_GENERATE_FORMS_NAME }}</a></li>
			    
			    @endif
			    
			    <li><a href="#" class="btn btn-warning" onclick="LoadStep('step7?np=logout');return false">Logout</a></li>
			    
			    
			  </ul>

          </li>
 </ul>
</div>

  @elseif (Session::get('auth_office_use'))
  
<ul class="nav nav-pills">
			  	<li class="dropdown-header">Offer Split Payments</li>
			  	@if (OFFICE_LINK_A_ENABLED == 'yes')
			    <li><a href="#" onclick="LoadStep('step7?np=2'); return false">{{ OFFICE_LINK_A_NAME }} </a></li>
			    @endif
			    
			    
			    @if (OFFICE_LINK_B_ENABLED == 'yes')
			    <li><a href="#" onclick="LoadStep('step7?np=3'); return false">{{ OFFICE_LINK_B_NAME }}</a></li>
				@endif

				@if (OFFICE_LINK_NOPAYMENT_ENALBED == 'yes')
			    <li><a href="#" onclick="LoadStep('step7?np=1');return false">{{ OFFICE_LINK_NOPAYMENT_NAME }}</a></li>
			    @endif
			     
			    <li><a href="#" class="btn btn-warning" onclick="LoadStep('step7?np=logout');return false;">Logout</a></li>
			    
			    
			  </ul>
<!--Default -->
  


  @endif

  <style>
.modal-dialog {
z-index:30000;
}
</style>
  
<script>

    jQuery('#officeusemenuxxx').click();

/** Monitor for Office password enter presses **/
jQuery('#officepasswd').keypress(function(e) {
	if(e.which == 13)  {
		e.preventDefault();
		e.stopPropagation();

		setTimeout(function(){ jQuery('#verify_office_pw').button('reset'); }, 2000);
		jQuery('#verify_office_pw').button('loading');

		LoadStep('office_login?p='+jQuery('#officepasswd').val());
		// post the password, and show them it's loading. Wonder how to do that.
		return false;
	} 
});

jQuery('#verify_office_pw').click(function(e) {
	e.stopPropagation();
	jQuery('#verify_office_pw').button('loading');

	setTimeout(function(){ jQuery('#verify_office_pw').button('reset'); }, 2000);

	LoadStep('office_login?p='+jQuery('#officepasswd').val());

});


/** Do not auto collapse drop downs if you're using an input field **/
$('.dropdown input, .dropdown label,.dropdown button').click(function(e) {
	e.stopPropagation();
});
$('.dropdown-menu input, .dropdown-menu label').click(function(e) {
	e.stopPropagation();
});
</script>
@if (Session::get('auth_office_pass'))
    	<input type="hidden" name="password" value="{{ Session::get('auth_office_pass') }}">
 @endif
@if (Input::get('np'))
    	<input type="hidden" name="np" value="{{ Input::get('np') }}">
  @endif
    	