<?php 
class ModelExtensionModulePrintDotApp extends Model {
	public function install() {
		$qry = $this->db->query("SELECT * from `" . DB_PREFIX . "option` where `type` = 'print_dot_app_web2print'");
		
		if ($qry->num_rows == 0) {
			$query = $this->db->query("INSERT INTO `" . DB_PREFIX . "option` set `type` = 'print_dot_app_web2print', sort_order = 0");
			$lastid = $this->db->getLastId();
			$langset = $this->db->query("SELECT language_id from `" . DB_PREFIX . "language`");
			foreach ($langset->rows as $row){	
				$this->db->query("INSERT INTO " . DB_PREFIX . "option_description(option_id, language_id, name) VALUES(" . $lastid . ", " . $row['language_id'] . ", 'Print.App')");
			}
		}
		$this->setDesignsLoadAjaxPerm();
	}
	
	private function setDesignsLoadAjaxPerm() {
		$perms = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE name = 'Administrator'");
		if ($perms->num_rows == 0) return;
        
        $openPerm = json_decode($perms->row['permission'], true);
        if (!in_array('extension/printapp_designs', $openPerm['access']))
            $openPerm['access'][] = 'extension/printapp_designs';
		
		$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(json_encode($openPerm)) . "' WHERE name = 'Administrator'");
	}
	
	public function getPrintDotAppWeb2Print($pid) {
		$optid = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` WHERE `type` = 'print_dot_app_web2print'");
		if (!isset($optid->row['option_id'])) {
			$this->install();
			$optid = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` WHERE `type` = 'print_dot_app_web2print'");
		}
		$optid = $optid->row['option_id'];
		$opt_qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option WHERE product_id=" . $pid. " and option_id=". $optid);
		return ($opt_qry->num_rows == 1) ? $opt_qry->row['value'] : '';
	}
}
?>