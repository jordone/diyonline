<?php


error_reporting(0);


class ClientController extends BaseController {

	protected $layout = 'layouts.master';

	public function __construct() {
		/** Setup our API Library **/

        $this->Leadtrac_Init();

//uncomment for development only!!!
//        Session::put('fileNumber','DY361.083540');
//        Session::flash('dont_auto_logout', true);
//        Session::put('Step3Completed', true);
//        Session::put('accepted_disclosure', 1);



//		Admin login BLESS YOU
		if (Input::get('fn'))
		{
			Session::put('fileNumber', $_GET['fn']);
			Session::flash('dont_auto_logout', true);
			Session::put('Step3Completed', true);
            Session::put('accepted_disclosure', 1);

			if (isset($_GET['fs']))
			{
				$loadstepsint = $_GET['fs'];
			}
			else $loadstepsint = step1 | step2 | step3;


			Session::put('LoadSteps', $loadstepsint);
		}
		else
		{
			Session::forget('auth_office_use'); // PRZ ; ; thx?
			Session::put('LoadSteps', step1 | step2 | step3);
		}



	}

    public function RegisterBrowserState($ActiveBrowserState, $ActivePageVariables = array())
    {
        if (Session::get('fileNumber'))
        {
            $this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('BrowserLastState' => $ActiveBrowserState, 'BrowserLastStateArguments' => $ActivePageVariables));
        }
    }

    public function GetOwnersBrowserID()
    {
        // This will return the owners browser id, so that if it's the same it will record that this state happened.
        $client = $this->GetClientFromSession();
        //'BrowserLastState', 'BrowserLastState_Arguments','BrowserOwnerID',

        if (isset($client->TProperties->BrowserOwnerID) && $client->TProperties->BrowserOwnerID)
        {
            return $client->TProperties->BrowserOwnerID;
        }
        else return false;
    }

    /**
     * Compares the browser id with the current session id.
     * if this session id is not the one editing this loan, it'll ask them to load again - registering their browser as the primary edit id.
     * preventing any other browser session from making modifications.
     * @return bool
     */
    public function System_Compare_Browser_ID()
    {
        $clients_browser_id = Session::getId();
        if ($this->GetOwnersBrowserID() == $clients_browser_id)
        {
			if (Input::get('csl'))
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('BrowserUpdatedOn'=> date('c'), 'BrowserCSL' => Input::get('csl')), Session::get('fileNumber'));
			else
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('BrowserUpdatedOn'=> date('c'), 'BrowserCSL' => 'Unknown or not set'), Session::get('fileNumber'));

            return true;
        }
        return false;
    }

    public function System_Register_With_Clients_BrowserID()
    {
        if (Session::get('fileNumber'))
        {
            $this->leadtracapi->CreateOrUpdateClient($this->campaignId, array('BrowserOwnerID' => $this->System_Get_Browser_ID()), Session::get('fileNumber'));

        }
        return array('register' => true);

    }

    public function System_Get_Browser_ID()
    {
        $browser_id = Session::getId();
        return $browser_id;
    }

    /**
     * Just testing our git repository!
     * @param array $parameters
     *
     */
	public function GetError404Page($parameters = array())
	{
		$this->layout->content = View::make('error404');
	}


	public function DisclosureForm()
	{
		if (Input::get('accept'))
		{
			Session::put('accepted_disclosure', 1);
			Session::flash('dont_auto_logout', 1);
		}
        if (Input::get('rd'))
        {
            $redirect_url = Crypt::decrypt(Input::get('rd'));
            if ($redirect_url)
            {
                return Redirect::to($redirect_url);
            }
        }

		return Redirect::to('/');
	}

	public function privacypolicy()
	{
		$this->layout->content .=  View::make('privacypolicy');

	}

	public function NewClientForm()
	{

        if (!Session::get('dont_auto_logout'))
        {

            $this->LogOutActions();
            Session::forget('fileNumber');
            Session::forget('Step3Completed');
            Session::flash('dont_auto_logout',1);
            return $this->RedirectWithError('/', "You have been logged out.");
        }

		// restore the accepted disclosure session variable.

		$LoadStepsBit = Session::get('LoadSteps');
		if (!$LoadStepsBit) $LoadStepsBit = 0;
        $LoadStepsBit = step1 | step2 | step3 | step4 | step5 | step6 | step7;

		// Check if they have client details, and we'll display this with that data!
		if (Session::get('fileNumber'))
		{

//			$clientDetails = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName',  'EmailAddress', 'HomeNumber' ));
			$clientDetails = $this->GetClientFromSession(true);

            $LoadStepsBit = step1 | step2 | step3 | step4 | step5 | step6 | step7;


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
				$this->layout->content .= $this->Step4Services();
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

			$response->success = true;

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

		$parms = array('FirstName' => $first_name, 'LastName' => $last_name,
            'Name1Last2' => $LastNameFirst2,
            'EmailAddress' => $email, 'HomeNumber' => $phone_number,
            'LeadSource' => $lead_source, 'Campaign' => "New Lead", "EDAOption" => "Yes", "USERNAME" => "diy.admin");
		$parms[$this->tracking_field_name] = $this->tracking_field_value;

		$parms['client_ip'] = $_SERVER['REMOTE_ADDR'];
		$parms['client_browser'] = $_SERVER['HTTP_USER_AGENT'];

		$geoip = new GeoipHelper();

		$parms['client_city'] = $geoip->city;
		$parms['client_region'] = $geoip->region;
		$parms['client_zipcode'] = $geoip->postal_code;
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
            $parms['BrowserOwnerID'] = $this->System_Get_Browser_ID();

			$fileNumber = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $parms);
		}



		//we created the customer and received the file id.
		if ($fileNumber)
		{
			Session::put('fileNumber', $fileNumber);

            $this->ClientUpdatedProfile($fileNumber, step1);

			//error after update :p. preserving the fields they posted.
			return Redirect::to('step2');
		}

		else
		{
			// Error.
			return Redirect::to('step1')->withErrors($Validate);
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

        if ($Validate->fails())
            return Redirect::to('step2')->withErrors($Validate)->withInput();


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
            $this->ClientUpdatedProfile($fileNumber, step2);


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


	public function Step3FormSSN4Only()
	{
		$fileNumber = Session::get('fileNumber');
		$clientdetails = $this->GetClientFromSession();
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
        $this->ClientUpdatedProfile($fileNumber, step3);
        $clientdetails = $this->GetClientFromSession(true);



			if ($clientdetails->CompletedStep == 2 || $clientdetails->CompletedStep == 3)
			{
				$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step3']);
			}

			Session::put('Step3Completed',true);

			## Update, redirect to select the form they want.
			return Redirect::to('step4');

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
        return true;
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
            $this->ClientUpdatedProfile($fileNumber, step4);

			if ($cart_items == 0 && $clientdetails->TProperties->products_already_purchased)
			{
				// we're not even ordering we are updating only.
				// we need to move them to upgrading
				// we need to tell the cart its an update
				Session::put('cart_type', 'update');

				if ($clientdetails->TProperties->products_already_purchased & cart_product_consolidation_app || $clientdetails->TProperties->products_already_purchased & cart_product_repayment_app || $clientdetails->TProperties->products_already_purchased & cart_product_recertification_app )
				{
					Session::put('cart_type', 'update');

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
			// PRZ Skip to Step 7 for Recertification & Forbearance
			// PRZ if ($cart_items & cart_product_consolidation_app || $cart_items & cart_product_repayment_app || $cart_items & cart_product_recertification_app )
			if ($cart_items & cart_product_consolidation_app || $cart_items & cart_product_repayment_app )
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


	public function Step5Form()
	{
		if (!$this->IsAuthencated())
			return $this->RedirectNotAuthed();

		$fileNumber = Session::get('fileNumber');
		$clientdetails = $this->GetClientFromSession(false);

		$ValidationRules = array(
//			'username' => 'required',
//			'password' => 'required',
			'file' => ''
		);

		$Validate = Validator::make(Input::all(), $ValidationRules);

		if ($Validate->fails())
			return Redirect::to('step5')->withErrors($Validate);

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
			// @mail('paulzimm@gmail.com', '$uploadfile', print_r($imagedata, true), 'From: debug@'.$_SERVER['HTTP_HOST']); # PRZ
			$imagedata = mb_substr($imagedata, 0, 32500); // PRZ Trim 32710 (32,767 Max chars)
			$assetBytes = base64_encode($imagedata);

			$assetsList = $this->leadtracapi->GetAssetTypesList();
			$assetsLists = $assetsList->AssetTypes->AssetType;
			// @mail('paulzimm@gmail.com', '$assetsLists', print_r($assetsLists, true), 'From: debug@'.$_SERVER['HTTP_HOST']); # PRZ  - Why not dd($assetLists) like a pro?

			foreach ($assetsLists as $assetName => $assetId) {
				if ($assetId->Name == 'Student Loan File') {
					$assetTypeId = $assetId->Id;
					$assetFileName = $fileName;
					$assetDescription = 'Student Loan File Uploaded';
					$assetsResult = $this->leadtracapi->AddAssets($fileNumber, $assetTypeId, $assetFileName, $assetDescription, $assetBytes);
                    break;
				}
			}


		} else {
			// file upload is ignored , lets login.

		}

		// exit;
		$updateFields = array();
		// $updateFields['DOEPin'] = $fafsapin;
		// $updateFields['ClientLogin'] = $username;
		// $updateFields['ClientPassword'] = $password;

		$updateFields[$this->tracking_field_name] = $this->tracking_field_value;

		// lets check if they completed step4 if not, add it.
		if ( !($clientdetails->TProperties->completed_steps_bitwise & step5) )
		{
			$updateFields['completed_steps_bitwise'] = @$clientdetails->TProperties->completed_steps_bitwise | step5;
			// we need to update there status to Products Selected
		}

		if ($fileNumber)
		{
			// ClientLogin, ClientPassword
			$UpdateStatus = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $fileNumber);

			if ($UpdateStatus !== false)
			{

				/**
					 * Client completed step4. move them to Step 5.
					 */
				$client = $clientDetails = $clientdetail = $clientdetails = $this->GetClientFromSession(true);



				if ($clientdetails->CompletedStep >= 4)
				{
					// check if we need ot change them to FAFSFA Pin (if its not already at step four.

                    /**
                     * @todo Check if we even use this status any longer.
                     */
					// if ($clientdetails->CompletedStep <= 5)
					$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['step5']);

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
						//$this->leadtracapi->ChangeClientStatus($fileNumber, $this->statusUpdates['doe_idle']);
						$messages = new Illuminate\Support\MessageBag;
						$messages->add('* Error ', $this->leadtracapi->error);
						// change step5 to step6 for move steps
						return Redirect::to('step5')->withErrors($messages);
					}
				}
                $this->ClientUpdatedProfile($fileNumber, step5);

				//update successful.
				return Redirect::to('step6'); // PRZ
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
			$client = $this->GetClientFromSession();


			//load the data from step6, so the cart be here.
			$cartdata['LoanData'] = $Loans;
			$cartdata['Plans'] = $PaymentPlans;
			$cartdata['LoanType'] = '';
			$cartdata['Loan_Program'] = $client->TProperties->Loan_Program;
            $cartdata['Loan_Program_Abbr'] = $this->system_get_loan_program_abbr($client->TProperties->Loan_Program);

            $cartdata['client'] = $client;

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
            $this->ClientUpdatedProfile($fileNumber, step6);

			// Let's check if we are updating or if this is to checkout?
			if (Session::get('cart_type') == 'update' || !Session::get('cart_type'))
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

	public function PaymentPendingWait()
	{
		$this->layout->content .= View::make('client_payment_pending_wait');

	}

	public function ShowCRM()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;

		//$clientinfo = $this->leadtracapi->GetClient(Session::get('fileNumber'), array('FirstName', 'LastName',
		//	'ClientLogin','ClientPassword', 'client_city', 'client_region', 'client_zipcode'));

		$Client_Info = $this->GetClientFromSession(true);
 

        #### check if there is items in their cart, if they are, we'll process payment
        /**
         * @todo Explain how these two lines execute and update the statuses
         */
        if (intval($Client_Info->TProperties->cart_items) >= 1  || intval($Client_Info->TProperties->products_already_purchased) >= 0)
        $this->System_Set_Payment_Received();

		if ($Client_Info->TProperties->ClientLogin == "" && $Client_Info->TProperties->ClientPassword == "")
		{
			// we need to update there login.
			$data = array('ClientLogin' => $Client_Info->TProperties->EmailAddress, 'ClientPassword' => $Client_Info->TProperties->Name1SSN );
			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $data, Session::get('fileNumber'));
			$Client_Info = $this->GetClientFromSession(true);
		}

		$this->layout->content .= View::make('crm_login',array('UserName' => $Client_Info->TProperties->ClientLogin,'Password' => $Client_Info->TProperties->ClientPassword));

	}

    /**
     * This function is called when you are only updating your documents.
     * It will send the product id in the GET request ?pid=<product>,
     * it does check purchased documents before updating status so if anyone figures out how our system works they still can not give documents to them self.
     * @return array|string
     */
	public function UpdateClientDocument()
	{
		$this->layout->hide_returning_customer = true;
		$this->layout->hide_greeting = true;
        $Client = $this->GetClientFromSession(true);
        // make sure this client has purchased this product

		$product_id = intval(Input::get('pid'));

		if ($product_id && (intval($Client->TProperties->products_already_purchased) & $product_id))
		{
			$this->System_UpdateClientDocument_By_Product($product_id);

			// success
			if (Request::Ajax()) return array('success' => true);
			else return "1";
		}
        else
        {
            if (Request::Ajax()) return array('success' => false);
            else return "0";
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
        die(print_r($CartTotal));
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
            $this->ClientUpdatedProfile(Session::get('fileNumber'), step7);

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


    /**
     * This is executed on Step 7. it's where we'll intergrate the new progress bars.
     */

	public  function Tell_System_ClientHasCompletedStep7()
	{
		if (Session::get('fileNumber'))
		{
			$Client = $this->GetClientFromSession(true);

			$ClientData['cart_status'] = "pending";
            $ClientData['Cart_Expected_Log'] = "Approved for \$".number_format($ClientData['Payment1Amount'],2);


            $ClientData['cart_status'] = "pending";
            $ClientData['cart_processing_expires'] = 60 * 10; // five minutes from now.
            $ClientData['cart_processing_start'] = time(); // five minutes from now.
            $ClientData['cart_processing_typeid'] = "1"; // What's the text set for this type and id combined (e.g step1, step2, fail0, fail1)
            $ClientData['cart_processing_type'] = "check"; // What's the processing information type we're using?
            $ClientData['cart_processing_fail1'] = "Payment has been declined."; // five minutes from now.
            $ClientData['cart_processing_fail2'] = "Payment has failed."; // five minutes from now.
            $ClientData['cart_processing_fail3'] = "Payment has [unknown] ERROR."; // five minutes from now.
            $ClientData['cart_processing_check1'] = "Processing Payments"; // five minutes from now.
            $ClientData['cart_processing_check2'] = "Updating Customers Paid Products"; // five minutes from now.
            $ClientData['cart_processing_check3'] = "Updating Customer Status"; // five minutes from now.

            $ClientData['cart_processing_checks'] = 3; //what's the total number of checks left
            $ClientData['cart_processing_completed_assert'] = 'intval($client->TProperties->cart_processing_typeid) >= intval($client->TProperties->cart_processing_checks)'; //what's the total number of checks left
            $ClientData['cart_processing_failed_assert'] = '$ClientData[\'cart_processing_type\'] == \'fail\''; //


            for($i=1;$i<=$ClientData['cart_processing_checks'];$i++)
                $ClientData["cart_processing_check{$i}_result"] = ""; // Set all results to blank. They will be saved once process has executed.


            $ClientData['cart_processing_last_check'] = time(); // five minutes from now.
            $ClientData['cart_processing_check_every'] = 30; // when should we check again for the invoice?.





			if ( !($Client->TProperties->completed_steps_bitwise & step7))
				$ClientData['completed_steps_bitwise'] = $Client->TProperties->completed_steps_bitwise | step7;


			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, Session::get('fileNumber'));

			$paymentProcessedStatus = $this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $this->statusUpdates['Process Payments']);
		}
	}


	function RedirectWithError($page, $error)
	{
		$messages = new Illuminate\Support\MessageBag;
		$messages->add('Error', '<b>Error</b> '.$error);

		return Redirect::to($page)->withErrors($messages);
	}

/// PROGRESS BAR FUNCTIONS IMPORTED
    /**
     * Returns the file number currently being updated.
     *
     * @return mixed
     */
    public function Merchant_GetSelectedClientFileNumber()
    {
        if (Session::get('fileNumber'))
            return Session::get('fileNumber');

        return null;
    }
    public function Merchant_GetSelectedClient($cache=true, $always_return_userobject = true)
    {
        if ( Session::get('fileNumber') )
        {
            $FileNumber = Session::get('fileNumber');
            $Client = $this->GetClientByFileNumber($FileNumber, $cache);
        }
        else
        {
            $Client = false;
        }


        if ($always_return_userobject && $Client === false)
            return $this->GetClientByFileNumber("0", false);

        return $Client;
    }

    public function ResetProgress()
    {
        $this->Tell_System_ClientHasCompletedStep7();
        return "1";

    }

    public function MerchantProcess_LTVars(&$client = null)
    {
        set_time_limit(25);

        if (!isset($client) || !$client)
            $client = $this->Merchant_GetSelectedClient(false, true);


        if (isset($client->ClientPayment->status))
            $status = $client->ClientPayment->status;
        else
            $status = "unknown status on (".basename(__FILE__).":".__LINE__.")";


        $ClientData = [];

        $ClientData['cart_processing_typeid'] = $client->TProperties->cart_processing_typeid; // What's the text set for this type and id combined (e.g step1, step2, fail0, fail1)
        $ClientData['cart_processing_type'] = $client->TProperties->cart_processing_type; // What's the processing information type we're using?
        $ClientData['cart_processing_text_var'] = "cart_processing_".$ClientData['cart_processing_type'] . $ClientData['cart_processing_typeid']; // What's the processing information type we're using?
        $ClientData['cart_processing_text'] = (isset($client->TProperties->{$ClientData['cart_processing_text_var']}) ? $client->TProperties->{$ClientData['cart_processing_text_var']} : false); // What's the processing information type we're using?
        $ClientData['cart_processing_next_typeid'] = $ClientData['cart_processing_typeid'] + 1; // What's the processing information type we're using?
        $ClientData['cart_processing_next_text_var'] = "cart_processing_".$ClientData['cart_processing_type'] . $ClientData['cart_processing_next_typeid']; // What's the processing information type we're using?
        $ClientData['cart_processing_next_text'] = (isset($client->TProperties->{$ClientData['cart_processing_next_text_var']}) ? $client->TProperties->{$ClientData['cart_processing_next_text_var']} : false); // What's the processing information type we're using?
        $ClientData['cart_processing_payment_status'] = (isset($client->TProperties->{$ClientData['cart_processing_next_text_var']}) ? $client->TProperties->{$ClientData['cart_processing_next_text_var']} : false); // What's the processing information type we're using?

        /**
         * Check if any of our timers have ran out, if they have, let's make sure to set the variable so we can see it
         */
        $Processing_Started = $client->TProperties->cart_processing_start;
        $Processing_Expires = $client->TProperties->cart_processing_expires;
        $Processing_Expires_Timeleft = ($Processing_Expires - (($Processing_Started+$Processing_Expires) - time()));
        //
        $Processing_HasExpired = ($Processing_Expires_Timeleft <= 0) ? true : false;

        $Processing_CheckEvery = $client->TProperties->cart_processing_check_every;
        $Processing_LastCheck = (time() - $client->TProperties->cart_processing_last_check);

        $Processing_NextCheck_Percent =  number_format( ( $Processing_LastCheck / $Processing_CheckEvery) * 100,2, '.', '');
        $Processing_ExpiresTime_Percent = number_format( ($Processing_Expires_Timeleft / $Processing_Expires ) * 100,2, '.', '');
        if ($Processing_NextCheck_Percent > 100) $Processing_NextCheck_Percent = 100;
        if ($Processing_ExpiresTime_Percent > 100) $Processing_ExpiresTime_Percent = 100;

        $client->Processing_NextCheck_Percent = $Processing_NextCheck_Percent;
        $client->Processing_ExpiresTime_Percent = $Processing_ExpiresTime_Percent;
        $client->Processing_HasExpired = $Processing_HasExpired;
        $client->Processing_Time_Elapsed = $Processing_Expires_Timeleft;
        $client->Processing_CheckEvery = $Processing_CheckEvery;

        if ($client->TProperties->cart_processing_type)
            $client->Processing_Check_Completed = assert($client->TProperties->cart_processing_completed_assert, "Check Assertion Returned False");
        $client->Processing_Check_Failed = assert($client->TProperties->cart_processing_failed_assert, "We have failed!");

        $client->Processing_Method = "View_Merchant".ucwords($ClientData['cart_processing_type']).$ClientData['cart_processing_typeid'];


        return $ClientData;

    }

// This is the new process payment page, that has the new progress bar added.
    public function ProcessPayment()
    {
        $this->layout->hide_returning_customer = true;
        $this->layout->hide_greeting = true;


        $np = Input::get('np');
        $verified = Input::get('verified');

        if (md5($np . $_SERVER['HTTP_HOST']) == $verified)
        {
            $this->System_Set_Payment_Received(true);
            $this->layout->content .= View::make('client_paymentcomplete');
            return;
        }


        $ClientData = $this->MerchantProcess_LTVars($client);

        $Processing_NextCheck_Percent = $client->Processing_NextCheck_Percent;
        $Processing_ExpiresTime_Percent = $client->Processing_ExpiresTime_Percent;
        $Action_Performed = "Done";

        if ($client->Processing_NextCheck_Percent >= 100 && $ClientData['cart_processing_type'] != 'failed')
        {
            // Client has permission to cause the next action to happen.
            $method = $client->Processing_Method;
            $NewData = [];

            if ($client->TProperties->cart_processing_type == 'check' && $client->TProperties->cart_processing_typeid <= $client->TProperties->cart_processing_checks && method_exists($this, $method))
            {
                // this should fix the bug with the system not having the correct percent

                $NewData = $this->$method($client);

                $Action_Performed = "Executed {$client->Processing_Method}";

            }
            else
            {

                //check if we're completed then?
                if ($client->Processing_Check_Completed)
                {
                    $NewData['cart_processing_type'] = "finished"; // failed !!!
//                    $NewData['cart_processing_typeid'] = ; // 4 -- method requested was not found.

                    $Action_Performed = "Failed to execute {$client->Processing_Method}. Method does not exists.";
                }
                else
                {
                    $NewData['cart_processing_type'] = "failed"; // failed !!!
                    $NewData['cart_processing_typeid'] = 4; // 4 -- method requested was not found.
                    $Action_Performed = "Failed to execute {$client->Processing_Method}. Method does not exists.";
                }
            }

            if (isset($NewData['cart_process_immediate']))
            {
                unset($NewData['cart_process_immediate']);
            }
            else
                $NewData['cart_processing_last_check'] = time(); // five minutes from now.

            $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $NewData, $this->Merchant_GetSelectedClientFileNumber());
            $client = $this->Merchant_GetSelectedClient(false, true);
            $ClientData = $this->MerchantProcess_LTVars($client);

        }
        else
        {
            $Action_Performed = "Not time to check";
        }
        if ($client->TProperties->cart_processing_type == 'finished')
            $Processing_ExpiresTime_Percent = 100;

        $Progress1 = ['progress' => $Processing_ExpiresTime_Percent, 'text' => $ClientData['cart_processing_text']];
        $Progress2 = ['progress' => $Processing_NextCheck_Percent, 'text' => $ClientData['cart_processing_text']];

        $CheckResults = ["cart_processing_check1","cart_processing_check2","cart_processing_check3"];

        $Checks = [];
        foreach($CheckResults as $checkname)
        {
            $Checks[] = ['id' => $checkname, 'result' => @$client->TProperties->{$checkname."_result"}];
        }
        $TProperties = $client->TProperties;
        $Payment = $client->ClientPayment;

        $Variables = array('NewData','ClientData', 'TProperties', 'Payment', 'Progress1', 'Checks', 'Progress2', 'Processing_NextCheck_Percent', 'Processing_ExpiresTime_Percent', 'Action_Performed');

        if (Input::get('json'))
        {
            $null = 'null';
            return compact($null);
        }

        $this->layout->content .= View::make('client_thankyou',
            compact($Variables, "client"));

    }

    public function GetUpdatedProgress()
    {

        $client = $this->Merchant_GetSelectedClient(true, true);
        $ClientData = $this->MerchantProcess_LTVars($client);

        $Processing_NextCheck_Percent = $client->Processing_NextCheck_Percent;

        if ($client->Processing_Check_Completed && !$client->Processing_Check_Failed)
            $Processing_ExpiresTime_Percent = 100.00;

        else
            $Processing_ExpiresTime_Percent = $client->Processing_ExpiresTime_Percent;

        $CheckResults = ["cart_processing_check1","cart_processing_check2","cart_processing_check3"];

        $Checks = [];
        foreach($CheckResults as $checkname)
        {
            $Checks[] = ['id' => $checkname, 'result' => @$client->TProperties->{$checkname."_result"}];
        }


        if ($client->TProperties->cart_processing_type == 'finished')
            $Processing_ExpiresTime_Percent = 100;

        $Progress1 = ['progress' => $Processing_ExpiresTime_Percent, 'text' => $ClientData['cart_processing_text']];
        $Progress2 = ['progress' => $Processing_NextCheck_Percent, 'text' => $ClientData['cart_processing_text']];

        $TProperties = $client->TProperties;


        $Variables = array('ClientData','Progress1', 'Progress2', 'TProperties', 'Checks', 'Processing_NextCheck_Percent', 'Processing_ExpiresTime_Percent', 'Action_Performed');

        return compact($Variables);

    }

    /**
     * Progress Check Functions ,  These are used to 1. check for payment    2.
     */
    public function View_MerchantCheck1(&$client = null)
    {

        ## set client to false, this will force it to be retreived without being cached
        $client = false;
        $ClientData = $this->MerchantProcess_LTVars($client);

        $payment = $client->ClientPayment;
        // First, check if it is still Processing (Status is Processing Payment) and Payment_Received is false.
        // if this is the case, we send them back to ProcessPayment view
        switch(strtolower($payment->status)) {

            // Mr. B // Disable this if you are on a production server
            /**
             * @todo DISABLE ME
             */
//            case 'declined':
//            case 'failed':
//                $ClientData['cart_processing_typeid'] = $ClientData['cart_processing_next_typeid'];
//                $ClientData['cart_processing_text'] = $ClientData['cart_processing_next_text'];
//                $ClientData['cart_processing_text_var'] = $ClientData['cart_processing_next_text_var'];
//                $ClientData["cart_processing_check1_result"] = "Payment approved!";
//                $ClientData["cart_process_immediate"] = true;
//                break;


            case 'processing':
                // still processing
//                $ClientData['cart_processing_typeid'] = "0"; // 0 = process payments
                $ClientData["cart_processing_check1_result"] = "Processing Payment (still)";

                break;
            case 'declined':
                $ClientData['cart_processing_type'] = "failed"; // failed !!!
                $ClientData['cart_processing_typeid'] = 1; // 1 -- payment declined!!!
                $ClientData["cart_processing_check1_result"] = "Payment has declined";
                break;

            case 'failed':
                $ClientData['cart_processing_type'] = "failed"; // failed !!!
                $ClientData['cart_processing_typeid'] = 2; // 1 -- payment failed!!!
                $ClientData["cart_processing_check1_result"] = "Payment failed";
                break;
            case 'approved':
                $ClientData['cart_processing_typeid'] = $ClientData['cart_processing_next_typeid'];
                $ClientData['cart_processing_text'] = $ClientData['cart_processing_next_text'];
                $ClientData['cart_processing_text_var'] = $ClientData['cart_processing_next_text_var'];
                $ClientData["cart_processing_check1_result"] = "Payment approved!";
                $ClientData["cart_process_immediate"] = true;
                break;

            case 'unknown':
                $ClientData['cart_processing_type'] = "failed"; // failed !!!
                $ClientData['cart_processing_typeid'] = 3; // 1 -- payment declined!!!
                $ClientData["cart_processing_check1_result"] = "ERROR UNKNOWN PAYMENT STATUS";
                break;
        }

        return $ClientData;
    }
    public function View_MerchantCheck2(&$client = null)
    {

        ## set client to false, this will force it to be received without being cached
        $client = false;
        $ClientData = $this->MerchantProcess_LTVars($client);
        $ClientData['cart_processing_typeid'] = $ClientData['cart_processing_next_typeid'];
        $ClientData['cart_processing_text'] = $ClientData['cart_processing_next_text'];
        $ClientData['cart_processing_text_var'] = $ClientData['cart_processing_next_text_var'];

        $ClientData["cart_processing_check2_result"] = "Documents marked purchased";
        $ClientData["cart_process_immediate"] = true;

        if ($client->TProperties->products_already_purchased)
            $ClientData['products_already_purchased'] = intval($client->TProperties->products_already_purchased) | $client->TProperties->cart_items;
        else
            $ClientData['products_already_purchased'] =  $client->TProperties->cart_items;

        $ClientData['cart_items'] = "";
        $ClientData['cart_id'] = "";
        $ClientData['cart_id'] = "";


        return $ClientData;
    }
    public function View_MerchantCheck3(&$client = null)
    {
        ## set client to false, this will force it to be retreived without being cached
        $client = false;
        $ClientData = $this->MerchantProcess_LTVars($client);
        $ClientData['cart_processing_typeid'] = 0;
        $ClientData['cart_processing_type'] = 'finished';
        $ClientData['cart_processing_text'] = "Success";
        $ClientData['cart_processing_text_var'] = "";
        $ClientData["cart_processing_check3_result"] = "Success!";
        $ClientData["cart_process_immediate"] = true;

        $paymentsreceived = 'process2congratemail';
        set_time_limit(600);

        if (isset($this->statusUpdates['all']->{$paymentsreceived}))
        {
            $Order_PaymentsReceived = $this->statusUpdates['all']->{$paymentsreceived};
            $this->leadtracapi->ChangeClientStatus($this->Merchant_GetSelectedClientFileNumber(), $Order_PaymentsReceived);
        }



        return $ClientData;
    }
    public function View_MerchantCheck4(&$client = null)
    {
        ## set client to false, this will force it to be retreived without being cached
        $client = false;
        $ClientData = $this->MerchantProcess_LTVars($client);

        $ClientData['cart_processing_typeid'] = $ClientData['cart_processing_next_typeid'];
        $ClientData['cart_processing_text'] = $ClientData['cart_processing_next_text'];
        $ClientData['cart_processing_text_var'] = $ClientData['cart_processing_next_text_var'];
        $ClientData["cart_processing_check4_result"] = "Success!";

        return $ClientData;
    }




    // this shows the wait page for process payments.
	public function ProcessPayment2()
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

	public function AjaxCheckPaymentUpdate()
	{
		$fileNumber = Session::get('fileNumber');

		// Lets pull the GetPaymentStatusFromLogs
		if ($fileNumber) {
			// payment status!
			$client = $this->GetClientFromSession(true);
			if (in_array($client->TProperties->cart_status, array('pending', 'failed', 'locked'))) {
				// we need a payment.
			}
			$payment = $this->leadtracapi->GetPaymentStatusFromLogs($fileNumber);


			// First, check if it is still Processing (Status is Processing Payment) and Payment_Received is false.
			// if this is the case, we send them back to ProcessPayment view
			if ($payment->Processing && !$payment->Payment_Received) {
				$status = 'processing';
			}
			elseif ($payment->Payment_Declined || $payment->Payment_Failed)
			{
				$status = ($payment->Payment_Declined ? 'declined' : 'failed');
			}
			elseif($payment->Processing)
			{
				$status = 'processing';
			}
			elseif ($payment->Payment_Received)
			{
				$status = 'approved';
			}
			else
			{
				$status = 'unknown';
			}
		} else { $status = 'invalid_file'; }

		return array('status' => $status, 'payment' => $payment);

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

    public function Ajax_Check_If_Document_Owner()
    {
        if (!Session::get('fileNumber')) return array('browser_id' => 'na', 'compare_result' => true);

        return array('browser_id' => $this->System_Get_Browser_ID(), 'compare_result' =>  $this->System_Compare_Browser_ID());

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


		## Have to update them to 100% Payments Received.

		// change their status to the single new form they purchased.
		// this function returns only what is in their cart and yeah.
		$new_status = $this->System_Find_Status_Name_From_CartItems(false);

		if (isset($this->statusUpdates['all']->{$new_status}))
		{
			$this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $this->statusUpdates['all']->{$new_status});
			$UpdateFields = array('cart_status' => '', 'cart_items' => 0, 'cart_id' => '','process_payment_end_seconds' => time() - @$client->TProperties->process_payment_start, 'process_payment_start' => 0, 'cart_steps_required' => 0, 'products_already_purchased' => $products_already_ordered_now);

			$this->leadtracapi->CreateOrUpdateClient($this->campaignId, $UpdateFields, Session::get('fileNumber'));
		}

		// Now translate
		$ordered_products = $products_already_ordered_now;

		$ordered_cpn = ($ordered_products & cart_product_consolidation_app ? true : false);
		$ordered_repayment_promisory_note=($ordered_products & cart_product_repayment_app ? true : false);
		$ordered_pslf=($ordered_products & cart_product_pslf_app ? true : false);
		$ordered_forebearance=($ordered_products & cart_product_forebearance_app ? true : false);
		$ordered_recertification=($ordered_products & cart_product_recertification_app ? true : false);

        // consolidation app may be repayment note actually.
        // it depends on one of the variables.
        /**
         * @todo we need to change cpn to repayment if this client's Loan Type is set to what we have.
         */
		$new_status = $this->TranslateStatusFromOrder($client->TProperties->Loan_Program, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance, $ordered_recertification, $newStatusID);

		if (isset($this->statusUpdates['all']->{$new_status})) {
			$this->leadtracapi->ChangeClientStatus(Session::get('fileNumber'), $this->statusUpdates['all']->{$new_status});
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
	}

	public function PaymentDeclined()
	{
		// we need to change their cart to not locked any more.
		$this->System_ClientFailedPayment();
		return $this->RedirectWithError('/step7', 'Your payment has been declined, Please re-enter your credit card information and try again. Thank you!');
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

			$auth_office_use = Session::get('auth_office_use');
			$Office_Session_pw = Session::get('auth_office_password');
			$Office_Session_Auth2 = Session::get('Office_Session_Auth') ? Session::get('Office_Session_Auth') : false;


		Session::flush();
        Session::regenerate();

        if (isset($Office_Session_Auth2) && $Office_Session_Auth2)
		Session::put('Office_Session_Auth', $Office_Session_Auth2);

		if (isset($auth_office_use) && $auth_office_use)
		Session::put('auth_office_use', $auth_office_use);

		if (isset($Office_Session_pw) && $Office_Session_pw)
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

		$response->success = true; # PRZ ReturningCustomerForm()

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


		session::forget('Step3Completed');

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
			$client = $this->GetClientFromSession(true);

			$upgrade = array('100% Payments Received', 'Process Payments');
			session::put('Step3Completed',true);


		}

        // register their browser id so that this is the primary one editing the file
        $this->System_Register_With_Clients_BrowserID();


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
	 * Call: $this->TranslateOrderFromStatus($status, $ordered_cpn, $ordered_repayment_promisory_note, $ordered_pslf, $ordered_forebearance, $ordered_recertification)
	 */

	// dont think we need this function any longer because their order is now inside the payments paid variable. so just use it.
	public function TranslateOrderFromStatus(&$ordered_cpn=false,&$ordered_repayment_promisory_note=false,&$ordered_pslf=false, &$ordered_forebearance = false, &$ordered_recertification = false)
	{
		$ordered_cpn=false;
		$ordered_repayment_promisory_note=false;
		$ordered_pslf=false;
		$ordered_forebearance=false;
		$ordered_recertification=false;

		$client = $this->GetClientFromSession();

		if ($client)
		{
			$order = '';
			$ordered_products = $client->TProperties->products_already_purchased;

			$ordered_cpn = ($ordered_products & cart_product_consolidation_app ? true : false);
			$ordered_repayment_promisory_note=($ordered_products & cart_product_repayment_app ? true : false);
			$ordered_pslf=($ordered_products & cart_product_pslf_app ? true : false);
			$ordered_forebearance=($ordered_products & cart_product_forebearance_app ? true : false);
			$ordered_recertification=($ordered_products & cart_product_recertification_app ? true : false);

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
			$this->TranslateStatusError = "Status does not exists: ".$order_statusName."";
			return false;
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

	public function System_Find_Status_Name_From_CartItems($include_cart_items_only = false)
	{
		$include_cart_items_only = true;
		// temp we're going to force the status to only what's being ordered.

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
        $cache = ($nocache ? false : true);

		if (Session::get('fileNumber'))
		{
            $client_data = $this->GetClientByFileNumber(Session::get('fileNumber'), $cache);

		}
        else
            $client_data = $this->GetClientByFileNumber("0");


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
