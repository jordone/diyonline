<?php
$version_1_method = 1;
$product_name = "A FEDERAL CONSOLIDATION APPLICATION AND PROMISORY NOTE";
$product_price = number_format(PRICE_REPAYMENT_NOTE,2);
$product_formname = "repayment_promisory_note";
$product_sessionname = "ordered_repayment_promisory_note";
$product_description = "To HELP you lock in your new re-payment plan, a federal re-payment request application filled out with the information you have provided will be generated for you. All you have to do is review, sign and mail away. In addition, an easy to follow detailed mailing instructions will be included.";

$product_purchased_already = false;
$product_in_cart = false;

if (isset($client) && isset($client->TProperties->products_already_purchased) && $client->TProperties->products_already_purchased & cart_product_consolidation_app )
    $purchased['consolidation_app'] = true;
if (isset($client) && isset($client->TProperties->products_already_purchased) && $client->TProperties->products_already_purchased & cart_product_repayment_app )
    $purchased['repayment_app'] = true;

if (isset($client) && isset($client->TProperties->cart_items) && $client->TProperties->cart_items & cart_product_recertification_app )
    $product_in_cart = true;


if (isset($purchased) && is_array($purchased) && isset($purchased['consolidation_app']) && $purchased['consolidation_app'] == true)
{
    $product_purchased_already = true;
}
elseif (isset($purchased) && is_array($purchased) && isset($purchased['repayment_app']) && $purchased['repayment_app'] == true)
{
    $product_purchased_already = true;
}
if ($cart_items & cart_product_consolidation_app || $cart_items & cart_product_repayment_app)
    $product_in_cart = true;

?>

@if ($product_price >= 0)
@include('products.product_wrapper')
@endif