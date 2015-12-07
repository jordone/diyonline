<?php	  		
$product_name = "Student Loan Forgiveness Application:";
$product_price = number_format(PRICE_PSLF_APP,2);
$product_formname = "pslf_app";
$product_sessionname = "ordered_pslf";
$product_description = "This will assist you in applying for Public Service Student Loan Forgiveness (PSLF), which can possibly eliminate some or all of your Student Loan Debt. This document will be filled out with the information you provided for you to review. In Addition, a step-by-step explanation of what needs to be done for completion and submission of your application will be included";

$product_purchased_already = false;
$product_in_cart= false;

if (isset($client) && isset($client->TProperties->products_already_purchased) && $client->TProperties->products_already_purchased & cart_product_pslf_app )
    $purchased['pslf_app'] = true;

if (isset($client) && isset($client->TProperties->cart_items) && $client->TProperties->cart_items & cart_product_pslf_app )
    $product_in_cart = true;

if (isset($purchased) && is_array($purchased) && isset($purchased['pslf_app']) && $purchased['pslf_app'] == true)
{
    $product_purchased_already = true;
}

if (isset($cart_items) && $cart_items & cart_product_pslf_app)
    $product_in_cart = true;


?>

@if ($product_price >= 0)
@include('products.product_wrapper')
@endif