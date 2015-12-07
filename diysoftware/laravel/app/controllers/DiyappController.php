<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/21/2015
 * Time: 3:46 AM
 */
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;

error_reporting(0);



class DiyappController extends BaseController
{
    protected $layout = 'layouts.master';

    public function __construct()
    {
        $this->Leadtrac_Init();

        /**
         * Adding this so I do not have to login every time. We shouldn't have it enabled but:
         * http://diy.app/step1?fn=DY415.19410
         *
         * works
         */
        if (Input::get('fn')) {
            Session::put('fileNumber', $_GET['fn']);
            Session::flash('dont_auto_logout', true);
            Session::put('Step3Completed', true);
            Session::put('accepted_disclosure', 1);
        }
    }

    public function View_Homepage()
    {
        /**
         * Check if they should be automatically logged out for reloading the page.
         */
        if (!Session::get('dont_auto_logout'))
        {

            $this->LogOutActions();
            Session::forget('fileNumber');
            Session::forget('Step3Completed');
            Session::flash('dont_auto_logout',1);
        }

        $this->View_Edit_Step('step1');
    }

    public function View_Edit_Step($step)
    {
        if (substr($step,0,4) != 'step') $step = 'step'.intval($step);


        if (Session::get('fileNumber'))
        $view_steps = ['step1','step2','step3','step4', 'step5', 'step6', 'step7'];

        else
            $view_steps = ['step1'];


        $view_steps = ['step1','step2','step3','step4', 'step5', 'step6', 'step7'];
        $CartTotal = Session::get('cartTotal');


        if ($step == 'step7')
        {
            $CartTotal = $this->System_Get_Cart_Total();
            Session::put('cartTotal', $CartTotal);
        }
        $TotalDue = $CartTotal;

        if (!in_array($step, $view_steps))
            $step = $view_steps[0];

        if (Session::get('fileNumber'))
        {
            $file = Session::get('fileNumber');
            if ($step == 'step6')
            $client = $this->GetClientByFileNumber($file, false);
            else
            $client = $this->GetClientByFileNumber($file);

            $update_enabled = true;

            if ($step == 'step7')
            {
                $repay =  Input::get("np");
                $ClientDetails = $client;
                $exp_years = range(date('Y'), date('Y')+19);
                $CartTotal = $this->System_Get_Cart_Total();

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
                    $this->layout->content .= View::make('steps.'.$step,compact('client', 'update_enabled', 'CartTotal','TotalDue'));

                }
            }
            else
                $this->layout->content .= View::make('steps.'.$step, compact('client', 'update_enabled','TotalDue'));
        }
        else
        {
            $client = $this->GetClientByFileNumber('0');

            // we've already verified they can view some of these steps by the $view_steps array, so let's just proceed with what's being loaded.
            $this->layout->content .= View::make('steps.'.$step, array('client' => $client, 'update_enabled' => true));

        }

    }




    public function LogOutActions()
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


}