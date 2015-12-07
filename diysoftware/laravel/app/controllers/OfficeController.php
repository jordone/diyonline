<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/11/2015
 * Time: 2:32 PM
:( humans suck. jews 2 bryan 0.
 */

use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;

error_reporting(0);


class OfficeController extends BaseController {
    protected $layout = 'layouts.master_office';
    private $menu_shown = false;

    public function __construct()
    {
        $this->Leadtrac_Init();
    }

    public function View_MerchantCloseFile()
    {
        $this->Merchant_ClearSelectedClient();
        return $this->RedirectWithSuccess('/office/merchant', 'You have closed your clients file. You can create a new one below!');

    }

    /// merchant signup
    public function View_Merchant1($filenumber = null)
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        if (isset($filenumber) && $filenumber)
        {
            if ($this->Merchant_GetSelectedClientFileNumber() != $filenumber)
            {
                $this->Merchant_SetSelectedClient($filenumber);
            }
        }

        $Client = $this->Merchant_GetSelectedClient(true, true);

        $this->layout->content .= View::make('steps.merchant1', ['client' => $Client, 'update_enabled' => true]);

        if (isset($Client->NextStep) && $Client->NextStep >= 2 && !Request::Ajax())
        {
            $this->View_Merchant2();
        }


    }

    /// merchant select service
    public function View_Merchant2($filenumber = null)
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        if (isset($filenumber) && $filenumber)
        {
            if ($this->Merchant_GetSelectedClientFileNumber() != $filenumber)
            {
                $this->Merchant_SetSelectedClient($filenumber);
            }
        }

        $Client = $this->Merchant_GetSelectedClient(true, true);
        $this->layout->content .= View::make('steps.merchant2', ['client' => $Client, 'update_enabled' => true]);
    }

    /// merchant select service
    public function View_Merchant3($filenumber = null)
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        if (isset($filenumber) && $filenumber)
        {
            if ($this->Merchant_GetSelectedClientFileNumber() != $filenumber)
            {
                $this->Merchant_SetSelectedClient($filenumber);
            }
        }
        $exp_years = range(date('Y'), date('Y')+19);

        $Client = $this->Merchant_GetSelectedClient(true, true);
        $this->layout->content .= View::make('steps.merchant3', ['client' => $Client, 'update_enabled' => true,'exp_years' => $exp_years, 'TotalDue' => $Client->TProperties->TotalPayments]);
    }


    public function Post_Merchant2($filenumber = null)
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        if (isset($filenumber) && $filenumber && $this->Merchant_GetSelectedClientFileNumber() != $filenumber)
        {
            $this->Merchant_SetSelectedClient($filenumber);
        }

        $clientdetails = $this->Merchant_GetSelectedClient(true, false);
        if (!$clientdetails)
            return   $this->RedirectWithError('/office/merchant', "Unable to obtain client details. No client has been selected or you have closed the selected clients file from another browser. Please visit the Client Lookup page to search your database");

        // Now, we check what they ordered:

        $paid_products = array(
            'consolidation_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_consolidation_app ? true : false),
            'repayment_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_repayment_app ? true : false),
            'recertification_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_recertification_app ? true : false),
            'pslf_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_pslf_app ? true : false),
            'forebearance_app' => ($clientdetails->TProperties->products_already_purchased & cart_product_forebearance_app ? true : false),
        );
        // alright lets store what they would like to order:
        $cart_items = 0;
        $CartTotal = 0;

        //items for re-payment (already consolidated)
        if (Input::get('repayment_promisory_note') && !$paid_products['repayment_app'])
        {
            $cart_items |= cart_product_repayment_app;
            $CartTotal += number_format(PRICE_REPAYMENT_NOTE,2);
        }

        //items for consolidation forms
        if (Input::get('consolidation_promisory_note') && !$paid_products['consolidation_app'])
        {
            $cart_items |= cart_product_consolidation_app;
            $CartTotal += number_format(PRICE_CONSOLIDATION_NOTE,2);

        }

        if (Input::get('pslf_app') && !$paid_products['pslf_app'])
        {
            $cart_items |= cart_product_pslf_app;
            $CartTotal += number_format(PRICE_PSLF_APP,2);

        }

        if (Input::get('forebearance_app') && !$paid_products['forebearance_app'])
        {
            $cart_items |= cart_product_forebearance_app;
            $CartTotal += number_format(PRICE_FOREBEARANCE_APP,2);

        }

        if (Input::get('recertification_app') && !$paid_products['recertification_app'])
        {
            $cart_items |= cart_product_recertification_app;
            $CartTotal += number_format(PRICE_RECERTIFICATION_APP,2);
        }

        if ($cart_items == 0)
        {
            return	$this->RedirectWithError('office/merchant/cart/'.$clientdetails->FileNumber, ' You must select at least one product to order.');
        }


