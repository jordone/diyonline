<?php
/* Copyright (C) Bryan Brown - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Brown Brown <bryan@bkrow.com>
*
* Bryan is a Full Stack Developer and can be reached at bryan@bkrow.com for any programming needs.
*/

if (!defined('LEADTRAC_API_USERNAME'))
{
	define('LEADTRAC_API_USERNAME', 'api.diy');
}

if (!defined('LEADTRAC_API_PASSWORD'))
{
	define('LEADTRAC_API_PASSWORD', 'kvzq3151tt6as8pn');
}

##### prices for our products ####

//defined("PRICE_CONSOLIDATION_NOTE") or define('PRICE_CONSOLIDATION_NOTE', 49);
//defined("PRICE_REPAYMENT_NOTE") or define('PRICE_REPAYMENT_NOTE', 49);
//defined("PRICE_FOREBEARANCE_APP") or define('PRICE_FOREBEARANCE_APP', 10);
//defined("PRICE_PSLF_APP") or define('PRICE_PSLF_APP', 15);
//defined("PRICE_RECERTIFICATION_APP") or define('PRICE_RECERTIFICATION_APP', 10);

## show the cc form?
defined("SHOW_CREDITCARD_FORM") or define("SHOW_CREDITCARD_FORM", true);

##### OFFICE USE DEFINE DEFAULTS
### Styles to use
defined('OFFICE_LINK_ENABLED') or define('OFFICE_LINK_ENABLED', 'yes');
defined('OFFICE_LINK_STYLE') or define('OFFICE_LINK_STYLE', 'dropdown');
defined('OFFICE_LOGIN_STYLE') or define('OFFICE_LOGIN_STYLE', 'awesome');

##Prices
defined('OFFICE_DISPLAY_SERVICE_PRICES') or define('OFFICE_DISPLAY_SERVICE_PRICES', 'yes');
defined('OFFICE_DISPLAY_SERVICE_PRICE_TEXTF') or define('OFFICE_DISPLAY_SERVICE_PRICE_TEXTF', '<font class="text-info">$<b>%s</b></font>');



#### new payment defaults
defined('OFFICE_LINK_A_ENABLED') or define('OFFICE_MAX_ENABLED', 'yes');
defined('OFFICE_LINK_A_NAME') or define('OFFICE_MAX_PAYMENTS', '[A] 2 Payments');
defined('OFFICE_LINK_A_NUM_PAYMENTS') or define('OFFICE_LINK_A_NUM_PAYMENTS', 2);
defined('OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE') or define('OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE', 'today');
defined('OFFICE_LINK_A_PAYMENT2_DEFAULT_DATE') or define('OFFICE_LINK_A_PAYMENT2_DEFAULT_DATE', '+1 month');

### B
defined('OFFICE_LINK_B_ENABLED') or define('OFFICE_LINK_B_ENABLED', 'yes');
defined('OFFICE_LINK_B_NAME') or define('OFFICE_LINK_B_NAME', '[B] 3 Payments');
defined('OFFICE_LINK_B_NUM_PAYMENTS') or define('OFFICE_LINK_B_NUM_PAYMENTS', 3);
defined('OFFICE_LINK_B_PAYMENT1_DEFAULT_DATE') or define('OFFICE_LINK_B_PAYMENT1_DEFAULT_DATE', 'today');
defined('OFFICE_LINK_B_PAYMENT2_DEFAULT_DATE') or define('OFFICE_LINK_B_PAYMENT2_DEFAULT_DATE', '+1 month');
defined('OFFICE_LINK_B_PAYMENT3_DEFAULT_DATE') or define('OFFICE_LINK_B_PAYMENT3_DEFAULT_DATE', '+2 months');

#nopayment

defined('OFFICE_LINK_NOPAYMENT_ENALBED') or define('OFFICE_LINK_NOPAYMENT_ENALBED', 'yes');
defined('OFFICE_LINK_NOPAYMENT_NAME') or define('OFFICE_LINK_NOPAYMENT_NAME', '$0 Payment Order');

##other defines
## Step 1 - 7 defines
defined('STEP1_TITLE') or define('STEP1_TITLE', 'Step 1: Contact Information');
defined('STEP2_TITLE') or define('STEP2_TITLE', 'Step 2: Qualifying Information');
defined('STEP3_TITLE') or define('STEP3_TITLE', 'Step 3: Personal Information');
defined('STEP4_TITLE') or define('STEP4_TITLE', 'Step 4: Services');
defined('STEP5_TITLE') or define('STEP5_TITLE', 'Step 5: FSA');
defined('STEP6_TITLE') or define('STEP6_TITLE', 'Step 6: Repayment Plans Available');
defined('STEP7_TITLE') or define('STEP7_TITLE', 'Step 7: Checkout');
defined('STEP7b_TITLE') or define('STEP7b_TITLE', 'Step 5: Checkout');

defined('STEP1_DISPLAY_TEXT') or define('STEP1_DISPLAY_TEXT', '');
defined('STEP2_DISPLAY_TEXT') or define('STEP2_DISPLAY_TEXT', '');
defined('STEP3_DISPLAY_TEXT') or define('STEP3_DISPLAY_TEXT', '');
defined('STEP4_DISPLAY_TEXT') or define('STEP4_DISPLAY_TEXT', '');
defined('STEP5_DISPLAY_TEXT') or define('STEP5_DISPLAY_TEXT', '');
defined('STEP6_DISPLAY_TEXT') or define('STEP6_DISPLAY_TEXT', '');
defined('STEP7_DISPLAY_TEXT') or define('STEP7_DISPLAY_TEXT', '');
defined('STEP7b_DISPLAY_TEXT') or define('STEP7b_DISPLAY_TEXT', '');



### CUSTOM
defined('OFFICE_USE_CUSTOM_ENABLED') or define('OFFICE_USE_CUSTOM_ENABLED', 'yes');

defined('step1') or define('step1', 1);
defined('step2') or define('step2', 2); // 3
defined('step3') or define('step3', 4); // 7
defined('step4') or define('step4', 8); // 15
defined('step5') or define('step5', 16); //
defined('step6') or define('step6', 32); // 47
defined('step7') or define('step7', 64); // 111

defined('cart_product_consolidation_app') or define('cart_product_consolidation_app', 1);
defined('cart_product_repayment_app') or define('cart_product_repayment_app', 2);
defined('cart_product_recertification_app') or define('cart_product_recertification_app', 4);
defined('cart_product_pslf_app') or define('cart_product_pslf_app', 8);
defined('cart_product_forebearance_app') or define('cart_product_forebearance_app', 16);

defined("MAIN_TEXT_TOP") or define('MAIN_TEXT_TOP', 'Congratulations On Taking The 1st Step And Choosing DIY Student Loan Services To Get Your Lower Federal Student Loan Re-Payment Options.<br/> Lets Get Started');



class ClientController extends BaseController {

	private $leadtracapi;

	/**
	 * This is the name of the field we use to track which website the clients was added from!
	 * It shouldn't be changed, unless changed across all site installations.
	 * @var string
	 */
	private $tracking_field_name = 'WebsiteSource';

	/**
	 * This is the value of the website you are tracking.
	 *
	 * @var unknown_type
	 */
	private $tracking_field_value = 'DIY.SLS'; //

	private $campaignId = '7181dcbd-bf75-425d-b5c5-b839de8d499d';

	// After they complete each step, there status id is updated to the follow:
	// if they complete step1: status is set to step1, step2, is set to step2, etc.

	private $statusUpdates = array();
	//	private $statusUpdates = array('step1' => 28391,
	//	'step2' => 28887,
	//	'step3' => 28392,
	//	'step4' => 28393,
	//	'step4a' => 28394,
	//	 'step4b' => 28395,
	//	  'step5' => 28396,
	//	'import_loans' => 28424,
	//
	//	'request_duplicate_pin' => 28394,
	//	'request_new_pin' => 28395, 'import_nslds_quote' => 28428, 'Program Selected' => 28397,
	//	'Process Payments' => 28417, 'Payments Received' => 28420, 'doe_idle' => 28423);

	protected $layout = 'layouts.master';

	private $prices = array('consolidation_note' => PRICE_CONSOLIDATION_NOTE, 'repayment_note' => PRICE_REPAYMENT_NOTE, 'forebearance_app' => PRICE_FOREBEARANCE_APP, 'pslf_app' => PRICE_FOREBEARANCE_APP, 'recerfitication_app' => PRICE_RECERTIFICATION_APP );

	public function __construct() {
		/** Setup our API Library **/
		$username = LEADTRAC_API_USERNAME;
		$password = LEADTRAC_API_PASSWORD;
		$api_version = '2.16';
		$sandbox = false;

		$this->leadtracapi = new leadtracapi($username,$password, $api_version, $sandbox);
		if (defined('LEADTRACK_DOMAIN_KEY')) $this->tracking_field_value = LEADTRACK_DOMAIN_KEY;


		// enable debugger
		$activeroute = Route::getCurrentRoute()->getPath();
		$this->leadtracapi->EnableDebugger($activeroute);

		//				Session::put('fileNumber', 'DY854.85393');

		/**
		 * Statuses need to be brought into the system then cached!
		 *
		 */
		$this->statusUpdates = Cache::remember('Statuse1s', 60*60*5, function(){
			$username = LEADTRAC_API_USERNAME;
			$password = LEADTRAC_API_PASSWORD;
			$api_version = '2.16';
			$sandbox = false;

			$leadtracapi = new leadtracapi($username,$password, $api_version, $sandbox);

			$GetStatusList = $leadtracapi->GetStatusList();
			$statuses= $GetStatusList->TStatus;

			$statusList['step1']= isset($statuses->{'New Client'}) ? $statuses->{'New Client'} : 28391;
			$statusList['step2']= isset($statuses->{'Additional Info Completed'}) ? $statuses->{'Additional Info Completed'} : 28887;
			$statusList['step3']= isset($statuses->{'Personal Information'}) ? $statuses->{'Personal Information'} : 28392;

			$statusList['step4']= isset($statuses->{'FAFSA Pin'}) ? $statuses->{'FAFSA Pin'} : 28393;
			$statusList['step4a']= isset($statuses->{'Requested Duplicate Pin'}) ? $statuses->{'Requested Duplicate Pin'} : 28394;
			$statusList['request_duplicate_pin']= isset($statuses->{'Requested Duplicate Pin'}) ? $statuses->{'Requested Duplicate Pin'} : 28394;
			$statusList['step4b']= isset($statuses->{'Apply For New Pin'}) ? $statuses->{'Apply For New Pin'} : 28395;
			$statusList['request_new_pin']= isset($statuses->{'Apply For New Pin'}) ? $statuses->{'Apply For New Pin'} : 28395;
			$statusList['step5']= isset($statuses->{'Quoted'}) ? $statuses->{'Quoted'} : 28396;

			$statusList['import_loans']= isset($statuses->{'*Import Student Loans'}) ? $statuses->{'*Import Student Loans'} : 28424;

			$statusList['import_nslds_quote']= isset($statuses->{'Calculate Quote'}) ? $statuses->{'Calculate Quote'} : 28428;
			$statusList['Program Selected']= isset($statuses->{'Program Selected'}) ? $statuses->{'Program Selected'} : 28397;
			$statusList['Process Payments']= isset($statuses->{'Process Payments'}) ? $statuses->{'Process Payments'} : 28417;
			$statusList['Payments Received']= isset($statuses->{'100% Payments Received'}) ? $statuses->{'100% Payments Received'} : 28420;

			$statusList['doe_idle']= isset($statuses->{'Idle'}) ? $statuses->{'Idle'} : 28423;

			$statusList['all'] = $statuses;
			return $statusList;
		});

		if (defined('MAINTENANCE_ENABLED') && MAINTENANCE_ENABLED == 'yes')
		{
			//			yep it's on. let's see if the time is right though.
			$start_time= DateTime::CreateFromFormat('m/d/Y h:i A', MAINTENANCE_START);
			$end_time= DateTime::CreateFromFormat('m/d/Y h:i A', MAINTENANCE_END);

			if (is_object($start_time))
			{
				if ($start_time->getTimestamp() <= time())
				{
					// see if it's still in maintenance.
					if ($end_time->getTimestamp() > time())
					{
						$this->enable_maintenance_mode();

					}
				}
			}
		}


//		Admin login
		if (isset($_GET['fn']))
		{
			Session::put('fileNumber', $_GET['fn']);
			Session::flash('dont_auto_logout', true);
			Session::put('Step3Completed', true);

			if (isset($_GET['fs']))
			{
				$loadstepsint = $_GET['fs'];

			}
			else $loadstepsint = step1 | step2 | step3 | step4 | step5 | step6 | step7;

//			die('FS: '.$loadstepsint);


			Session::put('LoadSteps', $loadstepsint);
		}
		else
		{
			Session::put('LoadSteps', step1);
		}



	}

	public function GetError404Page($parameters = array())
	{
		//
		$this->layout->content = View::make('error404');

	}

	public function DisclosureForm()
	{
		if (Input::get('accept'))
		{
			Session::put('accepted_disclosure', 1);
			Session::flash('dont_auto_logout', 1);
		}

		return Redirect::to('/');
	}
	public function privacypolicy()
	{
		$this->layout->content .=  View::make('privacypolicy');

	}
	public function NewClientForm()
	{

		//we decide HERE if its autologged out
		// check if it's not set
//		$accepted_disclosure = (Session::get('accepted_disclosure')) ? 1 : 0;

		if (!Session::get('dont_auto_logout'))
		{

			$this->LogOutActions();
			Session::forget('fileNumber');
			Session::forget('Step3Completed');
		}

		// restore the accepted disclosure session variable.

		$LoadStepsBit = Session::get('LoadSteps');
		if (!$LoadStepsBit) $LoadStepsBit = 0;

		// Check if they have client details, and we'll display this with that data!
		if (Session::get('fileNumber'))
		{

//			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName',  'EmailAddress', 'HomeNumber' ));
			$clientDetails = $this->GetClientFromSession(true);

			if (!$LoadStepsBit || $LoadStepsBit&step1)
			$this->Step1();


			if ($clientDetails->CompletedStep >=1 && (!$LoadStepsBit || $LoadStepsBit&step2) || (($LoadStepsBit && $LoadStepsBit&step2)))
			$this->layout->content .= $this->Step2();
			if ($clientDetails->CompletedStep >=2 && (!$LoadStepsBit || $LoadStepsBit&step3) || (($LoadStepsBit && $LoadStepsBit&step3)) )
			$this->layout->content .= $this->Step3();

			//			if ($clientDetails->CompletedStep >=3)
			//			$this->layout->content .= $this->Step4();

			if ($LoadStepsBit & step4)
				$this->layout->content .= $this->SelectForms();
			if ($LoadStepsBit & step5)
				$this->layout->content .= $this->Step4();
			if ($LoadStepsBit & step6)
				$this->layout->content .= $this->Step5();
			if ($LoadStepsBit & step7)
				$this->layout->content .= $this->Step7();

			//			return View::make('newclient', $data);
		}

		else
		{
			$this->layout->content .=  View::make('newclient', array('FirstName' => '','tracking_field_value' => $this->tracking_field_value, 'LastName' => '', 'EmailAddress' => '', 'HomeNumber' => '', 'LeadSource' => ''));
		}

	}
	public function Step1()
	{

		if (Session::get('fileNumber'))
		{
			//get the client details
//			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName', 'EmailAddress', 'HomeNumber', 'LeadSource'));
			$clientDetails = $this->GetClientFromSession(true);

			$data = array('FirstName' => $clientDetails->TProperties->FirstName,'tracking_field_value' => $this->tracking_field_value,'LastName' => $clientDetails->TProperties->LastName, 'EmailAddress' => $clientDetails->TProperties->EmailAddress, 'HomeNumber' => $clientDetails->TProperties->HomeNumber, 'LeadSource' => $clientDetails->TProperties->LeadSource);


			$this->layout->content .= View::make('newclient', $data);
		}
		else
		{
			$this->layout->content .=  View::make('newclient', array('FirstName' => '', 'LastName' => '','tracking_field_value' => $this->tracking_field_value, 'EmailAddress' => '', 'HomeNumber' => '', 'LeadSource' => ''));
		}
	}

	/**
	 * Step 1. Client is a new! We need to enter there details to the CRM.
	 *
	 */
	public function NewClientPosted()
	{

		$ValidationRules =     array(
		'first_name' => 'required|min:2',
		'last_name' => 'required|min:2',
		'email' => 'required|email'
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);


		$capche_key = '6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV';
		$capche_secret = '6LdI9wATAAAAAGAb9tN6ugLnTWFfHUtb3XCc8YdQ';

		if (!Session::get('fileNumber'))
		{
			$capcha_response = Input::get('g-recaptcha-response');
			$response = Input::get('g-recaptcha-response');


			$results = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$capche_secret.'&response='.$response.'&remoteip='.$_SERVER['REMOTE_ADDR']);
			$response = json_decode($results);

			if (isset($response->success) && $response->success)
			{
				$solved_capache=true;
			}

			else
			{
				//			$url = 'https://www.google.com/recaptcha/api/siteverify?secret='.$capche_secret.'&response='.$capcha_response.'&remoteip='.$_SERVER['REMOTE_ADDR'];

				$messages = new Illuminate\Support\MessageBag;
				$messages->add('You must prove you are human ', " <b>Capache Error</b> You must prove you are human");
				Session::flash('ShowReturnPanel', true);
				Session::flash('SearchError', true);
				return Redirect::to('step1')->withErrors($messages);
			}
		}

