<?php
class ModelTshirtgangProducts extends Model {

	public function isTsg($product_id = 0){
		$product_data = $this->get($product_id);
		if(isset($product_data['id'])) {
			return true;
		} else {
			return false;
		}
	}

	public function get($product_id = 0) {
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
		$sql .= "WHERE ocp.product_id=".$product_id;
		$query = $this->db->query($sql);
		$product_data = $query->rows;
		if(isset($product_data[0])){
			return $product_data[0];
		} else {
			return array();
		}
	}

	public function optionIds($product_id = 0) {
		$option_ids = array();
		$option_descriptions = array();

		$sql  = "SELECT ";
		$sql .= "  ocpo.product_option_id, ";
		$sql .= "  ocod.name ";
		$sql .= "FROM " . DB_PREFIX . "product_option ocpo ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "option_description ocod ";
		$sql .= "  ON ocod.option_id = ocpo.option_id ";
		$sql .= "WHERE ";
		$sql .= "    ocod.language_id=1 ";
		$sql .= "  AND ";
		$sql .= "    ocpo.product_id=".$product_id." "; 
		$sql .= "  AND ";
		$sql .= "    ocod.name IN ('Tshirt Size', 'Tshirt Color', 'Tshirt Style') ";
		$query = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$option_ids[$result['name']] = (int)$result['product_option_id'];
		}
		return $option_ids;
	}

	public function options(){
		$apparel_options = array();
        
		$apparel_options['styles'] = array();
		$apparel_options['colors'] = array();
		$apparel_options['sizes'] = array();
        
		$apparel_options['styles_colors'] = array();
		$apparel_options['styles_sizes']  = array();
		$apparel_options['colors_styles'] = array();
		$apparel_options['colors_sizes']  = array();
		$apparel_options['sizes_styles']  = array();
		$apparel_options['sizes_colors']  = array();
        
		$apparel_options['styles_colors_sizes'] = array();
		$apparel_options['styles_sizes_colors'] = array();
		$apparel_options['colors_styles_sizes'] = array();
		$apparel_options['colors_sizes_styles'] = array();
		$apparel_options['sizes_styles_colors'] = array();
		$apparel_options['sizes_colors_styles'] = array();

		// main data
		$apparel_options['styles_colors_sizes'] = [
			'Standard' => [
				'Black' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
					'4 X-Large',
					'5 X-Large',
					'6 X-Large',
				],
				'White' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
					'4 X-Large',
					'5 X-Large',
					'6 X-Large',
				],
				'Charcoal Grey' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Daisy' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
					'4 X-Large',
					'5 X-Large',
				],
				'Dark Chocolate' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Forest Green' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Gold' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Irish Green' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Light Blue' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Light Pink' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Military Green' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Navy' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
					'4 X-Large',
					'5 X-Large',
				],
				'Orange' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Purple' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Red' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
					'4 X-Large',
					'5 X-Large',
				],
				'Royal Blue' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
					'4 X-Large',
					'5 X-Large',
				],
				'Sport Grey' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
					'4 X-Large',
					'5 X-Large',
				],
				'Tan' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Burgundy' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Navy Ringer' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Black Ringer' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Red Ringer' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
			],
			'Mens Fitted' => [
				'Black' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'White' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Charcoal Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Daisy' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Dark Chocolate' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Irish Green' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Light Blue' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Military Green' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Navy' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Orange' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Purple' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Red' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Royal Blue' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Sport Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
			],
			'Ladies' => [
				'Black' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'White' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Irish Green' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Light Blue' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Light Pink' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Navy' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Red' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Royal Blue' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Sport Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
			],
			'Hooded Pullover' => [
				'Black' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'White' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Navy' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Red' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Royal Blue' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Sport Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
			],
			'Apron' => [
				'Black' => [
					'Large',
				],
				'White' => [
					'Large',
				],
			],
			'Vneck' => [
				'Black' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'White' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Charcoal Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Navy' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Red' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
				'Sport Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
					'3 X-Large',
				],
			],
			'Tanktop' => [
				'Black' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'White' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Charcoal Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Navy' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Red' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Royal Blue' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
				'Sport Grey' => [
					'Small',
					'Medium',
					'Large',
					'X-Large',
					'2 X-Large',
				],
			],
			'Kids' => [
				'Black' => [
					'2T',
					'3T',
					'4T',
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'White' => [
					'2T',
					'3T',
					'4T',
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Charcoal Grey' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Daisy' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Dark Chocolate' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Forest Green' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Gold' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Irish Green' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Light Blue' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Light Pink' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Military Green' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Navy' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Orange' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Purple' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Red' => [
					'2T',
					'3T',
					'4T',
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Royal Blue' => [
					'2T',
					'3T',
					'4T',
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Sport Grey' => [
					'2T',
					'3T',
					'4T',
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Tan' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
				'Burgundy' => [
					'X-Small (Youth)',
					'Small (Youth)',
					'Medium (Youth)',
				],
			],
			'Baby One Piece' => [
				'White' => [
					'6 Months',
					'12 Months',
					'18 Months',
				],
			],
		];

		// style
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			$apparel_options['styles'][] = $key1;
		}
        
		// color
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			foreach($value1 as $key2 => $value2){
				$apparel_options['colors'][] = $key2;
			}
		}
		$apparel_options['colors'] = array_unique($apparel_options['colors']);
        
		// size
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			foreach($value1 as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$apparel_options['sizes'][] = $value3;
				}
			}
		}
		$apparel_options['sizes'] = array_unique($apparel_options['sizes']);
        
		// style-color
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			$apparel_options['styles_colors'][$key1] = array();
			foreach($value1 as $key2 => $value2){
				$apparel_options['styles_colors'][$key1][] = $key2;
			}
		}
        
		// style-size
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			$apparel_options['styles_sizes'][$key1] = array();
			foreach($value1 as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					$apparel_options['styles_sizes'][$key1][] = $value3;
				}
			}
			$apparel_options['styles_sizes'][$key1] = array_unique($apparel_options['styles_sizes'][$key1]);
		}
        
		// color-style
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			foreach($value1 as $key2 => $value2){
				if(!isset($apparel_options['colors_styles'][$key2])){
					$apparel_options['colors_styles'][$key2] = array();
				}
				$apparel_options['colors_styles'][$key2][] = $key1;
			}
		}
        
		// color-size
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			foreach($value1 as $key2 => $value2){
				if(!isset($apparel_options['colors_sizes'][$key2])){
					$apparel_options['colors_sizes'][$key2] = array();
				}
				foreach($value2 as $key3 => $value3){ 
					$apparel_options['colors_sizes'][$key2][] = $value3;
				}
				$apparel_options['colors_sizes'][$key2] = array_unique($apparel_options['colors_sizes'][$key2]);
			}
		}
        
		// size-style
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			foreach($value1 as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					if(!isset($apparel_options['sizes_styles'][$value3])){
						$apparel_options['sizes_styles'][$value3] = array();
					}
					$apparel_options['sizes_styles'][$value3][] = $key1;
				}
			}
		}
		foreach($apparel_options['sizes_styles'] as $key => $value){
			$apparel_options['sizes_styles'][$key] = array_unique($apparel_options['sizes_styles'][$key]);
		}
        
		// size-color
		foreach($apparel_options['styles_colors_sizes'] as $key1 => $value1){
			foreach($value1 as $key2 => $value2){
				foreach($value2 as $key3 => $value3){
					if(!isset($apparel_options['sizes_colors'][$value3])){
						$apparel_options['sizes_colors'][$value3] = array();
					}
					$apparel_options['sizes_colors'][$value3][] = $key2;
				}
			}
		}
		foreach($apparel_options['sizes_colors'] as $key => $value){
			$apparel_options['sizes_colors'][$key] = array_unique($apparel_options['sizes_colors'][$key]);
		}
        
		//style-size-color
		foreach($apparel_options['styles_sizes'] as $key1 => $value1){
			$apparel_options['styles_sizes_colors'][$key1] = array();
			foreach($value1 as $key2 => $value2){
				$apparel_options['styles_sizes_colors'][$key1][$value2] = array();
				foreach($apparel_options['colors'] as $key3 => $value3){
					if(isset($apparel_options['styles_colors_sizes'][$key1][$value3]) && in_array($value2, $apparel_options['styles_colors_sizes'][$key1][$value3])){
						$apparel_options['styles_sizes_colors'][$key1][$value2][]=$value3;
					}
				}
			}
		}
        
		//color-style-size
		foreach($apparel_options['colors_styles'] as $key1 => $value1){
			$apparel_options['colors_styles_sizes'][$key1] = array();
			foreach($value1 as $key2 => $value2){
				$apparel_options['colors_styles_sizes'][$key1][$value2] = array();
				foreach($apparel_options['sizes'] as $key3 => $value3){
					if(in_array($value3, $apparel_options['styles_colors_sizes'][$value2][$key1])){
						$apparel_options['colors_styles_sizes'][$key1][$value2][] = $value3;
					}
				}
			}
		}
        
		//color-size-style
		foreach($apparel_options['colors_sizes'] as $key1 => $value1){
			$apparel_options['colors_sizes_styles'][$key1] = array();
			foreach($value1 as $key2 => $value2){
				$apparel_options['colors_sizes_styles'][$key1][$value2] = array();
				foreach($apparel_options['styles'] as $key3 => $value3){
					if(isset($apparel_options['styles_colors_sizes'][$value3][$key1]) && in_array($value2, $apparel_options['styles_colors_sizes'][$value3][$key1])){
						$apparel_options['colors_sizes_styles'][$key1][$value2][] = $value3;
					}
				}
			}
		}
        
		//size-style-color
		foreach($apparel_options['sizes_styles'] as $key1 => $value1){
			$apparel_options['sizes_styles_colors'][$key1] = array();
			foreach($value1 as $key2 => $value2){
				$apparel_options['sizes_styles_colors'][$key1][$value2] = array();
				foreach($apparel_options['colors'] as $key3 => $value3){
					if(isset($apparel_options['styles_colors_sizes'][$value2][$value3]) && in_array($key1, $apparel_options['styles_colors_sizes'][$value2][$value3])){
						$apparel_options['sizes_styles_colors'][$key1][$value2][] = $value3;
					}
				}
			}
		}
        
		//size-color-style
		foreach($apparel_options['sizes_colors'] as $key1 => $value1){
			$apparel_options['sizes_colors_styles'][$key1] = array();
			foreach($value1 as $key2 => $value2){
				$apparel_options['sizes_colors_styles'][$key1][$value2] = array();
				foreach($apparel_options['styles'] as $key3 => $value3){
					if(isset($apparel_options['styles_colors_sizes'][$value3][$value2]) && in_array($key1, $apparel_options['styles_colors_sizes'][$value3][$value2])){
						$apparel_options['sizes_colors_styles'][$key1][$value2][] = $value3;
					}
				}
			}
		}
		return $apparel_options;
	}
}