<?php
/* Copyright (C) Bryan Brown - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Brown Brown <Bryanb222@gmail.com>, Sun May 24 04:28:48 GMT 2015 04:28:48
*/

// include ('protection.php');


/**
 * LeadTrack Communication API Wrapper
 *
 */


### Classic step bitwise definitions
defined('step1') or define('step1', 1);
defined('step2') or define('step2', 2); // 3
defined('step3') or define('step3', 4); // 7
defined('step4') or define('step4', 8); // 15
defined('step5') or define('step5', 16); //
defined('step6') or define('step6', 32); // 47
defined('step7') or define('step7', 64); // 111

class leadtracapi
{
    private $userName;
    private $password;
    private $api_version;
    private $wsdl_url;

    private $sandbox = false;
    private $xml_request = '';

    private $my_version = 7;
    private $DebugPage = false;

    public function LeadTracAPI($userName, $password, $api_version = '2.16', $sandbox = false)
    {
        $this->userName = $userName;
        $this->password = $password;

        $this->api_version = $api_version;

        if ($sandbox)
            $this->wsdl_url = 'https://sandbox.leadtrac.net/';
        else
            $this->wsdl_url = 'https://api.leadtrac.net/';

        //update the default socket timeout
        ini_set("default_socket_timeout", 600);
        //		default_socket_timeout(300);


    }

    public function GetSoapOptions()
    {
        return array(
            'soap_version' => SOAP_1_2,
            'exceptions' => true,
            'trace' => 1,
            'connection_timeout' => 300,
            'cache_wsdl' => WSDL_CACHE_BOTH,
            'features' => SOAP_USE_XSI_ARRAY_TYPE
        );
    }

    public function GetLastRequestXML()
    {
        return $this->xml_request;
    }

    public function GetClientWSDL()
    {
        $url = $this->wsdl_url . $this->api_version . '/Client.asmx?wsdl';
        return $url;
    }

    public function GetDebtWSDL()
    {
        $url = $this->wsdl_url . $this->api_version . '/Debt.asmx?wsdl';
        return $url;
    }

    public function GetAssetWSDL()
    {
        $url = $this->wsdl_url . $this->api_version . '/Asset.asmx?wsdl';
        return $url;
    }

    function GetCachedVersion($function, $unique_id = null, $args_id = null)
    {
        // In your config file
        return false;
//		require_once(dirname(__FILE__)."/phpfastcache.php");
        phpFastCache::setup("storage", "redis"); #default global for everywhere.
//		 phpFastCache support "redis", "cookie", "apc", "memcache", "memcached", "wincache" ,"files", "sqlite" and "xcache";

        // You don't need to change your code when you change your caching system, blank will use default global:
        $cache = phpFastCache("files");
        $id = md5($function . $unique_id . $args_id);
        $results = $cache->get("cache_" . $id);

        if ($results)
            return $results;

        return false;
        // $cache = phpFastCache("memcache");

    }

    function SetCachedVersion($function, $unique_id, $args_id, $data)
    {
        // In your config file
//		require_once(dirname(__FILE__) . "/phpfastcache.php");
        phpFastCache::setup("storage", "redis"); #default global for everywhere.
//		 phpFastCache support "redis", "cookie", "apc", "memcache", "memcached", "wincache" ,"files", "sqlite" and "xcache";

        // You don't need to change your code when you change your caching system, blank will use default global:
        $cache = phpFastCache("files");
        $id = md5($function . $unique_id . $args_id);
        $results = $cache->get("cache_" . $id);

        if ($results)
            return $results;

        return $results;
//
    }

    // $cache = phpFastCache("memcache");	}

