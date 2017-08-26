<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter reCAPTCHA V2 Class
 *
 * Small library to use the Google reCAPTCHA V2 API
 *
 * @author		Mehdi Bounya
 * @link		https://github.com/mehdibo/Codeigniter-recaptcha/
 */

class Recaptcha{
	protected $site_key = NULL; // Site key given by google
	protected $secret_key = NULL; // Secret key given by google
	protected $CI; // CodeIgniter instance
	protected $options=array( // g-recaptcha tag attributes and grecaptcha.render parameters, DOC: https://developers.google.com/recaptcha/docs/display#render_param
		'theme'=>NULL, // dark|light
		'type'=>NULL, // audio|image
		'size'=>NULL, // compact|normal
		'tabindex'=>NULL,
		'callback'=>NULL,
		'expired-callback'=>NULL,
		);

	public function __construct($config)
	{
		// Set config values
		$this->site_key = (!empty($config['site_key'])) ? $config['site_key'] : $this->site_key;
		$this->secret_key = (!empty($config['secret_key'])) ? $config['secret_key'] : $this->secret_key;
		$this->CI =& get_instance(); // Get CI instance
		
		// Set options
		foreach($this->options as $key=>$value){
			$this->options[$key]=( !empty($config['options'][$key]) )? $config['options'][$key] : NULL;
		}
		
		// Check if the keys are set
		if (empty($this->site_key) || empty($this->secret_key)) {
			show_error('reCAPTCHA: please set both the site key and the secret key.');
			log_message('error', "reCAPTCHA: the keys are not there !.");
		}
		log_message('info', 'reCaptcha Class Initialized');

	 }

	// Create the reCaptcha box
	public function create_box($attributes=NULL)
 	{
		$attribs="";
		// Get attributes, as $attribute=>$value, or a signle inline string
		if(!empty($attributes)){
			// If it's an array
			if(is_array($attributes)){
				// Check if there is a class attribute
				if(!empty($attributes['class'])){
					// If TRUE, add g-recaptcha to it
					$attributes['class'].=" g-recaptcha";
				}else{
					// Else, add a g-recaptcha class
					$attributes['class']="g-recaptcha";
				}
				foreach($attributes as $attribute=>$value){
					$attribs.=$attribute."='".$value."' ";
				}
			}else{
				$attribs="class='g-recaptcha' ".$attributes;
			}
		}else{
			$attribs="class='g-recaptcha'";
		}
		// Set options
		foreach($this->options as $key=>$value){
			$attribs.=(!empty($value)) ? ' data-'.$key.'="'.$value.'"' : '';
		}
		return '<div '.$attribs.' data-sitekey="'.$this->site_key.'"></div>';
	}


	public function is_valid(){
		$response=$this->CI->input->post('g-recaptcha-response');
		$ip=$this->CI->input->ip_address();

		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
				CURLOPT_USERAGENT => 'CodeIgniter',
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'secret' => $this->secret_key,
					'response' => $response,
				)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);

		// Close request to clear up some resources
		curl_close($curl);

		// Decode the response
		$resp=json_decode($resp, TRUE);

		// If success return TRUE
		return ($resp['success']) ? TRUE : FALSE;
	}
}