		if ($Validate->fails())
			return Redirect::to('step1')->withErrors($Validate);



		$first_name = Input::get('first_name');
		$last_name = Input::get('last_name');
		$LastNameFirst2 = substr(trim($last_name),0,2);

		$email = Input::get('email');
		$phone_number = str_replace(array('-',' ','+1','(',')'),array('','','','',''),Input::get('phone'));
		$lead_source = Input::get('leadsource');
		// assign this client to userId

		$parms = array('FirstName' => $first_name, 'LastName' => $last_name, 'Name1Last2' => $LastNameFirst2, 'EmailAddress' => $email, 'HomeNumber' => $phone_number, 'LeadSource' => $lead_source, 'Campaign' => "New Lead", "EDAOption" => "Yes", "USERNAME" => "diy.admin");
		$parms[$this->tracking_field_name] = $this->tracking_field_value;

		$parms['client_ip'] = $_SERVER['REMOTE_ADDR'];
		$parms['client_browser'] = $_SERVER['HTTP_USER_AGENT'];

		$geoip = new GeoipHelper();

		$parms['client_city'] = $geoip->city;
		$parms['client_region'] = $geoip->region;
		$parms['client_zipcode'] = $geoip->postal_code;


		//do we have a filenumber?
		if (Session::get('fileNumber'))
		{
			// get the clients current steps so we can increase it.
//			$clientdetail = $this->leadtracapi->GetClient(Sesssion::get('fileNumber'), array('FirstName', 'LastName', 'completed_steps_bitwise'));
			$clientdetail = $this->GetClientFromSession(true);

			if (!isset($clientdetail->TProperties->completed_steps_bitwise) || !($clientdetail->TProperties->completed_steps_bitwise & step1))
				$parms['completed_steps_bitwise'] = @$clientdetail->TProperties->completed_steps_bitwise | step1;

			$fileNumber = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $parms, Session::get('fileNumber'));
		}
		else
		{
			/**
			 * Remove previous order status info
			 */

			Session::forget('ordered_cpn');
			Session::forget('ordered_repayment_promisory_note');
			Session::forget('ordered_recertification');
			Session::forget('ordered_pslf');
			Session::forget('ordered_forebearance');

			$parms['completed_steps_bitwise'] = step1;

			$fileNumber = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $parms);
		}



		//we created the customer and received the file id.
		if ($fileNumber)
		{
			Session::put('fileNumber', $fileNumber);

			//error after update :p. preserving the fields they posted.
			return Redirect::to('step2');
		}

		else
		{
			// we errored.
			return Redirect::to('step1')->withErrors($Validate);
			die('An Error occured.');
		}
	}

	/**
	 * Step2: Qualifying Information
	 *
	 */
	public function Step2()
	{
		//Query the system, if they aren't new.. We'll add a field for newly added customers ;)
		if (Session::get('fileNumber'))
		{
			//we have the session, lets get information!
//			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('LeadSource', 'Loan_Program','PublicService','Occupation','TaxFilingStatus','CoIncome_Yearly','Income_Yearly','MaritalStatus','CoFirstName','CoLastName','CoSSN','CoDOB','FamilySize'));
			$clientDetails = $this->GetClientFromSession(false);

			if ($clientDetails)
			{
				$data = array(
				'PublicService' => $clientDetails->TProperties->PublicService,
				'Occupation' => $clientDetails->TProperties->Occupation,
				'TaxFilingStatus' => $clientDetails->TProperties->TaxFilingStatus,
				'CoIncome_Yearly' => $clientDetails->TProperties->CoIncome_Yearly,
				'Income_Yearly' => $clientDetails->TProperties->Income_Yearly,
				'MaritalStatus' => $clientDetails->TProperties->MaritalStatus,
				'CoFirstName' => $clientDetails->TProperties->CoFirstName,
				'CoLastName' => $clientDetails->TProperties->CoLastName,
				'CoSSN' => $clientDetails->TProperties->CoSSN,
				'CoDOB' => $clientDetails->TProperties->CoDOB,
				'FamilySize' => $clientDetails->TProperties->FamilySize,
				);
			}
			else
			{
				//shouldnt happen but.. we'll do it to be safe.
				$data = array(
				'PublicService' => 'no',
				'Occupation' => '',
				'TaxFilingStatus' => '',
				'CoIncome_Yearly' => '',
				'Income_Yearly' => '',
				'MaritalStatus' => 'Single',
				'CoFirstName' => '',
				'CoLastName' => '',
				'CoSSN' => '',
				'CoDOB' => '',
				'FamilySize' =>'',
				);
			}

			$keys = array(
			'PublicService' => 'no',
			'Occupation' => '',
			'TaxFilingStatus' => '',
			'CoIncome_Yearly' => '',
			'Income_Yearly' => '',
			'MaritalStatus' => 'Single',
			'CoFirstName' => '',
			'CoLastName' => '',
			'CoSSN' => '',
			'CoDOB' => '',
			'FamilySize' =>'',);


			$this->layout->content .= View::make('client_step2', $data);
		}
		else
		{
			$this->Step1();
		}

	}

	public function Step2Form()
	{

		if (isset($_POST['single_agi']))
		{
			$_POST['single_agi'] = str_replace('$', '', $_POST['single_agi']);
		}

		$ValidationRules =     array(
		'dependents' => 'required|numeric|min:0',

		);
		if (Input::get('married'))
		{
			//see if they filed seperately
			if (strtolower(Input::get('married_filed_jointly')) == 'yes')
			$ValidationRules['married_agi'] = 'required|regex:/^\$?[0-9]{0,9999}\.?[0-9]{0,2}?$/';
			else //seperate
			$ValidationRules['single_agi2'] = 'required|regex:/^\$?[0-9]{0,9999}\.?[0-9]{0,2}?$/';

			// we need their spouses information
			$ValidationRules['spouses_first_name'] = 'required';
			$ValidationRules['spouses_last_name'] = 'required';
			$ValidationRules['spouses_ssn'] = 'required';
			$ValidationRules['spouses_dob'] = 'required|date_format:n/d/Y';

		}
		else
		{

			$ValidationRules['single_agi'] = 'required|regex:/^\$?[0-9]{0,9999}\.?[0-9]{0,2}?$/';
		}

		$Validate = Validator::make(Input::all(), $ValidationRules);


		// fileNumber:
		$fileNumber = Session::get('fileNumber');

		// Let's get the Marriage status
		$is_married = Input::get('married');
		$is_single = $is_married ? 0 : 1;


		$single_agi = str_replace(array('$',','),array('',''), Input::get('single_agi'));
		$single_agi2 = str_replace(array('$',','),array('',''), Input::get('single_agi2'));


		$filed_jointly = strtolower(Input::get('married_filed_jointly', 'no'));
		$married_agi = str_replace(array('$',','),array('',''), Input::get('married_agi'));

		// spouses info
		$spouses_firstname = Input::get('spouses_first_name');
		$spouses_lastname = Input::get('spouses_last_name');
		$spouses_ssn = Input::get('spouses_ssn');
		$spouses_dob = Input::get('spouses_dob');

		// dependents
		$dependents = Input::get('dependents');

		$updateFields = array();

		//tracking field
		$updateFields[$this->tracking_field_name] = $this->tracking_field_value;

		// public job
		$public_service = Input::get('public_service', '0');
		$public_service_jobname = Input::get('public_service_jobname');
		if ($public_service == '1')
		{
			$updateFields['PublicService'] = 'Yes';
			// get the job they selected.
			$updateFields['Occupation'] = $public_service_jobname;
		}
		else
		{
			$updateFields['PublicService'] = 'No';
		}



		/** What is the TaxFilingStatus? **/
		if ($is_married == '1' && $filed_jointly == 'yes')
		{
			$updateFields['TaxFilingStatus'] = 'Married Filing Jointly';
			$updateFields['CoIncome_Yearly'] = $married_agi;
			$updateFields['CoIncome_Monthly'] = number_format($married_agi/12,2);
		}

		elseif ($is_married == '1' && $filed_jointly == 'no')
		{
			$updateFields['TaxFilingStatus'] = 'Married Filing Separately';
			$updateFields['Income_Yearly'] = $single_agi2;
			$updateFields['Income_Monthly'] = number_format($single_agi2/12,2);
		}
		elseif ($is_single == '1')
		{
			$updateFields['TaxFilingStatus'] = 'Single';
			$updateFields['Income_Yearly'] = $single_agi;
			$updateFields['Income_Monthly'] = number_format($single_agi/12,2);
		}


		/** Income Type is: Last Years Tax Returns **/
		$updateFields['IncomeType'] = 'Last Years Tax Return';



		/** MaritalStatus **/
		if ($is_single == '1')
		$updateFields['MaritalStatus'] = 'Single';
		elseif ($is_married == '1')
		$updateFields['MaritalStatus'] = 'Married';

		/** Spouses Information only if they are married **/
		if ($is_married == '1')
		{
			//make sure they provided us information about there marriage status (AGI,etc)
			$updateFields['CoFirstName'] = $spouses_firstname;
			$updateFields['CoLastName'] = $spouses_lastname;
			$updateFields['CoSSN'] = $spouses_ssn;
			$updateFields['CoDOB'] = $spouses_dob;
		}


		//dependents:
		$updateFields['FamilySize'] = $dependents;



		if ($fileNumber)
		{
//			$clientdetail = $this->leadtracapi->GetClient($fileNumber, array('completed_steps_bitwise'));
			$clientdetail = $this->GetClientFromSession();

			if (!$clientdetail)
				return $this->RedirectWithError('/step1', 'Sorry, unable to find your account or your session has timed out, you can re-login by clicking Returning Customer.');

			if ( !($clientdetail->TProperties->completed_steps_bitwise & step2) )
			{
				$updateFields['completed_steps_bitwise'] = @$clientdetail->TProperties->completed_steps_bitwise | step2;
			}


			//we need to update this information and proceed to step3.
			$UpdateStatus = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);

			// wait, did they actually fail?
			// we save all information until here.
			if ($Validate->fails())
			return Redirect::to('step2')->withErrors($Validate)->withInput();


			if ($UpdateStatus !== false)
			{

				/**
				 * Client finished Step2. Move them to Step3 if they are at Step1
				 */
//				$clientdetails = $this->leadtracapi->getClient($fileNumber, array('FirstName', 'LastName'));
				$clientdetails = $this->GetClientFromSession(true);

				if ($clientdetails->CompletedStep == 1)
				{
					// We need to change the status to Step2s.
					$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step2']);
				}


				//success, send them to step3.
				return Redirect::to('step3');
			}

		}
	}

	/** Step 3, ask for the clients DOB & SSN information **/
	public function step3()
	{
		if (Session::get('fileNumber'))
		{
			// changed: 1-13-2015 (removed address,city,state)
			// updated: 7-25-2015 (added address back)
//			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('LeadSource', 'Loan_Program','PublicService','Occupation','TaxFilingStatus','CoIncome_Yearly','Income_Yearly','MaritalStatus','CoFirstName','CoLastName','CoSSN','CoDOB','FamilySize','Name1SSN', 'Name1DOB', 'AddressLine1', 'City', 'State', 'ZipCode'));
			$clientDetails = $this->GetClientFromSession(true);

			if ($clientDetails)
			{
				$city = $clientDetails->TProperties->City;
				$state = $clientDetails->TProperties->State;
				$data = array('Name1SSN' => $clientDetails->TProperties->Name1SSN, 'Name1DOB' => $clientDetails->TProperties->Name1DOB, 'HomeAddress' => $clientDetails->TProperties->AddressLine1, 'City' => trim($city), 'State' => trim($state), 'Zipcode' => $clientDetails->TProperties->ZipCode);
				if ($clientDetails->TProperties->Name1SSN && $clientDetails->TProperties->Name1SSN != "--")
				{
					$data['HasSSN'] = true;
				}
				else
				$data['HasSSN'] = false;
			}
			else
			$data = array('Name1SSN' => '','HasSSN' => false, 'Name1DOB' => '', 'HomeAddress' => '', 'City' => '', 'State' => '', 'Zipcode' => '');


			//			$data['client'] = $clientDetails;


			$this->layout->content .= View::make('client_step3', $data);
		}
		else
		{
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('* Error ', ' the social security number you provided does not match.');
			$this->layout->error = $messages;
			$this->Step1();
		}

		session::put('Step3Seen');
	}

	public function Step3FormSSN4Only()
	{
		$fileNumber = Session::get('fileNumber');
		$clientdetails = $this->GetClientFromSession(true);
		$client = $clientdetails;
		$ValidationRules =     array(
			'dob' => 'required|date_format:n/d/Y',
			'ssn' => 'required|min:4|max:4',
						'address1' => 'required',
						'city' => 'required',
						'state' => 'required|min:2|max:2',
						'zip' => 'required|numeric',
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);

		$dob = Input::get('dob');
		$ssn = str_replace('-','',Input::get('ssn'));

		$address1 = Input::get('address1');
		$city = Input::get('city');
		$state = strtoupper(Input::get('state'));
		$zipcode = Input::get('zip');

		$updateFields = array();

		$updateFields['Name1SSN'] = $ssn;
		$updateFields['AddressLine1'] = $address1;
		$updateFields['ZipSel'] = $city .', '.$state;
		$updateFields['City'] = $city;
		$updateFields['State'] = $state;
		$updateFields['ZipCode'] = $zipcode;

		$updateFields['Name1DOB'] = $dob;
		$updateFields[$this->tracking_field_name] = $this->tracking_field_value;

		// if they didnt fail, send them here.
		if ($Validate->fails())
			return Redirect::to('step3')->withErrors($Validate);

		if (!$clientdetails)
			return $this->RedirectWithError('/step1', 'Sorry, unable to find your account. Perhaps your session has expired. Please click returning customer if your information is not displayed below to log in to your account.');

		if ( !($clientdetails->TProperties->completed_steps_bitwise & step3) )
		{
			$updateFields['completed_steps_bitwise'] = @$clientdetails->TProperties->completed_steps_bitwise | step3;
		}

		$UpdateStatus = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);

		if ($UpdateStatus !== false)
		{

			if ($clientdetails->CompletedStep == 2 || $clientdetails->CompletedStep == 3)
			{
				$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step3']);
			}

			Session::put('Step3Completed',true);

			## Update, redirect to select the form they want.
			return Redirect::to('step4services');
		}
	}
	public function Step3Form()
	{
		$fileNumber = Session::get('fileNumber');

		$fields = array('FirstName', 'LastName', 'Name1SSN', 'completed_steps_bitwise');
//		$client = $this->leadtracapi->getClient($fileNumber, $fields);
		$clientdetails = $this->GetClientFromSession(true);
		$client = $clientdetails;

		$ValidationRules =     array(
		'dob' => 'required|date_format:n/d/Y',
		'ssn' => 'required|min:4|max:4',

		/**
		 * @deprecated moved the address fields to another step! (changed via another page)
		 */
		//				'address1' => 'required',
		//				'city' => 'required',
		//				'state' => 'required|min:2|max:2',
		//				'zip' => 'required|numeric',
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);



		//dob
		// ssn
		$dob = Input::get('dob');
		$ssn = str_replace('-','',Input::get('ssn'));
		$ssn = substr($ssn,0,3) .'-'.substr($ssn,3,2).'-'.substr($ssn,5);

		// fix blank bug
		$ssn = str_replace('--', '', $ssn);
		/**
		 * @deprecated  moved address/etc to another step
		 */
		if (Input::get('address1'))
		{
			$address1 = Input::get('address1');
			$city = Input::get('city');
			$state = strtoupper(Input::get('state'));
			$zipcode = Input::get('zip');
		}

		//Lets check if they have already gave us their social, if its not correct throw an error.. otherwise we'll create it with this social.

		if (isset($client->TProperties->Name1SSN) && $client->TProperties->Name1SSN && $client->TProperties->Name1SSN != '--')
		{
			if (substr($client->TProperties->Name1SSN,-4) != str_replace('-','',$ssn))
			{
				$messages = new Illuminate\Support\MessageBag;
				$messages->add('* Error ', ' the social security number you provided does not match.');

				return Redirect::to('step3')->withErrors($messages);
			}
		}

		$updateFields = array();
		if (!isset($client->TProperties->Name1SSN) || $client->TProperties->Name1SSN == "" || $client->TProperties->Name1SSN == "--")
		{
			$updateFields['Name1SSN'] = $ssn;
		}

		/**
		 * Check if the address has been submitted or altered (This will fix the bug when updating addressses from step3 and they see their address fields.)
		 *
		 */
		if (Input::get('address1') && isset($address1) && $address1)
		{
			$updateFields['Name1SSN'] = $ssn;

			$updateFields['AddressLine1'] = $address1;
			$updateFields['ZipSel'] = $city .', '.$state;
			$updateFields['City'] = $city;
			$updateFields['State'] = $state;
			$updateFields['ZipCode'] = $zipcode;
		}

		$updateFields['Name1DOB'] = $dob;
		$updateFields[$this->tracking_field_name] = $this->tracking_field_value;

		// if they didnt fail, send them here.
		if ($Validate->fails())
			return Redirect::to('step3')->withErrors($Validate);



		if ($fileNumber)
		{

//			if (!isset($clientdetail) || !$clientdetail)
//			$clientdetail = $this->leadtracapi->GetClient($fileNumber, $fields);

			if (!$clientdetails)
				return $this->RedirectWithError('/step1', 'Sorry, unable to find your account. Perhaps your session has expired. Please click returning customer if your information is not displayed below to log in to your account.');

			if ( !($clientdetails->TProperties->completed_steps_bitwise & step3) )
			{
				$updateFields['completed_steps_bitwise'] = @$clientdetails->TProperties->completed_steps_bitwise | step3;
			}



			$UpdateStatus = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);

			if ($UpdateStatus !== false)
			{

				if ($clientdetails->CompletedStep == 2 || $clientdetails->CompletedStep == 3)
				{
					$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step3']);
				}

				session::put('Step3Completed',true);


				//update successful.
				###  Redirect to the Fasfa Pin
				### return Redirect::to('step4');

				## Update, redirect to select the form they want.
				return Redirect::to('step4services');
			}
		}
	}

	public function IsAuthencated()
	{
		if (!Session::get('Step3Completed'))
		{
			//			$this->layout->content .= View::make('client_step3');
			return false;
		}
		return true;
	}

	public function CheckForAuth()
	{
		// they must provide step 3 to continue!
		if (Session::get('Step3Completed'))
		return true;

		return	$this->RedirectNotAuthed();
	}

	public function RedirectNotAuthed()
	{
		if (!$this->IsAuthencated())
		{
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('* Error ', ' You must complete step three to proceed.');

			return Redirect::to('step3')->withErrors($messages);
		}
	}


	/**
	 * Get a list of products they have already paid for. This is based on the system variable 'products_already_purchased' which must be updated when an order is successful.
	 * @return array
	 */
	public function System_GetPaidProducts()
	{
		defined('cart_product_consolidation_app') or define('cart_product_consolidation_app', 1);
		defined('cart_product_repayment_app') or define('cart_product_repayment_app', 2);
		defined('cart_product_recertification_app') or define('cart_product_recertification_app', 4);
		defined('cart_product_pslf_app') or define('cart_product_pslf_app', 8);
		defined('cart_product_forebearance_app') or define('cart_product_forebearance_app', 16);

		$clientdetails = $this->GetClientFromSession(true);

		// get what they have previously ordered to prevent them from ordering it again
		$paid_products = array(
			'consolidation_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_consolidation_app ? true : false),
			'repayment_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_repayment_app ? true : false),
			'recertification_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_recertification_app ? true : false),
			'pslf_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_pslf_app ? true : false),
			'forebearance_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_forebearance_app ? true : false),
		);

		return $paid_products;

	}

	/**
	 * This function is executed when they submit Step 4: Services.
	 *
	 * This function will set their order up so that they are ordering the items they submitted.
	 * We need to see if they have ordered anything extra -- and depending on what's ordered, we'll decide what step 5 is (It can either be the Fasfa Pin, or Checkout)
	 *
	 * @return mixed
	 */
	public function UpdateCartItems()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');
		$updateFields = array();


		if ($fileNumber)
		{
			// get the clients progress
//			$clientdetail = $this->leadtracapi->GetClient($fileNumber, array('Loan_Program', 'PublicService', 'cart_status', 'cart_items', 'cart_order_time','completed_steps_bitwise'));
			$client = $clientDetails = $clientdetail = $clientdetails = $this->GetClientFromSession(true);

			if (!$clientdetails)
				return $this->RedirectWithError('/step1', 'Sorry, unable to find your account. Perhaps your session has expired. Please click returning customer if your information is not displayed below to log in to your account.');

			// lets check if they completed step4 if not, add it.
			if ( !($clientdetails->TProperties->completed_steps_bitwise & step4) )
			{
				$updateFields['completed_steps_bitwise'] = @$clientdetails->TProperties->completed_steps_bitwise | step4;
				// we need to update there status to Products Selected
			}
			else
			{
				// they are most likely updating there order. So anything that needs to happen here, should.
			}

			/**
			 * @todo verify function is working @ 5am
			 */
			$paid_products = $this->System_GetPaidProducts();


			// lets see what they ordered
			$Ordered = array();
			// alright lets find out what they ordered
			$cart_items = 0;


			//items for re-payment (already consolidated)
			if (Input::get('repayment_promisory_note') && !$paid_products['repayment_app'])
			{
				$Ordered[] = 'repayment_promisory_note';
				$cart_items |= cart_product_repayment_app;
			}

			//items for consolidation forms
			if (Input::get('consolidation_promisory_note') && !$paid_products['consolidation_app'])
			{
				$Ordered[] = 'consolidation_promisory_note';
				$cart_items |= cart_product_consolidation_app;
			}

			if (Input::get('pslf_app') && !$paid_products['pslf_app'])
			{
				$Ordered[] = 'pslf_app';
				$cart_items |= cart_product_pslf_app;
			}

			if (Input::get('forebearance_app') && !$paid_products['forebearance_app'])
			{
				$Ordered[] = 'forebearance_app';
				$cart_items |= cart_product_forebearance_app;
			}

			if (Input::get('recertification_app') && !$paid_products['recertification_app'])
			{
				$Ordered[] = 'recertification_app';
				$cart_items |= cart_product_recertification_app;
			}
			Session::put('cart_type', 'order');

			// check if there order is nothing. if it's nothing, we need to send them back to select a form.
			if ($cart_items == 0 && !$clientdetails->TProperties->products_already_purchased)
			{
				return	$this->RedirectWithError('step4services', ' You must select an application you wish to have generated for you.');
			}

			if ($cart_items == 0 && $clientdetails->TProperties->products_already_purchased)
			{
				// we're not even ordering we are updating only.
				// we need to move them to upgrading
				// we need to tell the cart its an update
				Session::put('cart_type', 'update');

				if ($clientdetails->TProperties->products_already_purchased & cart_product_consolidation_app || $clientdetails->TProperties->products_already_purchased & cart_product_repayment_app || $clientdetails->TProperties->products_already_purchased & cart_product_recertification_app )
				{
					return Redirect::to('/step5');
				}
				else
				{
					return Redirect::to('/updatesuccess');
				}
			}
			else
			{
				// we're placing an order, let's record that these are the products we're buying.
				$updateFields['cart_items'] = $cart_items;
				$updateFields['cart_id'] = uniqid();
				$updateFields['cart_steps_required'] = 0;

				if ($cart_items & cart_product_consolidation_app || $cart_items & cart_product_repayment_app || $cart_items & cart_product_recertification_app )
					$updateFields['cart_steps_required'] = step1 | step2 | step3 | step4 | step5 | step6 | step7;

				else
					$updateFields['cart_steps_required'] = step1 | step2 | step3 | step4 | step7;

				$updateFields['cart_order_time'] = time();
			}

			if ( !($clientdetail->TProperties->completed_steps_bitwise & step4) )
			{
				$updateFields['completed_steps_bitwise'] = @$clientdetail->TProperties->completed_steps_bitwise | step4;
			}
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);


			$updateFields = $this->System_Setup_Default_Payment_From_Cart_Items(false, true);

			if (count($updateFields) && is_array($updateFields))
			{
				$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);
			}

			// redirect them to our Step Finder

			if ($cart_items & cart_product_consolidation_app || $cart_items & cart_product_repayment_app || $cart_items & cart_product_recertification_app )
			{
				## Return the the fasfa pin
				Session::put('checkout_title', STEP7_TITLE);
				return Redirect::to('step5');
			}
			else
			{
				Session::put('checkout_title',  STEP7b_TITLE);
				// redirect them to checkout.
				return Redirect::to('step7');
			}

		}

	}

	public function UpgradeOnly()
	{
		$this->layout->content .= View::make('upgradingonly');
	}

	public function Step4Services()
	{
		if (!$this->IsAuthencated())
		{
			return $this->RedirectNotAuthed();
		}
		$fileNumber = Session::get('fileNumber');
//		$Client = $this->leadtracapi->GetClient($fileNumber, array('Loan_Program', 'PublicService', 'Paid_Products'));
		$Client = $client = $clientDetails = $clientdetail = $clientdetails = $this->GetClientFromSession();


		// check if they are allowed to place an order.
		if ($this->Check_If_Client_Has_Pending_Orders())
		{
			return $this->RedirectWithError('/processpayment', "You have already placed an order with us and we are still processing. You cannot change your order until your previous order has been completed.");
		}
		else
		{
			// let them choose the services they can order.


		}
		// check what products they are allowed to purchase

		// check what products they have already purchased and prevent them from un-checking them.




		$this->layout->content .= View::make('step4services', array(
			'NewPayment' => 2,
			'purchased' => array(
				'consolidation_app' => ($Client->TProperties->products_already_purchased & cart_product_consolidation_app ? true : false),
				'repayment_app' => ($Client->TProperties->products_already_purchased & cart_product_repayment_app ? true : false),
				'recertification_app' => ($Client->TProperties->products_already_purchased & cart_product_recertification_app ? true : false),
				'pslf_app' => ($Client->TProperties->products_already_purchased & cart_product_pslf_app ? true : false),
				'forebearance_app' => ($Client->TProperties->products_already_purchased & cart_product_forebearance_app ? true : false),
				),
			'cart_items' => $Client->TProperties->cart_items,

			'PublicService' => true,
			'HasDefaulted' => false,
			'HasForbearance' => false,
		));


	}

	public function CartIsPending()
	{
		if (!$this->IsAuthencated())
		{
			return $this->RedirectNotAuthed();
		}
		$fileNumber = Session::get('fileNumber');
//		$Client = $this->leadtracapi->GetClient($fileNumber, array('Loan_Program', 'PublicService', 'Paid_Products'));
		$Client = $client = $clientDetails = $clientdetail = $clientdetails = $this->GetClientFromSession(false);


		$data = array();
		$this->layout->content .= View::make('cart_pending', $data);
	}


	public function SelectForms()
	{
		return $this->Step4Services();
	}

