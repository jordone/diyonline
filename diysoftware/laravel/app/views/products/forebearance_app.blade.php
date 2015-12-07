<?php	  		
$product_name = "Forbearance Application:";
$product_price = number_format(PRICE_FOREBEARANCE_APP,2);
$product_formname = "forebearance_app";
$product_sessionname = "ordered_forebearance";
$product_description = "This will assist you in applying for forbearance, which can postpone your student loan payments 3-36 months. A Forbearance application filled out with the information you provided will be generated for you. In addition, a step-by-step explanation of what needs to be done for completion and submission of your application will be included.";
$product_purchased_already = false;
$product_in_cart= false;

if (isset($client) && isset($client->TProperties->products_already_purchased) && $client->TProperties->products_already_purchased & cart_product_forebearance_app )
    $purchased['forebearance_app'] = true;

if (isset($client) && isset($client->TProperties->cart_items) && $client->TProperties->cart_items & cart_product_forebearance_app )
    $product_in_cart = true;

if (isset($purchased) && is_array($purchased) && isset($purchased['forebearance_app']) && $purchased['forebearance_app'] == true)
{
    $product_purchased_already = true;
}

if (isset($cart_items) && $cart_items & cart_product_forebearance_app)
    $product_in_cart = true;




?>
@if ($product_price >= 0)
@include('products.product_wrapper')
@endif