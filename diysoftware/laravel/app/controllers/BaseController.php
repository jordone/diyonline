<?php

use Illuminate\Support\MessageBag;

class BaseController extends Controller {

    /**
     * @var LeadTracAPI
     */
    public $leadtracapi = false;
    public $statusUpdates = false;
    public $tracking_field_name = 'WebsiteSource';
    public $tracking_field_value = 'DIY.SLS';
    public $campaignId = '7181dcbd-bf75-425d-b5c5-b839de8d499d';

    public $prices = array('consolidation_note' => PRICE_CONSOLIDATION_NOTE, 'repayment_note' => PRICE_REPAYMENT_NOTE, 'forebearance_app' => PRICE_FOREBEARANCE_APP, 'pslf_app' => PRICE_FOREBEARANCE_APP, 'recerfitication_app' => PRICE_RECERTIFICATION_APP);


    function enable_maintenance_mode()
    {
        $this->layout = 'layouts.maintenance_mode';

    }
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {

            if (Request::Ajax())
            {
                $this->layout = View::make('layouts.ajax');
            }
            else
                $this->layout = View::make($this->layout);

            $this->layout->content = " ";
        }
    }

    function RedirectWithError($page, $error)
    {
        $messages = new MessageBag;
        $messages->add('Error', '<b>Error</b> '.$error);

        return Redirect::to($page)->withErrors($messages);
    }
    function RedirectWithSuccess($page, $error)
    {
        $messages = new MessageBag;
        $messages->add('Success', '<b>Success</b> '.$error);

        return Redirect::to($page)->withErrors($messages);
    }


    /**
     * This function initiates all of the calls to setup usage for the Lead Trac api.
     * This will: Connect to leadtrac and assign the class to $this->leadtracapi, and it'll grab a list of all the statuses so we can use them.
     *
     * @return bool
     */
    function Leadtrac_Init()
    {
        $this->Leadtrac_Connect();
        $this->LoadLeadTracStatusArray();

        if (defined('LEADTRACK_DOMAIN_KEY')) $this->tracking_field_value = LEADTRACK_DOMAIN_KEY;


        return true;
    }

    function Leadtrac_Connect()
    {
        if (!isset($this->leadtracapi) || !is_object($this->leadtracapi)) {
            $username = LEADTRAC_API_USERNAME;
            $password = LEADTRAC_API_PASSWORD;
            $api_version = '2.16';
            $sandbox = false;

            $this->leadtracapi = new leadtracapi($username, $password, $api_version, $sandbox);

        }

        return $this->leadtracapi;

    }
    function LoadLeadTracStatusArray()
    {
        $leadtracapi = $this->Leadtrac_Connect();

        /**
         * @todo add descriptions
         */

        // This is a cache of your variables, so it's vital that it's reset if we add new ones ;p.
        // changing only Status3s     to another name, will cause a new copy to be downloaded. This name isn't used anywhere else
        $this->statusUpdates = Cache::remember('Statuse3s', 60*60*5, function() use ($leadtracapi){
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

    /**
     * This function is used to tell our system that the client has updated their profile.
     * It's used so our backend knows when updates have occured and can update the views.
     *
     * @param $filenumber
     * @param $stepBitwise
     */
    public function ClientUpdatedProfile($filenumber, $stepBitwise)
    {
        $LastUpdate = $this->GetClientUpdatedProfile($filenumber);

        if ($LastUpdate['laststep'] > 0 && $stepBitwise)
        {
            $updated_steps = $LastUpdate['laststep'] | $stepBitwise;
        }
        else
            $updated_steps = 0;

        Cache::forever('ClientUpdatedProfile.'.$filenumber, ['filenumber' => $filenumber, 'laststep' => $stepBitwise, 'steps' => $updated_steps, 'updatetime' => time()]);
    }

    public function GetClientUpdatedProfile($filenumber)
    {
        if (Cache::has('ClientUpdatedProfile.'.$filenumber))
        {
            $client_update = Cache::get('ClientUpdatedProfile.'.$filenumber);
            $steps_updated = [];
            $laststepnum = 0;

            if ($client_update['steps'] > 0)
            {
                for($i=1;$i<=7;$i++)
                {
                    if (defined('step'.$i))
                    {
                        $const = constant('step'.$i);
                        if ($client_update['steps'] & $const)
                            $steps_updated[] = $i;

                        if ($client_update['laststep'] == $const)
                            $laststepnum = $i;
                    }

                }
            }

            $client_update['steps_array'] = $steps_updated;
            $client_update['laststepnum'] = $laststepnum;
            $client_update['nextstepnum'] = $laststepnum+1;
            return $client_update;
        }

        return ['filenumber' => $filenumber, 'steps_array' => [], 'laststepnum' => 0, 'nextstepnum' => 0, 'laststep' => 0, 'step' => 0, 'updatetime' => -1];
    }

    public function GetClientByFileNumber($filenumber, $cache=true)
    {

        $PropertyList = ['ClientLogin','ClientPassword', 'cart_items','cart_processing_expires','cart_processing_start','cart_processing_last_check','cart_processing_check_every',
            "cart_processing_fail","cart_processing_typeid","cart_processing_type","cart_processing_fail0","cart_processing_fail1","cart_processing_fail2",
            "cart_processing_fail3",
            "cart_processing_fail4",
            "cart_processing_checks",
            "cart_processing_completed_assert",
            "cart_processing_failed_assert",
            "cart_processing_check1_result","cart_processing_check2_result","cart_processing_check3_result","cart_processing_check4_result","cart_processing_check5_result","cart_processing_check6_result","cart_processing_check7_result",
            "cart_processing_check1","cart_processing_check2","cart_processing_check3","cart_processing_check4","cart_processing_check5",
            "cart_processing_check6",


            'BrowserLastState', 'BrowserCSL', 'BrowserLastState_Arguments','BrowserOwnerID', 'Cart_Expected_Log', 'cart_processing_expires','cart_processing_start',
            'Cart_Locked', 'Loan_Program','PublicService','Occupation','HomeNumber','TaxFilingStatus','CoIncome_Yearly',
            'Income_Yearly','MaritalStatus','CoFirstName','CoLastName','CoSSN','CoDOB','FamilySize','Name1SSN', 'Name1DOB',
            'AddressLine1', 'City', 'State', 'ZipCode','ClientLogin','ClientPassword', 'client_city', 'client_region', 'client_zipcode',
            'EmailAddress', 'FirstName', 'LastName', 'Payment1Amount', 'AddressLine1', 'City', 'State', 'ZipCode',
            'Loan_ContractFee_Override','Payment1Amount','Payment1DueDate','NoPayments','StartDate','Loan_ContractTotalFee','Loan_ContractFee','TotalPayments'];



        if ($filenumber == "0")
        {
            $client = new \stdClass();

            $client->TProperties = new \stdClass();

            foreach($PropertyList as $PL)
                $client->TProperties->{$PL} = "";

            return $client;
        }


        $app = &$this;


        if ($cache == false) Cache::forget("Office.Files.{$filenumber}");

        $Client = Cache::remember("Office.Files.{$filenumber}", 3, function() use ($app, $filenumber,$PropertyList) {

            $clientdetails = $app->leadtracapi->getClients($filenumber, $PropertyList);

            if (is_array($clientdetails) && count($clientdetails))
                $client = array_shift($clientdetails);
            else
                $client = $clientdetails;

            if (!$client || !is_object($client)) return false;

            $Loans = $app->leadtracapi->GetAccountLoans($filenumber);
            $PaymentPlans = $app->leadtracapi->GetPaymentPlans($filenumber);
            $Payments = $this->leadtracapi->GetPaymentStatusFromLogs($filenumber);

            if (isset($client->TProperties->Loan_Program))
                $Loan_Program_Abbr = $app->system_get_loan_program_abbr($client->TProperties->Loan_Program);
            else
                $Loan_Program_Abbr = "";

            if (isset($client->TProperties))
                $client->TProperties->Special = ['Loans' => $Loans, 'LoanData' => $Loans, 'Plans' => $PaymentPlans, 'PaymentPlans' => $PaymentPlans, 'Loan_Program' => $client->TProperties->Loan_Program, 'Loan_Program_Abbr' => $Loan_Program_Abbr];


            $client->ClientPayment = $Payments;

            /**
             * @todo setup the file uniquely generated url based on the Session::id() and the file number.
             */
//            $encrypted_data_session_id = Crypt::encrypt(Session::getId() .'|'. json_encode($client));
//            $client->encrypted_data = $encrypted_data_session_id;

            return $client;

        });
        if (!$Client) return false;

        $this->leadtracapi->SetupClientDetailsFromCache($Client);



        return $Client;
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

    public function System_Get_Cart_Total()
    {

        $client = $this->GetClientByFileNumber(Session::get('fileNumber'));

        if ($client->TProperties->cart_items)
        {
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

}