//	public function SelectForms()
//	{
//		if (!$this->IsAuthencated())
//		{
//			return $this->RedirectNotAuthed();
//		}
//		$fileNumber = Session::get('fileNumber');
//		$Client = $this->leadtracapi->GetClient($fileNumber, array('Loan_Program', 'PublicService', 'Paid_Products'));
//
//		// check if they have there cart already pending and redirect them to the forms if they want.
//		if (isset($Client->TProperties->cart_status) && $Client->TProperties->cart_status)
//		{
//			// check if it's not set to pending.
//			if ($Client->TProperties->cart_status == 'pending' && $Client->TProperties->cart_items)
//			{
//
//			}
//		}
//
//
//		if (isset($Client->TProperties->products_already_purchased) && $Client->TProperties->products_already_purchased)
//		{
//
//		}
//
//		// we do it via the paid products variable
//		// lets not use the status any more
////		$this->TranslateOrderFromStatus($Client->Status->Sales, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance, $ordered_recertification);
//
//
//
//		Session::put('ordered_cpn',$ordered_cpn);
//		Session::put('ordered_repayment_promisory_note',$ordered_repayment_promisory_note);
//		Session::put('ordered_pslf',$ordered_pslf);
//		Session::put('ordered_forebearance',$ordered_forebearance);
//		Session::put('ordered_recertification',$ordered_recertification);
//
//		$paidfor_variable = 'Paid_Packages';
//
//
//
//		// if they ordered anything i need to know & check for payments or halt them here.
//		if ($ordered_recertification || $ordered_cpn || $ordered_repayment_promisory_note || $ordered_pslf || $ordered_forebearance)
//		{
//			// they ordered something
//			$ordered_something = true;
//			$hasPaid = false;
//			$hasPaid = $this->CheckPaymentUpdate(true);
//
//			if (!$hasPaid)
//			{
//				// check if they declined, if so, remove this status from them, put them back to Quoted.
//				$FailedArray = array('Payment Failure', 'Payment Declined', 'Canceled - Refund Pending', 'Canceled - Refund Complete');
//				//				die(print_r($Client->Status));
//
//				if (!isset($Client->Status->Accounting) || in_array($Client->Status->Accounting, $FailedArray))
//				{
//					// What have they actually paid for previously?
//					// Stay tuned, more coming at you soon!
//					if ($Client->TProperties->Paid_Products)
//					{
//						// alright its in json format
//						$products = @json_decode($Client->TProperties->Paid_Products);
//
//						if ($products->cpn || $products->rc || $products->rpn || $products->pslf || $products->fb)
//						{
//							// alright we have a product, lets get the status from these.
//							$SwitchStatus = $this->TranslateStatusFromOrder($Client->TProperties->Loan_Program, $products->cpn, $products->rpn, $products->pslf, $products->fb, $products->rc, $newStatus);
//
//							die("Status changed: ".$SwitchStatus." newstatus:".$newStatus);
//
//							if ($SwitchStatus)
//							{
//								die('Switch the status!');
//							}
//							else
//							{
//								//die back to quoted
//								die('Back to quoted');
//							}
//						}
//					}
//					// move them to quoted if there is nothing for them to see here.
//					else
//					{ // they haven't paid for anything, so status is Quoted.
//						if (isset($this->statusUpdates['all']->Quoted))
//						$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['all']->Quoted);
//
//						//redirect the page back here.
//						return Redirect::to('select_forms');
//					}
//				}
//				// stop them from seeing this page!
//
//				// if they are at 100% Payment Complete, then we have a bug! Attempt to fix it.
//				if ($Client->Status->Accounting == '100% Payments Received')
//				{
//					// see what the logs say? lol
//					$logs = $this->leadtracapi->GetPaymentStatusFromLogs($Client->FileNumber);
//
//					// if they didn't pay, they didn't pay!
//					// they are errored out, lets change the status back to false.
//					if ($logs->Log->Searched)
//					{
//						if ($logs->Log->Status != 'Approved')
//						{
//							$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['all']->Quoted);
//							$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['all']->{"Payment Declined"});
//							// redirect them back to step 6
//							return Redirect::to('select_forms');
//						}
//					}
//
//
//				}
//				else
//				{
//
//					//else move them to quoted!
//					$this->PaymentPendingWait();
//				}
//
//			}
//			else {
//				// hay, you paid. have we saved this ?
//				// lets update their paid_products to the current status
//
//				$this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('Paid_Products' => json_encode(array('cpn' => $ordered_cpn, 'rpn' => $ordered_repayment_promisory_note, 'pslf' => $ordered_pslf, 'fb' => $ordered_forebearance, 'rc' => $ordered_recertification))), $fileNumber);
//
//			}
//		}
//
//		//			$Dontshow_step6 = true;
//
//		// Which Forms can they order?
//		//Temp, lets show them all the forms.
//
//		$this->layout->content .= View::make('select_forms', array(
//		'NewPayment' => 2,
//		'PublicService' => true,
//		'HasDefaulted' => false,
//		'HasForbearance' => false,
//		));
//
//	}

	/**
	 *
	 * This is actually the step4 post. It's for selecting what you wish to purchase, but it also grabs what's already been purchased.
	 * @return mixed
	 */
	/**
	 * public function SelectFormsPosted()
	 */
