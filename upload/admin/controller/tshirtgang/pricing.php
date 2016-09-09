<?php
class ControllerTshirtgangPricing extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tshirtgang/pricing');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tshirtgang/pricing');
		//$data['debug_me'] = $this->model_tshirtgang_pricing->getAll();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			//$data['debug_me'] = $this->request->post;

			$this->model_tshirtgang_pricing->edit(array('code'  => 'WhiteShirt',                       'price' => $this->request->post['base_white'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'ColorShirt',                       'price' => $this->request->post['base_color'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'RingerShirt',                      'price' => $this->request->post['base_ringer'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'BabyOnePieceIncremental',          'price' => $this->request->post['baby_one_piece_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'LadiesIncremental',                'price' => $this->request->post['ladies_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'MensFittedIncremental',            'price' => $this->request->post['mens_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'HoodieIncremental',                'price' => $this->request->post['hooded_pullover_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'ApronIncremental',                 'price' => $this->request->post['apron_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'VneckIncremental',                 'price' => $this->request->post['vneck_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'TanktopIncremental',               'price' => $this->request->post['tanktop_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'Shirt_2XL_Incremental',            'price' => $this->request->post['2xl_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'Shirt_3XL6XL_Incremental',         'price' => $this->request->post['3xl_6xl_incr'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'StandardShipping',                 'price' => $this->request->post['classic_standard_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'RushDomesticShipping',             'price' => $this->request->post['classic_rush_domestic_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'US_CAD_YTHLG_Incremental',         'price' => $this->request->post['classic_xs_youth_large_incr_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'US_CAD_3XL6XL_Incremental',        'price' => $this->request->post['classic_3xl_6xl_incr_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'FlatRateDomestic',                 'price' => $this->request->post['flatrate_tshirt_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'HoodieFlatRateIncremental',        'price' => $this->request->post['flatrate_hoodie_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'RushDomesticShipping',             'price' => $this->request->post['flatrate_rush_domestic_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'InternationalShipping',            'price' => $this->request->post['intl_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'International_YTHLG_Incremental',  'price' => $this->request->post['intl_xs_youth_large_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'International_XL2XL_Incremental',  'price' => $this->request->post['intl_xl_2xl_incr_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'International_3XL6XL_Incremental', 'price' => $this->request->post['intl_3xl_6xl_incr_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'US_Hoodie_Price',                  'price' => $this->request->post['hoodie_domestic_shipping'] ));
			$this->model_tshirtgang_pricing->edit(array('code'  => 'International_Hoodie_Price',       'price' => $this->request->post['hoodie_intl_shipping'] ));
			if( isset($this->request->post['exclude_hpatv']) &&  $this->request->post['exclude_hpatv']=='on'){
				$this->model_tshirtgang_pricing->edit(array('code'  => 'ExcludeStyles', 'price' => '1.00'));
			} else {
				$this->model_tshirtgang_pricing->edit(array('code'  => 'ExcludeStyles', 'price' => '0.00'));
			}
			//UseFlatRate
			////US_CAD_2XL_Incremental
			////BackPrint
			////Shirt_YTHLG_Incremental
			////Shirt_XL_Incremental
			
			$this->load->model('tshirtgang/products');
			$this->model_tshirtgang_products->updateProductPricingAll();
			//$this->model_tshirtgang_products->updateProductStyleOptionPricingAll();
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['text_form'] = $this->language->get('text_form');

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
			'href' => $this->url->link('tshirtgang/pricing', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('tshirtgang/pricing', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('tshirtgang/pricing', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		
		$data['values'] = $this->model_tshirtgang_pricing->getAll();

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tshirtgang/pricing.tpl', $data));

	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'tshirtgang/pricing')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
