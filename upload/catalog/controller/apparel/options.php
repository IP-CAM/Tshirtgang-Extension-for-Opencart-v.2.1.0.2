<?php
class ControllerApparelOptions extends Controller {
	public function get(){
		$output = array();
		if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$output['product_id'] = $this->request->post['product_id'];
			$this->load->model('tshirtgang/products');
			$product_data = $this->model_tshirtgang_products->get($this->request->post['product_id']);
			$output['product_data'] = $product_data;
			$output['options']    = $this->model_tshirtgang_products->options();
			$output['option_ids'] = $this->model_tshirtgang_products->optionIds($this->request->post['product_id']);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput( json_encode($output));
	}
	
	public function getOptionsPriceSum(){
		$data = array();
		$new_price = 0.0;
		if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$product_id = $this->request->post['product_id'];
			$quantity   = $this->request->post['quantity'];
			$optionsData   = $this->request->post['optionsData'];
			$optionsData = html_entity_decode($optionsData);
			$optionsData = json_decode($optionsData);
			$this->load->model('catalog/product');
			$product_options = $this->model_catalog_product->getProductOptions($product_id);
			foreach($product_options as $product_option){
				foreach($product_option['product_option_value'] as $pov){
					foreach($optionsData as $key => $value){
						if($product_option['product_option_id'] == $value->id && $pov['product_option_value_id'] == $value->value){
							if($pov['price_prefix'] == '+'){
								$new_price += $pov['price'];
							} else {
								$new_price -= $pov['price'];
							}
						}
					}
				}
			}
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($product_id);
			$new_price = $this->currency->format($this->tax->calculate($new_price, $product_info['tax_class_id'], $this->config->get('config_tax')));
		}
		
		$this->response->setOutput(json_encode(array('new_price'=>$new_price)));
	}

}
