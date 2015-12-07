@extends('wizard')
@section('wizardtitle')
Your Loans
@stop
@section('step') data-step-number="steploans5" @stop

@section('wizardcontent')

	<h4 class="info-text"></h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'steploansf', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
	
  	@include('errors')

  	<table class="table table-striped">
	  	<thead>
	  		<tr>
		  		<th>Loan</th>
		  		<th>Balance</th>
		  		<th>Status</th>
	  		</tr>
	  	</thead>
	  	<tbody>
	  	
	  	@foreach($LoanData->Loans as $Loan)
	  	<tr>
	  		<td>{{{ $Loan->Creditor->Name }}}</td>
	  		<td>${{{ $Loan->Creditor->Balance }}}</td>
	  		<td>
	  		@if ($Loan->AccountStatus == 'L')
	  		Good
	  		@else
	  			@if ($Loan->AccountStatus == 'E' || $Loan->AccountStatus == 'K')
	  			Consolidated
	  			
	  			@else
					{{{ $Loan->AccountStatus }}}
	  			@endif
	  			
	  		@endif
	  		</td>
	  	</tr>
	  	@endforeach
	  	</tbody>
	  
	  </table>
  	  
  	  
  
	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkboxshowloans5" name="checkboxshowloans5"><label for="checkboxshowloans5" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 6</label>
	  </div>
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	<script>

	var prevElem = jQuery('*[data-step-number="steploans5"]').ScrollTo().prev(":first");
//	 jQuery('.wizard-content',prevElem).slideUp();
	</script>

@stop