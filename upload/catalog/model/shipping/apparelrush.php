<?php
class ModelShippingApparelrush extends Model {
	function getQuote($address) {
		$this->load->language('shipping/apparelrush');
		$price = 0.0;
		$excluded_styles = array('Hooded Pullover','Apron','Vneck','Tanktop');
		
		$pricing = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tshirtgang_pricing ");
		foreach ($query->rows as $value) {
			$pricing[$value['code']] = $value['price'];
		}
		$pricing['US_CAD_XL_Incremental'] = 0.0;

		$products = $this->cart->getProducts();
		foreach($products as $key => $product){
			foreach($product['option'] as $option){
				$products[$key][$option['name']] = $option['value'];
			}
		}

		$SizeQty_Reg = 0;
		$Reg_Shipping = 0.0;
		$Reg_Shipping_Rush = 0.0;
		//
		$SizeQty_XL = 0;
		$Additional_XL = 0.0;
		$Additional_XL_Rush = 0.0;
		//
		$SizeQty_2XL = 0;
		$Additional_2XL = 0.0;
		$Additional_2XL_Rush = 0.0;
		//
		$SizeQty_MXL = 0;
		$Additional_MXL = 0.0;
		$Additional_MXL_Rush = 0.0;
		//
		$SizeQty_Hoodie = 0;
		$Additional_Hoodie = 0.0;
		$Additional_Hoodie_Rush = 0.0;
		//
		$SizeQty_Excluded = 0;
		$SizeQty_Excluded = 0.0;
		$SizeQty_Included = 0.0;
		//
		$FlatRateIncremental = 0;
		$TotalQty = 0;
		
		if($this->session->data['shipping_address']['country'] == 'United States' || $this->session->data['shipping_address']['country'] == 'Canada'){
			foreach($products as $product){
				if(in_array($product['Tshirt Size'], array("X-Small (Youth)","Small (Youth)","Medium (Youth)","Small","Medium","Large","6 Months","12 Months","18 Months","2T","3T","4T")) &&  !in_array($product['Tshirt Style'], $excluded_styles )) {
					$SizeQty_Reg++;
				}
				if($product['Tshirt Size'] == "X-Large" && !in_array($product['Tshirt Style'], $excluded_styles )){
					$SizeQty_XL++;
				}
				if($product['Tshirt Size'] == "2 X-Large" && !in_array($product['Tshirt Style'], $excluded_styles )){
					$SizeQty_2XL++;
				}
				if(in_array($product['Tshirt Size'], array("3 X-Large","4 X-Large","5 X-Large","6 X-Large")) && !in_array($product['Tshirt Style'], $excluded_styles )){
					$SizeQty_MXL++;
				}
				if($product['Tshirt Style'] == 'Hooded Pullover' ){
					$SizeQty_Hoodie++;
				}
				// excluded items
				if(in_array($product['Tshirt Style'],$excluded_styles)){
					$SizeQty_Excluded++;
				}
			}
			$Reg_Shipping = ($SizeQty_Reg * $pricing['StandardShipping']) + ($SizeQty_Reg * $pricing['US_CAD_YTHLG_Incremental']);
			$Reg_Shipping_Rush = ($SizeQty_Reg * $pricing['RushDomesticShipping']) + ($SizeQty_Reg * $pricing['US_CAD_YTHLG_Incremental']);
			//
			$Additional_XL = ($SizeQty_XL * $pricing['StandardShipping']) + ($SizeQty_XL * $pricing['US_CAD_XL_Incremental']);
			$Additional_XL_Rush = ($SizeQty_XL * $pricing['RushDomesticShipping']) + ($SizeQty_XL * $pricing['US_CAD_XL_Incremental']);
			//
			$Additional_2XL = ($SizeQty_2XL * $pricing['StandardShipping']) + ($SizeQty_2XL * $pricing['US_CAD_2XL_Incremental']);
			$Additional_2XL_Rush = ($SizeQty_2XL * $pricing['RushDomesticShipping']) + ($SizeQty_2XL * $pricing['US_CAD_2XL_Incremental']);
			//
			$Additional_MXL = ($SizeQty_MXL * $pricing['StandardShipping']) + ($SizeQty_MXL * $pricing['US_CAD_3XL6XL_Incremental']);
			$Additional_MXL_Rush = ($SizeQty_MXL * $pricing['RushDomesticShipping']) + ($SizeQty_MXL * $pricing['US_CAD_3XL6XL_Incremental']);
			//
			if($this->session->data['shipping_address']['country'] == 'United States'){
				$Additional_Hoodie = ($SizeQty_Hoodie * $pricing['US_Hoodie_Price']);
				$Additional_Hoodie_Rush = ($SizeQty_Hoodie * $pricing['US_Hoodie_Price']) + ($SizeQty_Hoodie * $pricing['RushDomesticShipping']);
			} else { // Canada
				$Additional_Hoodie = ($SizeQty_Hoodie * $pricing['International_Hoodie_Price']); // Canada bound hoodies are international shipping
				$Additional_Hoodie_Rush = ($SizeQty_Hoodie * $pricing['International_Hoodie_Price']) + ($SizeQty_Hoodie * $pricing['RushDomesticShipping']);
			}
			//
			$SizeQty_Included = ($SizeQty_Reg + $SizeQty_XL + $Additional_2XL + $Additional_MXL);
			if($pricing['UseFlatRate'] == 1.00) {
				if($this->session->data['shipping_address']['country'] == 'United States'){
					$FlatRateIncremental = ($SizeQty_Hoodie > 0 ? $pricing['HoodieFlatRateIncremental'] : 0.0);
					$TotalQty = ($pricing['ExcludeStyles'] == 1.0 ? ($SizeQty_Included > 0 ? 1 : 0) + $SizeQty_Excluded : 1);
					$Total_Shipping = number_format(($TotalQty * $pricing['FlatRateDomestic']) + $FlatRateIncremental, 2, ".", "");
					$Total_Shipping_Rush = number_format(($TotalQty * $pricing['RushDomesticShipping']) +  $FlatRateIncremental, 2, ".", "");
				} else { // Canada
					$TotalQty = ($pricing['ExcludeStyles'] == 1.0 ? ($SizeQty_Included > 0 ? 1 : 0) + $SizeQty_Excluded : 1);			
					$Total_Shipping = number_format((($TotalQty - $SizeQty_Hoodie) * $pricing['FlatRateDomestic']) + $Additional_Hoodie, 2, ".", "");
					$Total_Shipping_Rush = number_format(($TotalQty * $pricing['RushDomesticShipping']) + $Additional_Hoodie, 2, ".", "");
				}
			} else {
				$Total_Shipping = number_format($Reg_Shipping + $Additional_XL + $Additional_2XL + $Additional_MXL + $Additional_Hoodie, 2, ".", "");
				$Total_Shipping_Rush = number_format($Reg_Shipping_Rush + $Additional_XL_Rush + $Additional_2XL_Rush + $Additional_MXL_Rush + $Additional_Hoodie_Rush, 2, ".", "");
			}
			$price = $Total_Shipping_Rush;
		} else { // international
			foreach($products as $product){
				if(in_array($product['Tshirt Size'], array("X-Small (Youth)","Small (Youth)","Medium (Youth)","Small","Medium","Large","6 Months","12 Months","18 Months","2T","3T","4T")) &&  !in_array($product['Tshirt Style'], $excluded_styles )) {
					$SizeQty_Reg++;
				}
				if(in_array($product['Tshirt Size'], array("X-Large","2 X-Large")) && !in_array($product['Tshirt Style'], $excluded_styles )){
					$SizeQty_XL++;
				}
				if(in_array($product['Tshirt Size'], array("3 X-Large","4 X-Large","5 X-Large","6 X-Large")) && $product['Tshirt Style'] != 'Hooded Pullover'){
					$SizeQty_MXL++;
				}
				if($product['Tshirt Style'] == 'Hooded Pullover' ){
					$SizeQty_Hoodie++;
				}
			}
			$Reg_Shipping      = ($SizeQty_Reg    * $pricing['InternationalShipping']) + ($SizeQty_Reg * $pricing['International_YTHLG_Incremental']);
			$Additional_XL     = ($SizeQty_XL     * $pricing['InternationalShipping']) + ($SizeQty_XL  * $pricing['International_XL2XL_Incremental']);
			$Additional_MXL    = ($SizeQty_MXL    * $pricing['InternationalShipping']) + ($SizeQty_MXL * $pricing['International_3XL6XL_Incremental']);
			$Additional_Hoodie = ($SizeQty_Hoodie * $pricing['International_Hoodie_Price']);
			$Total_Shipping = number_format($Reg_Shipping + $Additional_XL + $Additional_MXL + $Additional_Hoodie, 2, ".", "");
			$price = 500.0; // random large value, this should not show up anyway 
		}

		
		
		
		//$is_international = false;
		//$has_gng = false; //tanktop, vneck, apron
		//$has_redline = false;
		//$has_hoodie = false;
		//$has_size_xsmallyouth_to_large = false;
		//$has_size_xl_to_2xl = false;
		//$has_size_3xl_to_6xl = false;
    //
		//$pricing = array();
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tshirtgang_pricing ");
		//foreach ($query->rows as $value) {
		//	$pricing[$value['code']] = $value['price'];
		//}
    //
		//$products = $this->cart->getProducts();
		//foreach($products as $product){
		//	foreach($product['option'] as $option){
		//		if($option['name'] == 'Tshirt Style'){
		//			if($option['value'] == 'Hooded Pullover'){
		//				$has_hoodie = true;
		//			} elseif(in_array($option['value'], array('Apron','Vneck','Tanktop','Hooded Pullover'))){
		//				$has_gng = true;
		//			} else {
		//				$has_redline = true;
		//			}
		//		} elseif($option['name'] == 'Tshirt Size'){
		//			if(in_array($option['value'],array("X-Small (Youth)","Small (Youth)","Medium (Youth)","Small","Medium","Large"))){
		//				$has_size_xsmallyouth_to_large = true;
		//			} elseif(in_array($option['value'],array("X-Large","2 X-Large"))){
		//				$has_size_xl_to_2xl = true;
		//			} else { // in_array($option['value'],"3 X-Large","4 X-Large","5 X-Large","6 X-Large")
		//				$has_size_3xl_to_6xl = true;
		//			}
		//		} elseif($option['name'] == 'Tshirt Color'){
		//			// nothing to do here
		//		} else {
		//			// nothing to do here
		//		}
		//	}
		//}
    //
		//$is_international = ($this->session->data['shipping_address']['country'] == 'United States' || $this->session->data['shipping_address']['country'] == 'Canada') ? false : true;
    //
		//if($has_redline){
		//	$price += $pricing['FlatRateDomestic'];
		//}
		//if($has_gng){
		//	$price += $pricing['FlatRateDomestic'];
		//}
		//if($has_hoodie){
		//	$price += $pricing['HoodieFlatRateIncremental'];
		//}
		//if($is_international){
		//	$price += 123.45;
		//	if($has_hoodie){
		//		$price += $pricing['International_Hoodie_Price'];
		//	} else {
		//		$price += $pricing['InternationalShipping'];
		//	}
		//} else { // domestic
		//	$price += $pricing['RushDomesticShipping'];
		//	if($has_hoodie){
		//		$price += $pricing['US_Hoodie_Price'];
		//	}
		//}
    //
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('pickup_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
    //
		//if (!$this->config->get('apparelrush_geo_zone_id')) {
		//	$status = true;
		//} elseif ($query->num_rows) {
		//	$status = true;
		//} else {
		//	$status = false;
		//}

		$method_data = array();
		
		$quote_data = array();

		$quote_data['apparelrush'] = array(
			'code'         => 'apparelrush.apparelrush',
			'title'        => $this->language->get('text_description'),
			'cost'         => $price,
			'tax_class_id' => 0,
			'text'         => $this->currency->format($price)
		);
		
		$method_data = array(
			'code'       => 'apparelrush',
			'title'      => $this->language->get('text_title'),
			'quote'      => $quote_data,
			'sort_order' => $this->config->get('apparelrush_sort_order'),
			'error'      => false
		);
		
		return $method_data;
	}
}