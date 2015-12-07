@extends('wizard')

@section('wizardtitle')
	Office Login
@stop
@section('step') data-step-number="login" @stop
@section('wizardcontent')

@section('wizardform_open')
	{{ Form::open(array('url' => URL::to('/office/loginform'), 'class' => 'form-horizontal')) }}
@stop

<div class="row"><div class="col-sm-12">
	@include('errors')
	</div>
</div>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3">

		<br/>

		<br/>
		<div class="panel panel-warning paypanel">
			<div class="panel-heading"><b>Office use login</b></div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="myofficepasswd">Password</label>
					<div class="col-sm-5"><input type="text" id="myofficepasswd" name="password" class="form-control"></div>
					<div class="col-sm-2"><button type="submit" data-loading-text="Logging in" id="login_opzb" class="btn btn-primary"><i class="fa fa-glass"></i> Login</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	@section('wizardform_close')
		{{ Form::close() }}
	@stop

@stop