<?php
class ModelTshirtgangProducts extends Model {
	public function add($data) {
		$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "tshirtgang_products SET 
			id = '" .           $this->db->escape( $data['id']           ) . "',
			product_id = '" .   $this->db->escape( $data['product_id']   ) . "',
			title = '" .        $this->db->escape( $data['title']        ) . "',
			color = '" .        $this->db->escape( $data['color']        ) . "',
			style = '" .        $this->db->escape( $data['style']        ) . "',
			image = '" .        $this->db->escape( $data['image']        ) . "',
			overlay = '" .      $this->db->escape( $data['overlay']      ) . "',
			master_image = '" . $this->db->escape( $data['master_image'] ) . "',
			datetime_retrieved=NOW()"
		);
	}

	public function edit($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "tshirtgang_products SET 
			title = '" .        $this->db->escape( $data['title']        ) . "',
			color = '" .        $this->db->escape( $data['color']        ) . "',
			style = '" .        $this->db->escape( $data['style']        ) . "',
			image = '" .        $this->db->escape( $data['image']        ) . "',
			overlay = '" .      $this->db->escape( $data['overlay']      ) . "',
			master_image = '" . $this->db->escape( $data['master_image'] ) . "'
			WHERE id=" . (int)$id
		);
	}

	public function delete($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tshirtgang_products WHERE id=" . (int)$id );
	}

	public function get($data) {
		$product_data = array();
		$where = "";
		$where_and = array();
		$where_or  = array();
		
		$sql  = "SELECT ";
		$sql .= " tsgp.id,    ";
		$sql .= " tsgp.title, ";
		$sql .= " tsgp.color, ";
		$sql .= " tsgp.style, ";
		$sql .= " tsgp.datetime_retrieved, ";
		$sql .= " ocp.product_id, ";
		$sql .= " ocp.status ";
		$sql .= "FROM ";
		$sql .= " " . DB_PREFIX . "tshirtgang_products tsgp ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "product ocp ON ";
		$sql .= " tsgp.product_id = ocp.product_id ";
		if(isset($data['ids']) && is_array($data['ids']) && !empty($data['ids']) ){
			$ids=implode(',',$data['ids']);
			$where_and[] = " tsgp.id IN(". $ids .") ";
		}
		if(isset($data['search'])){
			$data['search'] = trim($data['search']);
			$data['search'] = $this->db->escape($data['search']);

			$where_or[] = " tsgp.id LIKE '%".$data['search']."%' ";
			$where_or[] = " tsgp.title LIKE '%".$data['search']."%' ";
			$where_or[] = " tsgp.color LIKE '%".$data['search']."%' ";
			$where_or[] = " tsgp.style LIKE '%".$data['search']."%' ";
			$where_or[] = " tsgp.datetime_retrieved LIKE '%".$data['search']."%' ";
			$where_or[] = " ocp.product_id LIKE '%".$data['search']."%' ";
		}
		if(!empty($where_or)){
			$where  = " ( ";
			$where .= implode(' OR ', $where_or);
			$where .= " ) ";
			$where_and[] = $where;
		}
		if(!empty($where_and)){
			$sql .= "WHERE ";
			$sql .= implode(' AND ',$where_and);
			$sql .= " ";
		}
		
		$sort_data = array(
			'tsgp.id',
			'tsgp.title',
			'tsgp.color',
			'tsgp.style',
			'tsgp.datetime_retrieved',
			'ocp.product_id',
			'ocp.status'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'] . " ";
			if (isset($data['order'])){
				if ( $data['order'] == 'DESC' ){
					$sql .= " DESC ";
				} else {
					$sql .= " ASC ";
				}
			}
		}
		if( isset($data['ipp'])){
			$sql .= " LIMIT ".(int)$data['ipp']." ";
			if( isset($data['page']) ){
				$sql .= " OFFSET ".( ((int)$data['page'] - 1) * (int)$data['ipp'] )." ";
			}
			if( isset($data['offset']) ){
				$sql .= " OFFSET ".(int)$data['offset']." ";
			}
		}
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$product_data[] = $result;
		}
		return $product_data;
	}

	public function getAll() {
		$product_data = array();
		$sql  = "SELECT ";
		$sql .= " tsgp.id,    ";
		$sql .= " tsgp.title, ";
		$sql .= " tsgp.color, ";
		$sql .= " tsgp.style, ";
		$sql .= " tsgp.datetime_retrieved, ";
		$sql .= " ocp.product_id, ";
		$sql .= " ocp.status ";
		$sql .= "FROM ";
		$sql .= " " . DB_PREFIX . "tshirtgang_products tsgp ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "product ocp ON ";
		$sql .= " tsgp.product_id = ocp.product_id ";
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$product_data[] = $result;
		}
		return $product_data;
	}

	public function getAllTsgIds() {
		$product_ids = array();
		$query = $this->db->query("SELECT id FROM " . DB_PREFIX . "tshirtgang_products");
		foreach ($query->rows as $result) {
			$product_ids[] = $result['id'];
		}
		return $product_ids;
	}

	public function getTotalCount() {
		$query = $this->db->query("SELECT COUNT(*) as product_count FROM " . DB_PREFIX . "tshirtgang_products");
		return $query->rows[0]['product_count'];
	}
	
	public function updateProductPricingAll(){
		$all_products = $this->getAll();
		$this->load->model('tshirtgang/pricing');
		foreach($all_products as $product){
			$new_price = $this->model_tshirtgang_pricing->get(
				array(
					'style' => $product['style'],
					'color' => $product['color']
				)
			);
			if(!is_null($product['product_id'])){
				$this->db->query("UPDATE " . DB_PREFIX . "product SET price='".(float)$new_price."' WHERE product_id=".$product['product_id']);
			}
		}
	}
	
	public function updateProductStyleOptionPricingAll(){ // TODO: move to pricing model
		$all_products = $this->getAll();
		$this->load->model('catalog/product');
		$this->load->model('tshirtgang/pricing');
		$this->load->model('catalog/option');
		$tshirt_option_names = array('Tshirt Color', 'Tshirt Style', 'Tshirt Size');
		$tshirt_colored = array(
			"Black",
			"Charcoal Grey",
			"Daisy",
			"Dark Chocolate",
			"Forest Green",
			"Gold",
			"Irish Green",
			"Light Blue",
			"Light Pink",
			"Military Green",
			"Navy",
			"Orange",
			"Purple",
			"Red",
			"Royal Blue",
			"Sport Grey",
			"Tan",
			"Burgundy"
		);
		$tshirt_ringer = array(
			"Navy Ringer",
			"Black Ringer",
			"Red Ringer"
		);
		$tshirt_options = array();
		$tshirt_options_price = array();
		$options = $this->model_catalog_option->getOptions();
		$temp_price = 0.0;
		foreach($options as $option){
			//if($option['name'] == 'Tshirt Color'){
			//	$tshirtcolor_option_id = $option['option_id'];
			//}
			//if($option['name'] == 'Tshirt Style'){
			//	$tshirtstyle_option_id = $option['option_id'];
			//}
			//if($option['name'] == 'Tshirt Size'){
			//	$tshirtsize_option_id = $option['option_id'];
			//}
			if(in_array($option['name'], $tshirt_option_names)){
				$tshirt_options[$option['name']] = array();
				$tshirt_options[$option['name']]['option_id'] = $option['option_id'];
				$tshirt_options[$option['name']]['prices'] = array();
			}
		}
		foreach($tshirt_option_names as $tshirt_option_name){
			$option_value_descriptions = $this->model_catalog_option->getOptionValueDescriptions($tshirt_options[$tshirt_option_name]['option_id']);
			foreach($option_value_descriptions as $opv){
				$temp_price = 0.0;
				if($tshirt_option_name=='Tshirt Color'){
					if( in_array($opv['option_value_description'][1]['name'], $tshirt_colored )){
						$temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'ColorShirt' ));
					} elseif( in_array($opv['option_value_description'][1]['name'], $tshirt_ringer  )){
						$temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'RingerShirt' ));
					} else { // white
						$temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'WhiteShirt' ));
					}
				}
				if($tshirt_option_name=='Tshirt Style'){
					if($opv['option_value_description'][1]['name'] == "Mens Fitted"     ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'MensFittedIncremental' ));
					if($opv['option_value_description'][1]['name'] == "Ladies"          ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'LadiesIncremental' ));
					if($opv['option_value_description'][1]['name'] == "Hooded Pullover" ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'HoodieIncremental' ));
					if($opv['option_value_description'][1]['name'] == "Apron"           ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'ApronIncremental' ));
					if($opv['option_value_description'][1]['name'] == "Vneck"           ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'VneckIncremental' ));
					if($opv['option_value_description'][1]['name'] == "Tanktop"         ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'TanktopIncremental' ));
					if($opv['option_value_description'][1]['name'] == "Baby One Piece"  ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'BabyOnePieceIncremental' ));
				}
				if($tshirt_option_name=='Tshirt Size'){
					if($opv['option_value_description'][1]['name'] == "2 X-Large" ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'Shirt_2XL_Incremental' ));
					if($opv['option_value_description'][1]['name'] == "3 X-Large" ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'Shirt_3XL6XL_Incremental' ));
					if($opv['option_value_description'][1]['name'] == "4 X-Large" ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'Shirt_3XL6XL_Incremental' ));
					if($opv['option_value_description'][1]['name'] == "5 X-Large" ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'Shirt_3XL6XL_Incremental' ));
					if($opv['option_value_description'][1]['name'] == "6 X-Large" ) $temp_price = $this->model_tshirtgang_pricing->get(array( 'code' => 'Shirt_3XL6XL_Incremental' ));
				}
				if($temp_price != 0.0){
					$tshirt_options_price = array(
						'option_value_id' => $opv['option_value_id'],
						'name'            => $opv['option_value_description'][1]['name'],
						'price'           => $temp_price
					);
					$tshirt_options[$tshirt_option_name]['prices'][] = $tshirt_options_price;
				}
			}
		}
		foreach($tshirt_options as $tso1){
			foreach($tso1['prices'] as $tso2){
				$sql  = "UPDATE " . DB_PREFIX . "product_option_value ocpov ";
				$sql .= "LEFT JOIN " . DB_PREFIX . "product ocp ";
				$sql .= " ON ocp.product_id = ocpov.product_id ";
				$sql .= "LEFT JOIN " . DB_PREFIX . "tshirtgang_products tsgp ";
				$sql .= " ON tsgp.product_id = ocp.product_id ";
				$sql .= "SET ocpov.price=". (float)$tso2['price'] . " ";
				$sql .= "WHERE ";
				$sql .= "  ocpov.option_value_id = " . (int)$tso2['option_value_id'] . " ";
				//$sql .= " AND ";
				//$sql .= "  tsgp.id IS NOT NULL ";
				$this->db->query($sql);
			}
		}
	}
	
	public function seoFriendlyUrl($data){
	
		if(isset($data['product_id']) && $data['keyword']){
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$data['product_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias   SET query = 'product_id=" . (int)$data['product_id'] . "', keyword = '" . $this->db->escape($this->toAscii($data['keyword']), "'") . "'");
		}
	}
	
	// from http://cubiq.org/the-perfect-php-clean-url-generator
	public function toAscii($str, $replace=array(), $delimiter='-') {
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
	
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
	
	public function setStatus($data){
		if( isset($data['product_id']) && isset($data['status']) ){
			if($data['status']){
				$this->db->query("UPDATE " . DB_PREFIX . "product SET status=1 WHERE product_id=".(int)$data['product_id']);
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET status=0 WHERE product_id=".(int)$data['product_id']);
			}
			
		}
	}
	
	public function getSizes(){
		return array(
			1  => "X-Small (Youth)",
			2  => "Small (Youth)",
			3  => "Medium (Youth)",
			4  => "Small",
			5  => "Medium",
			6  => "Large",
			7  => "X-Large",
			8  => "2 X-Large",
			9  => "3 X-Large",
			10 => "4 X-Large",
			11 => "5 X-Large",
			12 => "6 X-Large",
			13 => "2T",
			14 => "3T",
			15 => "4T",
			16 => "6 Months",
			17 => "12 Months",
			18 => "18 Months"
		);
	}

	public function getColors(){
		return array(
			1  => "White",
			2  => "Black",
			3  => "Charcoal Grey",
			4  => "Daisy",
			5  => "Dark Chocolate",
			6  => "Forest Green",
			7  => "Gold",
			8  => "Irish Green",
			9  => "Light Blue",
			10 => "Light Pink",
			11 => "Military Green",
			12 => "Navy",
			13 => "Orange",
			14 => "Purple",
			15 => "Red",
			16 => "Royal Blue",
			17 => "Sport Grey",
			18 => "Tan",
			19 => "Burgundy",
			20 => "Navy Ringer",
			21 => "Black Ringer",
			22 => "Red Ringer"
		);
	}
	
	public function getStyles(){
		return array(
			1 => "Standard",
			2 => "Mens Fitted",
			3 => "Ladies",
			4 => "Hooded Pullover",
			5 => "Apron",
			6 => "Vneck",
			7 => "Tanktop",
			8 => "Kids",
			9 => "Baby One Piece"
		);
	}
	
}
