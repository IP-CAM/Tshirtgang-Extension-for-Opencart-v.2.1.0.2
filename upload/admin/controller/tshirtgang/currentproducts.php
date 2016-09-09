<?php
// TODO: hard coding DIR_IMAGE.'catalog/apparel/' as image location. change.
class ControllerTshirtgangCurrentproducts extends Controller {
	private $error = array();

	public function index() {
		$this->getList();
	}

	public function syncStepwiseTest() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSync()) {
			$ipp  = $this->request->post['ipp'];
			$page = $this->request->post['page'];
			
			$delay = rand(1,5);
			sleep($delay);
			
			if($page < 10){
				$retrieved = rand(0,5);
				$duplicate = 5 - $retrieved;
				echo json_encode(
					array(
						'status'    => 'success',
						'done'      => false,
						'retrieved' => $retrieved,
						'duplicate' => $duplicate,
						'count'     => 5,
						'messages'   => array('thanks')
					)
				);
			} else {
				echo json_encode(
					array(
						'status'    => 'success',
						'done'      => true,
						'retrieved' => 0,
						'duplicate' => 0,
						'count'     => 0,
						'messages'   => array('bye')
					)
				);
			}
		} else {
			echo json_encode(
				array(
					'status'  => 'error',
					'messages' => array('ipp and page parameter required via POST method')
				)
			);
		}
	}
	
	public function setStatus(){
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSetStatus()) {
			$this->load->model('tshirtgang/products');
			$status = $this->request->post['status']=='1' ? true : false;
			$this->model_tshirtgang_products->setStatus(
				array(
					'product_id' => $this->request->post['product_id'],
					'status'     => $status
				)
			);
		}
	}
	
	public function syncStepwise() {
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSync()) {
			$ipp  = $this->request->post['ipp'];
			$page = $this->request->post['page'];
			
			$this->load->model('setting/setting');
			$tsg_setting = $this->model_setting_setting->getSetting('tshirtgang');
			$api_key  = $tsg_setting['tshirtgang_api_key'];
			$api_id   = "app-b4448ba35509b642695e"; //$tsg_setting['tshirtgang_api_id'];
			$api_url  = "https://www.tshirtgang.com/api/GetSellerProducts/";
			$color    = "all";
			$category = "all";
			
			$XML  = 	"<?xml version=\"1.0\" encoding=\"utf-8\"?>
						 <ProductForm>
							<Auth>
							   <key>$api_key</key>
							</Auth>
							<ProductInfo>
							   <itemsPerPage>$ipp</itemsPerPage>
							   <page>$page</page>
							   <color>$color</color>
							   <category>$category</category>
							</ProductInfo>
							<Extra>
							   <appID>$api_id</appID>
							</Extra>
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
			
			$retrived_products = new SimpleXMLElement($api_response);
			
			$this->load->model('tshirtgang/products');
			$previously_retrieved_ids = $this->model_tshirtgang_products->getAllTsgIds();
			
			$newly_retrieved_count = 0;
			$duplicate_count = 0;
			$total_items = 0;
			$messages = array();
			$retrievied_items_ids = array();
			$retrievied_items = array();
			
			
			if(isset($retrived_products->success)){
				if(isset($retrived_products->success->item)){
					foreach($retrived_products->success->item as $item){
						$total_items++;
						$has_error = false;
						if(in_array($item->productID, $previously_retrieved_ids)){
							// skip duplicate
							$duplicate_count++;
							// TODO: check, even if duplicate, if the images are present. if not download the images.
						} else {
							$newly_retrieved_count++;
							if(empty($item->title)){
								$messages[]='item '.$item->productID.' has no title'; // TODO: use language
								$has_error = true;
							}
							if(empty($item->overlay)){
								$messages[]='item '.$item->productID.' has no overlay'; // TODO: use language
								$has_error = true;
							}
							if(empty($item->title)){
								$messages[]='item '.$item->productID.' has no title'; // TODO: use language
								$has_error = true;
							}
							// -start- download images
							if(empty($item->image)){
								$messages[]='item '.$item->productID.' has no image'; // TODO: use language
								$has_error = true;
							} else {
								$file_extension = explode('/',$item->image);
								$file_extension = end($file_extension);
								$file_extension = explode('.',$file_extension);
								$file_extension = end($file_extension);
								$file_extension = strtolower($file_extension);
								if($file_extension == 'png'){
									file_put_contents(DIR_IMAGE.'catalog/apparel/'.$item->productID.'.'.$file_extension, fopen($item->image, 'r')); // TODO: 'catalog/apparel' hard-coded
								} else {
									$messages[]='item '.$item->productID.' image is not PNG'; // TODO: use language
									$has_error = true;
								}
							}
							// --end-- download images
							// -start- download and scale images
							if(empty($item->masterImage)){
								$messages[]='item '.$item->productID.' has no masterImage'; // TODO: use language
								$has_error = true;
							} else {
								$file_extension = explode('/',$item->masterImage);
								$file_extension = end($file_extension);
								$file_extension = explode('.',$file_extension);
								$file_extension = end($file_extension);
								$file_extension = strtolower($file_extension);
								if($file_extension == 'png'){
									$handle = fopen($item->masterImage, 'r');
									$img = new Imagick();
									$img->readImageFile($handle);
									fclose($handle);
									if($img->scaleImage(0,400)){
										file_put_contents(DIR_IMAGE.'catalog/apparel/overlay_'.$item->productID.'.'.$file_extension, $img->getImageBlob()); // TODO: 'catalog/apparel' hard-coded
									} else {
										$messages[]='item '.$item->productID.' overlay not created'; // TODO: use language
										$has_error = true;
									}
								} else {
									$messages[]='item '.$item->productID.' overlay is not PNG'; // TODO: use language
									$has_error = true;
								}
							}
							// --end-- download and scale images
							$oc_product_id=0;
							if(!$has_error){
								$oc_product_id = $this->addToProducts($item);
								$retrievied_items_ids[] = $item->productID;
							}
							$this->model_tshirtgang_products->add(
								array(
									'id'           => $item->productID,
									'product_id'   => $oc_product_id,
									'title'        => $item->title,
									'color'        => $item->color,
									'style'        => $item->style,
									'image'        => $item->image,
									'overlay'      => $item->overlay,
									'master_image' => $item->masterImage
								)
							);
						}
					}
					//$retrievied_items = $this->model_tshirtgang_products->get(array('ids'  => $retrievied_items_ids)); // retrieving from model also returns opencart product_id
					$this->response->setOutput(json_encode(array('status'=>'success', 'done'=>false, 'retrieved'=>$newly_retrieved_count, 'duplicate'=>$duplicate_count, 'count'=>$total_items, 'messages'=> $messages, 'items'=>$retrievied_items)));
				} else {
					// tshirtgang query success but returned empty result
					$this->response->setOutput(json_encode(array('status'=>'success', 'done'=>true, 'retrieved'=>0, 'duplicate'=>0, 'count'=>0, 'messages'=>array('finish'), 'items'=>array())));
				}
			} else {
				// api call not successful
				$this->response->setOutput(json_encode(array('status'  => 'error','messages' =>array('ipp and page parameter required via POST method'))));
			}
		}
	}
	
	public function addToProducts($item){
		// - getStockStatuses
		$this->load->model('localisation/stock_status');
		$stockStatuses = $this->model_localisation_stock_status->getStockStatuses();
		$status_in_stock_found = false;
		$status_in_stock_id = 0;
		foreach($stockStatuses as $stockStatus){
			if($stockStatus['name'] == 'In Stock'){
				$status_in_stock_found = true;
				$status_in_stock_id = $stockStatus['stock_status_id'];
			}
		}
		// TODO: get weight classes
		// TODO: length_class_id
		
		// -start- get 'Apparel' category ID, and also add tshirt_category if it does not exist
		$this->load->model('catalog/category');
		$categories = $this->model_catalog_category->getCategories();
		

		$product_category = array();
		$apparel_category_exist = false;
		$apparel_category_id = 0;
		$tshirt_category_exist = false;
		$tshirt_category_id = 0;
		foreach($categories as $category){
			if($category['name'] == 'Apparel') {
				$apparel_category_exist = true;
				$apparel_category_id = $category['category_id'];
				$product_category[]  = $category['category_id'];
			}
			if(isset($item->tshirt_category) && $category['name'] == $item->tshirt_category->title) {
				$tshirt_category_exist = true;
				$tshirt_category_id = $category['category_id'];;
			}
		}
		if(isset($item->tshirt_category) && !$tshirt_category_exist){
			$tshirt_category_id = $this->model_catalog_category->addCategory(
				array(
					'parent_id'      => 0,
					'column'         => 1,
					'sort_order'     => 1,
					'status'         => 1,
					'image'          => '',
					'top'            => 0,
					'category_store' => array(0),
					'category_description' => array (
						1 => array(
							'name'             => $item->tshirt_category->title,
							'description'      => '',
							'meta_title'       => '',
							'meta_description' => '',
							'meta_keyword'     => '',
							
						)
					),
					// -start- uksb google merchant plugin specific
					'google_category_gb' => '',
					'google_category_us' => '',
					'google_category_au' => '',
					'google_category_fr' => '',
					'google_category_de' => '',
					'google_category_it' => '',
					'google_category_nl' => '',
					'google_category_es' => '',
					'google_category_pt' => '',
					'google_category_cz' => '',
					'google_category_jp' => '',
					'google_category_dk' => '',
					'google_category_no' => '',
					'google_category_pl' => '',
					'google_category_ru' => '',
					'google_category_sv' => '',
					'google_category_tr' => '',
					// --end-- uksb google merchant plugin specific
				)
			);
		}
		if(isset($item->tshirt_category)){
			$product_category[] = $tshirt_category_id;
		}
		// --end-- get 'Apparel' category ID, and also add tshirt_category if it does not exist

		$this->load->model('tshirtgang/pricing');
		$this->load->model('tshirtgang/products');
		
		$tshirt_styles = $this->model_tshirtgang_products->getStyles();
		$tshirt_colors = $this->model_tshirtgang_products->getColors();
		$tshirt_sizes  = $this->model_tshirtgang_products->getSizes();

		$tshirt_styles_ids = array();
		$tshirt_colors_ids = array();
		$tshirt_sizes_ids  = array();

		// -start- get 'Tshirt Style/Size/Color' options
		$this->load->model('catalog/option');
		$tshirtstyle_option_id = 0;
		$tshirtsize_option_id  = 0;
		$tshirtcolor_option_id = 0;
		$options = $this->model_catalog_option->getOptions();
		foreach($options as $option){
			if($option['name'] == 'Tshirt Style'){
				$tshirtstyle_option_id = $option['option_id'];
			}
			if($option['name'] == 'Tshirt Size'){
				$tshirtsize_option_id  = $option['option_id'];
			}
			if($option['name'] == 'Tshirt Color'){
				$tshirtcolor_option_id = $option['option_id'];
			}
		}
		// styles
		$option_value_descriptions = $this->model_catalog_option->getOptionValueDescriptions($tshirtstyle_option_id);
		foreach($option_value_descriptions as $opv){
			foreach($tshirt_styles as $t_style){
				if($opv['option_value_description'][1]['name'] == $t_style){
					$tshirt_styles_ids[$t_style] = $opv['option_value_id'];
				}
			}
		}
		// sizes
		$option_value_descriptions = $this->model_catalog_option->getOptionValueDescriptions($tshirtsize_option_id);
		foreach($option_value_descriptions as $opv){
			foreach($tshirt_sizes as $t_size){
				if($opv['option_value_description'][1]['name'] == $t_size){
					$tshirt_sizes_ids[$t_size] = $opv['option_value_id'];
				}
			}
		}
		// colors
		$option_value_descriptions = $this->model_catalog_option->getOptionValueDescriptions($tshirtcolor_option_id);
		foreach($option_value_descriptions as $opv){
			foreach($tshirt_colors as $t_color){
				if($opv['option_value_description'][1]['name'] == $t_color){
					$tshirt_colors_ids[$t_color] = $opv['option_value_id'];
				}
			}
		}
		// --end-- get 'Tshirt Style/Size/Color' options
		
		$standard_price_inc   = 0.0;
		$ladies_price_inc     = $this->model_tshirtgang_pricing->get(array('code'=>'LadiesIncremental'));
		$mensfitted_price_inc = $this->model_tshirtgang_pricing->get(array('code'=>'MensFittedIncremental'));
		
		$this->load->model('catalog/product');
		$product_data = array(
			'model'           => $item->productID,
			'sku'             => $item->productID,
			'upc'             => '',
			'ean'             => '',
			'jan'             => '',
			'isbn'            => '',
			'mpn'             => 'fbt_'.$item->productID,
			'location'        => '',
			'quantity'        => 1000,
			'minimum'         => 1,
			'subtract'        => 1,
			'stock_status_id' => $status_in_stock_id,
			'date_available'  => date('Y-m-d'),
			'manufacturer_id' => 0,
			'shipping'        => 1,
			'price'           => $this->model_tshirtgang_pricing->get(array('style'=>$item->style, 'color'=>$item->color)),
			//'price'           => 0.0,
			'points'          => 0,        // TODO
			'weight'          => 10.00,    // TODO
			'weight_class_id' => 1,        // TODO: get this value from db
			'length'          => 10.00,    // TODO
			'width'           => 10.00,    // TODO
			'height'          => 10.00,    // TODO
			'length_class_id' => 1,        // TODO
			'status'          => 1,
			'tax_class_id'    => 1,        // TODO
			'sort_order'      => 0,
			'image'           => 'catalog/apparel/'.$item->productID.'.png',
			'product_store'    => array(0),    // TODO: is it always 0?
			'product_category' => $product_category
		);
		$product_data['product_description'] = array();
		$product_data['product_description'][1] = array( // 1=english
			'name'             => $item->title,
			'description'      => '&#x3C;p&#x3E;&#x3C;strong&#x3E;'.htmlspecialchars($item->title).'&#x3C;/strong&#x3E;. This cool, essential t-shirt is available in many styles and colors including mens, womens and kids. The graphic is printed on a quality, preshrunk cotton shirt you will love, satisfaction guaranteed. This tee is sure to be a favorite!&#x3C;/p&#x3E;',
			'tag'              => '',
			'meta_title'       => $item->title,
			'meta_description' => '',
			'meta_keyword'     => ''
		);
		$product_data['product_option'] = array();
		//////////////////////////////
		// 0 index for Style
		$product_data['product_option'][0] = array(
			'option_id' => $tshirtstyle_option_id,
			'type'      => 'select',
			'required'  => 1,
		);
		$product_data['product_option'][0]['product_option_value'] = array();
		/////////////////////////////////////////////////
		foreach($tshirt_styles_ids as $key => $value){
			$option_price = 0.0;
			if($key == "Standard")        $option_price = 0.0;
			if($key == "Mens Fitted")     $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'MensFittedIncremental'));
			if($key == "Ladies")          $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'LadiesIncremental'));
			if($key == "Hooded Pullover") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'HoodieIncremental'));
			if($key == "Apron")           $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ApronIncremental'));
			if($key == "Vneck")          $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'VneckIncremental'));
			if($key == "Tanktop")         $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'TanktopIncremental'));
			if($key == "Kids")            $option_price = 0.0;
			if($key == "Baby One Piece")  $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'BabyOnePieceIncremental'));
			$product_data['product_option'][0]['product_option_value'][] = array(
				'option_value_id' => $value,
				'quantity'        => 0,
				'subtract'        => 0,
				'price'           => $option_price,
				'price_prefix'    => '+',
				'points'          => 0,
				'points_prefix'   => '+',
				'weight'          => '0.0',
				'weight_prefix'   => '+'
			);
		}

		////////////////////////////////////////////////////
		// 1 index for Size
		$product_data['product_option'][1] = array(
			'option_id' => $tshirtsize_option_id,
			'type'      => 'select',
			'required'  => 1,
		);
		$product_data['product_option'][1]['product_option_value'] = array();
		//////////////////////////////////
		foreach($tshirt_sizes_ids as $key => $value){
			$option_price = 0.0;
			if($key == "2 X-Large") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'Shirt_2XL_Incremental'));
			if($key == "3 X-Large") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'Shirt_3XL6XL_Incremental'));
			if($key == "4 X-Large") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'Shirt_3XL6XL_Incremental'));
			if($key == "5 X-Large") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'Shirt_3XL6XL_Incremental'));
			if($key == "6 X-Large") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'Shirt_3XL6XL_Incremental'));
			$product_data['product_option'][1]['product_option_value'][] = array(
				'option_value_id' => $value,
				'quantity'        => 0,
				'subtract'        => 0,
				'price'           => $option_price,
				'price_prefix'    => '+',
				'points'          => 0,
				'points_prefix'   => '+',
				'weight'          => '0.0',
				'weight_prefix'   => '+'
			);
		}
		/////////////////////////////////
		// 2 index for Color
		$product_data['product_option'][2] = array(
			'option_id' => $tshirtcolor_option_id,
			'type'      => 'select',
			'required'  => 1,
		);
		$product_data['product_option'][2]['product_option_value'] = array();
		foreach($tshirt_colors_ids as $key => $value){
			$option_price = 0.0;
			if($key == "White")          $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'WhiteShirt'));
			if($key == "Black")          $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Charcoal Grey")  $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Daisy")          $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Dark Chocolate") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Forest Green")   $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Gold")           $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Irish Green")    $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Light Blue")     $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Light Pink")     $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Military Green") $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Navy")           $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Orange")         $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Purple")         $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Red")            $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Royal Blue")     $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Sport Grey")     $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Tan")            $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Burgundy")       $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'ColorShirt'));
			if($key == "Navy Ringer")    $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'RingerShirt'));
			if($key == "Black Ringer")   $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'RingerShirt'));
			if($key == "Red Ringer")     $option_price = $this->model_tshirtgang_pricing->get(array('code'=>'RingerShirt'));
			$product_data['product_option'][2]['product_option_value'][] = array(
				'option_value_id' => $value,
				'quantity'        => 0,
				'subtract'        => 0,
				'price'           => $option_price,
				'price_prefix'    => '+',
				'points'          => 0,
				'points_prefix'   => '+',
				'weight'          => '0.0',
				'weight_prefix'   => '+'
			);
		}
		
		// -start- uksb google merchant plugin specific
		$product_data['g_on_google']           = '1';
		$product_data['google_category_gb']    = '';
		$product_data['google_category_us']    = '';
		$product_data['google_category_au']    = '';
		$product_data['google_category_fr']    = '';
		$product_data['google_category_de']    = '';
		$product_data['google_category_it']    = '';
		$product_data['google_category_nl']    = '';
		$product_data['google_category_es']    = '';
		$product_data['google_category_pt']    = '';
		$product_data['google_category_cz']    = '';
		$product_data['google_category_jp']    = '';
		$product_data['google_category_dk']    = '';
		$product_data['google_category_no']    = '';
		$product_data['google_category_pl']    = '';
		$product_data['google_category_ru']    = '';
		$product_data['google_category_sv']    = '';
		$product_data['google_category_tr']    = '';
		$product_data['g_condition']           = 'new';
		$product_data['g_gtin']                = '';
		$product_data['g_identifier_exists']   = '1';
		$product_data['g_gender']              = 'unisex';
		$product_data['g_age_group']           = 'Adult';

		$product_data['g_size_type']           = 'regular';
		$product_data['g_size_system']         = 'US';

		$product_data['g_brand']               = 'Gildan';
		$this->load->model('setting/setting');
		$store_info = $this->model_setting_setting->getSetting('config');
		$product_data['g_brand']               = $store_info['config_meta_title'];

		//$data['g_colour']    = (isset($data['variant'])?implode(',', $data['g_colourx']):'');
		//$data['g_size']      = (isset($data['variant'])?implode(',', $data['g_sizex']):'');
		//$data['g_material']  = (isset($data['variant'])?implode(',', $data['g_materialx']):'');
		//$data['g_pattern']   = (isset($data['variant'])?implode(',', $data['g_patternx']):'');
		//$data['v_mpn']       = (isset($data['variant'])?implode(',', $data['v_mpnx']):'');
		//$data['v_gtin']      = (isset($data['variant'])?implode(',', $data['v_gtinx']):'');
		//$data['v_prices']    = (isset($data['variant'])?implode(',', $data['v_pricesx']):'');
		//$data['v_images']    = (isset($data['variant'])?implode(',', $data['v_imagesx']):'');

		$this->load->model('tshirtgang/pricing');

		$product_data['variant'] = array();
		$product_data['variant'][0] = array();
		$product_data['variant'][0]['g_colour']   = $item->color;
		$product_data['variant'][0]['g_size']     = 'Medium';
		$product_data['variant'][0]['g_material'] = '';
		$product_data['variant'][0]['g_pattern']  = '';
		$product_data['variant'][0]['v_mpn']      = $item->productID;
		$product_data['variant'][0]['v_gtin']     = '';
		//$product_data['variant'][0]['v_prices']   = $this->model_tshirtgang_pricing->get(array('style'=>$item->style, 'color'=>$item->color));
		$product_data['variant'][0]['v_prices']   = '';
		$product_data['variant'][0]['v_images']   = $item->image;

		$product_data['g_multipack']           = '0';
		$product_data['g_is_bundle']           = '0';
		$product_data['g_adult']               = '0';

		$product_data['g_adwords_redirect']    = '';
		$product_data['g_custom_label_0']      = '';
		$product_data['g_custom_label_1']      = '';
		$product_data['g_custom_label_2']      = '';
		$product_data['g_custom_label_3']      = '';
		$product_data['g_custom_label_4']      = '';
		$product_data['g_expiry_date']         = '';
		$product_data['g_unit_pricing_measure']      = '';
		$product_data['g_unit_pricing_base_measure'] = '';
		$product_data['g_energy_efficiency_class']   = '0';
		// --end-- uksb google merchant plugin specific
		
		//////////////////////////////////
		$product_id = $this->model_catalog_product->addProduct($product_data);
		//////////////////////////////////
		$this->model_tshirtgang_products->seoFriendlyUrl(
			array(
				'product_id' => $product_id,
				//'keyword'    => $product_id.' '.$item->title
				'keyword'    => $item->productID.' '.$item->title,
			)
		);
		return $product_id;
	}
	
	public function dataTableAjax(){
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$order_columns = $this->request->post['order'];
			$recordsTotal    = 0;
			$recordsFiltered = 0;
			$to_json = array();
			$columns = array('ocp.product_id','tsgp.id','tsgp.id','tsgp.title','tsgp.color','tsgp.style','ocp.status','tsgp.datetime_retrieved'); // NOTE: image derived from tsgp.id

			// -start- TODO:use
			$sql = " ORDER BY ";
			foreach($order_columns as $oc){
				$sql .= " ". $columns[$oc['column']] . " ";
				if($oc['dir']== 'desc'){
					$sql .= " DESC, ";
				}
			}
			// --end-- TODO:use
			
			$this->load->model('tshirtgang/products');
			
			$get_array = array(
				'ipp'    => $this->request->post['length'],
				'offset' => $this->request->post['start'],
				'sort'   => $columns[ $this->request->post['order'][0]['column'] ], // TODO:
				'order'  => ($this->request->post['order'][0]['dir']=='desc'? 'DESC':'ASC' ),
			);
			
			$to_json['debug'] = $this->request->post['search']['value'];
			if(empty($this->request->post['search']['value'])){
				
				$to_json['recordsFiltered'] = $this->model_tshirtgang_products->getTotalCount();
			} else {
				$get_array['search'] = $this->request->post['search']['value'];
				$items_filtered = $this->model_tshirtgang_products->get(array('search'=> $this->request->post['search']['value']));
				$to_json['recordsFiltered'] = count($items_filtered);
			}
			
			$items = $this->model_tshirtgang_products->get(	$get_array );
			
			
			$to_json['draw']            = $this->request->post['draw'];
			$to_json['recordsTotal']    = $this->model_tshirtgang_products->getTotalCount();
			$to_json['data']            = array();
			foreach($items as $item){
				$row = array();
				$row[] = $item['product_id'];
				$row[] = $item['id'];
				$row[] = (file_exists(DIR_IMAGE.'catalog/apparel/'.$item['id'].'.png') ? '/image/catalog/apparel/'.$item['id'].'.png':""); // TODO: hard coded
				$row[] = $item['title'];
				$row[] = $item['color'];
				$row[] = $item['style'];
				$row[] = $item['status'];
				$row[] = $item['datetime_retrieved'];
				$to_json['data'][] = $row;
			}
			
			$this->response->setOutput(json_encode($to_json));
		} else {
			$this->response->setOutput(json_encode(array('message'=>'POST parameters required')));
		}
	}
	
	public function getList(array $data=array()) {
		$this->language->load('tshirtgang/currentproducts');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tshirtgang/currentproducts', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		//$data['sync']   = $this->url->link('tshirtgang/currentproducts/syncStepwiseTest', 'token=' . $this->session->data['token'], 'SSL'); // TODO: test again
		$data['sync']   = 'index.php?route=tshirtgang/currentproducts/syncStepwise&token=' . $this->session->data['token'];
		
		$data['datatableajax'] = 'index.php?route=tshirtgang/currentproducts/dataTableAjax&token=' . $this->session->data['token'];
		
		$data['edit_ocp_link'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'].'&product_id=', 'SSL'); // ocp = open cart product
		$data['edit_ocp_status_link'] = $this->url->link('tshirtgang/currentproducts/setStatus', 'token=' . $this->session->data['token'].'&product_id=', 'SSL'); // ocp = open cart product
		//$data['edit_ocp_status_link'] = 'index.php?route=tshirtgang/currentproducts/setStatus&token=' . $this->session->data['token'].'&product_id=';
		
		$data['button_save']   = $this->language->get('button_save');
		$data['button_sync']   = $this->language->get('button_sync');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['text_list'] = $this->language->get('text_list');

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

		if (!isset($data['success'])) {
			$data['success'] = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->load->model('tshirtgang/products');
		$data['products_count'] = $this->model_tshirtgang_products->getTotalCount();
		$data['products']       = $this->model_tshirtgang_products->getAll();
		
		$data['items']        = isset($data['items']        ) ? $data['items']         : null ;
		$data['api_response'] = isset($data['api_response'] ) ? $data['api_response']  : ""   ;
		$data['xml']          = isset($data['xml']          ) ? $data['xml']           : ""   ;
		$data['tsg_setting']  = isset($data['tsg_setting']  ) ? $data['tsg_setting']   : ""   ;
		
		//$this->load->model('catalog/category');
		//$data['debug_me'] = $this->model_catalog_category->getCategories();

		//$this->load->model('tshirtgang/pricing');
		//$data['debug_me'] = $this->model_tshirtgang_pricing->get(array('code' => 'RushDomesticShipping'));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tshirtgang/currentproducts.tpl', $data));
	}
	
	public function validateSync(){
		if (!$this->user->hasPermission('modify', 'tshirtgang/currentproducts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if( !isset($this->request->post['ipp']) ) {
			$this->error['warning'] = $this->language->get('error_missing_ipp');
		}

		if( !isset($this->request->post['page']) ) {
			$this->error['warning'] = $this->language->get('error_missing_page');
		}
		
		return !$this->error;
	}

	public function validateSetStatus(){
		if (!$this->user->hasPermission('modify', 'tshirtgang/currentproducts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}
