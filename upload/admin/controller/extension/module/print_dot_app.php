<?php
class ControllerExtensionModulePrintDotApp extends Controller {
	private $error = array(); 

	public function index() {   
		$this->load->language('extension/module/print_dot_app');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('print_dot_app', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/print_dot_app', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);
		
		$data['action'] = $this->url->link('extension/module/print_dot_app', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		
		$data['api_label'] = $this->language->get('api_label');
		$data['secret_label'] = $this->language->get('secret_label');
		$data['cat_cust_enable_label'] = $this->language->get('cat_cust_enable_label');
		
		$data['enabled_label'] = $this->language->get('enabled_label');
		$data['disabled_label'] = $this->language->get('disabled_label');
		
		
		$data['button_save'] = $this->language-> get('button_save');
		
		// API Key
		if (isset($this->request->post['print_dot_app_api_value'])) {
			$data['print_dot_app_api_value'] = trim($this->request->post['print_dot_app_api_value']);
		} else {
			$data['print_dot_app_api_value'] = $this->config->get('print_dot_app_api_value');
		}
		
		// Secret Key
		if (isset($this->request->post['print_dot_app_secret_value'])) {
			$data['print_dot_app_secret_value'] = trim($this->request->post['print_dot_app_secret_value']);
		} else {
			$data['print_dot_app_secret_value'] = $this->config->get('print_dot_app_secret_value');
		}
		
		// Category Customization
		if (isset($this->request->post['print_dot_app_cat_cust_enable'])) {
			$data['print_dot_app_cat_cust_enable'] = trim($this->request->post['print_dot_app_cat_cust_enable']);
		} else {
			$data['print_dot_app_cat_cust_enable'] = $this->config->get('print_dot_app_cat_cust_enable');
		}	
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_code'] = $this->language->get('help_code');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language-> get('button_cancel');
		
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
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/print_dot_app', $data));

	}

	public function install() {
		$this->load->model('setting/setting');
		$this->load->model('extension/module/print_dot_app');
		$this->model_extension_module_print_dot_app->install();

		$settings = $this->model_setting_setting->getSetting('print_dot_app');
		$settings['installed'] = 1;
		$settings['print_dot_app_api_value'] = "";
		$settings['print_dot_app_secret_value'] = "";
		
		$this->model_setting_setting->editSetting('print_dot_app', $settings);
	}

	public function uninstall() {
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('print_dot_app');
		$settings['installed'] = 0;
		$this->model_setting_setting->editSetting('print_dot_app', $settings);
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/account')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
}
?>