<?php

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

	public function __construct() {
		/** Setup our API Library **/
		$username = 'api.diy';
		$password = 'kvzq3151tt6as8pn';
		$api_version = '2.16';
		$sandbox = false;

		$this->leadtracapi = new leadtracapi($username,$password, $api_version, $sandbox);
		if (defined('LEADTRACK_DOMAIN_KEY')) $this->tracking_field_value = LEADTRACK_DOMAIN_KEY;


		//				Session::put('fileNumber', 'DY854.85393');

		/**
		 * Statuses need to be brought into the system then cached!
		 * 
		 */
		$this->statusUpdates = Cache::remember('Statuses3', 60, function(){
			$username = 'api.diy';
			$password = 'kvzq3151tt6as8pn';
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
		

	}


	public function NewClientForm()
	{
		// Check if they have client details, and we'll display this with that data!
		if (Session::get('fileNumber'))
		{

			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName',  'EmailAddress', 'HomeNumber', 'LeadSource'));

			$this->Step1();


			if ($clientDetails->CompletedStep >=1)
			$this->layout->content .= $this->Step2();
			if ($clientDetails->CompletedStep >=2)
			$this->layout->content .= $this->Step3();

			//			if ($clientDetails->CompletedStep >=3)
			//			$this->layout->content .= $this->Step4();

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
			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName', 'EmailAddress', 'HomeNumber', 'LeadSource'));

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
			$fileNumber = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $parms, Session::get('fileNumber'));
		}
		else
		{
			/**
			 * Remove previous order status info
			 */

			Session::forget('ordered_cpn');
			Session::forget('ordered_repayment_promisory_note');
			Session::forget('ordered_pslf');
			Session::forget('ordered_forebearance');

			$fileNumber = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $parms);
		}
		$client = $this->leadtracapi->GetClient($fileNumber, array('FirstName', 'LastName'));


		//we created the customer and received the file id.
		if ($fileNumber)
		{
			Session::put('fileNumber', $fileNumber);

			//error after update :p. preserving the fields they posted.
			if ($Validate->fails())
			return Redirect::to('step1')->withErrors($Validate);

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
			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('PublicService','Occupation','TaxFilingStatus','CoIncome_Yearly','Income_Yearly','MaritalStatus','CoFirstName','CoLastName','CoSSN','CoDOB','FamilySize'));

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
			foreach ($data as $did => $val)
			{
				//				if (!$val && Input::get(''))

			}

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
				$clientdetails = $this->leadtracapi->getClient($fileNumber, array('FirstName', 'LastName'));

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
			//			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('Name1SSN', 'Name1DOB', 'AddressLine1', 'City', 'State', 'ZipCode'));
			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('Name1SSN', 'Name1DOB', 'AddressLine1', 'City', 'State', 'ZipCode'));

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

	public function Step3Form()
	{
		$fileNumber = Session::get('fileNumber');

		$fields = array('FirstName', 'LastName', 'Name1SSN');
		$client = $this->leadtracapi->getClient($fileNumber, $fields);

		$ValidationRules =     array(
		'dob' => 'required|date_format:n/d/Y',
		'ssn' => 'required|min:4|max:12',

		/**
		 * @deprecated moved the address fields to another step! (changed via another page)
		 */
		//		'address1' => 'required',
		//		'city' => 'required',
		//		'state' => 'required|min:2|max:2',
		//		'zip' => 'required|numeric',
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
		//		$address1 = Input::get('address1');
		//		$city = Input::get('city');
		//		$state = strtoupper(Input::get('state'));
		//		$zipcode = Input::get('zip');

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
		$updateFields['Name1SSN'] = $ssn;
		$updateFields['Name1DOB'] = $dob;
		$updateFields[$this->tracking_field_name] = $this->tracking_field_value;
		/**
		 * @deprecated moved address to another step
		 */
		//		$updateFields['AddressLine1'] = $address1;
		//		$updateFields['ZipSel'] = $city .', '.$state;
		//		$updateFields['City'] = $city;
		//		$updateFields['State'] = $state;
		//		$updateFields['ZipCode'] = $zipcode;

		if ($fileNumber)
		{


			$UpdateStatus = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);

			// if they didnt fail, send them here.
			if ($Validate->fails())
			return Redirect::to('step3')->withErrors($Validate);

			if ($UpdateStatus !== false)
			{
				/**
				 * Client completed step3.
				 */
				$clientdetails = $client;

				if ($clientdetails->CompletedStep == 2)
				{
					// We need to change the status to Step3s.
					$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step3']);
				}


				session::put('Step3Completed',true);

				//check here if they are upgrading, this will put down the defenses to order nothing.
				//we'll try to force the capture payments to work but idk if it will!
				/**
				 * @todo finish this part for the upgrades
				 */

				//update successful.
				return Redirect::to('step4');
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

	/** Fasfa PIN **/
	public function step4()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();



		$this->layout->content .= View::make('client_step4');

	}

	public function Step4Form()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');

		$ValidationRules =     array(
		'fafsa_pin' => 'required|digits:4',
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);

		if ($Validate->fails())
		return Redirect::to('step4')->withErrors($Validate);


		$fafsapin = Input::get('fafsa_pin');


		$updateFields = array();
		$updateFields['DOEPin'] = $fafsapin;

		$updateFields[$this->tracking_field_name] = $this->tracking_field_value;

		//this script can take up to 5 minutes?
		@set_time_limit(300); // set the time limit to 5 minutes to prevent it from timing out.
		@ini_set('max_execution_time', 300);
		ini_set('max_input_time',300);


		if ($fafsapin)
		{
			if ($fileNumber)
			{

				$UpdateStatus = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);

				if ($UpdateStatus !== false)
				{
					/**
					 * Client completed step4. move them to Step 5.
					 */
					$clientdetails = $this->leadtracapi->getClient($fileNumber, array('FirstName', 'LastName'));



					if ($clientdetails->CompletedStep >= 2)
					{
						//check if we need ot change them to FAFSFA Pin (if its not already at step four.
						if ($clientdetails->CompletedStep == 2)
						// We need to change the status to Step4.
						$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step4']);

						// Now we need to Set the status to import the Loans.
						$response = $this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['import_loans']);

						//check if the import was successful
						if ($response !== false)
						{
							//Now we need to run the Quote. We need to use the NSLDS Status (Capture Quote) to import the Quote.
							$get_quote = $this->leadtracapi->ChangeClientStatus($fileNumber,$this->statusUpdates['import_nslds_quote']);

						}
						else // show them the error message.
						{
							//move their status back
							//							$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['doe_idle']);
							$messages = new Illuminate\Support\MessageBag;
							$messages->add('* Error ', $this->leadtracapi->error);

							return Redirect::to('step4')->withErrors($messages);
						}
					}

					//update successful.
					return Redirect::to('step5');
				}
			}
		}
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

	/** Step 5, Loans & Stuff **/
	public function Step5()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');

		if ($fileNumber)
		{
			$Loans = $this->leadtracapi->GetAccountLoans($fileNumber);
			$PaymentPlans = $this->leadtracapi->GetPaymentPlans($fileNumber);

			if (!$Loans)
			return Redirect::to('step4');
			//			return View::make('client_step5', array('LoanData' => $Loans));
			$this->layout->content .= View::make('client_step5', array('LoanData' => $Loans, 'Plans' => $PaymentPlans));
		}

	}

	public function Step5Form()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		// They have submitted a plan.
		$fileNumber = Session::get('fileNumber');

		if ($fileNumber)
		{
			// See what plan they submitted for.
			$SelectedPlan = Input::get('repayment_plan');
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
					//for now we are returning them to Step5
					return Redirect::to('step5');
					//we would throw an error here, becuase we can't handle this update. or they didn't provide one.
					//we can use Flash Data to throw the error. But we need it to be on the Step.
					break;
			}
			$UpdateFields[$this->tracking_field_name] = $this->tracking_field_value;
			//lets update this client
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $UpdateFields, $fileNumber);
			// Update there status, we selected a program!

			//get the client
			$client = $this->leadtracapi->GetClient($fileNumber, array('FirstName', 'LastName'));


			if ($client->CompletedStep <= 4)
			$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['Program Selected']);

			// Send them to Step 6. Now they are choosing the Cart Options & Presented with the Greeting on how much there Plan is!
			return Redirect::to('step6');
		}

	}

	/**
	 * Step 6. Viewing the Plan they selected & Giving them the option to order the Products.
	 */
	public function Step6()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');



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
			// We need to switch the Loan_Program to there 3 letter format
			//		$Static_Plans = Array('IBR', 'ICR', 'STD', 'GRAD', 'EXF', 'EXG', 'PAY');
			//			die(print_r($Client,1));
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
			// alright now we should know the amount based off the $Plan


			if (isset($PaymentPlans->{$Plan}))
			{
				// Their new monthly repayment amount is $PaymentPlans->{$Plan}->Payment
				$NewPayment = $PaymentPlans->{$Plan}->Payment;

			}

			//load up the status session
			$this->TranslateOrderFromStatus($Client->Status->Sales, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance);
			Session::put('ordered_cpn',$ordered_cpn);
			Session::put('ordered_repayment_promisory_note',$ordered_repayment_promisory_note);
			Session::put('ordered_pslf',$ordered_pslf);
			Session::put('ordered_forebearance',$ordered_forebearance);

			$paidfor_variable = 'Paid_Packages';

			// if they ordered anything i need to know & check for payments or halt them here.
			if ($ordered_cpn || $ordered_repayment_promisory_note || $ordered_pslf || $ordered_forebearance)
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

							if ($products->cpn || $products->rpn || $products->pslf || $products->fb)
							{
								// alright we have a product, lets get the status from these.
								$SwitchStatus = $this->TranslateStatusFromOrder($Client->TProperties->Loan_Program, $products->cpn, $products->rpn, $products->pslf, $products->fb);
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
							return Redirect::to('step6');
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
								return Redirect::to('step6');
							}
						}
						//						if ($logs->Log->searched)
						//						{
						//							if ($logs->Log->Status != 'Approved')
						//							{
						//								//die we failed!
						//							}
						//						}

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

					$this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('Paid_Products' => json_encode(array('cpn' => $ordered_cpn, 'rpn' => $ordered_repayment_promisory_note, 'pslf' => $ordered_pslf, 'fb' => $ordered_forebearance))), $fileNumber);

				}
			}

			// Which Forms can they order?
			//Temp, lets show them all the forms.
			if (!isset($Dontshow_step6))
			{
				$this->layout->content .= View::make('client_step6', array('LoanType' => strtolower($Loans->Plan),
				'NewPayment' => $NewPayment,
				'Plan' => $Plan,
				'PublicService' => (isset($Client->TProperties->PublicService) && strtolower($Client->TProperties->PublicService) == 'yes' ? true : false ),
				'HasDefaulted' => $Loans->HasDefaulted,
				'HasForbearance' => $Loans->HasForbearance));
			}


		}

	}
	public function PaymentPendingWait()
	{
		$this->layout->content .= View::make('client_payment_pending_wait');

	}
	public function Step6Form()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		//Handle the Form Content
		$fileNumber = Session::get('fileNumber');

		// at the moment, lets just imagine that we are in a perfect world and the post data will come as expected.
		// they must select at-least ONE option here. Or we place an error in the Session ERROR_STEP6 telling them they need to select at-least 1 option.

		$Ordered = array();

		// alright lets find out what they ordered

		//items for re-payment (already consolidated)
		if (Input::get('repayment_promisory_note'))
		$Ordered[] = 'repayment_promisory_note';

		//items for consolidation forms
		if (Input::get('consolidation_promisory_note'))
		$Ordered[] = 'consolidation_promisory_note';

		if (Input::get('repayment_promisory_note_1'))
		$Ordered[] = 'repayment_promisory_note_1';

		//items for consolidation forms
		if (Input::get('consolidation_promisory_note_1'))
		$Ordered[] = 'consolidation_promisory_note_1';

		if (Input::get('pslf_app'))
		$Ordered[] = 'pslf_app';

		if (Input::get('forebearance_app'))
		$Ordered[] = 'forebearance_app';

		

		if (!count($Ordered) && !(Session::get('ordered_cpn') || Session::get('ordered_repayment_promisory_note') || Session::get('ordered_pslf') || Session::get('ordered_forebearance')))
		{
			// flash the session
			Session::flash('ERROR_STEP6', 'Error: You must select an application you wish to have generated for you.');
			return Redirect::to('step6');
		}

		Session::put('order', $Ordered);
		$ClientData = array();

		// we need to calculate the total program costs.
		$CartItems = $Ordered;
		$CartTotal = 0;

		$ordered_cpn = false;
		$ordered_repayment_promisory_note = false;
		$ordered_forebearance = false;
		$ordered_pslf = false;

		foreach ($CartItems as $CartItem)
		{
			switch (strtolower($CartItem))
			{
				// _1 is for the cheap version. (DISCOUNTED)
				case 'consolidation_promisory_note_1':
					$CartTotal+= 99.00;
					$ordered_cpn = true;
					break;
					// case 'consolidation_promisory_note':
					// $CartTotal+= 179.00;
					// $ordered_cpn = true;
					// break;
				case 'consolidation_promisory_note':
					$CartTotal+= 99.00;
					$ordered_cpn = true;
					break;
					
					//No Repayment Promisory Notes.
					
				case 'repayment_promisory_note_1':
					$CartTotal+= 99.00;
					$ordered_repayment_promisory_note = true;
					break;
					// case 'repayment_promisory_note':
					// $CartTotal+= 179.00;
					// $ordered_repayment_promisory_note = true;
					// break;
				case 'repayment_promisory_note':
					$CartTotal+= 99.00;
					$ordered_repayment_promisory_note = true;
					break;
				case 'forebearance_app':
					$CartTotal += 9.99;
					$ordered_forebearance = true;
					break;
				case 'pslf_app':
					$CartTotal += 9.99;
					$ordered_pslf = true;
					break;
			}
		}
		if (0 >= $CartTotal && !(Session::get('ordered_cpn') || Session::get('ordered_repayment_promisory_note') || Session::get('ordered_pslf') || Session::get('ordered_forebearance')))
		{
			//Send them back to Step6 with an error
			Session::flash('ERROR_STEP6', 'Error: You must select an application you wish to have generated for you. 1');
			return Redirect::to('step6');
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
		if (Session::get('ordered_cpn'))
		$ordered_cpn = true;

		if (Session::get('ordered_repayment_promisory_note'))
		$ordered_repayment_promisory_note = true;

		if (Session::get('ordered_pslf'))
		$ordered_pslf = true;

		if (Session::get('ordered_forebearance'))
		$ordered_forebearance = true;

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
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						//
						$order_statusName = 'Conso App IBR / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Conso App IBR / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Conso App IBR / FB Sent';
					}
					else
					{
						//only cons app.
						$order_statusName = 'Conso App IBR Sent';
					}
				}
				elseif ($ordered_repayment_promisory_note) // repay
				{
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						$order_statusName = 'Repay IBR App / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Repay IBR App / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Repay IBR App / FB Sent';
					}
					else
					{
						//only repay app
						$order_statusName = 'Repay IBR App Sent';
						
					}
				}

				elseif ($ordered_pslf && $ordered_forebearance)
				{
					//new status
					$order_statusName = 'Forbearance / Forgiveness App Sent';
				}
				elseif ($ordered_pslf)
				{
					// ordered only pslf
					$order_statusName = 'Forgiveness App Sent';
				}
				elseif ($ordered_forebearance)
				{
					//ordered only cpn & forebearance
					$order_statusName = 'Forbearance App Sent';
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
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						$order_statusName = 'Conso Stand App Sent / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Conso Stand App Sent / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Conso Stand App Sent / FB';
					}
					else
					{
						//only cons app.
						$order_statusName = 'Conso Stand App Sent';
					}
				}
				elseif ($ordered_repayment_promisory_note) // repay
				{
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						$order_statusName = 'Repay Stand App / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Repay Stand App / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Repay Stand App / FB Sent';
					}
					else
					{
						//only repay app
						$order_statusName = 'Repay Stand App Sent';
					}
				}

				elseif ($ordered_pslf && $ordered_forebearance)
				{
					//new status
					$order_statusName = 'Forbearance / Forgiveness App Sent';
				}
				elseif ($ordered_pslf)
				{
					// ordered only pslf
					$order_statusName = 'Forgiveness App Sent';
				}
				elseif ($ordered_forebearance)
				{
					//ordered only cpn & forebearance
					$order_statusName = 'Forbearance App Sent';
				}

				break;

			default:
				if ($ordered_pslf && $ordered_forebearance)
				{
					//new status
					$order_statusName = 'Forbearance / Forgiveness App Sent';
				}
				elseif ($ordered_pslf)
				{
					// ordered only pslf
					$order_statusName = 'Forgiveness App Sent';
				}
				elseif ($ordered_forebearance)
				{
					//ordered only cpn & forebearance
					$order_statusName = 'Forbearance App Sent';
				}
				break;


		}

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

		$clientinfo = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName','ClientLogin','ClientPassword', 'client_city', 'client_region', 'client_zipcode'));


		$this->layout->content .= View::make('crm_login',array('UserName' => $clientinfo->TProperties->ClientLogin,'Password' => $clientinfo->TProperties->ClientPassword));

	}

	/**
	 * Page for the Updating saying success
	 */
	public function UpdateSuccess()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;


		$this->layout->content .= View::make('client_thankyou', array('update_complete' => true));
	}
	/**
	 * Payment Information, also we'll verify that they have an order? Don't need to though
	 *
	 */
	public function Step7()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();

		$ClientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), Array('Payment1Amount'));

		// Years to accomdate credit card expirations. Paypal recommends +19 years in the future.
		$exp_years = range(date('Y'), date('Y')+19);
		$country_array = array(
		"US" => "United States","GB" => "United Kingdom","CN" => "China","AT" => "Austria",
		"AF" => "Afghanistan","AL" => "Albania","DZ" => "Algeria","AS" => "American Samoa","AD" => "Andorra","AO" => "Angola","AI" => "Anguilla","AQ" => "Antarctica","AG" => "Antigua and Barbuda","AR" => "Argentina","AM" => "Armenia","AW" => "Aruba","AU" => "Australia",
		"AZ" => "Azerbaijan","BS" => "Bahamas","BH" => "Bahrain","BD" => "Bangladesh","BB" => "Barbados","BY" => "Belarus","BE" => "Belgium","BZ" => "Belize","BJ" => "Benin","BM" => "Bermuda","BT" => "Bhutan","BO" => "Bolivia","BA" => "Bosnia and Herzegovina","BW" => "Botswana","BV" => "Bouvet Island","BR" => "Brazil","BQ" => "British Antarctic Territory","IO" => "British Indian Ocean Territory","VG" => "British Virgin Islands","BN" => "Brunei","BG" => "Bulgaria","BF" => "Burkina Faso","BI" => "Burundi",
		"KH" => "Cambodia","CM" => "Cameroon","CA" => "Canada","CT" => "Canton and Enderbury Islands","CV" => "Cape Verde","KY" => "Cayman Islands","CF" => "Central African Republic","TD" => "Chad","CL" => "Chile","CX" => "Christmas Island","CC" => "Cocos [Keeling] Islands","CO" => "Colombia","KM" => "Comoros","CG" => "Congo - Brazzaville","CD" => "Congo - Kinshasa","CK" => "Cook Islands","CR" => "Costa Rica","HR" => "Croatia","CU" => "Cuba","CY" => "Cyprus","CZ" => "Czech Republic",
		"CI" => "C�te d�Ivoire","DK" => "Denmark","DJ" => "Djibouti","DM" => "Dominica","DO" => "Dominican Republic","NQ" => "Dronning Maud Land","DD" => "East Germany","EC" => "Ecuador","EG" => "Egypt","SV" => "El Salvador","GQ" => "Equatorial Guinea","ER" => "Eritrea","EE" => "Estonia","ET" => "Ethiopia","FK" => "Falkland Islands",
		"FO" => "Faroe Islands","FJ" => "Fiji","FI" => "Finland","FR" => "France","GF" => "French Guiana","PF" => "French Polynesia","TF" => "French Southern Territories","FQ" => "French Southern and Antarctic Territories","GA" => "Gabon","GM" => "Gambia",
		"GE" => "Georgia","DE" => "Germany","GH" => "Ghana","GI" => "Gibraltar","GR" => "Greece","GL" => "Greenland","GD" => "Grenada","GP" => "Guadeloupe","GU" => "Guam","GT" => "Guatemala","GG" => "Guernsey","GN" => "Guinea","GW" => "Guinea-Bissau",
		"GY" => "Guyana","HT" => "Haiti","HM" => "Heard Island and McDonald Islands","HN" => "Honduras","HK" => "Hong Kong SAR China","HU" => "Hungary","IS" => "Iceland","IN" => "India","ID" => "Indonesia","IR" => "Iran","IQ" => "Iraq","IE" => "Ireland","IM" => "Isle of Man","IL" => "Israel","IT" => "Italy","JM" => "Jamaica","JP" => "Japan","JE" => "Jersey","JT" => "Johnston Island","JO" => "Jordan","KZ" => "Kazakhstan","KE" => "Kenya","KI" => "Kiribati","KW" => "Kuwait","KG" => "Kyrgyzstan","LA" => "Laos","LV" => "Latvia","LB" => "Lebanon","LS" => "Lesotho","LR" => "Liberia","LY" => "Libya","LI" => "Liechtenstein","LT" => "Lithuania","LU" => "Luxembourg","MO" => "Macau SAR China","MK" => "Macedonia","MG" => "Madagascar","MW" => "Malawi","MY" => "Malaysia","MV" => "Maldives",
		"ML" => "Mali","MT" => "Malta","MH" => "Marshall Islands","MQ" => "Martinique","MR" => "Mauritania","MU" => "Mauritius","YT" => "Mayotte","FX" => "Metropolitan France","MX" => "Mexico","FM" => "Micronesia","MI" => "Midway Islands","MD" => "Moldova","MC" => "Monaco","MN" => "Mongolia","ME" => "Montenegro","MS" => "Montserrat","MA" => "Morocco","MZ" => "Mozambique","MM" => "Myanmar [Burma]","NA" => "Namibia","NR" => "Nauru","NP" => "Nepal","NL" => "Netherlands","AN" => "Netherlands Antilles","NT" => "Neutral Zone","NC" => "New Caledonia","NZ" => "New Zealand","NI" => "Nicaragua","NE" => "Niger","NG" => "Nigeria","NU" => "Niue","NF" => "Norfolk Island","KP" => "North Korea","VD" => "North Vietnam","MP" => "Northern Mariana Islands","NO" => "Norway","OM" => "Oman","PC" => "Pacific Islands Trust Territory","PK" => "Pakistan","PW" => "Palau","PS" => "Palestinian Territories","PA" => "Panama","PZ" => "Panama Canal Zone","PG" => "Papua New Guinea","PY" => "Paraguay",
		"YD" => "People's Democratic Republic of Yemen","PE" => "Peru","PH" => "Philippines","PN" => "Pitcairn Islands","PL" => "Poland","PT" => "Portugal","PR" => "Puerto Rico","QA" => "Qatar","RO" => "Romania","RU" => "Russia","RW" => "Rwanda","RE" => "R�union","BL" => "Saint Barth�lemy","SH" => "Saint Helena","KN" => "Saint Kitts and Nevis","LC" => "Saint Lucia","MF" => "Saint Martin","PM" => "Saint Pierre and Miquelon","VC" => "Saint Vincent and the Grenadines","WS" => "Samoa","SM" => "San Marino","SA" => "Saudi Arabia","SN" => "Senegal","RS" => "Serbia","CS" => "Serbia and Montenegro","SC" => "Seychelles","SL" => "Sierra Leone","SG" => "Singapore","SK" => "Slovakia","SI" => "Slovenia","SB" => "Solomon Islands","SO" => "Somalia","ZA" => "South Africa","GS" => "South Georgia and the South Sandwich Islands","KR" => "South Korea","ES" => "Spain","LK" => "Sri Lanka","SD" => "Sudan","SR" => "Suriname","SJ" => "Svalbard and Jan Mayen","SZ" => "Swaziland","SE" => "Sweden","CH" => "Switzerland","SY" => "Syria","ST" => "S�o Tom� and Pr�ncipe","TW" => "Taiwan","TJ" => "Tajikistan","TZ" => "Tanzania","TH" => "Thailand","TL" => "Timor-Leste","TG" => "Togo","TK" => "Tokelau","TO" => "Tonga","TT" => "Trinidad and Tobago","TN" => "Tunisia","TR" => "Turkey","TM" => "Turkmenistan","TC" => "Turks and Caicos Islands","TV" => "Tuvalu","UM" => "U.S. Minor Outlying Islands","PU" => "U.S. Miscellaneous Pacific Islands","VI" => "U.S. Virgin Islands","UG" => "Uganda","UA" => "Ukraine","SU" => "Union of Soviet Socialist Republics","AE" => "United Arab Emirates","UY" => "Uruguay","UZ" => "Uzbekistan","VU" => "Vanuatu","VA" => "Vatican City","VE" => "Venezuela","VN" => "Vietnam","WK" => "Wake Island","WF" => "Wallis and Futuna","EH" => "Western Sahara","YE" => "Yemen","ZM" => "Zambia","ZW" => "Zimbabwe","AX" => "�land Islands",);

		$this->layout->content .= View::make('client_step7', array('exp_years' => $exp_years, 'TotalDue' => @number_format($ClientDetails->TProperties->Payment1Amount,2)));
	}

	/**
	 * Save the Payment information to their account.
	 * We are using Authorize.net, so, set the payment information!
	 */

	public function Step7Form()
	{
		if (!$this->IsAuthencated())
		return $this->RedirectNotAuthed();


		/**
		 * Make sure they have provided all of the fields we need!
		 */
		$ValidationRules =     array(
		//billing information

		'Mailing_Address_first_name' => 'required',
		'Mailing_Address_last_name' => 'required',
		'Mailing_Address' => 'required',
		'Mailing_Address_city' => 'required',
		'Mailing_Address_country' => 'required',
		'Mailing_Address_state' => 'required',
		'Mailing_Address_zipcode' => 'required',
		'Mailing_Address_email' => 'required|email',

		// required if !sameasmailing
		'Billing_first_name' => 'required_without:sameasmailing',
		'Billing_last_name' => 'required_without:sameasmailing',
		'Billing_address' => 'required_without:sameasmailing',
		'Billing_city' => 'required_without:sameasmailing',
		'Billing_country' => 'required_without:sameasmailing',
		'Billing_state' => 'required_without:sameasmailing',
		'Billing_zipcode' => 'required_without:sameasmailing',
		'Billing_email' => 'required_without:sameasmailing|email',

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
		'AccountFirstName' => Input::get('Mailing_Address_first_name'),
		'AccountLastName' => Input::get('Mailing_Address_last_name'),
		'AccountOwnerAddress' => Input::get('Mailing_Address'),
		'AccountOwnerCity' => Input::get('Mailing_Address_city'),
		'AccountOwnerState' => Input::get('Mailing_Address_state'),
		'AccountOwnerZipCode' => Input::get('Mailing_Address_zipcode'),


		// the credit card name is default to the mailing address
		'CreditCardName' => Input::get('billing_name'),


		'CreditCardType' => $this->cardType(Input::get('card_number')),

		);

		// Mailing Data
		$ClientData['AddressLine1'] = Input::get('Mailing_Address');
		$ClientData['ZipSel'] = Input::get('Mailing_Address_city') .', '.Input::get('Mailing_Address_state') ;
		$ClientData['City'] = Input::get('Mailing_Address_city') ;
		$ClientData['State'] = Input::get('Mailing_Address_state') ;
		$ClientData['ZipCode'] = Input::get('Mailing_Address_zipcode') ;

		// Billing Data (it's defaulted to the mailing information, but if they didn't check that box lets update that information

		if (!Input::get('sameasmailing'))
		{
			$ClientData['AccountFirstName'] = Input::get('Billing_first_name');
			$ClientData['AccountLastName'] = Input::get('Billing_last_name');
			$ClientData['AccountOwnerAddress'] = Input::get('Billing_Address');
			$ClientData['AccountOwnerCity'] = Input::get('Billing_city');
			$ClientData['AccountOwnerState'] = Input::get('Billing_state');
			$ClientData['AccountOwnerZipCode'] = Input::get('Billing_zipcode');

		}

		// we need to calculate the total program costs.
		$CartItems = Session::get('order');
		$CartTotal = 0;

		$ordered_cpn = false;
		$ordered_repayment_promisory_note = false;
		$ordered_forebearance = false;
		$ordered_pslf = false;

		foreach ($CartItems as $CartItem)
		{
			switch (strtolower($CartItem))
			{
				case 'consolidation_promisory_note_1':
					$CartTotal+= 99.00;
					$ordered_cpn = true;
					break;
				case 'consolidation_promisory_note':
					$CartTotal+= 99.00;
					$ordered_cpn = true;
					break;
				case 'repayment_promisory_note_1':
					$CartTotal+= 99.00;
					$ordered_repayment_promisory_note = true;
					break;
				case 'repayment_promisory_note':
					$CartTotal+= 99.00;
					$ordered_repayment_promisory_note = true;
					break;
				case 'forebearance_app':
					$CartTotal += 9.99;
					$ordered_forebearance = true;
					break;
				case 'pslf_app':
					$CartTotal += 9.99;
					$ordered_pslf = true;
					break;
			}
		}
		if (0 >= $CartTotal)
		{
			//Send them back to Step6 with an error
			Session::flash('ERROR_STEP6', 'Error: You must select an application you wish to have generated for you.');
			return Redirect::to('step6');
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


		if (Session::get('fileNumber'))
		//alright we have all of their information, save the client details, then update the status to what they had ordered.
		{
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));



			// we need to set their status to process payments.
			// also, lets see what happens when I do this!
			$paymentProcessedStatus = $this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $this->statusUpdates['Process Payments']);


			//see if they need to pay for something, change their status
			$OrderStatusID=Session::get('ORDER_COMPLETE_SET_STATUS');
			if ($OrderStatusID)
			$set_ordered_products_Status = $this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $OrderStatusID);
			// We need to move them to the ProcessPayment page
			return Redirect::to('processpayment');
		}



		//Make sure all of the account information entered
	}


	// this shows the wait page for process payments.
	public function ProcessPayment()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;

		$this->layout->content .= View::Make('client_thankyou');
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
				$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['Payments Received']);

				// generate forms:: we have to use the collelation chart provided (on the google drive).
				if ($return_bool)
				return true;

				return Redirect::to('paymentcompleted'); // we are done!
			}

			if ($payment->Payment_Declined || $payment->Payment_Failed)
			{
				if ($return_bool)
				return false;

				return Redirect::to('paymentfailed');
			}

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
		return Redirect::to('/step7');

		$this->layout->content .= View::make('client_paymentfail');
	}

	public function PaymentDeclined()
	{
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

		$this->TranslateOrderFromStatus("false", $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance);
		Session::put('ordered_cpn',$ordered_cpn);
		Session::put('ordered_repayment_promisory_note',$ordered_repayment_promisory_note);
		Session::put('ordered_pslf',$ordered_pslf);
		Session::put('ordered_forebearance',$ordered_forebearance);

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
			$client = $this->leadtracapi->GetClient($search, array('FirstName', 'LastName', 'Loan_Program'));
			$upgrade = array('100% Payments Received', 'Process Payments');

			session::forget('Step3Completed');
			$this->TranslateOrderFromStatus($client->Status->Sales, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance);
			Session::put('ordered_cpn',$ordered_cpn);
			Session::put('ordered_repayment_promisory_note',$ordered_repayment_promisory_note);
			Session::put('ordered_pslf',$ordered_pslf);
			Session::put('ordered_forebearance',$ordered_forebearance);



			if (isset($client->Status->Accounting) && in_array($client->Status->Accounting,$upgrade))
			{
				Session::put('ClientUpgrade', true);

			}
			else
			{
				Session::put('ClientUpgrade', false);
			}
		}

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

	public function TranslateOrderFromStatus($status, &$ordered_cpn=false,&$ordered_repayment_promisory_note=false,&$ordered_pslf=false, &$ordered_forebearance = false)
	{
		$status = trim($status);

		$ordered_cpn=false;
		$ordered_repayment_promisory_note=false;
		$ordered_pslf=false;
		$ordered_forebearance=false;

		//		die(substr($status,0,strlen('Repay Stand App')));

		if (substr($status,0,13) == 'Conso App IBR')
		$ordered_cpn = true;

		elseif (substr($status,0,strlen('Repay Stand App')) == 'Repay Stand App' || substr($status,0,strlen('Repay IBR App')) == 'Repay IBR App')
		$ordered_repayment_promisory_note = true;


		switch (trim($status))
		{
			case 'Forbearance / Forgiveness App Sent':
			case 'Repay IBR App / FB / PSLF Sent':
			case 'Repay Stand App / FB / PSLF Sent':
			case 'Conso App IBR / FB / PSLF Sent':
			case 'Conso Stand App Sent / FB / PSLF Sent':
				$ordered_pslf = true;
				$ordered_forebearance = true;
				break;

			case 'Forgiveness App Sent':
			case 'Repay IBR App / PSLF Sent':
			case 'Repay Stand App / PSLF Sent':
			case 'Conso Stand App Sent / PSLF Sent':
			case 'Conso App IBR / PSLF Sent':
				$ordered_pslf = true;
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
	function TranslateStatusFromOrder($Loan_Program,$ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance, &$newStatusId)
	{
		$Loan_Program = strtolower($Loan_Program);

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
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						//
						$order_statusName = 'Conso App IBR / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Conso App IBR / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Conso App IBR / FB Sent';
					}
					else
					{
						//only cons app.
						$order_statusName = 'Conso App IBR Sent';
					}
				}
				elseif ($ordered_repayment_promisory_note) // repay
				{
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						$order_statusName = 'Repay IBR App / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Repay IBR App / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Repay IBR App / FB Sent';
					}
					else
					{
						//only repay app
						$order_statusName = 'Repay IBR App Sent';
					}
				}

				elseif ($ordered_pslf && $ordered_forebearance)
				{
					//new status
					$order_statusName = 'Forbearance / Forgiveness App Sent';
				}
				elseif ($ordered_pslf)
				{
					// ordered only pslf
					$order_statusName = 'Forgiveness App Sent';
				}
				elseif ($ordered_forebearance)
				{
					//ordered only cpn & forebearance
					$order_statusName = 'Forbearance App Sent';
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
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						$order_statusName = 'Conso Stand App Sent / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Conso Stand App Sent / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Conso Stand App Sent / FB';
					}
					else
					{
						//only cons app.
						$order_statusName = 'Conso Stand App Sent';
					}
				}
				elseif ($ordered_repayment_promisory_note) // repay
				{
					if ($ordered_pslf && $ordered_forebearance)
					{
						//new status
						$order_statusName = 'Repay Stand App / FB / PSLF Sent';
					}
					elseif ($ordered_pslf)
					{
						// ordered only pslf
						$order_statusName = 'Repay Stand App / PSLF Sent';
					}
					elseif ($ordered_forebearance)
					{
						//ordered only cpn & forebearance
						$order_statusName = 'Repay Stand App / FB Sent';
					}
					else
					{
						//only repay app
						$order_statusName = 'Repay Stand App Sent';
					}
				}

				elseif ($ordered_pslf && $ordered_forebearance)
				{
					//new status
					$order_statusName = 'Forbearance / Forgiveness App Sent';
				}
				elseif ($ordered_pslf)
				{
					// ordered only pslf
					$order_statusName = 'Forgiveness App Sent';
				}
				elseif ($ordered_forebearance)
				{
					//ordered only cpn & forebearance
					$order_statusName = 'Forbearance App Sent';
				}

				break;

			default:
				if ($ordered_pslf && $ordered_forebearance)
				{
					//new status
					$order_statusName = 'Forbearance / Forgiveness App Sent';
				}
				elseif ($ordered_pslf)
				{
					// ordered only pslf
					$order_statusName = 'Forgiveness App Sent';
				}
				elseif ($ordered_forebearance)
				{
					//ordered only cpn & forebearance
					$order_statusName = 'Forbearance App Sent';
				}
				break;


		}

		// status number:



		if (isset($this->statusUpdates['all']->{$order_statusName}))
		{
			$newStatusId = $this->statusUpdates['all']->{$order_statusName};
			return $order_statusName;
		}

		else
		{
			return false;
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('<b>System Error:</b> Unable to find the Status ID from your order. Status: ', @$order_statusName);

			return Redirect::to('step6')->withErrors($messages);
		}

	}
}
