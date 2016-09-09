<?php
class ControllerModuleTshirtgang extends Controller {
	private $error = array();

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tshirtgang_products` (
			`id` int(10) UNSIGNED NOT NULL,
			`product_id` int(10) UNSIGNED NOT NULL,
			`title` varchar(256) NOT NULL,
			`color` varchar(64) NOT NULL,
			`style` varchar(64) NOT NULL,
			`image` varchar(128) NOT NULL,
			`overlay` varchar(128) NOT NULL,
			`master_image`varchar(128) NOT NULL,
			`datetime_retrieved` datetime NOT NULL,
			PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8"
		);

		$this->db->query("ALTER TABLE `" . DB_PREFIX . "tshirtgang_products` ADD INDEX(`product_id`)");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tshirtgang_pricing` (
			`code` varchar(64) NOT NULL,
			`price` decimal(6,2) NOT NULL,
			`modified_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`code`)) DEFAULT CHARSET=utf8"
		);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('UseFlatRate'                     , '1.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('ExcludeStyles'                   , '1.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('WhiteShirt'                      ,'18.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('ColorShirt'                      ,'18.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('RingerShirt'                     ,'18.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('BabyOnePieceIncremental'         , '1.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('LadiesIncremental'               , '1.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('MensFittedIncremental'           , '1.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('HoodieIncremental'               ,'21.99')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('ApronIncremental'                , '1.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('VneckIncremental'                , '4.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('TanktopIncremental'              , '4.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('FlatRateDomestic'                , '4.95')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('HoodieFlatRateIncremental'       , '5.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('BackPrint'                       , '5.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('StandardShipping'                , '4.95')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('InternationalShipping'           , '9.50')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('RushDomesticShipping'            , '9.50')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('Shirt_YTHLG_Incremental'         , '0.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('Shirt_XL_Incremental'            , '0.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('Shirt_2XL_Incremental'           , '1.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('Shirt_3XL6XL_Incremental'        , '2.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('US_CAD_YTHLG_Incremental'        , '0.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('US_CAD_2XL_Incremental'          , '0.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('US_CAD_3XL6XL_Incremental'       , '2.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('US_Hoodie_Price'                 ,'10.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('International_YTHLG_Incremental' , '0.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('International_XL2XL_Incremental' , '2.00')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('International_3XL6XL_Incremental', '6.50')");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tshirtgang_pricing`(`code`,`price`) VALUES('International_Hoodie_Price'      ,'19.99')");

		// -start- create 'Apparel' category if it does not exist
		$this->load->model('catalog/category');
		$categories = $this->model_catalog_category->getCategories();

		$apparel_category_exist = false;
		foreach($categories as $category){
			if($category['name'] == 'Apparel') {
				$apparel_category_exist = true;
			}
		}
		if(!$apparel_category_exist){
			$this->model_catalog_category->addCategory(
				array(
					'parent_id'      => 0,
					'column'         => 1,
					'sort_order'     => 1,
					'status'         => 1,
					'image'          => '',
					'top'            => 1,
					'category_store' => array(0),
					'category_description' => array (
						1 => array(
							'name'             => 'Apparel',
							'description'      => '',
							'meta_title'       => '',
							'meta_description' => '',
							'meta_keyword'     => ''
						)
					)
				)
			);
		}
		// -end- create 'Apparel' category if it does not exist
		
		// -start- add option 'Tshirt Style'-'Standard,Ladies,Mens Fitted'
		$this->load->model('catalog/option');
		$options = $this->model_catalog_option->getOptions();

		$tshirtstyle_option_exist = false;
		$tshirtcolor_option_exist = false;
		$tshirtsize_option_exist  = false;
		foreach($options as $option){
			if($option['name'] == 'Tshirt Style') {
				$tshirtstyle_option_exist = true;
			}
			if($option['name'] == 'Tshirt Color') {
				$tshirtcolor_option_exist = true;
			}
			if($option['name'] == 'Tshirt Size') {
				$tshirtsize_option_exist = true;
			}
		}
		if(!$tshirtstyle_option_exist){
			$option_id = $this->model_catalog_option->addOption( $this->tshirtStyleOption() );
		}
		if(!$tshirtcolor_option_exist){
			$option_id = $this->model_catalog_option->addOption( $this->tshirtColorOption() );
		}
		if(!$tshirtsize_option_exist){
			$option_id = $this->model_catalog_option->addOption( $this->tshirtSizeOption() );
		}
		// --end-- add option 'Tshirt Style'-'Standard,Ladies,Mens Fitted'
		
		// -start- create 'apparel' directory to store images
		if(file_exists(DIR_IMAGE.'catalog/apparel')){
			// file or directory already exists
			if(is_dir(DIR_IMAGE.'catalog/apparel')){
				// directory: no problem
			} else {
				// error: apparel non-directory (file?) exist
			}
		} else {
			// directory does not exist. create!
			mkdir(DIR_IMAGE.'catalog/apparel', 0755);
		}
		// -end- create 'apparel' directory to store images
		
		// event
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('tshirtgang_sendorder', 'post.order.history.add', 'module/tshirtgang_sendorder/send');

		// shipping extension
		$this->load->model('extension/extension');
		$this->model_extension_extension->install('shipping', 'apparelstandard');
		$this->model_extension_extension->install('shipping', 'apparelrush');
		
		// order status
		$this->load->model('localisation/order_status');
		$this->model_localisation_order_status->addOrderStatus(
			array(
				'order_status' => array(
					'1' => array('name' => 'Send order to Tshirtgang'),
				)
			)
		);
		
	}