//	public function SelectFormsPosted()
//	{
//		if (!$this->IsAuthencated())
//		{
//			return $this->RedirectNotAuthed();
//		}
//
//		Session::forget('order');
//		Session::forget('temp_order');
//		Session::forget('ClientUpgrade');
//		Session::forget('ordered_repayment_promisory_note');
//		Session::forget('ordered_recertification');
//		Session::forget('ordered_pslf');
//		Session::forget('ordered_forebearance');
//		Session::forget('ORDER_COMPLETE_SET_STATUS');
//		Session::forget('SelectedPlan');
//
//		$Ordered = array();
//
//		// alright lets find out what they ordered
//
//		//items for re-payment (already consolidated)
//		if (Input::get('repayment_promisory_note'))
//		$Ordered[] = 'repayment_promisory_note';
//
//		//items for consolidation forms
//		if (Input::get('consolidation_promisory_note'))
//		$Ordered[] = 'consolidation_promisory_note';
//
//		if (Input::get('repayment_promisory_note_1'))
//		$Ordered[] = 'repayment_promisory_note_1';
//
//		//items for consolidation forms
//		if (Input::get('consolidation_promisory_note_1'))
//		$Ordered[] = 'consolidation_promisory_note_1';
//
//		if (Input::get('pslf_app'))
//		$Ordered[] = 'pslf_app';
//
//		if (Input::get('forebearance_app'))
//		$Ordered[] = 'forebearance_app';
//
//		if (Input::get('recertification_app'))
//		$Ordered[] = 'recertification_app';
//
//		if (!count($Ordered) && !(Session::get('ordered_cpn') || Session::get('ordered_repayment_promisory_note') || Session::get('ordered_recertification') || Session::get('ordered_pslf') || Session::get('ordered_forebearance')))
//		{
//			// flash the session
//			return	$this->RedirectWithError('select_forms', ' You must select an application you wish to have generated for you.');
//
//			//			$messages = new Illuminate\Support\MessageBag;
//			//			$messages->add('* Error ', ' Error: You must select an application you wish to have generated for you.');
//
//			//			return Redirect::to('select_forms')->withErrors($messages);
//		}
//
//		Session::put('temp_order', $Ordered);
//
//
//		//Recertification or PSLF or Forbearance
//
//		if (Input::get('consolidation_promisory_note') || Input::get('recertification_app'))
//		{
//			## Return the the fasfa pin
//			Session::put('checkout_title', STEP7_TITLE);
//			return Redirect::to('step4');
//		}
//		else
//		{
//			Session::put('order', $Ordered);
//			Session::put('checkout_title',  STEP7b_TITLE);
//			### They have completed all of the nessecary things, let's push them to the finish line now.
//
//
//			// need to calculate the loan totals.
//
//
//
//			return $this->Step6Form();
//		}
//
//	}




	/** Fasfa PIN **/
	public function step5()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$this->layout->content .= View::make('client_step5');
	}

	/**
	 * step5 actually
	 * @return mixed
     */
	public function Step5Form()
	{
		if (!$this->IsAuthencated())
			return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');
		$clientdetails = $this->GetClientFromSession(true);

		$ValidationRules = array(
//			'username' => 'required',
//			'password' => 'required',
			'file' => ''
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);

		if ($Validate->fails())
			return Redirect::to('step5')->withErrors($Validate);

		// $fafsapin = Input::get('fafsa_pin');
//		$username = Input::get('username');
//		$password = Input::get('password');


		// Load the Login page


		$file = Input::file('file');
		if ($file) {

			$fileName = $file->getClientOriginalName();
			$allowed_file_types = array('txt', 'png', 'jpeg', 'gif', 'doc', 'pdf');

			$file_extention_array = @explode('.', $fileName);
			$file_ext = strtolower(end($file_extention_array));

			if (!in_array($file_ext, $allowed_file_types)) {
				$messages = new Illuminate\Support\MessageBag;
				$messages->add('* Error ', ' The file you have uploaded is not a supported file type. Please try again.');

				return Redirect::to('step5')->withErrors($messages);
			}

			$imagedata = file_get_contents($file);
			$assetBytes = base64_encode($imagedata);
			$assetsList = $this->leadtracapi->GetAssetTypesList();
			$assetsLists = $assetsList->AssetTypes->AssetType;
			foreach ($assetsLists as $assetName => $assetId) {
				if ($assetId->Name == 'Student Loan File') {
					$assetTypeId = $assetId->Id;
					$assetFileName = $fileName;
					$assetDescription = 'Student Loan File Uploaded';
					// $assetBytes = 'VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==';
					$assetsResult = $this->leadtracapi->AddAssets($fileNumber, $assetTypeId, $assetFileName, $assetDescription, $assetBytes);
					// echo $assetsResult->AddAssetResult->FileName;
				}
			}


		} else {
			// file upload is ignored , lets login.
//			require_once dirname(__FILE__).'/../classes/simplehtml.php';

//			$loginPage = file_get_html("https://www.nslds.ed.gov/npas/index.htm");

//			$loginPage = new Curl();
//			$loginPage->setCookieFile(storage_path() . '/'.Session::get('fileNumber'));
//			$loginPage->setCookieJar(storage_path() . '/'.Session::get('fileNumber'));

//			$content = $loginPage->get('https://www.nslds.ed.gov/npas/index.htm');


//			$loginPageContent = str_get_html($content);

//			$inputboxes = $loginPageContent->find('input [type="hidden"]');

//			foreach($inputboxes as $inputbox)
//			{
//
//				$name = $inputbox->name;
//				$value = $inputbox->value;
//
//				echo("name: $name value: $value <br/>");
//
//			}
//
//			$this->layout->content = $inputboxes->innerHTML;
		}

		// exit;
		$updateFields = array();
		// $updateFields['DOEPin'] = $fafsapin;
//		$updateFields['ClientLogin'] = $username;
//		$updateFields['ClientPassword'] = $password;

		$updateFields[$this->tracking_field_name] = $this->tracking_field_value;

		// lets check if they completed step4 if not, add it.
		if ( !($clientdetails->TProperties->completed_steps_bitwise & step5) )
		{
			$updateFields['completed_steps_bitwise'] = @$clientdetails->TProperties->completed_steps_bitwise | step5;
			// we need to update there status to Products Selected
		}

		//this script can take up to 5 minutes?
		@set_time_limit(300); // set the time limit to 5 minutes to prevent it from timing out.
		@ini_set('max_execution_time', 300);
		ini_set('max_input_time',300);


		// if ($fafsapin)
		// {
		if ($fileNumber)
		{
			// ClientLogin, ClientPassword
			$UpdateStatus = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);

			if ($UpdateStatus !== false)
			{

				/**
					 * Client completed step4. move them to Step 5.
					 */
//				$clientdetails = $this->leadtracapi->getClient($fileNumber, array('FirstName', 'LastName'));
				$client = $clientDetails = $clientdetail = $clientdetails = $this->GetClientFromSession(true);


				// echo $this->statusUpdates['step4'].'<br />';
				// echo $this->statusUpdates['import_loans'].'<br />';
				// echo $this->statusUpdates['import_nslds_quote'].'<br />';
				// exit;
				if ($clientdetails->CompletedStep >= 4)
				{
					//check if we need ot change them to FAFSFA Pin (if its not already at step four.
					if ($clientdetails->CompletedStep <= 5)
					$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step5']);

					// Now we need to Set the status to import the Loans.
					$response = $this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['import_loans']);
					// var_dump($response);
					//check if the import was successful
					if ($response !== false)
					{
						//Now we need to run the Quote. We need to use the NSLDS Status (Capture Quote) to import the Quote.
						$get_quote = $this->leadtracapi->ChangeClientStatus($fileNumber,$this->statusUpdates['import_nslds_quote']);
					}
					else // show them the error message.
					{
						//move their status back
						//$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['doe_idle']);
						$messages = new Illuminate\Support\MessageBag;
						$messages->add('* Error ', $this->leadtracapi->error);
						// change step5 to step6 for move steps
						return Redirect::to('step5')->withErrors($messages);
					}
				}

				//update successful.
				return Redirect::to('step6')->with('mode', '1');
			}
		}
		// }
	}

	public function FormUpdateStatus()
	{
		//make sure they have an open session id.
		$fileNumber = Session::get('fileNumber');
		$updateStatus = Input::get('status');

		if ($fileNumber && $updateStatus)
		{
			// lets get ready to change the clients status
			$new_status_id = false;
			switch (strtolower($updateStatus))
			{
				case 'fafsaduppin':
					$new_status_id = $this->statusUpdates['request_duplicate_pin'];
					break;
				case 'fafsanewpin':
					$new_status_id = $this->statusUpdates['request_new_pin'];
					break;
			}

			if ($new_status_id)
			{
				//We have a new status id, is there cases where we shouldnt allow them to use this to change there status? probably if they haven't reached at least step 3.
				// for now, we will do it directly.
				$this->leadtracapi->ChangeClientStatus($fileNumber, $new_status_id);
				return '1';
			}

		}
		return '0';
	}

	public function StepShowLoans()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');
		//$fileNumber = 'DY172.894724';

		if ($fileNumber)
		{
			$Loans = $this->leadtracapi->GetAccountLoans($fileNumber);
			$PaymentPlans = $this->leadtracapi->GetPaymentPlans($fileNumber);

			//			die(print_r($PaymentPlans,1));

			if (!$Loans || (!isset($Loans->Loans) || !count($Loans->Loans)))
			{
				$messages = new Illuminate\Support\MessageBag;
				$messages->add('* Error ', "<b>ERROR</b> We couldn't find any eligible loans.");

				return Redirect::to('step4')->withErrors($messages);
			}
			//			return View::make('client_step5', array('LoanData' => $Loans));
			$this->layout->content .= View::make('client_showloans', array('LoanData' => $Loans, 'Plans' => $PaymentPlans, 'LoanType' => ''));
		}
//		$this->layout->content .= View::make('client_step6', array('LoanData' => array()));

	}

	public function StepShowLoansForm()
	{
		return Redirect::to('step6');
	}

	/** Step 6, Loans & Stuff **/
	public function Step6()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');
		// $fileNumber = 'DY388.198110';

		if ($fileNumber)
		{
			$Loans = $this->leadtracapi->GetAccountLoans($fileNumber);


			$PaymentPlans = $this->leadtracapi->GetPaymentPlans($fileNumber);
			// echo '<pre>';
			// print_r($Loans);
			// echo '</pre>';exit;
//						die(print_r($Loans,1));

			if (!$Loans)
			{
				$messages = new Illuminate\Support\MessageBag;
				$messages->add('* Error ', "<b>ERROR</b> Sorry, we couldn't find any loans for your account that would be eligible. Please contact us");
				return;
				return Redirect::to('step5')->withErrors($messages);
			}

			// return View::make('client_step5', array('LoanData' => $Loans));

//						$Client = $this->leadtracapi->GetClient($fileNumber, array('Loan_Program', 'PublicService', 'Paid_Products'));
			$client = $this->GetClientFromSession();


			//load the data from step6, so the cart be here.
			$cartdata['LoanData'] = $Loans;
			$cartdata['Plans'] = $PaymentPlans;
			$cartdata['LoanType'] = '';
			$cartdata['Loan_Program'] = $client->TProperties->Loan_Program;
			$cartdata['client'] = $client;
			$cartdata['Loan_Program_Abbr'] = $this->system_get_loan_program_abbr($client->TProperties->Loan_Program);

			$this->layout->content .= View::make('client_step6', $cartdata);
		}

	}

	public function Step6Form()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		// They have submitted a plan.
		$fileNumber = Session::get('fileNumber');

		$clientdetails = $this->GetClientFromSession(true);

		//$fileNumber = 'DY172.894724';
		if ($fileNumber)
		{
			// See what plan they submitted for.
			$SelectedPlan = Input::get('repayment_plan');
			Session::put('SelectedPlan', $SelectedPlan);
			$UpdateFields = array('Loan_Program' => '');
			//We need to update the system with the provided repayment plan.
			switch (strtolower($SelectedPlan))
			{
				case 'income_contingent':
					$UpdateFields['Loan_Program'] = 'Income Contingent';
					break;

				case 'income_based':
					$UpdateFields['Loan_Program'] = 'Income-Based';
					break;

				case 'pay':
					$UpdateFields['Loan_Program'] = 'Pay As You Earn';
					break;

				case 'standard':
					$UpdateFields['Loan_Program'] = 'Standard';
					break;

				case 'graduated':
					$UpdateFields['Loan_Program'] = 'Graduated';
					break;

				case 'extended_repayment_plan':
					$UpdateFields['Loan_Program'] = 'Extended Graduated';
					break;

				default:

					$messages = new Illuminate\Support\MessageBag;
					$messages->add('* Error ', "<b>ERROR</b> You must select one of the repayment plans below!");

					return Redirect::to('step6')->withErrors($messages);
					break;
			}
			$UpdateFields[$this->tracking_field_name] = $this->tracking_field_value;
			//lets update this client

			// lets check if they completed step6 if not, add it.
			if ( !($clientdetails->TProperties->completed_steps_bitwise & step6) )
			{
				$UpdateFields['completed_steps_bitwise'] = @$clientdetails->TProperties->completed_steps_bitwise | step6;
				$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['Program Selected']);
			}


			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $UpdateFields, $fileNumber);

			// Let's check if we are updating or if this is to checkout?
			if (Session::get('cart_type') == 'update')
			return Redirect::to('/updatesuccess');

			// Send them to Step 7. now they can checkout
			return Redirect::to('step7');
		}

	}

	public function system_get_loan_program_abbr($Loan_Program)
	{
		$Plan = "";
		switch (strtolower($Loan_Program))
		{
			case 'pay as you earn':
				$Plan = 'PAY';
				break;
			case 'income_based':
			case 'income-based':
				$Plan = 'IBR';
				break;
			case 'income contingent':
				$Plan = 'ICR';
				break;
			case 'standard':
				$Plan = 'STD';
				break;
			case 'graduated':
				$Plan = 'GRAD';
				break;

			case 'extended fixed':
				$Plan = 'EXF';
				break;
			case 'extended graduated':
				$Plan = 'EXG';
				break;
		}

		return $Plan;
	}

	/**
	 * Step 6. Viewing the Plan they selected & Giving them the option to order the Products.
	 */
	public function Step6xxx()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');
		//$fileNumber = 'DY172.894724';
		// exit;

		if ($fileNumber)
		{
			// Lets get the Program they selected, is there any other information we might need?
			$Client = $this->leadtracapi->GetClient($fileNumber, array('Loan_Program', 'PublicService', 'Paid_Products'));


			// check if they have paid for anything


			// if they have seen this status, change their progress to Quoted
			// BUT check if they are at program selected first.
			if ($Client->CompletedStep <= 5)
			$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step5']);


			//We need to import their loans so we can tell if they are seeing the Consolidated or the Repayment options
			$Loans = $this->leadtracapi->GetAccountLoans($fileNumber);

			switch (strtolower($Client->TProperties->Loan_Program))
			{
				case 'pay as you earn':
					$Plan = 'PAY';
					break;
				case 'income-based':
					$Plan = 'IBR';
					break;
				case 'income contingent':
					$Plan = 'ICR';
					break;
				case 'standard':
					$Plan = 'STD';
					break;
				case 'graduated':
					$Plan = 'GRAD';
					break;

				case 'extended fixed':
					$Plan = 'EXF';
					break;
				case 'extended graduated':
					$Plan = 'EXG';
					break;
			}

			// Lets get the Programs, easiest way is to use the GetPaymentPlans
			$PaymentPlans = $this->leadtracapi->GetPaymentPlans($fileNumber);


			if (isset($PaymentPlans->{$Plan}))
			{
				// Their new monthly repayment amount is $PaymentPlans->{$Plan}->Payment
				if (isset($PaymentPlans->{$Plan}->Payment))
				$NewPayment = @number_format($PaymentPlans->{$Plan}->Payment,2);

			}

			//load up the status session
			$this->TranslateOrderFromStatus($Client->Status->Sales, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance, $ordered_recertification);
			Session::put('ordered_cpn',$ordered_cpn);
			Session::put('ordered_repayment_promisory_note',$ordered_repayment_promisory_note);
			Session::put('ordered_pslf',$ordered_pslf);
			Session::put('ordered_forebearance',$ordered_forebearance);
			Session::put('ordered_recertification',$ordered_recertification);

			$paidfor_variable = 'Paid_Packages';



			// if they ordered anything i need to know & check for payments or halt them here.
			if ($ordered_recertification || $ordered_cpn || $ordered_repayment_promisory_note || $ordered_pslf || $ordered_forebearance)
			{
				// they ordered something
				$ordered_something = true;
				$hasPaid = false;
				$hasPaid = $this->CheckPaymentUpdate(true);

				if (!$hasPaid)
				{
					// check if they declined, if so, remove this status from them, put them back to Quoted.
					$FailedArray = array('Payment Failure', 'Payment Declined', 'Canceled - Refund Pending', 'Canceled - Refund Complete');
					if (in_array($Client->Status->Accounting, $FailedArray))
					{
						// What have they actually paid for previously?
						// Stay tuned, more coming at you soon!
						if ($Client->TProperties->Paid_Products)
						{
							// alright its in json format
							$products = @json_decode($Client->TProperties->Paid_Products);

							if ($products->cpn || $products->rc || $products->rpn || $products->pslf || $products->fb)
							{
								// alright we have a product, lets get the status from these.
								$SwitchStatus = $this->TranslateStatusFromOrder($Client->TProperties->Loan_Program, $products->cpn, $products->rpn, $products->pslf, $products->fb, $products->rc, $newStatus);

								die("Status changed: ".$SwitchStatus." newstatus:".$newStatus);

								if ($SwitchStatus)
								{
									die('Switch the status!');
								}
								else
								{
									//die back to quoted
									die('Back to quoted');
								}
							}
						}
						// move them to quoted if there is nothing for them to see here.
						else
						{ // they haven't paid for anything, so status is Quoted.
							if (isset($this->statusUpdates['all']->Quoted))
							$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['all']->Quoted);

							//redirect the page back here.
							return $this->redirectwitherror('select_forms', 'The status has been changed to quoted.');

						}
					}
					// stop them from seeing this page!

					// if they are at 100% Payment Complete, then we have a bug! Attempt to fix it.
					if ($Client->Status->Accounting == '100% Payments Received')
					{
						// see what the logs say? lol
						$logs = $this->leadtracapi->GetPaymentStatusFromLogs($Client->FileNumber);

						// if they didn't pay, they didn't pay!
						// they are errored out, lets change the status back to false.
						if ($logs->Log->Searched)
						{
							if ($logs->Log->Status != 'Approved')
							{
								$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['all']->Quoted);
								$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['all']->{"Payment Declined"});
								// redirect them back to step 6
								return $this->redirectwitherror('select_forms', 'The status is not approved');

							}
						}


					}
					else
					{

						//else move them to quoted!
						$this->PaymentPendingWait();
						$Dontshow_step6 = true;
					}

				}
				else {
					// hay, you paid. have we saved this ?
					// lets update their paid_products to the current status

					$this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('Paid_Products' => json_encode(array('cpn' => $ordered_cpn, 'rpn' => $ordered_repayment_promisory_note, 'pslf' => $ordered_pslf, 'fb' => $ordered_forebearance, 'rc' => $ordered_recertification))), $fileNumber);

				}
			}

			//			$Dontshow_step6 = true;

			// Which Forms can they order?
			//Temp, lets show them all the forms.

			$this->layout->content .= View::make('client_step6', array('LoanType' => strtolower($Loans->Plan),
			'NewPayment' => $NewPayment,
			'Plan' => $Plan,
			'PublicService' => (isset($Client->TProperties->PublicService) && strtolower($Client->TProperties->PublicService) == 'yes' ? true : false ),
			'HasDefaulted' => $Loans->HasDefaulted,
			'HasForbearance' => $Loans->HasForbearance,
			));
			//			}
			//			else
			//			{
			//				return 	array('LoanType' => strtolower($Loans->Plan),
			//				'NewPayment' => $NewPayment,
			//				'Plan' => $Plan,
			//				'PublicService' => (isset($Client->TProperties->PublicService) && strtolower($Client->TProperties->PublicService) == 'yes' ? true : false ),
			//				'HasDefaulted' => $Loans->HasDefaulted,
			//				'HasForbearance' => $Loans->HasForbearance);
			//
			//			}


		}

	}
	public function PaymentPendingWait()
	{
		$this->layout->content .= View::make('client_payment_pending_wait');

	}

	public function S1tep6Form9()
	{


		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		//Handle the Form Content
		$fileNumber = Session::get('fileNumber');
		//$fileNumber = 'DY172.894724';
		// at the moment, lets just imagine that we are in a perfect world and the post data will come as expected.
		// they must select at-least ONE option here. Or we place an error in the Session ERROR_STEP6 telling them they need to select at-least 1 option.

		$Ordered = array();

		// alright lets find out what they ordered

		Session::put('order', $Ordered);
		$ClientData = array();

		// we need to calculate the total program costs.
		$CartItems = $Ordered;


		$CartTotal = 0;

		$ordered_cpn = false;
		$ordered_repayment_promisory_note = false;
		$ordered_forebearance = false;
		$ordered_pslf = false;
		$ordered_recertification  = false;
		//		 $CartItems = array("consolidation_promisory_note_1","consolidation_promisory_note","repayment_promisory_note_1","repayment_promisory_note","forebearance_app","pslf_app");
		foreach ($CartItems as $CartItem)
		{
			switch (strtolower($CartItem))
			{
				// _1 is for the cheap version. (DISCOUNTED)
				case 'consolidation_promisory_note_1':
					$CartTotal+= PRICE_CONSOLIDATION_NOTE;
					$ordered_cpn = true;
					break;
					// case 'consolidation_promisory_note':
					// $CartTotal+= 179.00;
					// $ordered_cpn = true;
					// break;
				case 'consolidation_promisory_note':
					$CartTotal+= PRICE_CONSOLIDATION_NOTE;
					$ordered_cpn = true;
					break;
				case 'repayment_promisory_note_1':
					$CartTotal+= PRICE_REPAYMENT_NOTE;
					$ordered_repayment_promisory_note = true;
					break;
					// case 'repayment_promisory_note':
					// $CartTotal+= 179.00;
					// $ordered_repayment_promisory_note = true;
					// break;
				case 'repayment_promisory_note':
					$CartTotal+= PRICE_REPAYMENT_NOTE;
					$ordered_repayment_promisory_note = true;
					break;
				case 'forebearance_app':
					//					$CartTotal += 9.99;
					$CartTotal += PRICE_FOREBEARANCE_APP;
					$ordered_forebearance = true;
					break;
				case 'pslf_app':
					$CartTotal += PRICE_PSLF_APP;
					//					$CartTotal += 9.99;
					$ordered_pslf = true;
					break;

				case 'recertification_app':
					$CartTotal += PRICE_RECERTIFICATION_APP;
					//					$CartTotal += 9.99;
					$ordered_recertification = true;
					break;

			}
		}

		if(in_array("repayment_promisory_note",$CartItems))
		{
			Session::put('ordered_repayment', 'yes');
		}
		else{
			Session::put('ordered_repayment', '');
		}







		if (0 >= $CartTotal && !(Session::get('ordered_cpn') || Session::get('ordered_recertification') || Session::get('ordered_repayment_promisory_note') || Session::get('ordered_pslf') || Session::get('ordered_forebearance')))
		{
			//Send them back to Step6 with an error
			Session::flash('ERROR_STEP6', 'Error: You must select an application you wish to have generated for you. 1');
			return $this->RedirectWithError('select_form', 'You must select an application you wish to have generated for you. 1');

		}
		// alright lets set the ProgramPriceOverride
		$ClientData['Loan_ContractFee_Override'] = $CartTotal;
		$ClientData['Payment1Amount'] = $CartTotal;
		$ClientData['Payment1DueDate'] = date('m/j/Y');
		$ClientData['NoPayments'] = 1;
		$ClientData['StartDate'] = date('m/j/Y');
		$ClientData['Loan_ContractTotalFee'] = $CartTotal;
		$ClientData['Loan_ContractFee'] = $CartTotal;
		$ClientData['TotalPayments'] = $CartTotal;

		$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));


		// Alright here we are going to determin what status they get based on their order:
		// first, what plan did they select:
		$Client = $this->leadtracapi->GetClient($fileNumber, array('Loan_Program', 'PublicService'));
		//We need to import their loans so we can tell if they are seeing the Consolidated or the Repayment options

		$Loan_Program = strtolower($Client->TProperties->Loan_Program);

		// we need to update the variables based on what they already ordered
		/**
		 * @see  Update / Upgrade Clients
		 */
		//		if (Session::get('ordered_cpn'))
		//		$ordered_cpn = true;

		//		if (Session::get('ordered_repayment_promisory_note'))
		//		$ordered_repayment_promisory_note = true;

		//		if (Session::get('ordered_pslf'))
		//		$ordered_pslf = true;

		//		if (Session::get('ordered_forebearance'))
		//		$ordered_forebearance = true;

		//		if (Session::get('ordered_recertification'))
		//		$ordered_recertification = true;


		// need to get the new order status name, based on whats ordered.
		$order_statusName = $this->TranslateStatusFromOrder($Loan_Program,$ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance, $ordered_recertification, $newStatusId);
		// status number:





		if (isset($this->statusUpdates['all']->{$order_statusName}))
		{
			$newStatusId = $this->statusUpdates['all']->{$order_statusName};
			Session::put('ORDER_COMPLETE_SET_STATUS', $newStatusId);
		}

		else
		{
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('<b>System Error:</b> Unable to find the Status ID from your order. Status: ', @$order_statusName);

			return Redirect::to('step6')->withErrors($messages);
		}

		if (0 >= $CartTotal && (Session::get('ordered_cpn') || Session::get('ordered_repayment_promisory_note') || Session::get('ordered_pslf') || Session::get('ordered_forebearance')))
		{
			return Redirect::to('updatesuccess');
		}


		return Redirect::to('step7');

	}

	public function ShowCRM()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;

		//$clientinfo = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName',
		//	'ClientLogin','ClientPassword', 'client_city', 'client_region', 'client_zipcode'));

		$Client_Info = $this->GetClientFromSession(true);

		if ($Client_Info->TProperties->ClientLogin == "" && $Client_Info->TProperties->ClientPassword == "")
		{
			// we need to update there login.
			$data = array('ClientLogin' => $Client_Info->TProperties->EmailAddress, 'ClientPassword' => $Client_Info->TProperties->Name1SSN );
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $data, Session::get('fileNumber'));
			$Client_Info = $this->GetClientFromSession(true);
		}

		$this->layout->content .= View::make('crm_login',array('UserName' => $Client_Info->TProperties->ClientLogin,'Password' => $Client_Info->TProperties->ClientPassword));

	}

	public function UpdateClientDocument()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;

		$product_id = intval(Input::get('pid'));
		if ($product_id)
		{
			$this->System_UpdateClientDocument_By_Product($product_id);

			// success
			if (Request::Ajax()) return array('success' => true);
			else return "1";
		}

	}
	/**
	 * Page for the Updating saying success
	 */
	public function UpdateSuccess()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;

		$Client = $this->GetClientFromSession();
		$products = array(				'consolidation_app' => ($Client->TProperties->products_already_purchased & cart_product_consolidation_app ? true : false),
			'repayment_app' => ($Client->TProperties->products_already_purchased & cart_product_repayment_app ? true : false),
			'recertification_app' => ($Client->TProperties->products_already_purchased & cart_product_recertification_app ? true : false),
			'pslf_app' => ($Client->TProperties->products_already_purchased & cart_product_pslf_app ? true : false),
			'forebearance_app' => ($Client->TProperties->products_already_purchased & cart_product_forebearance_app ? true : false),);

		$this->layout->content .= View::make('upgradingonly', array('client' => $Client, 'ProductsOrdered' => $products));