    public function GetCampaignList()
    {

        // cache this bitch

        $campaignlist = $this->GetCachedVersion('GetCampaignList');

        if ($campaignlist) return $campaignlist;

        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->GetCampaignList(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->GetCampaignListResult)) {
                $this->SetCachedVersion(__FUNCTION__, 1, 1, $call);
            }
            return $call->GetCampaignListResult;

        } catch (SoapFault $fault) {
            return false;
            //error
        }

    }


    /**
     * This will search the AssetTypesList for a file upload.
     *
     */
    public function GetAssetTypesList()
    {
        $soap_url = $this->GetAssetWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->GetAssetTypes(new SoapVar($parms, SOAP_ENC_OBJECT));
            return $call->GetAssetTypesResult;


        } catch (SoapFault $fault) {
            return false;
            //error
        }

    }


    /**
     * This will search the AssetTypesList for a file upload.
     *
     */
    public function AddAssets($fileNumber, $assetTypeId, $assetFileName, $assetDescription, $assetBytes)
    {
        $soap_url = $this->GetAssetWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar($fileNumber, XSD_STRING, null, null, 'ns1:fileNumber');
        $parms[] = new SoapVar($assetTypeId, XSD_STRING, null, null, 'ns1:assetTypeId');
        $parms[] = new SoapVar($assetFileName, XSD_STRING, null, null, 'ns1:assetFileName');
        $parms[] = new SoapVar($assetDescription, XSD_STRING, null, null, 'ns1:assetDescription');
        $parms[] = new SoapVar($assetBytes, XSD_STRING, null, null, 'ns1:assetBytes');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->AddAsset(new SoapVar($parms, SOAP_ENC_OBJECT));
            return $call;

        } catch (SoapFault $fault) {
            return false;
            //error
        }

    }


    /**
     * This will search the LogEntries for a payment, or, it'll try to determin if it has failed by checking the Accounting Status.
     *
     *
     * @param string $fileNumber
     * @return stdClass
     */
    public function GetPaymentStatusFromLogs($fileNumber)
    {

        if (is_object($fileNumber) && isset($fileNumber->FileNumber)) { $Client = $fileNumber; $fileNumber = $Client->FileNumber; }
        // get the client so we can look at the status.

        if (!isset($Client) || !$Client)
            $Client = $this->GetClient($fileNumber, array('LastName')); // we just need to request the lastname  it will send us the status.

        $processing = false;
        $payment_failed = false;
        $payment_declined = false;
        $payment_received = false;

        // First, lets make sure the client is at Process Payments
        if (isset($Client->Status->Accounting)) {
            $AccountingStatus = $Client->Status->Accounting;
            // find out if it equals Process Payments
            switch (strtolower($AccountingStatus)) {
                case 'process payments':
                    $processing = true;
                    break;
                case 'payment failure':
                    $processing = false;
                    $payment_failed = true;
                    break;
                case 'payment declined':
                    $processing = false;
                    $payment_failed = false;
                    $payment_declined = true;
                    break;

                // if there status is set to: 100% Payments Received, we have the payment then.
                case '100% payments received':
                    $processing = $payment_failed = $payment_declined = false;
                    $payment_received = true;

                    break;
            }
        }

        $Response = new stdClass();

        // lets put this together
        $Response->Processing = $processing;
        $Response->Payment_Failed = $payment_failed;
        $Response->Payment_Declined = $payment_declined;
        $Response->Payment_Received = $payment_received;
        $Response->Payment_Received_Via = 'Status';

        $Response->Log = new stdClass();
        $Response->Log->Searched = false;
        $Response->Log->Status = false;
        $Response->Log->Amount = false;

        /**
         * Let the logs verify for us that we was paid for this.
         * @see I moved this down to another location.
         */
        if ($Response->Payment_Received || $Response->Payment_Failed || $Response->Payment_Declined)
        {
            // Payment Received was via Status
            // retrn our response
//            return $Response; // we are done, something has determined our payment status for us!
        }

        else
        {
            //alright, the status is still set to Process Payments
            // Get the Logs, and look for one that says it received a payment!

            // our search goes back 1 week and forward 1 day.
            $startDate = date('c', strtotime('-7 days'));
            $endDate = date('c', strtotime('+1 day'));

            $LogEntries = $this->GetLogEntries($fileNumber, $startDate, $endDate);

            if (isset($LogEntries->Entries) && isset($LogEntries->Entries->LogEntry) && is_array($LogEntries->Entries->LogEntry)) {
                // loop though each LogEntry, we need to find something like: Payment #1 Approved for $800.00 (000000)
                $Response->Log->Searched = true;
                foreach ($LogEntries->Entries->LogEntry as $Log) {
                    $LogEntry = trim($Log->Entry);
                    //Preg Match
                    $Expression = '^Payment #([\d]+) (Approved|Declined|Failed) for \$([\d]+\.?[\d]+) \(\d+\)';
                    if (preg_match("/$Expression/i", $LogEntry, $result)) {
                        list(, $invoice_id, $payment_status, $payment_amount) = $result;
                        // we found a payment in the logs. Lets put this information in the Response and break out of this loop.
                        $Response->Payment_Received_Via = 'Log';
                        $Response->Payment_Received = true;
                        $Response->Log = new stdClass();
                        $Response->Log->Status = strtolower($payment_status);
                        $Response->Log->Amount = $payment_amount;

                        // break because we are done!
                        break;
                    }

                    // see if the payment cleared
                    $Expression = '^Payment #([\d]+) status changed to Cleared.';
                    if (preg_match("/$Expression/i", $LogEntry, $result)) {
                        list(, $invoice_id) = $result;

                        $Response->Payment_Received_Via = 'Log';
                        $Response->Payment_Received = true;
                        $Response->Log = new stdClass();
                        $Response->Log->Status = 'Approved';
                        $Response->Log->Amount = '0.00';

                    }

                }
            }

            // if the log status was Approved, set it to approved.
            /**
             * $Response->Processing = $processing;
             * $Response->Payment_Failed = $payment_failed;
             * $Response->Payment_Declined = $payment_declined;
             * $Response->Payment_Received = $payment_received;
             */

            // see what happened.
            if ($Response->Log->Status == 'approved') {
                $payment_received = true;
            }
            if ($Response->Log->Status == 'declined' || $Response->Log->Status == 'failed') {
                $payment_failed = true;
                $payment_received = false;
                $payment_declined = true;

            }
        }
        $Response->Processing = $processing;
        $Response->Payment_Failed = $payment_failed;
        $Response->Payment_Declined = $payment_declined;
        $Response->Payment_Received = $payment_received;


        /**
         * Get they payment status
         *
         */
        if ($Response->Processing && !$Response->Payment_Received) {
            $status = 'processing';
        }
        elseif ($Response->Payment_Declined || $Response->Payment_Failed)
        {
            $status = ($Response->Payment_Declined ? 'declined' : 'failed');
        }
        elseif($Response->Processing)
        {
            $status = 'processing';
        }
        elseif ($Response->Payment_Received)
        {
            $status = 'approved';
        }
        else
        {
            $status = 'unknown';
        }
        $Response->status = $status;

        // return the $Response
        return $Response;
    }

    /**
     * This returns the Logs from the System. We use this to find out if they have paid there invoice because we do not have any access to the Payments. Which is weird.
     *
     * @param string $fileNumber
     * @param string $startDate  - date(c)
     * @param string $endDate  - date(c)
     * @return object
     */
    public function GetLogEntries($fileNumber, $startDate, $endDate)
    {
        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar($fileNumber, XSD_STRING, null, null, 'ns1:fileNumber');
        $parms[] = new SoapVar($startDate, XSD_STRING, null, null, 'ns1:startDate');
        $parms[] = new SoapVar($endDate, XSD_STRING, null, null, 'ns1:endDate');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->GetLogEntries(new SoapVar($parms, SOAP_ENC_OBJECT));

            if (isset($call->GetLogEntriesResult))
                return $call->GetLogEntriesResult;

            return false;

        } catch (SoapFault $fault) {
            return false;
            //error
        }

    }

    public function GetDebts($fileNumber)
    {
        $soap_url = $this->GetDebtWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar($fileNumber, XSD_STRING, null, null, 'ns1:fileNumber');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());
        try {
            $call = $soap->GetDebts(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->GetDebtsResult) && $call->GetDebtsResult->IsSuccessful == 1) {
                // Lets return GetDebtsResult
                return $call->GetDebtsResult;
            }
            return $call;
        } catch (SoapFault $fault) {
            return false;
        }
        return false;
    }

    /**
     * This is a wrapper for $this->GetDebts
     * It'll sort the information and provide us which cart type we will be using.
     * Also removing any cancelled loans from the list, so only active loans will show.
     *
     * @param string $fileNumber
     */
    public function GetAccountLoans($fileNumber)
    {
        $debts = $this->GetDebts($fileNumber);
        if (!$debts || !isset($debts->Debts->Debt)) return false;
        // We need to see if the Debts is an array, some cases it might not be?
        $debtList = $debts->Debts->Debt;
        if (!is_array($debtList)) $debtList = array($debtList);

        //Now we need to loop though each debt. Disregard any loans that status is CA
        $LoanArray = array();
        $CantHelpLoans = array('ca', 'pn', 'al', 'bc', 'bk', 'cs', 'db', 'dc', 'dd', 'de', 'di', 'dk', 'dl', 'dn', 'do', 'dp', 'dr', 'ds', 'dt', 'du', 'dw', 'dz', 'fc', 'fd', 'fr', 'fx', 'ia', 'od', 'pc', 'pd', 'pf', 'pm', 'pn', 'px', 'pz', 'rf', 'ua', 'ub', 'uc', 'ud', 'ui', 'xd');
        foreach ($debtList as $debt) {

            // if the account status is cancelled, we'll not include it!
            if (isset($debt->AccountStatus) && in_array(strtolower(substr($debt->AccountStatus, 0, 2)), $CantHelpLoans))
                continue;

            $tmp_debt = new stdClass();
            $tmp_debt->NegotiationStatus = $debt->NegotiationStatus;

            // we need to determin if the account status
            if (isset($debt->AccountStatus))
                $tmp_debt->AccountStatus = $debt->AccountStatus;
            else
                $tmp_debt->AccountStatus = substr($debt->DebtType, 0, 1);
            $tmp_debt->DebtType = $debt->DebtType;
            $tmp_debt->MinimumPayment = $debt->MinimumPayment;

            // Now we need to select the creditor, we'll use the Level 0 creditors.
            //			$tmp_debt->Creditor = '';
            $creditor = new stdClass();

            $tmp_creditlist = $debt->CreditorHistory->DebtCreditor;
            if (!is_array($tmp_creditlist)) $tmp_creditlist = array($tmp_creditlist);
            // we want the first creditor
            $first_creditor = array_pop($tmp_creditlist);

            $creditor->Name = $first_creditor->Creditor->Name;
            $creditor->LoanAmount = number_format($first_creditor->Amount, 2);
            $tmp_debt->Properties = array();

            if (isset($debt->Properties) && isset($debt->Properties->DebtProperty)) {
                // The real amount is in the Properties. Lets convert that to an array.
                $properties = $this->ConvertPropertyListFromPropertyArray($debt->Properties->DebtProperty);
                $tmp_debt->Properties = $properties;
            }
            if (isset($tmp_debt->Properties['Loan_Outstanding_Principal_Balance']))
                $creditor->Balance = number_format($tmp_debt->Properties['Loan_Outstanding_Principal_Balance'], 2);
            elseif (isset($tmp_debt->Properties['Loan_Balance']))
                $creditor->Balance = number_format($tmp_debt->Properties['Loan_Balance'], 2);
            else {
                $creditor->Balance = number_format($first_creditor->Amount, 2);
            }


            // we need to make sure the TypeCode is being set, if not we'll just set it based on the first letter of the DebtType
            if (!isset($tmp_debt->Properties['TypeCode']))
                $tmp_debt->Properties['TypeCode'] = substr($debt->DebtType, 0, 1);

            if (isset($tmp_debt->AccountStatus)) {
                $tmp_debt->TAccountStatus = substr($tmp_debt->AccountStatus, 0, 2);
            } else
                $tmp_debt->TAccountStatus = '';


            $tmp_debt->Creditor = $creditor;

            $LoanArray[] = $tmp_debt;
        }

        $return = new stdClass();

        // We need to check what Loan Types they have. If they have a Direct Consolidation Loan, it'll determin the cart.
        // Initially, the cart is going to be set to Consolidation
        $return->Plan = 'Consolidate';
        $return->HasForbearance = false;
        $return->HasDefaulted = false;

        foreach ($LoanArray as $Loan) {
            if (in_array(strtolower($Loan->Properties['TypeCode']), array('k', 'j'))) $return->Plan = 'Repayment';

            // see if this account has any loans in FB.
            if (in_array(strtolower($Loan->TAccountStatus), array('fb'))) $return->HasForbearance = true;

            // see if any of the loans have defaulted.
            if (in_array(strtolower($Loan->TAccountStatus), array('df', 'dt', 'du', 'dx', 'dz'))) $return->HasDefaulted = true;
        }
        $return->Loans = $LoanArray;

        return $return;
    }

    /**
     * Returns an array list of Repayment Options from the Provided Loan Data.
     *
     * @param unknown_type $LoanData
     */
    public function GetPaymentPlans($fileNumber)
    {
        /**
         * We need to use the Detail Parm I found (undocumented!) to see if they are Qualified for that status.
         *
         */

        $Static_Plans = Array('IBR', 'ICR', 'STD', 'GRAD', 'EXF', 'EXG', 'PAY');

        $Data = $this->GetClient($fileNumber, array(
            'IBR_Detail',
            'IBR_Note',
            'IBR_Term',
            'IBR_Payment',
            'IBR_AmountForgiven',

            'ICR_Detail',
            'ICR_Note',
            'ICR_Term',
            'ICR_Payment',
            'ICR_AmountForgiven',

            'STD_Detail',
            'STD_Note',
            'STD_Term',
            'STD_Payment',
            'STD_AmountForgiven',

            'GRAD_Detail',
            'GRAD_Note',
            'GRAD_Term',
            'GRAD_Payment',
            'GRAD_AmountForgiven',

            'EXF_Detail',
            'EXF_Note',
            'EXF_Term',
            'EXF_Payment',
            'EXF_AmountForgiven',

            'EXG_Detail',
            'EXG_Note',
            'EXG_Term',
            'EXG_Payment',
            'EXG_AmountForgiven',

            'PAY_Detail',
            'PAY_Note',
            'PAY_Term',
            'PAY_Payment',
            'PAY_AmountForgiven',
        ));

        $PaymentPlans = new stdClass();

        foreach ($Static_Plans as $PlanName) {
            $PlanName_Detail = $PlanName . '_Detail';

            // It should be set here. This is a temp set of data so we never trigger any errors!
            if (!isset($PaymentPlans->{$PlanName}))
                $PaymentPlans->{$PlanName} = new stdClass;

            // Lets predefine these values.
            $PaymentPlans->{$PlanName}->Term = '';
            $PaymentPlans->{$PlanName}->Payment = '';
            $PaymentPlans->{$PlanName}->Forgiven = '';
            $PaymentPlans->{$PlanName}->Note = '';
            $PaymentPlans->{$PlanName}->Qualified = false;

            if (isset($Data->TProperties->{$PlanName_Detail})) {

                $PaymentPlans->{$PlanName}->Term = (isset($Data->TProperties->{$PlanName . '_Term'}) ? $Data->TProperties->{$PlanName . '_Term'} : 0);
                $PaymentPlans->{$PlanName}->Payment = (isset($Data->TProperties->{$PlanName . '_Payment'}) && $Data->TProperties->{$PlanName . '_Payment'} ? number_format($Data->TProperties->{$PlanName . '_Payment'}, 2) : 0);
                $PaymentPlans->{$PlanName}->Note = (isset($Data->TProperties->{$PlanName . '_Note'}) ? $Data->TProperties->{$PlanName . '_Note'} : 0);
                $PaymentPlans->{$PlanName}->Forgiven = @number_format((isset($Data->TProperties->{$PlanName . '_AmountForgiven'}) ? $Data->TProperties->{$PlanName . '_AmountForgiven'} : 0), 2);
                $PaymentPlans->{$PlanName}->Qualified = false;
                //See if we can find the word Disqualified
                if (stristr($Data->TProperties->{$PlanName_Detail}, 'disqualified')) {
                    $PaymentPlans->{$PlanName}->Qualified = false;
                } else {
                    $PaymentPlans->{$PlanName}->Qualified = true;
                }
            }
        }

        //Alright, lets send away our Data
        return $PaymentPlans;

    }

    /**
     * @param $start
     * @param $end
     * @return array or bool
     */
    public function GetUpdatedClients($start, $end, $WebsiteSource = null)
    {
        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar($start, XSD_STRING, null, null, 'ns1:start');
        $parms[] = new SoapVar($end, XSD_STRING, null, null, 'ns1:end');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->GetUpdatedClients(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->GetUpdatedClientsResult->string)) {
                if (!is_array($call->GetUpdatedClientsResult->string))
                {
                    $filenumbers = array($call->GetUpdatedClientsResult->string);
                }
                else
                    $filenumbers = $call->GetUpdatedClientsResult->string;


                // get the clients now.
                $client_docs = $this->GetClients($filenumbers, array('FirstName', 'LastName', 'EmailAddress', 'HomeNumber', 'WebsiteSource', 'LoanProgram', 'BrowserUpdatedOn', 'BrowserCSL'), $WebsiteSource);



                return $client_docs;
            }
        } catch (SoapFault $fault) {
            return false;
        }
    }

    /**
     * This is a wrapper to convert Name Value objects to an array.
     *
     * @param array $PropertyNameValueArray
     * @return array
     */
    public function ConvertPropertyListFromPropertyArray($PropertyNameValueArray)
    {
        $propertyList = array();
        if (!is_array($PropertyNameValueArray)) $PropertyNameValueArray = array($PropertyNameValueArray);
        foreach ($PropertyNameValueArray as $propertyobj) {
            if (isset($propertyobj->Name))
                $propertyList[$propertyobj->Name] = $propertyobj->Value;
        }

        return $propertyList;
    }


    public function GetStatusList()
    {
        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->GetStatusList(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->GetStatusListResult)) {
                $statuses = new stdClass();
                foreach ($call->GetStatusListResult->StatusItem as $StatusItem) {
                    $statuses->{$StatusItem->Name} = $StatusItem->StatusId;
                }
            }
            $call->GetStatusListResult->TStatus = $statuses;

            return $call->GetStatusListResult;


        } catch (SoapFault $fault) {
            return false;
            //error
        }

    }

    public function GetClients($fileNumbers, $properties, $WebsiteSource = null)
    {
        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');


        //

        ## if they don't request this, request it anyway.
        if (!in_array('completed_steps_bitwise', $properties))
            array_push($properties, 'completed_steps_bitwise');

        if (!in_array('cart_status', $properties))
            array_push($properties, 'cart_status');

        if (!in_array('cart_items', $properties))
            array_push($properties, 'cart_items');

        if (!in_array('cart_id', $properties))
            array_push($properties, 'cart_id');

        if (!in_array('cart_steps_required', $properties))
            array_push($properties, 'cart_steps_required');

        if (!in_array('cart_order_time', $properties))
            array_push($properties, 'cart_order_time');

        if (!in_array('products_already_purchased', $properties))
            array_push($properties, 'products_already_purchased');

        if (!in_array('WebsiteSource', $properties))
            array_push($properties, 'WebsiteSource');


        // prepare the properties
        $clientProperties = array();
        $i = 0;

        foreach ($properties as $propertyName) {
            $clientProperties[$i] = new SoapVar($propertyName, XSD_STRING, null, null, 'ns1:string');
            $i++;
        }
        $parms[] = new SoapVar($clientProperties, SOAP_ENC_OBJECT, NULL, NULL, 'ns1:propertyNameList');


        $fileNumberList = array();
        $i = 0;

        if (!is_array($fileNumbers)) $fileNumbers = array($fileNumbers);
        foreach ($fileNumbers as $fileNumber) {
            $fileNumberList[$i] = new SoapVar($fileNumber, XSD_STRING, null, null, 'ns1:string');
            $i++;
        }

        $parms[] = new SoapVar($fileNumberList, SOAP_ENC_OBJECT, NULL, NULL, 'ns1:fileNumberList');


        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->GetClients(new SoapVar($parms, SOAP_ENC_OBJECT));

            if (isset($call->GetClientsResult) && isset($call->GetClientsResult->Client)) {
                $clients = $call->GetClientsResult->Client;
                if (!is_array($clients)) $clients = array($clients);

                // foreach clients as client

                foreach ($clients as $client_i => $client) {
                    $Properties = @$client->Properties->ClientProperty;
                    if (!is_array($Properties)) $Properties = array($Properties);

                    $client->TProperties = new stdClass();


                    foreach ($Properties as $prop) {
                        if (isset($prop->Name) && isset($prop->Value))
                        {
                            if (!$prop->Name)
                            {
                                continue;
                            }
                            $client->TProperties->{$prop->Name} = @$prop->Value;
                        }
                    }


                    $client->TStatus = new stdClass();

                    $clientStatus = $client->CurrentStatus->ClientStatus;
                    if (!is_array($clientStatus)) $clientStatus = array($client->CurrentStatus->ClientStatus);
                    foreach($clientStatus as $csi)
                    {
                        $client->TStatus->{$csi->WorkflowName} = new stdClass();
                        $client->TStatus->{$csi->WorkflowName}->status = $csi->Name;
                        $client->TStatus->{$csi->WorkflowName}->updated = $csi->UpdatedOn;
                    }


                    // tDates
                    $client->TDates = new stdClass();

                    $CreatedOn = new DateTime($client->CreatedOn, new DateTimeZone('America/Los_Angeles'));
                    $client->TDates->CreatedOn = $CreatedOn->format('m/d/Y h:ia');
                    $UpdatedOn = new DateTime($client->UpdatedOn, new DateTimeZone('America/Los_Angeles'));
                    $UpdatedOn->setTimezone(new DateTimeZone('America/New_York'));

                    $client->TDates->UpdatedOn = $UpdatedOn->format('m/d/Y h:ia T');

                    $fromNow = Carbon::instance($UpdatedOn)->diffInMinutes();
                    $client->TDates->UpdatedMinutesAgo = $fromNow;

                    if (!isset($client->TProperties->completed_steps_bitwise))
                        $client->TProperties->completed_steps_bitwise = 0;

                    $client->TProperties->completed_steps_bitwise = intval($client->TProperties->completed_steps_bitwise);

                    //our defaults
                    $client->CompletedStep = 0;
                    $client->NextStep = 1;


                    for ($stepnumber = 1; $stepnumber <= 7; $stepnumber++) {
                        $constant = 'step' . $stepnumber;
                        if (defined($constant)) {
                            $constant_value = constant($constant);

                            if ($client->TProperties->completed_steps_bitwise & $constant_value) {
                                $client->CompletedStep = $stepnumber;
                                $client->NextStep = $stepnumber + 1;
                                $client->{'Completed_Step_'.$stepnumber} = true;
                            }
                            else
                            {
                                $client->{'Completed_Step_'.$stepnumber} = false;
                            }
                        }
                    }



                    $client->Generated_UTIME = time();
                    $client->Generated_DATETIME = date('c');

                    $this->SetupClientDetailsFromCache($client);

                    if (isset($WebsiteSource) && $WebsiteSource)
                    {
                        if (strtolower($client->TProperties->WebsiteSource) == strtolower($WebsiteSource))
                            $clients[$client_i] = $client;
                    }
                    else
                        $clients[$client_i] = $client;
                }

                if (!count($clients))
                    return false;

                usort($clients, function($a, $b)
                {
                    return ($a->TDates->UpdatedMinutesAgo < $b->TDates->UpdatedMinutesAgo) ? -1 : (($a->TDates->UpdatedMinutesAgo > $b->TDates->UpdatedMinutesAgo) ? 1 : 0);
                });

                return $clients;
            }
            return array();

        } catch (SoapFault $fault) {

            return false;
        }
    }
    public function SetupClientDetailsFromCache(&$client)
    {
        if (!isset($client->TDates)) $client->TDates = new stdClass();

        $CreatedOn = new DateTime($client->CreatedOn, new DateTimeZone('America/Los_Angeles'));
        $client->TDates->CreatedOn = $CreatedOn->format('m/d/Y h:ia');
        $UpdatedOn = new DateTime($client->UpdatedOn, new DateTimeZone('America/Los_Angeles'));
        $UpdatedOn->setTimezone(new DateTimeZone('America/New_York'));

        $client->TDates->UpdatedOn = $UpdatedOn->format('m/d/Y h:ia T');

        $fromNow = Carbon::instance($UpdatedOn)->diffInMinutes();
        $client->TDates->UpdatedMinutesAgo = $fromNow;

    }
    public function GetClient($fileNumber, $properties = array())
    {
        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar($fileNumber, XSD_STRING, null, null, 'ns1:fileNumber');


        ## if they don't request this, request it anyway.
        if (!in_array('completed_steps_bitwise', $properties))
            array_push($properties, 'completed_steps_bitwise');

        if (!in_array('cart_status', $properties))
            array_push($properties, 'cart_status');

        if (!in_array('cart_items', $properties))
            array_push($properties, 'cart_items');

        if (!in_array('cart_id', $properties))
            array_push($properties, 'cart_id');

        if (!in_array('cart_steps_required', $properties))
            array_push($properties, 'cart_steps_required');

        if (!in_array('cart_order_time', $properties))
            array_push($properties, 'cart_order_time');

        if (!in_array('products_already_purchased', $properties))
            array_push($properties, 'products_already_purchased');

        // prepare the properties
        $clientProperties = array();
        $i = 0;

        foreach ($properties as $propertyName) {
            $clientProperties[$i] = new SoapVar($propertyName, XSD_STRING, null, null, 'ns1:string');
            $i++;
        }
        $parms[] = new SoapVar($clientProperties, SOAP_ENC_OBJECT, NULL, NULL, 'ns1:propertyNameList');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->GetClient(new SoapVar($parms, SOAP_ENC_OBJECT));


            if (isset($call->GetClientResult)) {
                //Lets reorganize the Parms.

                // Translate the ClientStatus so ClientStatus->{WorkName}= {STATUS}
                if (isset($call->GetClientResult->CurrentStatus->ClientStatus)) {
                    $call->GetClientResult->Status = new stdClass();
                    if (!is_array($call->GetClientResult->CurrentStatus->ClientStatus))
                        $call->GetClientResult->CurrentStatus->ClientStatus = array($call->GetClientResult->CurrentStatus->ClientStatus);

                    foreach ($call->GetClientResult->CurrentStatus->ClientStatus as $cin) {
                        if (isset($cin->WorkflowName) && isset($cin->Name))
                            $call->GetClientResult->Status->{$cin->WorkflowName} = $cin->Name;
                    }
                }

                $Properties = $call->GetClientResult->Properties->ClientProperty;
                if (!is_array($Properties)) $Properties = array($Properties);

                $call->GetClientResult->TProperties = new stdClass();
                foreach ($Properties as $prop) {
                    if (isset($prop->Name) && isset($prop->Value))
                        $call->GetClientResult->TProperties->{$prop->Name} = @$prop->Value;
                }


                if (!isset($call->GetClientResult->TProperties->completed_steps_bitwise))
                    $call->GetClientResult->TProperties->completed_steps_bitwise = 0;

                $call->GetClientResult->TProperties->completed_steps_bitwise = intval($call->GetClientResult->TProperties->completed_steps_bitwise);

                //our defaults
                $call->GetClientResult->CompletedStep = 0;
                $call->GetClientResult->NextStep = 1;


                for ($stepnumber = 1; $stepnumber <= 7; $stepnumber++) {
                    $constant = 'step' . $stepnumber;
                    if (defined($constant)) {
                        $constant_value = constant($constant);

                        if ($call->GetClientResult->TProperties->completed_steps_bitwise & $constant_value) {
                            $call->GetClientResult->CompletedStep = $stepnumber;
                            $call->GetClientResult->NextStep = $stepnumber + 1;
                            $call->GetClientResult->{'Completed_Step_'.$stepnumber} = true;
                        }
                        else
                        {
                            $call->GetClientResult->{'Completed_Step_'.$stepnumber} = false;
                        }
                    }
                }




                return $call->GetClientResult;
            }

            return false;


        } catch (SoapFault $fault) {
            return false;
        }
    }

    /**
     * Search for a client by there Social Security Number. It'll then validate that there Last Name matches what is on file for a little added security.
     *
     * @param string $lastname
     * @param string $ssn
     * @return Clients File ID if found (or false)
     */
    public function FindClientsMatching($firstname, $lastname, $WebsiteSource = null)
    {
        //remove any dashes from the $ssn

        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName' );
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password' );
        $parms[] = new SoapVar("FirstName", XSD_STRING, null, null, 'ns1:propertyName' );
        $parms[] = new SoapVar($firstname, XSD_STRING, null, null, 'ns1:propertyValue' );

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->SearchByProperty( new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->SearchByPropertyResult))
            {
                //check if we have any result here? what if it is an array?
                if (isset($call->SearchByPropertyResult->string))
                {

                    if (is_array($call->SearchByPropertyResult->string))
                    {
                        $clients  = $call->SearchByPropertyResult->string;
                    }

                    else
                    {
                        $clients = array($call->SearchByPropertyResult->string);
                    }


                    //alright we have the client file id, lets check that there last name matches.
                    $clientdetails = $this->getClients($clients, Array('ClientLogin','ClientPassword',
                        'BrowserLastState', 'BrowserCSL', 'BrowserLastState_Arguments','BrowserOwnerID', 'Cart_Expected_Log', 'Cart_Locked', 'Loan_Program','PublicService','Occupation','HomeNumber','TaxFilingStatus','CoIncome_Yearly',
                        'Income_Yearly','MaritalStatus','CoFirstName','CoLastName','CoSSN','CoDOB','FamilySize','Name1SSN', 'Name1DOB',
                        'AddressLine1', 'City', 'State', 'ZipCode','ClientLogin','ClientPassword', 'client_city', 'client_region', 'client_zipcode',
                        'EmailAddress', 'FirstName', 'LastName', 'Payment1Amount', 'AddressLine1', 'City', 'State', 'ZipCode',
                        'Loan_ContractFee_Override','Payment1Amount','Payment1DueDate','NoPayments','StartDate','Loan_ContractTotalFee','Loan_ContractFee','TotalPayments'), $WebsiteSource);

                    $results = array();
                    foreach($clientdetails as $clientdetail) {
                        if (strtolower($clientdetail->TProperties->LastName) == strtolower($lastname)) {
                            array_push($results, $clientdetail);
                        }
                    }

                    return $results;
                }
            }

            return false;

        } catch (SoapFault $fault) {
            return false;
            //error
        }
    }

    public function EnableDebugger($ActivePage)
    {
        $this->DebugPage = true;
        $this->ActivePage = $ActivePage;

    }
    public function DisableDebugger()
    {
        $this->DebugPage = false;
    }
    public function Debug_AddLogEntry($LogText)
    {
        $debug_file = dirname(__FILE__) . '/variables.txt';
        file_put_contents($debug_file, PHP_EOL . $this->ActivePage . ": ". $LogText, FILE_APPEND);
    }
    public function Debug_Log_Updated_Fields($fields)
    {
        if ($this->DebugPage)
        {
            $record = join(',', $fields);
            $this->Debug_AddLogEntry("fields updated: ".$record);
        }
    }
    public function Debug_Log_Updated_Status($statusId)
    {
        if ($this->DebugPage)
        {
            $record = "status updated: $statusId";
            $this->Debug_AddLogEntry($record);
        }
    }
    public function ChangeClientStatus($fileNumberList = array(), $statusId = 0)
    {
        if (isset($this->DebugPage))
        {
            $this->Debug_Log_Updated_Status($statusId);
        }

        if (!is_array($fileNumberList))
            $fileNumberList = array($fileNumberList);

        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar($statusId, XSD_STRING, null, null, 'ns1:statusId');

        // prepare the properties
        $fileNumberListObj = array();
        $i = 0;

        foreach ($fileNumberList as $fileNumber) {
            $fileNumberListObj[$i] = new SoapVar($fileNumber, XSD_STRING, null, null, 'ns1:string');
            $i++;
        }
        $parms[] = new SoapVar($fileNumberListObj, SOAP_ENC_OBJECT, NULL, NULL, 'ns1:fileNumberList');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->ChangeClientStatus(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->ChangeClientStatusResult)) {
                if (strtolower(substr($call->ChangeClientStatusResult, 0, 7)) == 'success') {
                    return true;
                } else {
                    $this->error = str_replace('FAIL: FAIL:', 'FAIL: ', $call->ChangeClientStatusResult);
                    return false;
                }
            }

            return false;

        } catch (SoapFault $fault) {
            return false;
        }

    }


    public function CreateOrUpdateClient($campaignId, $properties, $fileNumber = null)
    {
        if (isset($this->DebugPage))
        {
            $this->Debug_Log_Updated_Fields(array_keys($properties));
        }
        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar($campaignId, XSD_STRING, null, null, 'ns1:campaignId');

        // clientProperties
        $clientProperties = new ArrayObject();
        $propertyList = new ArrayObject();

        if (isset($fileNumber) && $fileNumber)
            $properties['LeadNumber'] = $fileNumber;


        // Encryption Storage System


        $i = 0;

        foreach ($properties as $propertyName => $propertyValue) {
            @$clientProperties[$i]->{'ns1:Name'} = $propertyName;
            @$clientProperties[$i]->{'ns1:Value'} = $propertyValue;
            $propertyList->append(new SoapVar($clientProperties[$i], SOAP_ENC_OBJECT, NULL, NULL, 'ns1:ClientProperty'));
            $i++;
        }

        //			array('FirstName', 'LastName', 'EmailAddress', 'HomeNumber');

        $parms[] = new SoapVar($propertyList, SOAP_ENC_OBJECT, NULL, NULL, 'ns1:propertyList');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->CreateOrUpdateClient(new SoapVar($parms, SOAP_ENC_OBJECT));

            //if this was success! return it.
            if (isset($call->CreateOrUpdateClientResult)) {
                if (substr($call->CreateOrUpdateClientResult, 0, 7) == 'SUCCESS') {
                    if (substr($call->CreateOrUpdateClientResult, 8))
                        return substr($call->CreateOrUpdateClientResult, 8);

                    else
                        return true;
                } else {
                    $this->error = $call->CreateOrUpdateClientResult;
                    return false;
                }
            }

            return false;


        } catch (SoapFault $fault) {

            echo "<br/><br/><font color='red'>";
            print("Returned the following ERROR: " . $fault->faultcode . "-" . $fault->faultstring);
            echo "</font>";
            return false;
            //error
        }

    }

    /**
     * Search for a client by there Social Security Number. It'll then validate that there Last Name matches what is on file for a little added security.
     *
     * @param string $lastname
     * @param string $ssn
     * @return Clients File ID if found (or false)
     */
    public function FindClientByLastNameAndSSN($lastname, $ssn)
    {
        //remove any dashes from the $ssn

        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar("Name1SSN", XSD_STRING, null, null, 'ns1:propertyName');
        $parms[] = new SoapVar($ssn, XSD_STRING, null, null, 'ns1:propertyValue');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->SearchByProperty(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->SearchByPropertyResult)) {
                //check if we have any result here? what if it is an array?
                if (isset($call->SearchByPropertyResult->string)) {
                    /**
                     * @todo Handle if there is more than one (there should never be!)
                     */
                    if (is_array($call->SearchByPropertyResult->string)) {
                        $client_file_id = array_shift($call->SearchByPropertyResult->string);
                    } else {
                        $client_file_id = $call->SearchByPropertyResult->string;
                    }


                    //alright we have the client file id, lets check that there last name matches.
                    $clientdetails = $this->getClient($client_file_id, array('LastName'));

                    if (strtolower($clientdetails->TProperties->LastName) == strtolower($lastname)) {
                        return $client_file_id;
                    }

                }
            }

            return false;

        } catch (SoapFault $fault) {
            return false;
            //error
        }
    }

    /**
     * Search for a client by there Email Address. It'll then validate that there Last Name matches what is on file for a little added security.
     *
     * @param string $lastname
     * @param string $email
     * @return Clients File ID if found (or false)
     */
    public function FindClientByLastNameAndEmail($lastname, $email)
    {
        //remove any dashes from the $ssn

        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar("EmailAddress", XSD_STRING, null, null, 'ns1:propertyName');
        $parms[] = new SoapVar($email, XSD_STRING, null, null, 'ns1:propertyValue');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->SearchByProperty(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->SearchByPropertyResult)) {
                //check if we have any result here? what if it is an array?


                if (isset($call->SearchByPropertyResult->string)) {
                    /**
                     * @todo Handle if there is more than one (there should never be!)
                     */
                    if (is_array($call->SearchByPropertyResult->string)) {

                        $fileids = array();

                        foreach ($call->SearchByPropertyResult->string as $fileid) {
                            $fileids[] = $fileid;
                        }

                    } else {
                        $fileids = array($call->SearchByPropertyResult->string);
                    }


                    //alright we have the client file id, lets check that there last name matches.
                    $clients = $this->GetClients($fileids, array('LastName'));

                    //foreach client and see if we have a match
                    foreach ($clients as $clientdetails) {
                        if (strtolower($clientdetails->TProperties->LastName) == strtolower($lastname)) {
                            return $clientdetails->FileNumber;
                        }
                    }

                }
            }

            return false;

        } catch (SoapFault $fault) {
            return false;
            //error
        }
    }


    /**
     * Search for a client by the City. It'll then validate that there Last Name matches what is on file for a little added security.
     *
     * @param string $city
     * @return Clients File ID if found (or false)
     */
    public function FindClientsByCity($city)
    {
        //remove any dashes from the $ssn

        $soap_url = $this->GetClientWSDL();
        $parms = array();
        $parms[] = new SoapVar($this->userName, XSD_STRING, null, null, 'ns1:userName');
        $parms[] = new SoapVar($this->password, XSD_STRING, null, null, 'ns1:password');
        $parms[] = new SoapVar("client_region", XSD_STRING, null, null, 'ns1:propertyName');
        $parms[] = new SoapVar($city, XSD_STRING, null, null, 'ns1:propertyValue');

        $soap = new SoapClient($soap_url, $this->GetSoapOptions());

        try {
            $call = $soap->SearchByProperty(new SoapVar($parms, SOAP_ENC_OBJECT));
            if (isset($call->SearchByPropertyResult)) {
                //check if we have any result here? what if it is an array?


                if (isset($call->SearchByPropertyResult->string)) {
                    /**
                     * @todo Handle if there is more than one (there should never be!)
                     */
                    if (is_array($call->SearchByPropertyResult->string)) {

                        $fileids = array();

                        foreach ($call->SearchByPropertyResult->string as $fileid) {
                            $fileids[] = $fileid;
                        }

                    } else {
                        $fileids = array($call->SearchByPropertyResult->string);
                    }


                    //alright we have the client file id, lets check that there last name matches.
                    $clients = $this->GetClients($fileids, array('FirstName', 'LastName', 'client_city', 'client_region', 'client_zipcode'));

                    return $clients;

                }
            }

            return false;

        } catch (SoapFault $fault) {
            return false;
            //error
        }
    }

}