	public function uninstall() {
		$this->load->model('setting/setting');
		if($this->config->get('tshirtgang_delete_on_uninstall') != '1' ){
			return;
		}

		$this->load->model('tshirtgang/products');
		$current_products = $this->model_tshirtgang_products->getAll();

		$this->load->model('catalog/product');
		
		foreach($current_products as $product){
			// delete image file
			if(file_exists(DIR_IMAGE.'catalog/apparel/'.$product['id'].'.png')){ // assume png, small letters !?!?
				unlink(DIR_IMAGE.'catalog/apparel/'.$product['id'].'.png');      // assume png, small letters !?!?
			}
			// delete overlay image file
			if(file_exists(DIR_IMAGE.'catalog/apparel/overlay_'.$product['id'].'.png')){ // assume png, small letters !?!?
				unlink(DIR_IMAGE.'catalog/apparel/overlay_'.$product['id'].'.png');      // assume png, small letters !?!?
			}
			// delete opencart product
			$this->model_catalog_product->deleteProduct($product['product_id']); // opencart product_id
		}
		
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "tshirtgang_pricing`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "tshirtgang_products`");

		// event
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('tshirtgang_sendorder');

		// shipping extension
		$this->load->model('extension/extension');
		$this->model_extension_extension->uninstall('shipping', 'apparelstandard');
		$this->model_extension_extension->uninstall('shipping', 'apparelrush');

		// order status
		$this->load->model('localisation/order_status');
		$statuses = $this->model_localisation_order_status->getOrderStatuses(
			array(
				'order' => 'DESC',
			)
		);
		foreach($statuses as $key => $value){
			if($value === 'Send order to Tshirtgang'){
				$this->model_localisation_order_status->deleteOrderStatus($key);
			}
		}
	}

	public function index() {
		$this->load->language('module/tshirtgang');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('tshirtgang', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit']     = $this->language->get('text_edit');
		
		$data['text_enabled']  = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['text_delete_on_uninstall']      = $this->language->get('text_delete_on_uninstall');
		$data['text_dont_delete_on_uninstall'] = $this->language->get('text_dont_delete_on_uninstall');

		$data['entry_status']  = $this->language->get('entry_status');
		$data['entry_api_key'] = $this->language->get('entry_api_key');
		$data['entry_api_id']  = $this->language->get('entry_api_id');

		$data['button_save']   = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['help_api_key']  = $this->language->get('help_api_key');
		$data['help_api_id']   = $this->language->get('help_api_id');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['api_key'])) {
			$data['error_api_key'] = $this->error['api_key'];
		} else {
			$data['error_api_key'] = '';
		}
		/*
		if (isset($this->error['api_id'])) {
			$data['error_api_id'] = $this->error['api_id'];
		} else {
			$data['error_api_id'] = '';
		}
		*/
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/tshirtgang', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/tshirtgang', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}
		
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/tshirtgang', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/tshirtgang', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['api_key'])) {
			$data['tshirtgang_api_key'] = $this->request->post['tshirtgang_api_key'];
		} else {
			$data['tshirtgang_api_key'] = $this->config->get('tshirtgang_api_key');;
		}
		/*
		if (isset($this->request->post['api_id'])) {
			$data['tshirtgang_api_id'] = $this->request->post['tshirtgang_api_id'];
		} else {
			$data['tshirtgang_api_id'] = $this->config->get('tshirtgang_api_id');;
		}
		*/
		if (isset($this->request->post['status'])) {
			$data['tshirtgang_status'] = $this->request->post['tshirtgang_status'];
		} else {
			$data['tshirtgang_status'] = $this->config->get('tshirtgang_status');;
		}
		
		if (isset($this->request->post['delete_on_uninstall'])) {
			$data['tshirtgang_delete_on_uninstall'] = $this->request->post['tshirtgang_delete_on_uninstall'];
		} else {
			$data['tshirtgang_delete_on_uninstall'] = $this->config->get('tshirtgang_delete_on_uninstall');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('module/tshirtgang.tpl', $data));
	}
	
	protected function tshirtStyleOption() {
		return
			array(
				'type'       => 'select',
				'sort_order' => 1,
				'option_description' => array(
					1 => array( // 1 for enlish
						'name' => 'Tshirt Style'
					)
				),
				'option_value' => array(
					0 => array(
						'image'                    => '',
						'sort_order'               => 1,
						'option_value_description' => array(
							1 => array(
								'name' => 'Standard'
							)
						)
					),
					1 => array(
						'image'                    => '',
						'sort_order'               => 2,
						'option_value_description' => array(
							1 => array(
								'name' => 'Ladies'
							)
						)
					),
					2 => array(
						'image'                    => '',
						'sort_order'               => 3,
						'option_value_description' => array(
							1 => array(
								'name' => 'Mens Fitted'
							)
						)
					),
					3 => array(
						'image'                    => '',
						'sort_order'               => 4,
						'option_value_description' => array(
							1 => array(
								'name' => 'Hooded Pullover'
							)
						)
					),
					4 => array(
						'image'                    => '',
						'sort_order'               => 5,
						'option_value_description' => array(
							1 => array(
								'name' => 'Apron'
							)
						)
					),
					5 => array(
						'image'                    => '',
						'sort_order'               => 6,
						'option_value_description' => array(
							1 => array(
								'name' => 'Vneck'
							)
						)
					),
					6 => array(
						'image'                    => '',
						'sort_order'               => 7,
						'option_value_description' => array(
							1 => array(
								'name' => 'Tanktop'
							)
						)
					),
					7 => array(
						'image'                    => '',
						'sort_order'               => 8,
						'option_value_description' => array(
							1 => array(
								'name' => 'Kids'
							)
						)
					),
					8 => array(
						'image'                    => '',
						'sort_order'               => 9,
						'option_value_description' => array(
							1 => array(
								'name' => 'Baby One Piece'
							)
						)
					)
				)
			)
		;
	}
	
	protected function tshirtSizeOption() {
		return
			array(
				'type'       => 'select',
				'sort_order' => 2,
				'option_description' => array(
					1 => array( // 1 for enlish
						'name' => 'Tshirt Size'
					)
				),
				'option_value' => array(
					0 => array(
						'image'                    => '',
						'sort_order'               => 1,
						'option_value_description' => array(
							1 => array(
								'name' => 'X-Small (Youth)'
							)
						)
					),
					1 => array(
						'image'                    => '',
						'sort_order'               => 2,
						'option_value_description' => array(
							1 => array(
								'name' => 'Small (Youth)'
							)
						)
					),
					2 => array(
						'image'                    => '',
						'sort_order'               => 3,
						'option_value_description' => array(
							1 => array(
								'name' => 'Medium (Youth)'
							)
						)
					),
					3 => array(
						'image'                    => '',
						'sort_order'               => 4,
						'option_value_description' => array(
							1 => array(
								'name' => 'Small'
							)
						)
					),
					4 => array(
						'image'                    => '',
						'sort_order'               => 5,
						'option_value_description' => array(
							1 => array(
								'name' => 'Medium'
							)
						)
					),
					5 => array(
						'image'                    => '',
						'sort_order'               => 6,
						'option_value_description' => array(
							1 => array(
								'name' => 'Large'
							)
						)
					),
					6 => array(
						'image'                    => '',
						'sort_order'               => 7,
						'option_value_description' => array(
							1 => array(
								'name' => 'X-Large'
							)
						)
					),
					7 => array(
						'image'                    => '',
						'sort_order'               => 8,
						'option_value_description' => array(
							1 => array(
								'name' => '2 X-Large'
							)
						)
					),
					8 => array(
						'image'                    => '',
						'sort_order'               => 9,
						'option_value_description' => array(
							1 => array(
								'name' => '3 X-Large'
							)
						)
					),
					9 => array(
						'image'                    => '',
						'sort_order'               => 10,
						'option_value_description' => array(
							1 => array(
								'name' => '4 X-Large'
							)
						)
					),
					10 => array(
						'image'                    => '',
						'sort_order'               => 11,
						'option_value_description' => array(
							1 => array(
								'name' => '5 X-Large'
							)
						)
					),
					11 => array(
						'image'                    => '',
						'sort_order'               => 12,
						'option_value_description' => array(
							1 => array(
								'name' => '6 X-Large'
							)
						)
					),
					12 => array(
						'image'                    => '',
						'sort_order'               => 13,
						'option_value_description' => array(
							1 => array(
								'name' => '2T'
							)
						)
					),
					13 => array(
						'image'                    => '',
						'sort_order'               => 14,
						'option_value_description' => array(
							1 => array(
								'name' => '3T'
							)
						)
					),
					14 => array(
						'image'                    => '',
						'sort_order'               => 15,
						'option_value_description' => array(
							1 => array(
								'name' => '4T'
							)
						)
					),
					15 => array(
						'image'                    => '',
						'sort_order'               => 16,
						'option_value_description' => array(
							1 => array(
								'name' => '6 Months'
							)
						)
					),
					16 => array(
						'image'                    => '',
						'sort_order'               => 17,
						'option_value_description' => array(
							1 => array(
								'name' => '12 Months'
							)
						)
					),
					17 => array(
						'image'                    => '',
						'sort_order'               => 18,
						'option_value_description' => array(
							1 => array(
								'name' => '18 Months'
							)
						)
					)
				)
			)
		;
	}

	protected function tshirtColorOption() {
		return
			array(
				'type'       => 'select',
				'sort_order' => 3,
				'option_description' => array(
					1 => array( // 1 for enlish
						'name' => 'Tshirt Color'
					)
				),
				'option_value' => array(
					0 => array(
						'image'                    => '',
						'sort_order'               => 1,
						'option_value_description' => array(
							1 => array(
								'name' => 'White'
							)
						)
					),
					1 => array(
						'image'                    => '',
						'sort_order'               => 2,
						'option_value_description' => array(
							1 => array(
								'name' => 'Black'
							)
						)
					),
					2 => array(
						'image'                    => '',
						'sort_order'               => 3,
						'option_value_description' => array(
							1 => array(
								'name' => 'Charcoal Grey'
							)
						)
					),
					3 => array(
						'image'                    => '',
						'sort_order'               => 4,
						'option_value_description' => array(
							1 => array(
								'name' => 'Daisy'
							)
						)
					),
					4 => array(
						'image'                    => '',
						'sort_order'               => 5,
						'option_value_description' => array(
							1 => array(
								'name' => 'Dark Chocolate'
							)
						)
					),
					5 => array(
						'image'                    => '',
						'sort_order'               => 6,
						'option_value_description' => array(
							1 => array(
								'name' => 'Forest Green'
							)
						)
					),
					6 => array(
						'image'                    => '',
						'sort_order'               => 7,
						'option_value_description' => array(
							1 => array(
								'name' => 'Gold'
							)
						)
					),
					7 => array(
						'image'                    => '',
						'sort_order'               => 8,
						'option_value_description' => array(
							1 => array(
								'name' => 'Irish Green'
							)
						)
					),
					8 => array(
						'image'                    => '',
						'sort_order'               => 9,
						'option_value_description' => array(
							1 => array(
								'name' => 'Light Blue'
							)
						)
					),
					9 => array(
						'image'                    => '',
						'sort_order'               => 10,
						'option_value_description' => array(
							1 => array(
								'name' => 'Light Pink'
							)
						)
					),
					10 => array(
						'image'                    => '',
						'sort_order'               => 11,
						'option_value_description' => array(
							1 => array(
								'name' => 'Military Green'
							)
						)
					),
					11 => array(
						'image'                    => '',
						'sort_order'               => 12,
						'option_value_description' => array(
							1 => array(
								'name' => 'Navy'
							)
						)
					),
					12 => array(
						'image'                    => '',
						'sort_order'               => 13,
						'option_value_description' => array(
							1 => array(
								'name' => 'Orange'
							)
						)
					),
					13 => array(
						'image'                    => '',
						'sort_order'               => 14,
						'option_value_description' => array(
							1 => array(
								'name' => 'Purple'
							)
						)
					),
					14 => array(
						'image'                    => '',
						'sort_order'               => 15,
						'option_value_description' => array(
							1 => array(
								'name' => 'Red'
							)
						)
					),
					15 => array(
						'image'                    => '',
						'sort_order'               => 16,
						'option_value_description' => array(
							1 => array(
								'name' => 'Royal Blue'
							)
						)
					),
					16 => array(
						'image'                    => '',
						'sort_order'               => 17,
						'option_value_description' => array(
							1 => array(
								'name' => 'Sport Grey'
							)
						)
					),
					17 => array(
						'image'                    => '',
						'sort_order'               => 18,
						'option_value_description' => array(
							1 => array(
								'name' => 'Tan'
							)
						)
					),
					18 => array(
						'image'                    => '',
						'sort_order'               => 19,
						'option_value_description' => array(
							1 => array(
								'name' => 'Burgundy'
							)
						)
					),
					19 => array(
						'image'                    => '',
						'sort_order'               => 20,
						'option_value_description' => array(
							1 => array(
								'name' => 'Navy Ringer'
							)
						)
					),
					20 => array(
						'image'                    => '',
						'sort_order'               => 21,
						'option_value_description' => array(
							1 => array(
								'name' => 'Black Ringer'
							)
						)
					),
					21 => array(
						'image'                    => '',
						'sort_order'               => 22,
						'option_value_description' => array(
							1 => array(
								'name' => 'Red Ringer'
							)
						)
					)
				)
			)
		;
	}

	
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/tshirtgang')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['tshirtgang_api_key']) {
			$this->error['api_key'] = $this->language->get('error_api_key');
		}
		/*
		if (!$this->request->post['tshirtgang_api_id']) {
			$this->error['api_id'] = $this->language->get('error_api_id');
		}
		*/
		return !$this->error;
	}
	
}