//		try {
//			$this->layout->content .= View::make('client_thankyou', array('update_complete' => true));
//		} catch (Exception $e) {
//			die("Error: ".$e->getMessage());
//		}
//			return true;
	}
	/**
	 * Payment Information, also we'll
	 *  that they have an order? Don't need to though
	 *
	 */
	public function Step7()
	{

		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();


		// Make sure they are not pending a payment already, if so, prevent this page from loading.
		$ClientDetails = $this->GetClientFromSession(true);

		if ($this->Check_If_Client_Has_Pending_Orders() == true)
		{
			// they have pending orders, lets move them to /processpayment
			return $this->RedirectWithError('/processpayment', 'Error, you currently have a payment pending for a previous order. You must wait until that order has finalized to make any changes. Thank you.');

		}

		if ($ClientDetails->TProperties->cart_status != "")
		{

		}

//		$ClientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), Array('EmailAddress', 'FirstName', 'LastName', 'Payment1Amount', 'AddressLine1', 'City', 'State', 'ZipCode'));
		// calculate the payment data.

//		$this->System_Setup_Default_Payment_From_Cart_Items("");
//		$this->System_Setup_Default_Payment_From_Cart_Items_Without_Payment("");

		$CartTotal = $this->System_Get_Cart_Total();

		$ClientDetails = $this->GetClientFromSession();



		// Years to accomdate credit card expirations. Paypal recommends +19 years in the future.
		$exp_years = range(date('Y'), date('Y')+19);

		$country_array = array(
		"US" => "United States","GB" => "United Kingdom","CN" => "China","AT" => "Austria",
		"AF" => "Afghanistan","AL" => "Albania","DZ" => "Algeria","AS" => "American Samoa","AD" => "Andorra","AO" => "Angola","AI" => "Anguilla","AQ" => "Antarctica","AG" => "Antigua and Barbuda","AR" => "Argentina","AM" => "Armenia","AW" => "Aruba","AU" => "Australia",
		"AZ" => "Azerbaijan","BS" => "Bahamas","BH" => "Bahrain","BD" => "Bangladesh","BB" => "Barbados","BY" => "Belarus","BE" => "Belgium","BZ" => "Belize","BJ" => "Benin","BM" => "Bermuda","BT" => "Bhutan","BO" => "Bolivia","BA" => "Bosnia and Herzegovina","BW" => "Botswana","BV" => "Bouvet Island","BR" => "Brazil","BQ" => "British Antarctic Territory","IO" => "British Indian Ocean Territory","VG" => "British Virgin Islands","BN" => "Brunei","BG" => "Bulgaria","BF" => "Burkina Faso","BI" => "Burundi",
		"KH" => "Cambodia","CM" => "Cameroon","CA" => "Canada","CT" => "Canton and Enderbury Islands","CV" => "Cape Verde","KY" => "Cayman Islands","CF" => "Central African Republic","TD" => "Chad","CL" => "Chile","CX" => "Christmas Island","CC" => "Cocos [Keeling] Islands","CO" => "Colombia","KM" => "Comoros","CG" => "Congo - Brazzaville","CD" => "Congo - Kinshasa","CK" => "Cook Islands","CR" => "Costa Rica","HR" => "Croatia","CU" => "Cuba","CY" => "Cyprus","CZ" => "Czech Republic",
		"DK" => "Denmark","DJ" => "Djibouti","DM" => "Dominica","DO" => "Dominican Republic","NQ" => "Dronning Maud Land","DD" => "East Germany","EC" => "Ecuador","EG" => "Egypt","SV" => "El Salvador","GQ" => "Equatorial Guinea","ER" => "Eritrea","EE" => "Estonia","ET" => "Ethiopia","FK" => "Falkland Islands",
		"FO" => "Faroe Islands","FJ" => "Fiji","FI" => "Finland","FR" => "France","GF" => "French Guiana","PF" => "French Polynesia","TF" => "French Southern Territories","FQ" => "French Southern and Antarctic Territories","GA" => "Gabon","GM" => "Gambia",
		"GE" => "Georgia","DE" => "Germany","GH" => "Ghana","GI" => "Gibraltar","GR" => "Greece","GL" => "Greenland","GD" => "Grenada","GP" => "Guadeloupe","GU" => "Guam","GT" => "Guatemala","GG" => "Guernsey","GN" => "Guinea","GW" => "Guinea-Bissau",
		"GY" => "Guyana","HT" => "Haiti","HM" => "Heard Island and McDonald Islands","HN" => "Honduras","HK" => "Hong Kong SAR China","HU" => "Hungary","IS" => "Iceland","IN" => "India","ID" => "Indonesia","IR" => "Iran","IQ" => "Iraq","IE" => "Ireland","IM" => "Isle of Man","IL" => "Israel","IT" => "Italy","JM" => "Jamaica","JP" => "Japan","JE" => "Jersey","JT" => "Johnston Island","JO" => "Jordan","KZ" => "Kazakhstan","KE" => "Kenya","KI" => "Kiribati","KW" => "Kuwait","KG" => "Kyrgyzstan","LA" => "Laos","LV" => "Latvia","LB" => "Lebanon","LS" => "Lesotho","LR" => "Liberia","LY" => "Libya","LI" => "Liechtenstein","LT" => "Lithuania","LU" => "Luxembourg","MO" => "Macau SAR China","MK" => "Macedonia","MG" => "Madagascar","MW" => "Malawi","MY" => "Malaysia","MV" => "Maldives",
		"ML" => "Mali","MT" => "Malta","MH" => "Marshall Islands","MQ" => "Martinique","MR" => "Mauritania","MU" => "Mauritius","YT" => "Mayotte","FX" => "Metropolitan France","MX" => "Mexico","FM" => "Micronesia","MI" => "Midway Islands","MD" => "Moldova","MC" => "Monaco","MN" => "Mongolia","ME" => "Montenegro","MS" => "Montserrat","MA" => "Morocco","MZ" => "Mozambique","MM" => "Myanmar [Burma]","NA" => "Namibia","NR" => "Nauru","NP" => "Nepal","NL" => "Netherlands","AN" => "Netherlands Antilles","NT" => "Neutral Zone","NC" => "New Caledonia","NZ" => "New Zealand","NI" => "Nicaragua","NE" => "Niger","NG" => "Nigeria","NU" => "Niue","NF" => "Norfolk Island","KP" => "North Korea","VD" => "North Vietnam","MP" => "Northern Mariana Islands","NO" => "Norway","OM" => "Oman","PC" => "Pacific Islands Trust Territory","PK" => "Pakistan","PW" => "Palau","PS" => "Palestinian Territories","PA" => "Panama","PZ" => "Panama Canal Zone","PG" => "Papua New Guinea","PY" => "Paraguay",
		"YD" => "People's Democratic Republic of Yemen","PE" => "Peru","PH" => "Philippines","PN" => "Pitcairn Islands","PL" => "Poland","PT" => "Portugal","PR" => "Puerto Rico","QA" => "Qatar","RO" => "Romania","RU" => "Russia","RW" => "Rwanda","RE" => "R�union","BL" => "Saint Barth�lemy","SH" => "Saint Helena","KN" => "Saint Kitts and Nevis","LC" => "Saint Lucia","MF" => "Saint Martin","PM" => "Saint Pierre and Miquelon","VC" => "Saint Vincent and the Grenadines","WS" => "Samoa","SM" => "San Marino","SA" => "Saudi Arabia","SN" => "Senegal","RS" => "Serbia","CS" => "Serbia and Montenegro","SC" => "Seychelles","SL" => "Sierra Leone","SG" => "Singapore","SK" => "Slovakia","SI" => "Slovenia","SB" => "Solomon Islands","SO" => "Somalia","ZA" => "South Africa","GS" => "South Georgia and the South Sandwich Islands","KR" => "South Korea","ES" => "Spain","LK" => "Sri Lanka","SD" => "Sudan","SR" => "Suriname","SJ" => "Svalbard and Jan Mayen","SZ" => "Swaziland","SE" => "Sweden","CH" => "Switzerland","SY" => "Syria","ST" => "S�o Tom� and Pr�ncipe","TW" => "Taiwan","TJ" => "Tajikistan","TZ" => "Tanzania","TH" => "Thailand","TL" => "Timor-Leste","TG" => "Togo","TK" => "Tokelau","TO" => "Tonga","TT" => "Trinidad and Tobago","TN" => "Tunisia","TR" => "Turkey","TM" => "Turkmenistan","TC" => "Turks and Caicos Islands","TV" => "Tuvalu","UM" => "U.S. Minor Outlying Islands","PU" => "U.S. Miscellaneous Pacific Islands","VI" => "U.S. Virgin Islands","UG" => "Uganda","UA" => "Ukraine","SU" => "Union of Soviet Socialist Republics","AE" => "United Arab Emirates","UY" => "Uruguay","UZ" => "Uzbekistan","VU" => "Vanuatu","VA" => "Vatican City","VE" => "Venezuela","VN" => "Vietnam","WK" => "Wake Island","WF" => "Wallis and Futuna","EH" => "Western Sahara","YE" => "Yemen","ZM" => "Zambia","ZW" => "Zimbabwe","AX" => "�land Islands",);
		$repay =  Input::get("np");

		if ($repay == 1)
		{
			$this->layout->content .= View::make('client_step7_nopayment',  array('Repayment'=>$repay,'orderpay'=>false,'exp_years' => $exp_years, 'TotalDue' => $CartTotal,'EmailAddress' => $ClientDetails->TProperties->EmailAddress, 'AddressLine1' => $ClientDetails->TProperties->AddressLine1,'Firstname' =>  $ClientDetails->TProperties->FirstName, 'Lastname' => $ClientDetails->TProperties->LastName , 'City' => $ClientDetails->TProperties->City, 'State' =>  $ClientDetails->TProperties->State, 'Zipcode' =>  $ClientDetails->TProperties->ZipCode));
		}
		elseif ($repay == 2)
		{
			$this->layout->content .= View::make('client_step7_2payments',  array('Repayment'=>$repay,'orderpay'=>false,'exp_years' => $exp_years, 'TotalDue' => $CartTotal,'EmailAddress' => $ClientDetails->TProperties->EmailAddress, 'AddressLine1' => $ClientDetails->TProperties->AddressLine1,'Firstname' =>  $ClientDetails->TProperties->FirstName, 'Lastname' => $ClientDetails->TProperties->LastName , 'City' => $ClientDetails->TProperties->City, 'State' =>  $ClientDetails->TProperties->State, 'Zipcode' =>  $ClientDetails->TProperties->ZipCode));
		}
		elseif ($repay == 3)
		{
			$this->layout->content .= View::make('client_step7_3payments',  array('Repayment'=>$repay,'orderpay'=>false,'exp_years' => $exp_years, 'TotalDue' => $CartTotal,'EmailAddress' => $ClientDetails->TProperties->EmailAddress, 'AddressLine1' => $ClientDetails->TProperties->AddressLine1,'Firstname' =>  $ClientDetails->TProperties->FirstName, 'Lastname' => $ClientDetails->TProperties->LastName , 'City' => $ClientDetails->TProperties->City, 'State' =>  $ClientDetails->TProperties->State, 'Zipcode' =>  $ClientDetails->TProperties->ZipCode));
		}
		elseif ($repay == 'login')
		{
			$this->layout->content .= View::make('client_step7_officelogin',  array('Repayment'=>$repay,'orderpay'=>false,'exp_years' => $exp_years, 'TotalDue' => $CartTotal,'EmailAddress' => $ClientDetails->TProperties->EmailAddress, 'AddressLine1' => $ClientDetails->TProperties->AddressLine1,'Firstname' =>  $ClientDetails->TProperties->FirstName, 'Lastname' => $ClientDetails->TProperties->LastName , 'City' => $ClientDetails->TProperties->City, 'State' =>  $ClientDetails->TProperties->State, 'Zipcode' =>  $ClientDetails->TProperties->ZipCode));
		}
		elseif ($repay == 'logout')
		{
			Session::forget('auth_office_use');
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('* Error ', "<b>Success</b> You have logged out of office use!");

			return Redirect::to('step7')->withErrors($messages);

		}
		else
		{
			$this->layout->content .= View::make('client_step7', array('Repayment'=>$repay,'orderpay'=>false,'exp_years' => $exp_years, 'TotalDue' => $CartTotal,'EmailAddress' => $ClientDetails->TProperties->EmailAddress, 'AddressLine1' => $ClientDetails->TProperties->AddressLine1,'Firstname' =>  $ClientDetails->TProperties->FirstName, 'Lastname' => $ClientDetails->TProperties->LastName , 'City' => $ClientDetails->TProperties->City, 'State' =>  $ClientDetails->TProperties->State, 'Zipcode' =>  $ClientDetails->TProperties->ZipCode)); // AddressLine1
		}
	}

	/**
	 * Save the Payment information to their account.
	 * We are using Authorize.net, so, set the payment information!
	 */

	public function CheckoutWithNoPayment()
	{
		if (!$this->IsAuthencated())
			return $this->RedirectNotAuthed();

		$clientdata = $this->GetClientFromSession(true);

		$ValidationRules =     array(
			'Billing_first_name' => 'required',
			'Billing_last_name' => 'required',
			'Billing_address' => 'required',
			'Billing_city' => 'required',
			'Billing_country' => 'required',
			'Billing_state' => 'required',
			'Billing_zipcode' => 'required',
			'Billing_email' => 'required|email',

			// required if !sameasmailing
			'Mailing_Address_first_name' => 'required_without:sameasmailing',
			'Mailing_Address_last_name' => 'required_without:sameasmailing',
			'Mailing_Address' => 'required_without:sameasmailing',
			'Mailing_Address_city' => 'required_without:sameasmailing',
			'Mailing_Address_country' => 'required_without:sameasmailing',
			'Mailing_Address_state' => 'required_without:sameasmailing',
			'Mailing_Address_zipcode' => 'required_without:sameasmailing',
			'Mailing_Address_email' => 'required_without:sameasmailing|email',

			// credit card info
			'card_number' => 'required',
			'billing_name' => 'required',
			'expiration_month' => 'required',
			'expiration_year' => 'required',
			'cvv' => 'required',
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);

		if ($Validate->fails())
			return Redirect::to('step7')->withErrors($Validate);


		$ClientData = array(
			'PaymentType' => 'Office',
			'PaymentProcessor' => 'Office',

			'AccountFirstName' => Input::get('Billing_first_name'),
			'AccountLastName' => Input::get('Billing_last_name'),
			'AccountOwnerAddress' => Input::get('Billing_city'),
			'AccountOwnerCity' => Input::get('Billing_country'),
			'AccountOwnerState' => Input::get('Billing_state'),
			'AccountOwnerZipCode' => Input::get('Billing_zipcode'),


			// the credit card name is default to the mailing address

		);

		if (Input::get('sameasmailing'))
		{
			$ClientData['AccountFirstName'] = Input::get('Billing_first_name');
			$ClientData['AccountLastName'] = Input::get('Billing_last_name');
			$ClientData['AddressLine1'] = Input::get('Billing_address');
			$ClientData['City'] = Input::get('Billing_city');
			$ClientData['State'] = Input::get('Billing_state');
			$ClientData['ZipCode'] = Input::get('Billing_zipcode');
		}
		else
		{
			$ClientData['AddressLine1'] = Input::get('Mailing_Address');
			$ClientData['ZipSel'] = Input::get('Mailing_Address_city') .', '.Input::get('Mailing_Address_state') ;
			$ClientData['City'] = Input::get('Mailing_Address_city') ;
			$ClientData['State'] = Input::get('Mailing_Address_state') ;
			$ClientData['ZipCode'] = Input::get('Mailing_Address_zipcode') ;
		}

		$ClientData['Payment1Amount'] = 0;
		$ClientData['Payment1DueDate'] = date('m/j/Y');
		$ClientData['NoPayments'] = 0;
		$ClientData['StartDate'] = date('m/j/Y', strtotime(OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE));

		$ClientData['Loan_ContractFee_Override'] =  0;
		$ClientData['Loan_ContractTotalFee'] = 0;
		$ClientData['Loan_ContractFee'] = 0;
		$ClientData['TotalPayments'] = 0;

		$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));
		$this->Tell_System_ClientHasCompletedStep7();



		// we need to move this cart item over
		//$client->TProperties->products_already_purchased


	}

	public function Step7Form()
	{

		//
		if (!$this->IsAuthencated())
			return $this->RedirectNotAuthed();



		$clientdata = $this->GetClientFromSession(true);

		$ValidationRules =     array(
			'Billing_first_name' => 'required',
			'Billing_last_name' => 'required',
			'Billing_address' => 'required',
			'Billing_city' => 'required',
			'Billing_country' => 'required',
			'Billing_state' => 'required',
			'Billing_zipcode' => 'required',
			'Billing_email' => 'required|email',

			// required if !sameasmailing
			'Mailing_Address_first_name' => 'required_without:sameasmailing',
			'Mailing_Address_last_name' => 'required_without:sameasmailing',
			'Mailing_Address' => 'required_without:sameasmailing',
			'Mailing_Address_city' => 'required_without:sameasmailing',
			'Mailing_Address_country' => 'required_without:sameasmailing',
			'Mailing_Address_state' => 'required_without:sameasmailing',
			'Mailing_Address_zipcode' => 'required_without:sameasmailing',
			'Mailing_Address_email' => 'required_without:sameasmailing|email',

			// credit card info
			'card_number' => 'required_with_all:card_number,billing_name,expiration_month,expiration_year,cvv',
			'billing_name' => 'required_with_all:card_number,billing_name,expiration_month,expiration_year,cvv',
			'expiration_month' => 'required_with_all:card_number,billing_name,expiration_month,expiration_year,cvv',
			'expiration_year' => 'required_with_all:card_number,billing_name,expiration_month,expiration_year,cvv',
			'cvv' => 'required_with_all:card_number,billing_name,expiration_month,expiration_year,cvv',
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);

		if ($Validate->fails())
		{
			if (Input::get('np') && Session::get('auth_office_use'))
				return Redirect::to('step7', array('np' => Input::get('np')))->withErrors($Validate);

			return Redirect::to('step7')->withErrors($Validate);
		}

		/**
		 * We need all of this information or we send them back to Step7.
		 */

		$ClientData = array(
			'PaymentType' => 'Credit Card',
			'PaymentProcessor' => 'Authorize.Net',

			'CreditCardNumber' => Input::get('card_number'),
			'CreditCardExpirationMonth' => str_pad(Input::get('expiration_month'),2,'0', STR_PAD_LEFT),
			'CreditCardExpirationYear' => Input::get('expiration_year'),
			'CreditCardCVV' => Input::get('cvv'),
			//
			//		// mailing data
			//
			//		// billing data -- default to Mailing_Address information
			'AccountFirstName' => Input::get('Billing_first_name'),
			'AccountLastName' => Input::get('Billing_last_name'),
			'AccountOwnerAddress' => Input::get('Billing_city'),
			'AccountOwnerCity' => Input::get('Billing_country'),
			'AccountOwnerState' => Input::get('Billing_state'),
			'AccountOwnerZipCode' => Input::get('Billing_zipcode'),


			// the credit card name is default to the mailing address
			'CreditCardName' => Input::get('billing_name'),
			'CreditCardType' => $this->cardType(Input::get('card_number')),

		);

		// Mailing Data
		// Billing Data (it's defaulted to the mailing information, but if they didn't check that box lets update that information

		if (Input::get('sameasmailing'))
		{
			$ClientData['AccountFirstName'] = Input::get('Billing_first_name');
			$ClientData['AccountLastName'] = Input::get('Billing_last_name');
			$ClientData['AddressLine1'] = Input::get('Billing_address');
			$ClientData['City'] = Input::get('Billing_city');
			$ClientData['State'] = Input::get('Billing_state');
			$ClientData['ZipCode'] = Input::get('Billing_zipcode');
		}
		else
		{
			$ClientData['AddressLine1'] = Input::get('Mailing_Address');
			$ClientData['ZipSel'] = Input::get('Mailing_Address_city') .', '.Input::get('Mailing_Address_state') ;
			$ClientData['City'] = Input::get('Mailing_Address_city') ;
			$ClientData['State'] = Input::get('Mailing_Address_state') ;
			$ClientData['ZipCode'] = Input::get('Mailing_Address_zipcode') ;
		}

		// alright lets check if they are overriding any prices because they are auth'd
		if (Session::get('auth_office_use'))
		{
			// check if they have change the cart total to 0$
			$ClientData['Payment1Amount'] = input::get('tprice');
			$ClientData['Payment1DueDate'] = date('m/j/Y');
			$ClientData['NoPayments'] = 1;
			$ClientData['StartDate'] = date('m/j/Y', strtotime(OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE));

			$ClientData['Loan_ContractFee_Override'] =  input::get('tprice');
			$ClientData['Loan_ContractTotalFee'] =  input::get('tprice');
			$ClientData['Loan_ContractFee'] =  input::get('tprice');
			$ClientData['TotalPayments'] = input::get('tprice');

			// check if they are posting to np=2 (link a)
			if (Input::get('np') == 2 || Input::get('np') == 3)
			{
				$number_payments = 1;
				// we need to get ini settings
				if (Input::get('np') == 2)
					$number_payments = number_format(  OFFICE_LINK_A_NUM_PAYMENTS );

				elseif(Input::get('np') == 3)
					$number_payments = number_format(  OFFICE_LINK_B_NUM_PAYMENTS );
				## if number_payments is wrong, we're fixing it.

				if ($number_payments<2)
				{
					$number_payments = 1;
					return $this->RedirectWithError('step7?np='.Input::get('np'), 'Must have a minimum of two payments');
				}
				$payments = array();


				### settings
				$ClientData['NoPayments'] = $number_payments;


				for($payment_num=1;$payment_num<=$number_payments;$payment_num++)
				{
					// payment is:
					$payment_price = str_replace('$','', input::get( sprintf('fixed_payments_price_%s',$payment_num) ) );
					$payment_duedate =   DateTime::createFromFormat('m/d/Y', input::get( sprintf('fixed_payments_duedate_%s',$payment_num)) );

					if ($payment_num == 1)
						$ClientData['StartDate'] = $payment_duedate->format('m/j/Y');

					$ClientData['Payment'.$payment_num.'Amount'] = $payment_price;
					if (!method_exists($payment_duedate, 'format'))
					{
						return $this->RedirectWithError('step7?np=2', 'Must enter a valid date for payment#'.$payment_num);
					}

					$ClientData['Payment'.$payment_num.'DueDate'] = $payment_duedate->format('m/j/Y');

				}
			}


			elseif (Input::get('np') == 1)
			{
				$ClientData['Payment1Amount'] = 0;
				$ClientData['Payment1DueDate'] = date('m/j/Y');
				$ClientData['NoPayments'] = 0;
				$ClientData['StartDate'] = date('m/j/Y', strtotime(OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE));

				$ClientData['Loan_ContractFee_Override'] =  0;
				$ClientData['Loan_ContractTotalFee'] = 0;
				$ClientData['Loan_ContractFee'] = 0;
				$ClientData['TotalPayments'] = 0;
			}
		}
		else
		{
			$ClientSetupVars = $this->System_Setup_Default_Payment_From_Cart_Items('pending', true);
			$ClientData = array_merge($ClientData,$ClientSetupVars);
		}

		if (!isset($ClientData['NoPayments']))
			$ClientData['NoPayments'] = 1;

		if (!isset($ClientData['StartDate']))
			$ClientData['StartDate'] = date('m/j/Y');

		//alright we have all of their information, save the client details, then update the status to what they had ordered.
		if (Session::get('fileNumber'))
		{

			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));

			// mark this step as being completed

			// check if this is a zero dollar order
			if(Input::get('np') == 1)
			{
				//set payment received
				$this->System_Set_Payment_Received();

				// We need to move them to the ProcessPayment page
				return Redirect::to('processpayment?np='.Session::get('auth_office_pass').'&verified='.md5(Session::get('auth_office_pass') . $_SERVER['HTTP_HOST']));
			}
			else
			{
				// We call this to initiate the payment.
				$this->Tell_System_ClientHasCompletedStep7();
			}

			// we need to set their status to process payments.
			// also, lets see what happens when I do this!
			return Redirect::to('processpayment');
		}

	}

	public function System_ClientFailedPayment()
	{
		$Client = $this->GetClientFromSession(true);
		$ClientData = array();

		if ( ($Client->TProperties->completed_steps_bitwise & step7))
		$ClientData['completed_steps_bitwise'] = $Client->TProperties->completed_steps_bitwise & ~step7;

		$ClientData['cart_status'] = "";
		$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));

		return true;
	}

	public  function  Tell_System_ClientHasCompletedStep7()
	{
		if (Session::get('fileNumber'))
		{
			$Client = $this->GetClientFromSession(true);

			$ClientData['cart_status'] = "pending";

			if ( !($Client->TProperties->completed_steps_bitwise & step7))
				$ClientData['completed_steps_bitwise'] = $Client->TProperties->completed_steps_bitwise | step7;


			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));

			$paymentProcessedStatus = $this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $this->statusUpdates['Process Payments']);
		}
	}


	public function Step7Form_Old()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		Session::put('ordered_repayment', '');


		/*****************************
		** 24 June 2015
		** Amit Sharma &
		********/

		/**
		 * Amit ur bad bro. learn 2 code.
		 *
		 * 9/1/2015
		 */


		$pass = Input::get("password");
		$re = Input::get("re");

		if( Session::get('auth_office_use') && Input::get('np') == 1)
		{

			if(Input::get('np') == 1)
			{
				//set these fields:
				if (!isset($ClientData) || !is_array($ClientData))
				$ClientData=array();

				$ClientData['Payment1Amount'] = 0;
				$ClientData['Payment1DueDate'] = date('m/j/Y');
				$ClientData['NoPayments'] = 0;
				$ClientData['StartDate'] = date('m/j/Y', strtotime(OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE));

				$ClientData['Loan_ContractTotalFee'] =  0;
				$ClientData['Loan_ContractFee'] =  0;
				$ClientData['TotalPayments'] = 0;

				$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));

				$order_statusName = '100% Payments Received';
				$Order_PaymentsReceived = $this->statusUpdates['all']->{$order_statusName};

				$OrderStatusName = 	$this->System_Find_Status_Name_From_Purchased_Items(true);
				$OrderedProductStatus = $this->statusUpdates['all']->{$OrderStatusName};

				$this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $Order_PaymentsReceived);
				$this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $OrderedProductStatus);

				// We need to move them to the ProcessPayment page
				return Redirect::to('processpayment?np='.Session::get('auth_office_pass').'&verified='.md5(Session::get('auth_office_pass') . $_SERVER['HTTP_HOST']));
			}

		}


		else{
			/**
		 * Make sure they have provided all of the fields we need!
		 */

		}


		//Make sure all of the account information entered
	}

	function RedirectWithError($page, $error)
	{
		$messages = new Illuminate\Support\MessageBag;
		$messages->add('Error', '<b>Error</b> '.$error);

		return Redirect::to($page)->withErrors($messages);
	}


	// this shows the wait page for process payments.
	public function ProcessPayment()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;

		$np = Input::get('np');
		$verified = Input::get('verified');

		if (md5($np . $_SERVER['HTTP_HOST']) == $verified)
		{
			$this->layout->content .= View::Make('client_thankyou', array('instantly' => true, 'client' => $this->GetClientFromSession()));
		}
		else
		$this->layout->content .= View::Make('client_thankyou', array('instantly' => false, 'client' => $this->GetClientFromSession()));
		//		$this->layout->content .= "<pre>". print_r( $this->leadtracapi->GetClient(Session::get('fileNumber'), array('LastName')), 1)." </pre>";

	}

	// we are checking for a payment update. If no payments are found, we will send them back to ProcessPayment.
	// or we will send them to the errors, where they can provide a new card number OR what ever.
	public function CheckPaymentUpdate($return_bool = false)
	{
		$fileNumber = Session::get('fileNumber');

		// Lets pull the GetPaymentStatusFromLogs
		if ($fileNumber)
		{
			// payment status!
			$client = $this->GetClientFromSession(true);
			if (in_array($client->TProperties->cart_status, array('pending', 'failed', 'locked')))
			{
				// we need a payment.
				// whats the amount we need to find
				/**
				 * @todo we need to check if this status has been good, and when it's good something needs to trigger.. and we need to move there status to generate the forms.
				 * 	
				 */
			}
			$payment = $this->leadtracapi->GetPaymentStatusFromLogs($fileNumber);



			// First, check if it is still Processing (Status is Processing Payment) and Payment_Received is false.
			// if this is the case, we send them back to ProcessPayment view
			if ($payment->Processing && !$payment->Payment_Received)
			{
				if ($return_bool)
				return false;

				// send them back, no payment was found.
				return Redirect::to('processpayment');
			}

			// lets check if payment received and update there status!
			if ($payment->Payment_Received)
			{
				// update the status to 100% blah..
//				$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['Payments Received']);
				// we're not allowed to change this.

				$this->System_Set_Payment_Received();

				// generate forms:: we have to use the collelation chart provided (on the google drive).
				if ($return_bool)
				return true;

				return Redirect::to('paymentcompleted'); // we are done!
			}
			//			$fileNumber = Session::get('fileNumber');

			elseif ($payment->Payment_Declined || $payment->Payment_Failed)
			{
				if ($return_bool)
				return false;

				return Redirect::to('paymentfailed');
			}
			elseif($payment->Processing)
			{
				return Redirect::to('processpayment/?processing=1');
			}

			return Redirect::to('paymentcompleted'); // we are done!

		}
	}


	public function System_Set_Payment_Received()
	{
		$client = $this->GetClientFromSession(true);

		$order = intval($client->TProperties->cart_items);
		// trigger status

		$products_already_ordered = intval($client->TProperties->products_already_purchased);
		if ( !($products_already_ordered & $order) )
		{
			$products_already_ordered_now = $order | $products_already_ordered;
		}
		else
			$products_already_ordered_now = $products_already_ordered;


		$new_status = $this->System_Find_Status_Name_From_Purchased_Items(true);

		## Have to update them to 100% Payments Received.

		$paymentsreceived = '100% Payments Received';
		if (isset($this->statusUpdates['all']->{$paymentsreceived}))
		{
			$Order_PaymentsReceived = $this->statusUpdates['all']->{$paymentsreceived};
			$this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $Order_PaymentsReceived);
		}

		// change their status to this.
		if (isset($this->statusUpdates['all']->{$new_status}))
		{
			$this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $this->statusUpdates['all']->{$new_status});
			$UpdateFields = array('cart_status' => '', 'cart_items' => 0, 'cart_id' => '','cart_steps_required' => 0, 'products_already_purchased' => $products_already_ordered_now);

			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $UpdateFields, Session::get('fileNumber'));
		}
	}

	/**
	 * Final Success Page. This page will remove the processing step from view and tell them that the forms was generated.
	 *
	 */
	public function PaymentCompleted()
	{

		return Redirect::to('crm');
		// Just show them it's been completed & we are good.
		$this->layout->content .= View::make('client_paymentcomplete');
	}

	/**
	 * Generic: PaymentFailed & PaymentDeclined display.
	 *
	 */
	public function PaymentFailed()
	{
		// we need to change their cart to not locked any more.
		$this->System_ClientFailedPayment();

		return $this->RedirectWithError('/step7', 'Your payment has failed, Please re-enter your credit card information and try again. Thank you!');
		return Redirect::to('/step7');
		$this->layout->content .= View::make('client_paymentfail');
	}

	public function PaymentDeclined()
	{
		return $this->RedirectWithError('/step7', 'Your payment has been declined, Please re-enter your credit card information and try again. Thank you!');

		$this->layout->content .= View::make('client_paymentcomplete');
	}

	/**
	* Return credit card type if number is valid
	* @return string
	* @param $number string
	**/
	function cardType($number)
	{
		$number=preg_replace('/[^\d]/','',$number);
		if (preg_match('/^3[47][0-9]{13}$/',$number))
		{
			return 'American Express';
		}
		elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',$number))
		{
			return 'Diners Club';
		}
		elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/',$number))
		{
			return 'Discover';
		}
		elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/',$number))
		{
			return 'JCB';
		}
		elseif (preg_match('/^5[1-5][0-9]{14}$/',$number))
		{
			return 'MasterCard';
		}
		elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/',$number))
		{
			return 'Visa';
		}
		else
		{
			return 'Unknown';
		}
	}
	function LogOutActions()
	{
		if (Session::has('auth_office_use'))
		{
			$Office_Session_Auth = Session::get('auth_office_use');
			$Office_Session_pw = Session::get('auth_office_password');
		}
		Session::flush();

		if (isset($Office_Session_Auth))
		Session::put('$Office_Session_Auth', $Office_Session_Auth);
		if (isset($Office_Session_pw))
		Session::put('auth_office_password', $Office_Session_pw);
	}
	/**
	 * Returning Customer Form Processing. (This will redirect them back to the front end, which should have all of there information!
	 */
	public function ReturningCustomerForm()
	{
		$lastname = Input::get('lastname');
		$email = Input::get('email');
		$capche_key = '6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV';
		$capche_secret = '6LdI9wATAAAAAGAb9tN6ugLnTWFfHUtb3XCc8YdQ';
		$response = Input::get('g-recaptcha-response');
		//check if the y are correct
		$results = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$capche_secret.'&response='.$response.'&remoteip='.$_SERVER['REMOTE_ADDR']);
		$response = json_decode($results);

		if (isset($response->success) && $response->success)
		{
			$solved_capache=true;
		}

		else
		{
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('You must prove you are human ', " <b>Capache Error</b>");
			Session::flash('ShowReturnPanel', true);
			Session::flash('SearchError', true);

			return Redirect::to('/')->withErrors($messages);
		}

