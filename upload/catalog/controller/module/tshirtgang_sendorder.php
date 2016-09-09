<?php
class ControllerModuleTshirtgangSendorder extends Controller {
	private $error = array();

	public function index() {
		
	}

	public function send($order_id){
		$this->load->model('setting/setting');
		$tsg_setting = $this->model_setting_setting->getSetting('tshirtgang');
		$api_key  = $tsg_setting['tshirtgang_api_key'];
		$api_id   = $tsg_setting['tshirtgang_api_id'];
		$api_url  = "https://www.tshirtgang.com/api/CreateMultipleOrder/";

		$this->load->model('checkout/order');
		$this->load->model('account/order');
		$this->load->model('tshirtgang/products');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if($order_info['order_status']==='Send order to Tshirtgang'){
			$cust_name        = $order_info['shipping_firstname'].' '.$order_info['shipping_lastname'];
			$address1         = $order_info['shipping_address_1'];
			$address2         = $order_info['shipping_address_2'];
			$city             = $order_info['shipping_city'];
			$zip_code         = $order_info['shipping_postcode'];
			$state_province   = $order_info['shipping_zone'];
			$country          = $order_info['shipping_country'];
			$phone_number     = $order_info['telephone'];
			$priorityShipping = $order_info['shipping_code']=="apparelrush.apparelrush"?'1':'0';

			$order_products = $this->model_account_order->getOrderProducts($order_id);
			$has_tsg_products = false;

			foreach($order_products as $order_product){
				if($this->model_tshirtgang_products->isTsg($order_product['product_id'])){
					$has_tsg_products = true;
				}
			}

			if($has_tsg_products){
				$XML  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
					<OrderForm>
						<Auth>
								<key>$api_key</key>
						</Auth>";
				foreach($order_products as $order_product){
					if($this->model_tshirtgang_products->isTsg($order_product['product_id'])){
						$tsg_sku  = $this->model_tshirtgang_products->get($order_product["product_id"]);
						$tsg_sku  = $tsg_sku["id"];
						$qty      = $order_product["quantity"];
						$comment  = $order_info['comment'];
						$comment .= " - Opencart order for ($qty) - ".$order_product["name"];
						$comment  = str_replace('&', '&amp;',$comment); // replace offending & to &amp

						$product_options = $this->model_account_order->getOrderOptions($order_id, $order_product['order_product_id']);
						foreach($product_options as $option){
							if($option['name'] == 'Tshirt Style'){
								$style = $option["value"];
								if($style == 'Vneck'){
									$style = 'V-neck';
								}
							} elseif($option['name'] == 'Tshirt Size'){
								$size = $option["value"];
							} elseif($option['name'] == 'Tshirt Color'){
								$color = $option["value"];;
							}
						}

						$XML .= "<Order> 
									<fullname>$cust_name</fullname>
									<address1>$address1</address1>
									<address2>$address2</address2>
									<city>$city</city>
									<postal>$zip_code</postal>
									<state>$state_province</state>
									<country>$country</country>
									<phonenumber>$phone_number</phonenumber>
									<sku>$tsg_sku</sku>
									<size>$size</size>
									<style>$style</style>
									<color>$color</color>
									<quantity>$qty</quantity>
									<priorityShipping>$priorityShipping</priorityShipping>
									<comments></comments>
							</Order>";
					} else {
						//error_log('not TSG product. skip! ' . $order_product['product_id']);
					}
				}
				$XML .= "</OrderForm>";
				$port = 443; //($port == null ? (preg_match("/^https/", $url) ? 443 : 80) : $port);
				$ch = curl_init(); // initialize curl handle
				curl_setopt($ch, CURLOPT_URL, $api_url); // set url to post to
				curl_setopt($ch, CURLOPT_FAILONERROR, 1); // Fail on errors
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
				curl_setopt($ch, CURLOPT_PORT, $port); //Set the port number
				curl_setopt($ch, CURLOPT_TIMEOUT, 15); // times out after 15s
				curl_setopt($ch, CURLOPT_POSTFIELDS, $XML); // add POST fields
				//curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				if($port==443) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				}
				$api_response = curl_exec($ch);
				//echo "<pre>";var_dump(htmlentities($api_response));echo "</pre>";die;
				curl_close($ch);
				error_log('sent to TSG!');
			} else {
				//error_log('has_tsg_products NOT!');
			}
		} else {
			//error_log('order_status !== Send order to Tshirtgang');
		}
	}
}