// we're placing an order, let's record that these are the products we're buying.
        $updateFields[$this->tracking_field_name] = $this->tracking_field_value;
        $updateFields['cart_items'] = $cart_items;
        $updateFields['cart_id'] = uniqid();
        $updateFields['cart_steps_required'] = 0;

        if ($cart_items & cart_product_consolidation_app || $cart_items & cart_product_repayment_app || $cart_items & cart_product_recertification_app )
            $updateFields['cart_steps_required'] = step1 | step2 | step3 | step4 | step5 | step6 | step7;

        else
            $updateFields['cart_steps_required'] = step1 | step2 | step3 | step4 | step7;

        $updateFields['cart_order_time'] = time();

        // alright lets set the ProgramPriceOverride
        $updateFields['Loan_ContractFee_Override'] = number_format($CartTotal,2);
        $updateFields['Payment1Amount'] = number_format($CartTotal,2);
        $updateFields['Payment1DueDate'] = date('m/j/Y');
        $updateFields['NoPayments'] = 1;
        $updateFields['StartDate'] = date('m/j/Y');
        $updateFields['Loan_ContractTotalFee'] = number_format($CartTotal,2);
        $updateFields['Loan_ContractFee'] = number_format($CartTotal,2);
        $updateFields['TotalPayments'] = number_format($CartTotal,2);
        $updateFields['Cart_Expected_Log'] = "Approved for \$".number_format($updateFields['Payment1Amount'],2);


        if ( !($clientdetails->TProperties->completed_steps_bitwise & step4) )
            $updateFields['completed_steps_bitwise'] = @$clientdetails->TProperties->completed_steps_bitwise | step4;

        $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $updateFields, $this->Merchant_GetSelectedClientFileNumber());
        $this->Merchant_GetSelectedClient(false, false);

        return Redirect::to('office/merchant/payments/'.$clientdetails->FileNumber);
    }

    public function Post_Merchant3()
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        /**
         * Check if they are logged in
         */
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
            return Redirect::to('/office/merchant/payments/'.$this->Merchant_GetSelectedClientFileNumber())->withErrors($Validate);
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

        $ClientData['Payment1Amount'] = input::get('tprice');
        $ClientData['Payment1DueDate'] = date('m/j/Y');
        $ClientData['NoPayments'] = intval(input::get('ltf.NoPayments'));
        $ClientData['StartDate'] = date('m/j/Y', strtotime(OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE));

        $ClientData['Loan_ContractFee_Override'] =  input::get('tprice');
        $ClientData['Loan_ContractTotalFee'] =  input::get('tprice');
        $ClientData['Loan_ContractFee'] =  input::get('tprice');
        $ClientData['TotalPayments'] = input::get('tprice');

        $payments = array();

        $number_payments = intval(input::get('ltf.NoPayments'));


        ### settings
        $ClientData['NoPayments'] = $number_payments;

        $total_payment_amount = 0;

        for($payment_num=1;$payment_num<=$number_payments;$payment_num++)
        {
            // payment is:
            $payment_price = str_replace('$','', input::get( sprintf('fixed_payments_price_%s',$payment_num) ) );
            $payment_duedate =   DateTime::createFromFormat('m/d/Y', input::get( sprintf('fixed_payments_duedate_%s',$payment_num)) );

            if ($payment_num == 1)
                $ClientData['StartDate'] = $payment_duedate->format('m/j/Y');

            if ($payment_price <= 0)
                return $this->RedirectWithError('/office/merchant/payments/'.$this->Merchant_GetSelectedClientFileNumber(), 'Must enter a valid amount for payment#'.$payment_num);

            $ClientData['Payment'.$payment_num.'Amount'] = $payment_price;
            $total_payment_amount+= $payment_price;
            if (!method_exists($payment_duedate, 'format'))
            {
                return $this->RedirectWithError('/office/merchant/payments/'.$this->Merchant_GetSelectedClientFileNumber(), 'Must enter a valid date for payment#'.$payment_num);
            }

            $ClientData['Payment'.$payment_num.'DueDate'] = $payment_duedate->format('m/j/Y');
        }

        if (Input::get('cart_is_free_override'))
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
        // set what we're looking for in the log
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

        // we could assign the status here that would be a list of the products. but since we're going to see that it's uon this status, I'll do it another place. Then move it back up here

        $ClientData['cart_processing_check4'] = "Progress changed to 100% complete"; // five minutes from now.

