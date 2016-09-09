<?php
class ControllerTshirtgangCategories extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tshirtgang/categories');

		$this->document->setTitle($this->language->get('heading_title'));

		//$this->load->model('tshirtgang/categories');
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
		
		$this->load->model('catalog/category');
		$data['debug_me'] = $this->model_catalog_category->getCategories();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tshirtgang/categories', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tshirtgang/categories.tpl', $data));
	}
}