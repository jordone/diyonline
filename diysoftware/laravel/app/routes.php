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

//debug
//Route::get('resetprogress', 'ClientController@resetprogress');
Route::get('/getprogress', 'ClientController@GetUpdatedProgress');

/**
 * Routes for the office viewer
 */

// JE Previously was.... Route::get('office/', 'OfficeController@View_Office_HomePage

Route::get('office/', 'OfficeController@View_Merchant1');
Route::get('office/login', 'OfficeController@View_Office_Login_Page');
Route::post('office/loginform', 'OfficeController@Post_Office_Login_Page');
Route::get('office/search', 'OfficeController@View_Search_Page');
Route::get('office/recent', 'OfficeController@View_Recently_Updated');
Route::post('office/search', 'OfficeController@Post_Search_Page');
Route::get('office/searching', 'OfficeController@Post_Searching_Page');
Route::post('office/searching', 'OfficeController@Post_Searching_Page');
Route::get('office/results', 'OfficeController@View_Search_Results');
Route::post('office/results', 'OfficeController@Post_Search_Results');
Route::any('office/clearsearch', 'OfficeController@View_Clearout_Search_Results');

Route::get('office/view/{file}', 'OfficeController@View_Document');
Route::get('office/view/{file}/{step}', 'OfficeController@View_Document_Step');
Route::get('office/checkclientupdated/{file}/{lastupdate}', 'OfficeController@CheckForClientUpdate');



Route::pattern('step_id', '[0-9]+');
Route::pattern('lastupdate', '[0-9]+');

Route::pattern('file', '[a-zA-Z]+[0-9]+\.[0-9]+');
Route::pattern('step', 'step[0-9]+');

/**
 * Merchant: Close Client File
 */
Route::get('office/merchant/close', 'OfficeController@View_MerchantCloseFile');
/**
 * Step 1: Signup
 */
Route::get('office/merchant/', 'OfficeController@View_Merchant1');
Route::get('office/merchant/signup', 'OfficeController@View_Merchant1');
Route::get('office/merchant/signup/{file}', 'OfficeController@View_Merchant1');

/**
 * Step 2: Services
 */
Route::get('office/merchant/cart', 'OfficeController@View_Merchant2');
Route::get('office/merchant/cart/{file}', 'OfficeController@View_Merchant2');
Route::post('office/merchant/cart/save', 'OfficeController@Post_Merchant2');
Route::post('office/merchant/cart/save/{file}', 'OfficeController@Post_Merchant2');

/**
 * Step 3: Payments
 */
Route::get('office/merchant/payments', 'OfficeController@View_Merchant3');
Route::get('office/merchant/payments/{file}', 'OfficeController@View_Merchant3');
Route::get('office/merchant/payments/process/', 'OfficeController@View_MerchantProcess');
Route::get('office/merchant/payments/process/{file}', 'OfficeController@View_MerchantProcess');
Route::get('office/merchant/payments/progress/{file}', 'OfficeController@ViewMerchantProgress');

Route::get('office/progressbar/{file}', 'OfficeController@ViewMerchantProgress');

Route::post('office/merchant/payments/save', 'OfficeController@Post_Merchant3');
Route::post('office/merchant/payments/save/{file}', 'OfficeController@Post_Merchant3');


Route::post('office/merchant/save/ltf', 'OfficeController@Post_Merchant_LFT_SaveFormData');

Route::get('office/editfile', 'OfficeController@View_EditDocument');
Route::post('office/editfile', 'OfficeController@Post_EditFile');

## Cart is pending, no changes can be made yet.
Route::get('/step4services', 'ClientController@Step4Services');
Route::get('/IsDocumentOwner', 'ClientController@Ajax_Check_If_Document_Owner');
Route::get('/ajaxcheckpayment/'.Session::getId(), 'ClientController@AjaxCheckPaymentUpdate');
Route::post('/save_cart_items', 'ClientController@UpdateCartItems');
//Route::get('/upgradeonly', 'ClientController@ProcessPayments');
Route::post('/disclosuref', 'ClientController@DisclosureForm');
Route::get('/privacypolicy', 'ClientController@privacypolicy');
Route::get('/UpdateClientDocument', 'ClientController@UpdateClientDocument');


Route::get('/', 'DiyappController@View_Homepage');
Route::get('/{step}/', 'DiyappController@View_Edit_Step');


//Route::get('step1', 'ClientController@Step1');
//Route::get('step2', 'ClientController@Step2');
//Route::get('step3', 'ClientController@Step3');
//Route::get('step4', 'ClientController@Step4Services');
//Route::get('step5', 'ClientController@Step5');
//Route::get('step6', 'ClientController@Step6');
//Route::get('step7', 'ClientController@Step7');

Route::post('newclientform', 'ClientController@NewClientPosted');
Route::post('step2f', 'ClientController@Step2Form');
Route::post('step3f', 'ClientController@Step3FormSSN4Only');

//Route::get('select_forms', 'ClientController@SelectForms');
//Route::post('select_forms_post', 'ClientController@SelectFormsPosted');

Route::post('/step4f', 'ClientController@UpdateCartItems');
Route::post('step5f', 'ClientController@Step5Form');

// is this step 6? I don't think it's being used.
Route::get('stepshowloans', 'ClientController@StepShowLoans');
Route::post('steploansf', 'ClientController@StepShowLoansForm');

/// ???
Route::get('step6upload', 'ClientController@Step6upload');
Route::post('step6f', 'ClientController@Step6Form');
Route::post('step7f', 'ClientController@Step7Form');

// Step 7: PAYMENT INFORMATION -- Information is saved to the Session. 

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


#### Login to office use system
Route::get('office_login', 'ClientController@CheckOfficePassword');


Route::post('updatestatus', 'ClientController@FormUpdateStatus');

Route::get('returningcustomer', 'ClientController@ReturningCustomer');
Route::get('crm', 'ClientController@ShowCRM');

Route::post('returningclientf', 'ClientController@ReturningCustomerForm');

route::get('paymentpendingmsg', 'ClientController@PaymentPendingWait');


route::post('RegisterBrowserSession','ClientController@System_Register_With_Clients_BrowserID');

Route::get('logout', function(){
    Session::forget('fileNumber');
    return Redirect::to('/');
});

