<?php

define('PRINT_DOT_APP_RUNTIME_API_URL', 'https://yhlk1004od.execute-api.eu-west-1.amazonaws.com/prod/runtime');

class ControllerExtensionPrintappDesigns extends Controller {

    public function load() {
        $path = isset($this->request->get['path']) ? $this->request->get['path'] : false;
        $authKey = $this->config->get('print_dot_app_secret_value');
		$url = PRINT_DOT_APP_RUNTIME_API_URL.'/designs'.($path ? '/'.$path : '');

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: '.$authKey
		  ),
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
    }
}