//		$this->TranslateOrderFromStatus("false", $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance);
//		Session::put('ordered_cpn',$ordered_cpn);
//		Session::put('ordered_repayment_promisory_note',$ordered_repayment_promisory_note);
//		Session::put('ordered_pslf',$ordered_pslf);
//		Session::put('ordered_forebearance',$ordered_forebearance);

		session::forget('Step3Completed');

		//		$ssn = str_replace('-', '', $ssn); // remove any dashes.
		//		$ssn = substr($ssn,0,3) .'-'.substr($ssn,3,2).'-'.substr($ssn,5);
		//find out if they have anything matching.
		//		$search = $this->leadtracapi->FindClientByLastNameAndSSN($lastname, $ssn);
		$search = $this->leadtracapi->FindClientByLastNameAndEmail($lastname, $email);

		if (!$search)
		{
			//Show the page & add errors!
			Session::flash('ShowReturnPanel', true);
			Session::flash('SearchError', true);
			Session::forget('fileNumber');
		}

		else
		{
			Session::put('fileNumber', $search);

			// lets see if they are upgrading/updating their profile (see if they have paid for anything.)
//			$client = $this->leadtracapi->GetClient($search, array('FirstName', 'LastName', 'Loan_Program'));
			$client = $this->GetClientFromSession(true);

			$upgrade = array('100% Payments Received', 'Process Payments');
			session::forget('Step3Completed');

//			$this->TranslateOrderFromStatus($client->Status->Sales, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance);
//			Session::put('ordered_cpn',$ordered_cpn);
//			Session::put('ordered_repayment_promisory_note',$ordered_repayment_promisory_note);
//			Session::put('ordered_pslf',$ordered_pslf);
//			Session::put('ordered_forebearance',$ordered_forebearance);



//			if (isset($client->Status->Accounting) && in_array($client->Status->Accounting,$upgrade))
//			{
//				Session::put('ClientUpgrade', true);
//
//			}
//			else
//			{
//				Session::put('ClientUpgrade', false);
//			}
		}

		// run step 1, step 2.
		Session::flash('dont_auto_logout',true);


		return Redirect::to('/');
	}

	public function ReturningCustomer()
	{
		Session::flash('ShowReturnPanel', false);
		$this->layout->content = View::make('returningclient');

	}

	/**
	 * Call: $this->TranslateOrderFromStatus($status, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance = false)
	 */

	public function TranslateOrderFromStatus($status, &$ordered_cpn=false,&$ordered_repayment_promisory_note=false,&$ordered_pslf=false, &$ordered_forebearance = false, &$ordered_recertification = false)
	{
		$status = trim($status);

		$ordered_cpn=false;
		$ordered_repayment_promisory_note=false;
		$ordered_pslf=false;
		$ordered_forebearance=false;
		$ordered_recertification=false;

		//		die(substr($status,0,strlen('Repay Stand App')));

		if (substr($status,0,13) == 'Conso App IBR')
		$ordered_cpn = true;

		elseif (substr($status,0,strlen('Repay Stand App')) == 'Repay Stand App' || substr($status,0,strlen('Repay IBR App')) == 'Repay IBR App')
		$ordered_repayment_promisory_note = true;


		switch (trim($status))
		{
			## Ordered all of the forms
			case 'Recertification / Forbearance / Forgiveness App Sent':
			case 'Repay IBR App / RC / FB / PSLF Sent':
			case 'Repay Stand App / RC / FB / PSLF Sent':
			case 'Conso App IBR / RC / FB / PSLF Sent':
			case 'Conso Stand App Sent / RC / FB / PSLF Sent':
				$ordered_pslf = true;
				$ordered_forebearance = true;
				$ordered_recertification = true;
				break;

			case 'Forbearance / Forgiveness App Sent':
			case 'Repay IBR App / FB / PSLF Sent':
			case 'Repay Stand App / FB / PSLF Sent':
			case 'Conso App IBR / FB / PSLF Sent':
			case 'Conso Stand App Sent / FB / PSLF Sent':
				$ordered_pslf = true;
				$ordered_forebearance = true;
				break;

				## Order PSLF + REPAY + Recertification
			case 'Recertification / Forgiveness App Sent':
			case 'Repay IBR App / RC / PSLF Sent':
			case 'Repay Stand App / RC / PSLF Sent':
			case 'Conso Stand App Sent / RC / PSLF Sent':
			case 'Conso App IBR / RC / PSLF Sent':
				$ordered_pslf = true;
				$ordered_recertification = true;
				break;

			case 'Forgiveness App Sent':
			case 'Repay IBR App / PSLF Sent':
			case 'Repay Stand App / PSLF Sent':
			case 'Conso Stand App Sent / PSLF Sent':
			case 'Conso App IBR / PSLF Sent':
				$ordered_pslf = true;
				break;

				## forebearance + recertification
			case 'Recertification / Forbearance App Sent':
			case 'Repay Stand App / RC / FB Sent':
			case 'Repay IBR App / RC / FB Sent':
			case 'Conso Stand App Sent / RC / FB':
			case 'Conso App IBR / RC / FB Sent':
				$ordered_forebearance = true;
				$ordered_recertification = true;
				break;

				# recertification + repay

			case 'RC App Sent':
			case 'Repay Stand App / RC Sent':
			case 'Repay IBR App / RC Sent':
			case 'Conso Stand App Sent / RC':
			case 'Conso App IBR / RC Sent':
				$ordered_recertification = true;
				break;

			case 'Forbearance App Sent':
			case 'Repay Stand App / FB Sent':
			case 'Repay IBR App / FB Sent':
			case 'Conso Stand App Sent / FB':
			case 'Conso App IBR / FB Sent':
				$ordered_forebearance = true;
				break;


			case 'Conso App IBR Sent':
				$ordered_cpn = true;
				break;
			case 'Repay IBR App':
			case 'Repay IBR App Sent':
				$ordered_repayment_promisory_note = true;
				break;

		}


	}
	/**
	 * @example $Loan_Program is taken from $Client->TProperties->Loan_Program
	 * @example
	 $this->TranslateStatusFromOrder($Client->TProperties->Loan_Program, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance);
	 *
	 */
	function TranslateStatusFromOrder($Loan_Program,$ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance, $ordered_recertification, &$newStatusId)
	{
		$Loan_Program = strtolower($Loan_Program);
		$Plan = "";
		$this->TranslateStatusError = false;



		switch ($Loan_Program)
		{
			case 'pay as you earn':
				$Plan = 'PAY';
				break;

				$Plan = 'IBR';
				break;
			case 'income-based':
				$Plan = 'IBR';
				break;
			case 'income contingent':
				$Plan = 'ICR';
				break;
			case 'standard':
				$Plan = 'STD';
				break;
			case 'graduated':
				$Plan = 'GRAD';
				break;
			case 'extended fixed':
				$Plan = 'EXF';
				break;
			case 'extended graduated':
				$Plan = 'EXG';
				break;
		}

		// alright: we know what they ordered:
		switch (strtolower($Plan))
		{
			case 'pay':
			case 'ibr':
			case 'icr':
				// see if they ordered everything:
				if ($ordered_cpn)
				{
					// see if they ordered the 2 options
					$order_statusName = 'Conso App IBR / ';

					if ($ordered_recertification)
					{
						$order_statusName .= 'RC / ';
					}
					if ($ordered_forebearance)
					{
						//new status
						$order_statusName .= 'FB / ';
					}

					if ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName .= 'PSLF / ';
					}

					$order_statusName = rtrim(rtrim($order_statusName,' '),'/');
					if ($order_statusName != 'Conso Stand App Sent')
					$order_statusName = trim($order_statusName) . ' Sent';
				}
				elseif ($ordered_repayment_promisory_note) // repay
				{
					$order_statusName = "Repay IBR App / ";

					if ($ordered_recertification)
					{
						$order_statusName .= 'RC / ';
					}
					if ($ordered_forebearance)
					{
						//new status
						$order_statusName .= 'FB / ';
					}

					if ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName .= 'PSLF / ';
					}

					$order_statusName = rtrim(rtrim($order_statusName,' '),'/');
					if ($order_statusName != 'Conso Stand App Sent')
					$order_statusName = trim($order_statusName) . ' Sent';

				}
				else
				{
					// check what they ordered.
					$order_statusName = "";

					if ($ordered_recertification)
					{
						$order_statusName = "RC / ";
					}
					if ($ordered_forebearance)
					{
						$order_statusName .= "Forbearance / ";
					}
					if ($ordered_pslf)
					{
						$order_statusName .= "Forgiveness / ";
					}
					$order_statusName = rtrim(rtrim($order_statusName,' '),'/');
					if ($order_statusName != 'Conso Stand App Sent')
					$order_statusName = trim($order_statusName) . ' Sent';

				}

				break;

				// standard status classes
			case 'std':
			case 'grad':
			case 'exg':
				// see if they ordered everything:
				if ($ordered_cpn)
				{
					// see if they ordered the 2 options
					$order_statusName = 'Conso Stand App Sent / ';

					if ($ordered_recertification)
					{
						$order_statusName .= 'RC / ';
					}
					if ($ordered_forebearance)
					{
						//new status
						$order_statusName .= 'FB / ';
					}

					if ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName .= 'PSLF / ';
					}

					$order_statusName = rtrim(rtrim($order_statusName,' '),'/');
					if ($order_statusName != 'Conso Stand App Sent')
					$order_statusName = trim($order_statusName) . ' Sent';


				}

				elseif ($ordered_repayment_promisory_note) // repay
				{
					$order_statusName = "Repay Stand App / ";

					if ($ordered_recertification)
					{
						$order_statusName .= 'RC / ';
					}
					if ($ordered_forebearance)
					{
						//new status
						$order_statusName .= 'FB / ';
					}

					if ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName .= 'PSLF / ';
					}

					$order_statusName = rtrim(rtrim($order_statusName,' '),'/');
					if ($order_statusName != 'Conso Stand App Sent')
					$order_statusName = trim($order_statusName) . ' Sent';

				}

				else
				{
					// check what they ordered.
					$order_statusName = "";

					if ($ordered_recertification)
					{
						$order_statusName = "RC / ";
					}
					if ($ordered_forebearance)
					{
						$order_statusName .= "Forbearance / ";
					}
					if ($ordered_pslf)
					{
						$order_statusName .= "Forgiveness / ";
					}
					$order_statusName = rtrim(rtrim($order_statusName,' '),'/');
					if ($order_statusName != 'Conso Stand App Sent')
					$order_statusName = trim($order_statusName) . ' App Sent';
				}

				break;

			default:
				// check what they ordered.
				$order_statusName = "";

				if ($ordered_recertification)
				{
					$order_statusName = "RC / ";
				}
				if ($ordered_forebearance)
				{
					$order_statusName .= "Forbearance / ";
				}
				if ($ordered_pslf)
				{
					$order_statusName .= "Forgiveness / ";
				}
				$order_statusName = rtrim(rtrim($order_statusName,' '),'/');
				$order_statusName = trim($order_statusName) . ' App Sent';

				break;
		}

		// status number:
		$order_statusName = str_replace('Sent Sent', 'Sent', $order_statusName);

		if ($order_statusName == 'RC Sent') $order_statusName = 'RC App Sent';

		if (isset($this->statusUpdates['all']->{$order_statusName}))
		{
			$newStatusId = $this->statusUpdates['all']->{$order_statusName};
			return $order_statusName;
		}

		else
		{
			//			die('<div class="stepdef" data-step-number="step6">Status isnt working for us '.$order_statusName.'.</div>');

			$this->TranslateStatusError = "Status does not exists: ".$order_statusName."";
			return false;
			//			return Redirect::to('step6')->withErrors($messages);
		}

	}

	function CheckOfficePassword()
	{
		$redirect_to = 'step7'. (Input::get('np') ? '?np='.Input::get('np') : '');

		## check if the office login timeout has expired
		if ((Session::get('office_login_timeout') > 0 && time() > Session::get('office_login_timeout')))
		{
			Session::put('office_login_attempts', 0);
		}
		## when does the bruteforce check expire ?

		$login_attempts = Session::get('office_login_attempts') ? Session::get('office_login_attempts') : 0;
		## check if they exceeded 3 max logins.
		if ($login_attempts > 3)
		{
			Session::put('office_login_timeout', time() + 60);
			$messages = new Illuminate\Support\MessageBag;
			$timeleft =Session::get('office_login_timeout') - time();
			$messages->add('Error', '<b>Login Error:</b> To many login attempts, please wait <b>'.$timeleft.'</b> seconds before you try again.' );

			return Redirect::to($redirect_to)->withErrors($messages);
		}


		## increase the number of times attempted to login
		$login_attempts++;
		Session::put('office_login_attempts', $login_attempts);


		### now verify if the password is bad or good..
		if (!verify_office_password(Input::get('p')))
		{
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('Error', '<b>Office Password Not Authorized:</b> Please enter office password to access this restricted area.. ['. Session::get('office_login_attempts').'/3]');

			return Redirect::to($redirect_to)->withErrors($messages);
		}
		else
		{
			// password verified, phew.
			$this->Grant_Office_Use_Access(Input::get('p'));
			Session::forget('office_login_attempts');
			Session::forget('office_login_timeout');
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('Success','<b>Access Granted:</b> You now have access to office use only features.');
			$this->errors = $messages;
			$this->layout->errors = $this->errors;

			return Redirect::to('step7');

			//			$this->Step7();
		}
	}

	public function Grant_Office_Use_Access($pass)
	{
		Session::put('auth_office_use', true);
		Session::put('auth_office_pass', $pass);
		return true;
	}

	public function Revoke_Office_Use_Access()
	{
		Session::forget('auth_office_use');

		return true;
	}

	public function System_UpdateClientDocument_By_Product($itemcode)
	{
		if (!$this->IsAuthencated())
			return $this->RedirectNotAuthed();

		$status_name = false;

		$client = $this->GetClientFromSession();
		$items_bitwise = intval($itemcode);

		$Loan_Program = $client->TProperties->Loan_Program;

		switch ($Loan_Program)
		{
			case 'pay as you earn':
				$Plan = 'PAY';
				break;
			case 'income-based':
				$Plan = 'IBR';
				break;
			case 'income contingent':
				$Plan = 'ICR';
				break;
			case 'standard':
				$Plan = 'STD';
				break;
			case 'graduated':
				$Plan = 'GRAD';
				break;
			case 'extended fixed':
				$Plan = 'EXF';
				break;
			case 'extended graduated':
				$Plan = 'EXG';
				break;

			default:
				$Plan = "";
				break;
		}

		/**
		 * I've made it set to one status each order. So, this should always work!
		 */
		if ($items_bitwise == cart_product_forebearance_app)
		{
			$status_name = "Forbearance App Sent";
		}
		elseif ($items_bitwise == cart_product_pslf_app)
		{
			$status_name = "Forgiveness App Sent";
		}
		elseif ($items_bitwise == cart_product_recertification_app)
		{
			$status_name = "RC App Sent";
		}
		elseif($items_bitwise == cart_product_consolidation_app)
		{
			switch(strtolower($Plan)) {
				default:
				case 'pay':
				case 'ibr':
				case 'icr':
					$status_name = "Conso App IBR Sent";
					break;

				case 'std':
				case 'grad':
				case 'exg':
					$status_name = "Conso Stand App Sent";
					break;
			}

		}
		elseif ($items_bitwise == cart_product_repayment_app)
		{
			switch(strtolower($Plan)) {
				default:
				case 'pay':
				case 'ibr':
				case 'icr':
					$status_name =  "Repay IBR App Sent";
					break;

				case 'std':
				case 'grad':
				case 'exg':
					$status_name = "Repay Stand App Sent";
					break;
			}

		}

		if (isset($this->statusUpdates['all']->{$status_name}))
		{
			$client_status = $this->statusUpdates['all']->{$status_name};
			$this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $client_status);
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('cart_update_forms_status' => ''), Session::get('fileNumber'));
			return true;
		}


		return $status_name;

	}
	public function System_Find_Status_Name_From_Purchased_Items($include_cart_items_only = false)
	{
		$include_cart_items_only = true;
		// temp we're goingt o force the status to only what's being ordered.

		$client = $this->GetClientFromSession(true);
		$items_bitwise = 0;
		$items_bitwise = intval($client->TProperties->cart_items);

		if (!$include_cart_items_only)
		$items_bitwise |= intval($client->TProperties->products_already_purchased);

		// we need to know what loan program they are using.

		$Loan_Program = $client->TProperties->Loan_Program;

		switch ($Loan_Program)
		{
			case 'pay as you earn':
				$Plan = 'PAY';
				break;
			case 'income-based':
				$Plan = 'IBR';
				break;
			case 'income contingent':
				$Plan = 'ICR';
				break;
			case 'standard':
				$Plan = 'STD';
				break;
			case 'graduated':
				$Plan = 'GRAD';
				break;
			case 'extended fixed':
				$Plan = 'EXF';
				break;
			case 'extended graduated':
				$Plan = 'EXG';
				break;

			default:
				$Plan = "";
				break;
		}

		/**
		 * I've made it set to one status each order. So, this should always work!
		 */
		if ($items_bitwise == cart_product_forebearance_app)
		{
			$status_name = "Forbearance App Sent";
		}
		elseif ($items_bitwise == cart_product_pslf_app)
		{
			$status_name = "Forgiveness App Sent";
		}
		elseif ($items_bitwise == cart_product_recertification_app)
		{
			$status_name = "RC App Sent";
		}
		elseif($items_bitwise == cart_product_consolidation_app)
		{
			switch(strtolower($Plan)) {
				default:
				case 'pay':
				case 'ibr':
				case 'icr':
					$status_name = "Conso App IBR Sent";
					break;

				case 'std':
				case 'grad':
				case 'exg':
					$status_name = "Conso Stand App Sent";
					break;
			}

		}
		elseif ($items_bitwise == cart_product_repayment_app)
		{
			switch(strtolower($Plan)) {
				default:
				case 'pay':
				case 'ibr':
				case 'icr':
					$status_name =  "Repay IBR App Sent";
					break;

				case 'std':
				case 'grad':
				case 'exg':
					$status_name = "Repay Stand App Sent";
					break;
			}

		}

		return $status_name;

		## if it's all 3
		if ($items_bitwise == (cart_product_forebearance_app && cart_product_pslf_app && cart_product_recertification_app))
		{
			$status_name = "Forbearance / Forgiveness App / RC App Sent";
		}
		## if its only forebearance and pslf
		elseif ($items_bitwise == (cart_product_forebearance_app && cart_product_pslf_app))
		{
			$status_name = "Forbearance / Forgiveness App";
		}
		// if its only forebearance
		elseif ($items_bitwise == (cart_product_forebearance_app))
		{
			$status_name = "Forbearance App Sent";
		}
		// if its only forebearance and recertification_app
		elseif ($items_bitwise == (cart_product_forebearance_app && cart_product_recertification_app))
		{
			$status_name = "Forbearance / RC App Sent";
		}
		// if its only pslf and recert
		elseif ($items_bitwise == (cart_product_pslf_app && cart_product_recertification_app))
		{
			$status_name = "Forgiveness / RC App Sent";
		}
		elseif ($items_bitwise == (cart_product_pslf_app))
		{
			$status_name = "Forgiveness App Sent";
		}
		elseif ($items_bitwise == (cart_product_recertification_app))
		{
			$status_name = "RC App Sent";
		}


		switch(strtolower($Plan))
		{
			case 'pay':
			case 'ibr':
			case 'icr':
				// check what they ordered.
				// it'll be ibr
				$status_name = "";

				if ($items_bitwise & cart_product_consolidation_app || $items_bitwise & cart_product_repayment_app)
				{
					if ($items_bitwise & cart_product_consolidation_app)
						$status_name = "Conso App IBR / ";
					if ($items_bitwise & cart_product_repayment_app)
						$status_name = "Repay IBR App / ";

					if ($items_bitwise & cart_product_forebearance_app)
						$status_name .= "FB / ";
					if ($items_bitwise & cart_product_pslf_app)
						$status_name .= "PSLF / ";
					if ($items_bitwise & cart_product_recertification_app)
						$status_name .= "RC App / ";
				}

			break;

			case 'std':
			case 'grad':
			case 'exg':
			$status_name = "";

			if ($items_bitwise & cart_product_consolidation_app || $items_bitwise & cart_product_repayment_app)
			{
				if ($items_bitwise & cart_product_consolidation_app)
					$status_name = "Conso Stand App Sent / ";
				if ($items_bitwise & cart_product_repayment_app)
					$status_name = "Repay Stand App / ";

				if ($items_bitwise & cart_product_forebearance_app)
					$status_name .= "FB / ";
				if ($items_bitwise & cart_product_pslf_app)
					$status_name .= "PSLF / ";
				if ($items_bitwise & cart_product_recertification_app)
					$status_name .= "RC App";
			}
			break;

		}

		$status_name = rtrim($status_name, ' ');
		$status_name = rtrim($status_name, '/');

		return $status_name;

	}

	public function System_Setup_Default_Payment_From_Cart_Items($set_cart_status = false, $returnUpdateOnly = false)
	{

		$client = $this->GetClientFromSession(true);

		if ($client->TProperties->cart_items)
		{
			// cart_product_consolidation_app, cart_product_repayment_app, cart_product_recertification_app, cart_product_pslf_app, cart_product_forebearance_app

			$CartTotal = 0;

			if ($client->TProperties->cart_items & cart_product_consolidation_app)
			{
				$CartTotal += number_format(PRICE_CONSOLIDATION_NOTE,2);
			}

			if ($client->TProperties->cart_items & cart_product_repayment_app)
			{
				$CartTotal += number_format(PRICE_REPAYMENT_NOTE,2);
			}

			if ($client->TProperties->cart_items & cart_product_forebearance_app)
			{
				$CartTotal += number_format(PRICE_FOREBEARANCE_APP,2);
			}

			if ($client->TProperties->cart_items & cart_product_pslf_app)
			{
				$CartTotal += number_format(PRICE_PSLF_APP,2);
			}

			if ($client->TProperties->cart_items & cart_product_recertification_app)
			{
				$CartTotal += number_format(PRICE_RECERTIFICATION_APP,2);
			}

			$ClientData=array();

			// alright lets set the ProgramPriceOverride
			$ClientData['Loan_ContractFee_Override'] = number_format($CartTotal,2);
			$ClientData['Payment1Amount'] = number_format($CartTotal,2);
			$ClientData['Payment1DueDate'] = date('m/j/Y');
			$ClientData['NoPayments'] = 1;
			$ClientData['StartDate'] = date('m/j/Y');
			$ClientData['Loan_ContractTotalFee'] = number_format($CartTotal,2);
			$ClientData['Loan_ContractFee'] = number_format($CartTotal,2);
			$ClientData['TotalPayments'] = number_format($CartTotal,2);
			$ClientData['Cart_Expected_Log'] = "Approved for \$".number_format($ClientData['Payment1Amount'],2);
			$ClientData['Cart_Locked'] = 0;

			if ($set_cart_status)
		{
			$ClientData['cart_status'] = $set_cart_status;
		}

			if ($returnUpdateOnly)
			return $ClientData;

			else

			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));

		}




	}
	public function System_Get_Cart_Total()
	{

		$client = $this->GetClientFromSession(true);

		if ($client->TProperties->cart_items)
		{
			// cart_product_consolidation_app, cart_product_repayment_app, cart_product_recertification_app, cart_product_pslf_app, cart_product_forebearance_app

			$CartTotal = 0;

			if ($client->TProperties->cart_items & cart_product_consolidation_app)
			{
				$CartTotal += number_format(PRICE_CONSOLIDATION_NOTE,2);
			}

			if ($client->TProperties->cart_items & cart_product_repayment_app)
			{
				$CartTotal += number_format(PRICE_REPAYMENT_NOTE,2);
			}

			if ($client->TProperties->cart_items & cart_product_forebearance_app)
			{
				$CartTotal += number_format(PRICE_FOREBEARANCE_APP,2);
			}

			if ($client->TProperties->cart_items & cart_product_pslf_app)
			{
				$CartTotal += number_format(PRICE_PSLF_APP,2);
			}

			if ($client->TProperties->cart_items & cart_product_recertification_app)
			{
				$CartTotal += number_format(PRICE_RECERTIFICATION_APP,2);
			}

			return number_format($CartTotal,2);
		}




	}

	public function GetClientFromSession($nocache=false)
	{
		static $client_data = false;

		if ($client_data && !$nocache)
			return $client_data;

		if (Session::get('fileNumber'))
		{
			$client_data = $this->leadtracapi->GetClient(Session::get('fileNumber'), Array('ClientLogin','ClientPassword','LeadSource', 'Cart_Expected_Log', 'Cart_Locked', 'Loan_Program','PublicService','Occupation','HomeNumber','TaxFilingStatus','CoIncome_Yearly','Income_Yearly','MaritalStatus','CoFirstName','CoLastName','CoSSN','CoDOB','FamilySize','Name1SSN', 'Name1DOB', 'AddressLine1', 'City', 'State', 'ZipCode','ClientLogin','ClientPassword', 'client_city', 'client_region', 'client_zipcode','EmailAddress', 'FirstName', 'LastName', 'Payment1Amount', 'AddressLine1', 'City', 'State', 'ZipCode', 'Loan_ContractFee_Override','Payment1Amount','Payment1DueDate','NoPayments','StartDate','Loan_ContractTotalFee','Loan_ContractFee','TotalPayments'));
		}

		return $client_data;
	}

	public function Check_If_Client_Has_Pending_Orders()
	{
		$clientdetail = $this->GetClientFromSession();

		if (in_array($clientdetail->TProperties->cart_status, array('pending', 'declined', 'locked')))
		{
			//check if the client has ordered tho

			return true;
		}

		return false;
	}
}
