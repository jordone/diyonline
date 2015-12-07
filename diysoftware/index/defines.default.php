<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/21/2015
 * Time: 3:49 AM
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

define('cart_product_consolidation_app', 1);
define('cart_product_repayment_app', 2);
define('cart_product_recertification_app', 4);
define('cart_product_pslf_app', 8);
define('cart_product_forebearance_app', 16);

defined("MAIN_TEXT_TOP") or define('MAIN_TEXT_TOP', 'Congratulations On Taking The 1st Step And Choosing DIY Student Loan Services To Get Your Lower Federal Student Loan Re-Payment Options.<br/> Lets Get Started');
