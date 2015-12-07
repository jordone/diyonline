<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'ClientController@NewClientForm');
Route::post('newclientform', 'ClientController@NewClientPosted');
Route::get('step1', 'ClientController@Step1');

Route::get('step2', 'ClientController@Step2');
Route::post('step2f', 'ClientController@Step2Form');
Route::get('step3', 'ClientController@Step3');
Route::post('step3f', 'ClientController@Step3Form');

Route::get('step4', 'ClientController@Step4');
Route::post('step4f', 'ClientController@Step4Form');

Route::get('step5', 'ClientController@Step5');
Route::post('step5f', 'ClientController@Step5Form');

Route::get('step6', 'ClientController@Step6');
Route::post('step6f', 'ClientController@Step6Form');

// Step 7: PAYMENT INFORMATION -- Information is saved to the Session. 

Route::get('step7', 'ClientController@Step7');
Route::post('step7f', 'ClientController@Step7Form');

// Completed: We need to give them a completed step.
Route::get('processpayment', 'ClientController@ProcessPayment');
Route::post('paymentupdate', 'ClientController@CheckPaymentUpdate');

// payment completed

//completed says it generated the forms and will be the final success page they will see. It should update the top view too!
Route::get('paymentcompleted', 'ClientController@PaymentCompleted');

Route::get('paymentfailed', 'ClientController@PaymentFailed');
Route::get('paymentdeclined', 'ClientController@PaymentFailed');

// client has updated the generated forms
Route::get('updatesuccess', 'ClientController@UpdateSuccess');



Route::post('updatestatus', 'ClientController@FormUpdateStatus');
Route::get('returningcustomer', 'ClientController@ReturningCustomer');
Route::get('crm', 'ClientController@ShowCRM');

Route::post('returningclientf', 'ClientController@ReturningCustomerForm');


route::get('paymentpendingmsg', 'ClientController@PaymentPendingWait');


route::get('clientinfo', function(){
	$fileNumber = Session::get('fileNumber');
		$username = 'api.diy';
		$password = 'diyapi';
		$campaignId = '7181dcbd-bf75-425d-b5c5-b839de8d499d';
		$leadtrackapi = new leadtracapi($username, $password);
		
		$clientinfo = $leadtrackapi->GetClient($fileNumber, array('FirstName', 'LastName', 'WebsiteSource', 'Paid_Products'));
		
//		$clientinfo = $leadtrackapi->FindClientsByCity("NJ");
		
		
		
		return "<pre>".print_r($clientinfo,1)."</pre>";
	
		
		
});

Route::get('logout', function(){
	Session::forget('fileNumber');
	return Redirect::to('/');
});

Route::get('wizard', function(){
	return View::make('wizard');
});

//
//Route::get('/', function()
//{
//	return View::make('newcustomer');
//});
//
//Route::get('newclient', function()
//{
//    return View::make('newclient');
//});