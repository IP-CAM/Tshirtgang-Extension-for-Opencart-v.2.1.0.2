<?php
class ModelTshirtgangPricing extends Model {
	public function add($data) {
	}
	public function edit($data) {
		if(isset($data['code']) && isset($data['price'])){
			$this->db->query("UPDATE " . DB_PREFIX . "tshirtgang_pricing SET price=".(float)$data['price']." WHERE code='". $data['code'] ."'");

			$sql  = "UPDATE " . DB_PREFIX . "product_option_value pov ";
			$sql .= "  LEFT JOIN " . DB_PREFIX . "option                   o   ON   o.option_id       = pov.option_id       ";
			$sql .= "  LEFT JOIN " . DB_PREFIX . "option_description       od  ON  od.option_id       = pov.option_id       ";
			$sql .= "  LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON ovd.option_value_id = pov.option_value_id ";
			$sql .= "SET pov.price='".(float)$data['price']."' ";

			if($data['code'] == 'WhiteShirt'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Color' ";
				$sql .= "AND ovd.name='White' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'ColorShirt'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Color' ";
				$sql .= "AND ovd.name IN ('Black','Charcoal Grey','Daisy','Dark Chocolate','Forest Green','Gold','Irish Green','Light Blue','Light Pink','Military Green','Navy','Orange','Purple','Red','Royal Blue','Sport Grey','Tan','Burgundy') ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'RingerShirt'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Color' ";
				$sql .= "AND ovd.name IN ('Navy Ringer','Black Ringer','Red Ringer') ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'BabyOnePieceIncremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Style' ";
				$sql .= "AND ovd.name = 'Baby One Piece' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'LadiesIncremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Style' ";
				$sql .= "AND ovd.name = 'Ladies' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'MensFittedIncremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Style' ";
				$sql .= "AND ovd.name = 'Mens Fitted' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'HoodieIncremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Style' ";
				$sql .= "AND ovd.name = 'Hooded Pullover' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'ApronIncremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Style' ";
				$sql .= "AND ovd.name = 'Apron' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'VneckIncremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Style' ";
				$sql .= "AND ovd.name = 'Vneck' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'TanktopIncremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Style' ";
				$sql .= "AND ovd.name = 'Tanktop' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'Shirt_2XL_Incremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Size' ";
				$sql .= "AND ovd.name = '2 X-Large' ";
				$this->db->query($sql);
			} elseif ($data['code'] == 'Shirt_3XL6XL_Incremental'){
				$sql .= "WHERE o.type='select' AND od.name='Tshirt Size' ";
				$sql .= "AND ovd.name IN ('3 X-Large','4 X-Large','5 X-Large','6 X-Large') ";
				$this->db->query($sql);
			}
		}
	}
	public function delete($code) {
	}
	
	public function get($data) {
		$returnval = 0;
		$pricing_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tshirtgang_pricing");
		foreach ($query->rows as $row) {
			$pricing_data[$row['code']] = $row;
		}
		if(isset($data['code'])){
			return $pricing_data[$data['code']]['price'];
		} elseif( isset($data['style']) && isset($data['color']) && isset($data['size']) && isset($data['shipping']) ){
			return 300.00;
		} elseif( isset($data['style']) && isset($data['color']) && isset($data['size']) ){
			return 200.00;
		} elseif( isset($data['style']) && isset($data['color']) ){ // assume regular size
			if($data['color'] == "White"){
				$returnval = $pricing_data['WhiteShirt']['price'];
			} elseif(strpos($data['color'], 'Ringer') !== false){
				$returnval = $pricing_data['RingerShirt']['price'];
			} else {
				$returnval = $pricing_data['ColorShirt']['price'];
			}
			
			if($data['style']       == "Mens Fitted"){
				$returnval += $pricing_data['MensFittedIncremental']['price'];
			} elseif($data['style'] == "Ladies"){
				$returnval += $pricing_data['LadiesIncremental']['price'];
			} elseif($data['style'] == "Hooded Pullover"){
				$returnval += $pricing_data['HoodieIncremental']['price'];
			} elseif($data['style'] == "Apron"){
				$returnval += $pricing_data['ApronIncremental']['price'];
			} elseif($data['style'] == "Vneck"){
				$returnval += $pricing_data['VneckIncremental']['price'];
			} elseif($data['style'] == "Tanktop"){
				$returnval += $pricing_data['TanktopIncremental']['price'];
			} elseif($data['style'] == "Baby One Piece"){
				$returnval += $pricing_data['BabyOnePieceIncremental']['price'];
			}
			
			return $returnval;
		} else {
			return 500.00;
		}
	}
	public function getAll() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tshirtgang_pricing");
		foreach ($query->rows as $row) {
			$pricing_data[$row['code']] = $row['price'];
		}
		return $pricing_data;
	}
	public function getAllCodes() {
	}
}
