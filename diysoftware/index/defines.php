<?php

## Website Source
define("LEADTRACK_DOMAIN_KEY", 'MAINSITE');
define("LEADTRAC_WEBSITE_SOURCE", 'MAINSITE');

define('LEADTRAC_API_USERNAME', 'diy.admin');
define('LEADTRAC_API_PASSWORD', 'wo01e89KI2B0');


define('WEBSITE_LOGO', '55ccc087b0fd5.png');
define('WEBSITE_TITLE', 'DIY Student Loan Services');
define('COMPANY_NAME', 'DIY Student Loan Services');

defined("MAIN_TEXT_TOP") or define('MAIN_TEXT_TOP', 'CONGRATULATIONS ON TAKING THE 1ST STEP AND CHOOSING DIY STUDENT LOAN SERVICES TO HELP YOU GET YOUR LOWER FEDERAL STUDENT LOAN RE-PAYMENT OPTIONS. LETS GET STARTED!');

## PRICES ##
defined("PRICE_CONSOLIDATION_NOTE") or define('PRICE_CONSOLIDATION_NOTE', '74.00');
defined("PRICE_RECERTIFICATION_APP") or define('PRICE_RECERTIFICATION_APP', '15.00');
defined("PRICE_FOREBEARANCE_APP") or define('PRICE_FOREBEARANCE_APP', '15.00');
defined("PRICE_REPAYMENT_NOTE") or define('PRICE_REPAYMENT_NOTE', '-1');
defined("PRICE_PSLF_APP") or define('PRICE_PSLF_APP', '-1');
//defined("SHOW_CREDITCARD_FORM") or define('SHOW_CREDITCARD_FORM', '-1');

### LINK NAMES FOR OFFICE USE ####

#### Here's the list!

### Styles to use
defined('MAINTENANCE_ENABLED') or define('MAINTENANCE_ENABLED', 'no');
defined('MAINTENANCE_START') or define('MAINTENANCE_START', '08/13/2015 6:00 AM');
defined('MAINTENANCE_END') or define('MAINTENANCE_END', '08/22/2015 4:05 AM');


### Styles to use
defined('OFFICE_LINK_ENABLED') or define('OFFICE_LINK_ENABLED', 'yes');
defined('OFFICE_LINK_STYLE') or define('OFFICE_LINK_STYLE', 'dropdown');
defined('OFFICE_LOGIN_STYLE') or define('OFFICE_LOGIN_STYLE', 'dropdown');


#### new payment defaults
defined('OFFICE_LINK_A_ENABLED') or define('OFFICE_LINK_A_ENABLED', 'yes');
defined('OFFICE_LINK_A_NAME') or define('OFFICE_LINK_A_NAME', '[B]');
defined('OFFICE_LINK_A_NUM_PAYMENTS') or define('OFFICE_LINK_A_NUM_PAYMENTS', '2');
defined('OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE') or define('OFFICE_LINK_A_PAYMENT1_DEFAULT_DATE', 'today');
defined('OFFICE_LINK_A_PAYMENT2_DEFAULT_DATE') or define('OFFICE_LINK_A_PAYMENT2_DEFAULT_DATE', '+1 month');

### B
defined('OFFICE_LINK_B_ENABLED') or define('OFFICE_LINK_B_ENABLED', 'yes');
defined('OFFICE_LINK_B_NAME') or define('OFFICE_LINK_B_NAME', '[C]');
defined('OFFICE_LINK_B_NUM_PAYMENTS') or define('OFFICE_LINK_B_NUM_PAYMENTS', '3');
defined('OFFICE_LINK_B_PAYMENT1_DEFAULT_DATE') or define('OFFICE_LINK_B_PAYMENT1_DEFAULT_DATE', 'today');
defined('OFFICE_LINK_B_PAYMENT2_DEFAULT_DATE') or define('OFFICE_LINK_B_PAYMENT2_DEFAULT_DATE', '+1 month');
defined('OFFICE_LINK_B_PAYMENT3_DEFAULT_DATE') or define('OFFICE_LINK_B_PAYMENT3_DEFAULT_DATE', '+1 months');


defined('OFFICE_LINK_NOPAYMENT_ENALBED') or define('OFFICE_LINK_NOPAYMENT_ENALBED', 'yes');
defined('OFFICE_LINK_NOPAYMENT_NAME') or define('OFFICE_LINK_NOPAYMENT_NAME', 'Office');


### CUSTOM
defined('OFFICE_USE_CUSTOM_ENABLED') or define('OFFICE_USE_CUSTOM_ENABLED', 'yes');
defined('OFFICE_LINK_GENERATE_FORMS_NAME') or define('OFFICE_LINK_GENERATE_FORMS_NAME', 'yes');

### Step Customizations
defined('STEP_DISPLAY_TEXT') or define('STEP_DISPLAY_TEXT', '');

## Step 1 - 7 defines
defined('STEP1_TITLE') or define('STEP1_TITLE', 'Step 1: Contact Information');
defined('STEP2_TITLE') or define('STEP2_TITLE', 'Step 2: Qualifying Information');
defined('STEP3_TITLE') or define('STEP3_TITLE', 'Step 3: Personal Information');
defined('STEP4_TITLE') or define('STEP4_TITLE', 'Step 4: Services');
defined('STEP5_TITLE') or define('STEP5_TITLE', 'Step 5: Student Loans');
defined('STEP6_TITLE') or define('STEP6_TITLE', 'Step 6: Repayment Plans Available ');
defined('STEP7_TITLE') or define('STEP7_TITLE', 'Step 7: Checkout');
defined('STEP7b_TITLE') or define('STEP7b_TITLE', 'Step 5: Checkout');

defined('STEP1_DISPLAY_TEXT') or define('STEP1_DISPLAY_TEXT', 'Please enter your contact information & then check the box at the bottom of the page to move to the next step:  ');
defined('STEP2_DISPLAY_TEXT') or define('STEP2_DISPLAY_TEXT', 'Please enter your qualifying information & then check the box at the bottom of the page to move to the next step: ');
defined('STEP3_DISPLAY_TEXT') or define('STEP3_DISPLAY_TEXT', 'Please enter your personal information & then check the box at the bottom of the page to move to the next step:');
defined('STEP4_DISPLAY_TEXT') or define('STEP4_DISPLAY_TEXT', 'Please select which application (only one) you would like to apply for & then check the box at the bottom of the page to move to the next step:');
defined('STEP5_DISPLAY_TEXT') or define('STEP5_DISPLAY_TEXT', 'Please upload your student loans & then check the box at the bottom of the page to move to the next step:');
defined('STEP6_DISPLAY_TEXT') or define('STEP6_DISPLAY_TEXT', 'Please select a program that best fits your financial situation & then check the box at the bottom of the page to move to the next step:');
defined('STEP7_DISPLAY_TEXT') or define('STEP7_DISPLAY_TEXT', '');
defined('STEP7b_DISPLAY_TEXT') or define('STEP7b_DISPLAY_TEXT', '');


defined('step1') or define('step1', 1);
defined('step2') or define('step2', 2); // 3
defined('step3') or define('step3', 4); // 7
defined('step4') or define('step4', 8); // 15
defined('step5') or define('step5', 16); //
defined('step6') or define('step6', 32); // 47
defined('step7') or define('step7', 64); // 111