//        $ClientData['cart_processing_check5'] = "Updating Statuses"; // five minutes from now.
//        $ClientData['cart_processing_check6'] = "Unlocking Cart"; // five minutes from now.
//        $ClientData['cart_processing_check7'] = "Completed. 100% Payment Received &amp; Documents have been generated."; // five minutes from now.
        $ClientData['cart_processing_checks'] = 3; //what's the total number of checks left
        $ClientData['cart_processing_completed_assert'] = 'intval($client->TProperties->cart_processing_typeid) >= intval($client->TProperties->cart_processing_checks)'; //what's the total number of checks left
        $ClientData['cart_processing_failed_assert'] = '$ClientData[\'cart_processing_type\'] == \'fail\''; //


        for($i=1;$i<=7;$i++)
            $ClientData["cart_processing_check{$i}_result"] = ""; // Set all results to blank. They will be saved once process has executed.


        $ClientData['cart_processing_last_check'] = time(); // five minutes from now.
        $ClientData['cart_processing_check_every'] = 30; // when should we check again for the invoice?.
        $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $ClientData, $this->Merchant_GetSelectedClientFileNumber());
        $this->leadtracapi->ChangeClientStatus($this->Merchant_GetSelectedClientFileNumber(), $this->statusUpdates['Process Payments']);

        // force our cache to update.
        $this->Merchant_GetSelectedClient(false, false);

        // redirect them to process payments
        return $this->RedirectWithSuccess('/office/merchant/payments/process/'.$this->Merchant_GetSelectedClientFileNumber(), "Completed! We're now attempting to charge your customers credit card, please do not refresh the page until this process has completed!" );
    }

    /**
     * @param stdObject $client
     * @return array
     */
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

    public function View_Progressbar()
    {
        return View::make('layouts.progress');
    }
    public function ViewMerchantProgress($filenumber = null)
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }
        if (isset($filenumber) && $filenumber && $this->Merchant_GetSelectedClientFileNumber() != $filenumber)
        {
            $this->Merchant_SetSelectedClient($filenumber);
        }
        $client = $this->Merchant_GetSelectedClient(true, true);
        $ClientData = $this->MerchantProcess_LTVars($client);

        $Processing_NextCheck_Percent = $client->Processing_NextCheck_Percent;

        if ($client->Processing_Check_Completed && !$client->Processing_Check_Failed)
            $Processing_ExpiresTime_Percent = 101.00;

        else
            $Processing_ExpiresTime_Percent = $client->Processing_ExpiresTime_Percent;

        $CheckResults = ["cart_processing_check1","cart_processing_check2","cart_processing_check3","cart_processing_check4",
            //"cart_processing_check5", "cart_processing_check6", "cart_processing_check7"
        ];

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
    public function View_MerchantProcess($filenumber = null)
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }
        if (isset($filenumber) && $filenumber && $this->Merchant_GetSelectedClientFileNumber() != $filenumber)
        {
            $this->Merchant_SetSelectedClient($filenumber);
        }

        $client = $this->Merchant_GetSelectedClient(true, true);
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
            $Processing_ExpiresTime_Percent = 101;

        $Progress1 = ['progress' => $Processing_ExpiresTime_Percent, 'text' => $ClientData['cart_processing_text']];
        $Progress2 = ['progress' => $Processing_NextCheck_Percent, 'text' => $ClientData['cart_processing_text']];

        $CheckResults = ["cart_processing_check1","cart_processing_check2","cart_processing_check3","cart_processing_check4",
//            "cart_processing_check5", "cart_processing_check6", "cart_processing_check7"
        ];

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
            return compact($Variables);
        }

        $this->layout->content .= View::make('steps.merchant4',
            compact($Variables, "client"));

    }
    /**
     * Merchant Process1: Processing Ajax Function
     *
     */
    public function View_MerchantCheck1(&$client = null)
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        ## set client to false, this will force it to be retreived without being cached
        $client = false;
        $ClientData = $this->MerchantProcess_LTVars($client);

        $payment = $client->ClientPayment;
        // First, check if it is still Processing (Status is Processing Payment) and Payment_Received is false.
        // if this is the case, we send them back to ProcessPayment view
        switch(strtolower($payment->status)) {
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
        if (!$this->System_IsUserLoggedIn()) {
            return $this->View_Office_Login_Page();
        }

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
        if (!$this->System_IsUserLoggedIn()) {
            return $this->View_Office_Login_Page();
        }

        ## set client to false, this will force it to be retreived without being cached
        $client = false;
        $ClientData = $this->MerchantProcess_LTVars($client);
        $ClientData['cart_processing_typeid'] = 0;
        $ClientData['cart_processing_type'] = 'finished';
        $ClientData['cart_processing_text'] = "Success";
        $ClientData['cart_processing_text_var'] = "";
        $ClientData["cart_processing_check3_result"] = "Success!";
        $ClientData["cart_process_immediate"] = true;

        $paymentsreceived = 'process2paidemail';

        if (isset($this->statusUpdates['all']->{$paymentsreceived}))
        {
            $Order_PaymentsReceived = $this->statusUpdates['all']->{$paymentsreceived};
            $this->leadtracapi->ChangeClientStatus($this->Merchant_GetSelectedClientFileNumber(), $Order_PaymentsReceived);
        }



        return $ClientData;
    }
    public function View_MerchantCheck4(&$client = null)
    {
        if (!$this->System_IsUserLoggedIn()) {
            return $this->View_Office_Login_Page();
        }

        ## set client to false, this will force it to be retreived without being cached
        $client = false;
        $ClientData = $this->MerchantProcess_LTVars($client);

        $ClientData['cart_processing_typeid'] = $ClientData['cart_processing_next_typeid'];
        $ClientData['cart_processing_text'] = $ClientData['cart_processing_next_text'];
        $ClientData['cart_processing_text_var'] = $ClientData['cart_processing_next_text_var'];
        $ClientData["cart_processing_check4_result"] = "Success!";

        return $ClientData;
    }



    public function Post_Merchant_LFT_SaveFormData()
    {
        // associate the post fields with the actual fields in lead trac, and have nothing left to do.
        // voila.

        $Fields = Input::get('ltf');
        $Step = Input::get('merchant.step');
        $UpdateClientStatus = Input::get('merchant.status');

        $Required_Fields = explode('|',Input::get('merchant.fields'));
        $ValidationRules =     array(

        );

        /// here's our pages by step:
        $Pages = [];

        $Pages[1] = 'office/merchant';
        $Pages[2] = 'office/merchant/cart';

        $Page_Saved_From = (isset($Pages[$Step]) && $Pages[$Step] ? $Pages[$Step] : $Pages[1]);


        foreach($Required_Fields as $Required_Field)
        {
            if (stristr($Required_Field, 'email'))
                $ValidationRules['ltf.'.$Required_Field] = 'required|email';

            else {
                if (Input::get('merchant.vr.'.$Required_Field))
                    $ValidationRules['ltf.'.$Required_Field] = Input::get('merchant.vr.'.$Required_Field);
                else
                    $ValidationRules['ltf.'.$Required_Field] = 'required|min:1';
            }
        }

        $Validate = Validator::make(Input::all(), $ValidationRules);

        if ($Validate->fails())
            return Redirect::to($Page_Saved_From)->withErrors($Validate);


        /// get the fields we're updating
        if (is_array($Fields))
        {
            // we should update the tracking field to keep our client close ;0
            $Fields[$this->tracking_field_name] = $this->tracking_field_value;
            $Fields['SYSTEM_SIGNUP_TYPE'] = "MERCHANT";

            if (isset($Fields['HomeNumber'])) $Fields['HomeNumber'] = str_replace(['(',')','-',' '], ['','','',''], $Fields['HomeNumber']);



            $fileNumber = $this->leadtracapi->CreateOrUpdateClient($this->campaignId, $Fields, $this->Merchant_GetSelectedClientFileNumber());
            if ($fileNumber)
            {
                $this->Merchant_SetSelectedClient($fileNumber);
            }
            else
            {
                return $this->RedirectWithError($Page_Saved_From, "Error, Client File Number wasn't returned after being saved.");
            }
        }
        else
        {
            return $this->RedirectWithError($Page_Saved_From, "Error, you must provide which fields to update.");
        }

        /**
         * Check that we've got a file number before attempting to go any farther.
         */
        if ($fileNumber)
        {
            if (!is_array($UpdateClientStatus) && $UpdateClientStatus != "") $UpdateClientStatus = array($UpdateClientStatus);

            if (is_array($UpdateClientStatus))
            {
                foreach($UpdateClientStatus as $Status)
                {
                    // Update the client's status. to each of these (or it's just one)

                }
            }
            $this->Merchant_GetSelectedClient(false, false);
        }
        // reset our cached version to the newest

        switch(intval($Step))
        {
            default:
            case 1:
                if (Request::Ajax())
                    $url = "office/merchant/cart/{$fileNumber}";
                else
                    $url = "office/merchant/signup/{$fileNumber}";
                break;

        }

        return Redirect::to($url);

    }

    /**
     * Returns the file number currently being updated.
     *
     * @return mixed
     */
    public function Merchant_GetSelectedClientFileNumber()
    {
        if (Session::get('Merchant.FileNumber'))
            return Session::get('Merchant.FileNumber');

        return null;
    }
    public function Merchant_GetSelectedClient($cache=true, $always_return_userobject = true)
    {
        if ( Session::get('Merchant.FileNumber') )
        {
            $FileNumber = Session::get('Merchant.FileNumber');
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

    public function Merchant_SetSelectedClient($FileNumber)
    {
        Session::put('Merchant.FileNumber', $FileNumber);

        return true;
    }

    public function Merchant_ClearSelectedClient()
    {
        Session::forget('Merchant.FileNumber');
        return true;
    }


    /**
     * Home Page
     */
    public function View_Office_HomePage()
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        else
        {

            $this->View_Search_Page();

            if (!Session::get('Office.Search.Results'))
                $this->View_Recently_Updated();
            else
                $this->View_Search_Results();

        }
    }

    public function View_Recently_Updated()
    {
        if (!$this->System_IsUserLoggedIn())
        {
            return $this->View_Office_Login_Page();
        }

        $app = &$this;

        $RecentlyUpdated_Storage_Key = md5($_SERVER['HTTP_HOST']);

        $RecentDocuments = Cache::remember('RecentlyUpdated.'.$RecentlyUpdated_Storage_Key, 4, function() use ($app) {
            $Documents =  $app->leadtracapi->GetUpdatedClients(date('c', strtotime('5 day ago')), date('c'), $app->tracking_field_value);

            return $Documents;
        });

        $this->layout->content .= View::make('office.recentlyupdatedclients', array('recentclients' => $RecentDocuments));

    }
    /**
     * Load the Login Page
     */
    public function View_Office_Login_Page()
    {
        if (!$this->System_IsUserLoggedIn()) {
            $this->layout->content .= View::make('office.login');
        }

        else
            return  $this->RedirectWithSuccess('/office/', "You are already logged in.");
    }

    public function Post_Office_Login_Page()
    {
        $password = "";

        if (Input::get('password'))
            $password = Input::get('password');
        elseif (Input::get('p'))
            $password = Input::get('p');

        return $this->System_Verify_OfficePassword($password);

    }

    public function System_Verify_OfficePassword($password)
    {
        $redirect_to = 'office/login';

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
        if (!verify_office_password($password))
        {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('Error', '<b>Office Password Not Authorized:</b> Please enter office password to access this restricted area.. ['. Session::get('office_login_attempts').'/3]');

            return Redirect::to($redirect_to)->withErrors($messages);
        }
        else
        {
            // password verified, phew.
            $this->System_User_Has_Logged_In($password);
            Session::forget('office_login_attempts');
            Session::forget('office_login_timeout');

            return $this->RedirectWithSuccess('/office/', 'You are now logged in. You can create a new client below or search for client using the button above.');

            //			$this->Step7();
        }
    }


    /**
     * Logout Actions (Removes the auth office_use)
     */
    function System_LogOutActions()
    {
        Session::flush();
    }

    public function System_User_Has_Logged_In()
    {
        $Office_Session_Auth = true;
        $Office_Session_pw = Input::get('password', Input::get('p'));

        if (isset($Office_Session_Auth) && $Office_Session_Auth)
            Session::put('Office_Session_Auth', $Office_Session_Auth);
        if (isset($Office_Session_pw) && $Office_Session_pw)
            Session::put('auth_office_password', $Office_Session_pw);
    }

    public function View_Search_Page()
    {
        $this->layout->content .= View::make('office.search');
    }

    /**
     * This page initiates the Search - it's sort of a queue page that executes the actual page that needs loaded.
     * @return mixed
     */
    public function Post_Searching_Page()
    {
        if (Input::get('firstname') && Input::get('lastname')) {

            $this->layout->content .= View::make('office.searching');
        }
        else
        {
            return $this->RedirectWithError('/office/search', 'Search Failed. You must provide customers First and Last Name. Last name must be exactly what your client provided.');
        }
    }


    /**
     * Here's the search results.
     * @return mixed
     */
    public function Post_Search_Page()
    {
        Session::put('Office.Search.FirstName', Input::get('firstname'));
        Session::put('Office.Search.LastName', Input::get('lastname'));

        if (Input::get('firstname') && Input::get('lastname')) {
            // issue teh search,
            $search_results = $this->leadtracapi->FindClientsMatching(Input::get('firstname'), Input::get('lastname'), $this->tracking_field_value);

            if (!is_array($search_results) || !count($search_results))
                return $this->RedirectWithError('/office/', 'Search found zero clients matching: "'.htmlspecialchars(Input::get('firstname')).' '.htmlspecialchars(Input::get('lastname')).'"');

            Session::put('Office.Search.Results', $search_results );
            ### We need to actually split the results into 2.
            return $this->RedirectWithSuccess('/office/results', 'Found '.count(Session::get('Office.Search.Results')).' clients matching your search');
        }
        else
        {
            return $this->RedirectWithError('/office/', 'You must enter both a first and last name to search for documents.');
        }
    }

    public function View_Search_Results()
    {

        $this->layout->content .= View::make('office.search_results', array('clientlists' => Session::get('Office.Search.Results')));
    }

    public function View_Clearout_Search_Results()
    {
        Session::forget('Office.Search');
//        return $this->RedirectWithSuccess('/office/', 'Your search has been emptied.');
//        $this->layout->content .= View::make('office.search_results', array('clientlists' => Session::get('Office.Search.Results')));

        return $this->View_Office_HomePage();

    }

    public function Post_View_Client()
    {
        Session::put('Office.SelectedFileNumber', Input::get('file'));
    }

    public function View_Document($file)
    {
        if (!$this->System_IsUserLoggedIn())
            return $this->RedirectWithError('/office/', 'You must first log in to access this area.');

        $client = $this->GetClientByFileNumber($file);

        if (!$client)
            return $this->RedirectWithError('/office/', 'Document cannot be located.');

        $clientLastUpdate = $this->GetClientUpdatedProfile($file);


        $this->layout->content .= View::make('office.document_viewer', array('client' => $client, 'lastupdate' => $clientLastUpdate));

        $steps_displayed = [];


        for($i=1;$i<=7;$i++)
        {
            if (constant('step'.$i))
            {
                $step_constant = constant('step'.$i);

                //check if it's completed
                if ($client->TProperties->completed_steps_bitwise & $step_constant)
                {
                    $this->View_Document_Step($file, 'step'.$i);
                    $steps_displayed[] = $i;
                }
                else
                    break;
            }
        }

//        if (!in_array($client->NextStep, $steps_displayed))
//        {
//            $this->View_Document_Step($file, 'step'.$client->NextStep);
//        }

    }
    public function CheckForClientUpdate($file, $last_update = 0)
    {
        $clientLastUpdate = $this->GetClientUpdatedProfile($file);

        if ($last_update < $clientLastUpdate['updatetime'])
        {
            return ['HasUpdated' => true, 'lastupdate' => $clientLastUpdate];
        }
        else return ['HasUpdated' => false, 'lastupdate' => $clientLastUpdate];

    }
    public function View_Document_Step($file, $step)
    {
        if (!$this->System_IsUserLoggedIn())
            return $this->RedirectWithError('/office/', 'You must first log in to access this area.');

        if (!$file)
            return $this->RedirectWithError('/office/search', 'Document cannot be located. No file provided.');

        $client = $this->GetClientByFileNumber($file);

        if (!$client)
            return $this->RedirectWithError('/office/search', 'Document cannot be located. Error#2.');

        $this->layout->content .= View::make('steps.'.$step, array('client' => $client, 'update_enabled' => false, 'office_viewer' => true));
    }





    public function System_IsUserLoggedIn()
    {

        if (Session::get('Office_Session_Auth'))
        {
            if (!Session::get('auth_office_use'))
                Session::put('auth_office_use', true);

            return true;
        }

        return false;
    }
}