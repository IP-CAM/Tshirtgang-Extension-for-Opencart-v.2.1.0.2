<?php
class ControllerTshirtgangSales extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tshirtgang/sales');

		$this->document->setTitle($this->language->get('heading_title'));

		//$this->load->model('tshirtgang/sales');
		//if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		//	$this->model_setting_setting->editSetting('google_analytics', $this->request->post);
		//	$this->session->data['success'] = $this->language->get('text_success');
		//	$this->response->redirect($this->url->link('extension/analytics', 'token=' . $this->session->data['token'], 'SSL'));
		//}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tshirtgang/sales', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['datatableajax'] = 'index.php?route=tshirtgang/sales/getListAjax&token=' . $this->session->data['token'];
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tshirtgang/sales.tpl', $data));
	}
	
	
	public function getListAjax() {
		//$this->response->setOutput(json_encode($this->request->post));
		//return;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$ipp  = $this->request->post['length'];
			$page = ($this->request->post['start']/$this->request->post['length'])+1;
			
			$this->load->model('setting/setting');
			$tsg_setting = $this->model_setting_setting->getSetting('tshirtgang');
			$api_key  = $tsg_setting['tshirtgang_api_key'];
			$api_id   = $tsg_setting['tshirtgang_api_id'];
			$api_url  = "https://www.tshirtgang.com/api/GetSellerHistory/";
			
			
			$XML = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
					<ProductForm>
					   <Auth>
					      <key>$api_key</key>
					   </Auth>
					   <OrderInfo> 
					      <itemsPerPage>$ipp</itemsPerPage>
					      <page>$page</page>
					   </OrderInfo>
					</ProductForm>";

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

			$seller_history = new SimpleXMLElement($api_response);
			
			$to_json = array();
			$to_json['draw']            = $this->request->post['draw'];
			$to_json['recordsTotal']    = 1000000;
			$to_json['recordsFiltered'] = 1000000;
			$to_json['data']            = array();

			if(isset($seller_history->success)){
				if(isset($seller_history->success->order)){
					foreach($seller_history->success->order as $key => $order){
						//var_dump($order);exit;
						unset($order->buyersName);
						unset($order->buyersAddress);
						unset($order->buyersAddress2);
						unset($order->buyersCity);
						unset($order->buyersPostal);
						unset($order->buyersState);
						unset($order->buyersCountry);
						unset($order->buyerComments);
						unset($order->priceShirt);
						unset($order->priceShipping);
						unset($order->priceTax);
						unset($order->priceDiscount);
						unset($order->marketplace);


						
						$row = array();

						$row[] = $order->orderNumber;
						$row[] = $order->orderStatus;//
						$row[] = $order->sku;
						$row[] = $order->title;
						$row[] = $order->color;
						$row[] = $order->size;
						$row[] = $order->style;
						$row[] = $order->quantity;
						$row[] = $order->priorityShipping;//
						$row[] = $order->trackingNumber;//
						$to_json['data'][] = $order;
					}
					$this->response->setOutput(json_encode($to_json));
				} else {
					// tshirtgang query success but returned empty result
					$this->response->setOutput(json_encode(array('data'=>array())));
				}
			} else {
				// api call not successful
				$this->response->setOutput(json_encode(array('status'  => 'error','messages' =>array('server returned error status'))));
			}
		} else {
			$this->response->setOutput(json_encode(array('hi'=>'hello')));return;
			$this->response->setOutput(json_encode(array('message'=>'ipp and page parameter required via POST method')));
		}
		
	}
	
	public function validate(){
		if (!$this->user->hasPermission('modify', 'tshirtgang/sales')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	
}