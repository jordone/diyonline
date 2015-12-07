
<div class="CartProgram">
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
			  	<div class="col-sm-1 col-xs-2 col-md-1 text-right" style="padding:0px 0px 0px 10px;">		
			  		@if (isset($product_purchased_already) && $product_purchased_already == true)
					<input class="paid_already" type="checkbox" name="" value="1" data-price="0.00" id="{{ $product_formname }}" checked="checked" disabled>
			  		@else
			 		<input type="checkbox" name="{{ $product_formname }}" id="{{ $product_formname }}" value="1" data-price="{{ $product_price }}" {{ ($product_in_cart == true ? "checked='checked'" : "") }}>
					@endif	
					<label class="checkbox-label" for="{{ $product_formname }}" style="margin:0px;padding-left:10px;">&nbsp;</label>				
				</div>
				<div class="col-xs-10 col-md-11 col-sm-11">
					<label class="checkbox-label" for="{{ $product_formname }}">						
		 
					  		@if (defined('OFFICE_DISPLAY_SERVICE_PRICES') && OFFICE_DISPLAY_SERVICE_PRICES == 'yes')
								@if (isset($product_purchased_already) && $product_purchased_already == true)
								 <b>$0.00</b> &nbsp;
								 @else
								 {{ sprintf(OFFICE_DISPLAY_SERVICE_PRICE_TEXTF,$product_price) }} 
								@endif 
								@else
								{{-- Check if they have already purchased it. --}}
									@if (isset($product_purchased_already) && $product_purchased_already == true)
										<b>$0.00</b> &nbsp;
									@else

									@endif
					  		@endif
				  		<b>{{ rtrim(htmlspecialchars($product_name),':') }}</b>
					 </label>
					
					<div class="" style="font-size:11px;padding-bottom:5px;">
					  	{{ $product_description }}

						@if (isset($product_purchased_already) && $product_purchased_already == true)
							<br/><br/>
							You have already purchased this document. Thank you!
						@endif

						<br/>
					</div>					 
					 	
				</div>
			</div>

		</div>
	</div>
</div>