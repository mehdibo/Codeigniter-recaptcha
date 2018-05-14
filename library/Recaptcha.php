<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter reCAPTCHA V2 Library
 *
 * A library to implement Google reCAPTCHA V2
 * https://developers.google.com/recaptcha/intro
 *
 * You can get the keys from here:
 * https://www.google.com/recaptcha/admin
 *
 * In the "Choose the type of reCAPTCHA" choose "reCAPTCHA V2"
 *
 * @package Codeigniter-recaptcha
 * @license MIT License
 * @link	https://github.com/mehdibo/Codeigniter-recaptcha
 */

/**
 * Recaptcha class
 *
 * This class contains the methods that you need to create a reCAPTCHA box
 * And validate the response
 *
 * @category   Libraries
 * @package	   CodeIgniter
 * @subpackage Libraries
 * @license	MIT License
 * @link	   https://github.com/mehdibo/Codeigniter-recaptcha
 */
class Recaptcha
{
	/**
	 * Site key provided by Google
	 *
	 * @var string
	 */
	private $_site_key;

	/**
	 * Secret key provided by Google
	 *
	 * @var string
	 */
	private $_secret_key;

	/**
	 * API endpoint
	 *
	 * @var string
	 */
	private $_url = 'https://www.google.com/recaptcha/api/siteverify';

	/**
	 * CI instance
	 *
	 * @var object
	 */
	private $_ci;

	/**
	 * Array of render parameters for the reCAPTCHA
	 * https://developers.google.com/recaptcha/docs/display#render_param
	 *
	 * Parameter			Options
	 * theme				light|dark
	 * type				 image|audio
	 * size				 normal|compact
	 * 
	 * @var array
	 */
	private $_parameters = array(
		'theme'			=> 'light',
		'type'			 => 'image',
		'size'			 => 'normal',
		'tabindex'		 => 0,
		'callback'		 => NULL,
		'expired-callback' => NULL,
	);

	/**
	 * __construct
	 *
	 * @param array $config An array of options
	 *					  'site_key'   => Site key
	 *					  'secret_key' => Secret key
	 *					  'parameters' => array( 'parameter' => value )
	 *
	 * @return void
	 */
	public function __construct($config = NULL)
	{
		// If a parameter was passed
		if ($config !== NULL) {
			// Check if keys were passed
			if ( ! empty($config['site_key']) && ! empty($config['secret_key'])) {
				// Set the keys
				$this->set_keys($config['site_key'], $config['secret_key']);
			}

			if ( ! empty($config['parameters'])) {
				// Pass the parameters
				$this->set_parameters($config['parameters']);
			}
		}

		// Get CodeIgniter instance
		$this->_ci =& get_instance();

		log_message('info', 'reCaptcha Class Initialized');
	}

	/**
	 * Set site and secret keys
	 *
	 * @param string $site   The reCAPTCHA site key
	 * @param string $secret The reCAPTCHA secret key
	 * 
	 * @return void
	 */
	public function set_keys($site, $secret)
	{
		$this->_site_key = $site;
		$this->_secret_key = $secret;
	}

	/**
	 * Set a renderig parameter
	 * Available parameters: https://developers.google.com/recaptcha/docs/display#render_param
	 *
	 * @param string $name  Parameter name
	 * @param mixed  $value Parameter's value
	 * 
	 * @return void
	 */
	public function set_parameter($name, $value)
	{
		$this->_parameters[ $name ] = $value;
	}

	/**
	 * Set multiple parameters
	 * Available parameters: https://developers.google.com/recaptcha/docs/display#render_param
	 *
	 * @param array $array An array of parameters and values, 'parameter_name' => 'value'
	 * 
	 * @return void
	 */
	public function set_parameters($array)
	{
		foreach ($array as $name => $value) {
			$this->set_parameter($name, $value);
		}
	}

	/**
	 * Gets the recaptcha's HTML code
	 *
	 * @param array $attr Array of attributes to add, 'attr' => 'value'
	 *					Ex: 'id' => 'recaptcha-box'
	 *
	 * @return string reCAPTCHA's HTML code
	 */
	public function create_box($attr = NULL)
	{
		// Start creating the box
		$box = '<div';

		// Add the site key
		$box .= ' data-sitekey="'. html_escape($this->_site_key) .'"';

		// Add parameters
		foreach ($this->_parameters as $parameter => $value){
			// Check if the value is not NULL
			if($value !== NULL)
			{
				// Add it to the box
				$box .= ' data-'. html_escape($parameter) .'="'. html_escape($value) .'"';
			}
		}

		// Check if there are attributes passed
		if($attr === NULL){
			// No attributes were passed add the g-recaptcha class
			$box .= ' class="g-recaptcha"';
		}else{
			// Attributes are passed, check if there is a class attribute
			if( empty($attr['class']) ){
				// No class attribute was passed
				// Add the g-recaptcha class
				$attr['class'] = 'g-recaptcha';
			}else{
				// There is a class attribute passed
				// Add g-recaptcha to the previous value
				$attr['class'] .= ' g-recaptcha';
			}

			// Loop through the attributes and add them to the box
			foreach($attr as $attrib => $value){
				$box .= ' '. html_escape($attrib) .'="'. html_escape($value) .'"';
			}
		}

		// Close the box
		$box .= '></div>';

		return $box;
	}

	/**
	 * Checks if the reCAPTCHA puzzle was passed
	 *
	 * @param string $response The g-recaptcha-response submitted by the form
	 * @param string $ip	   User IP to send to Google
	 *						 FALSE  To not send the IP
	 *						 NULL   To get the IP automatically
	 * 
	 * @return array Response returned by Google's server
	 */
	public function is_valid($response = NULL, $ip = FALSE)
	{
		// Prepare post data
		$post_data = array(
			'response' => $response
		);

		// If no response was passed get it from the post data
		if ($response === NULL) {
			$post_data['response'] = $this->_ci->input->post('g-recaptcha-response');
		}

		// If an IP was passed add it to post_data
		if( ! empty($ip) )
		{
			$post_data['remoteip'] = $ip;
		}
		elseif($ip === NULL)
		{
			$post_data['remoteip'] = $this->_ci->input->ip_address();
		}

		// If no response was set return fail
		if( empty($post_data['response']) ){
			return array(
				'success' => FALSE,
			);
		}

		// Pass the secret key
		$post_data['secret'] = $this->_secret_key;

		// Start the request
		$curl = curl_init();

		// Set cURL options
		// Return the response
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

		// Set the URL
		curl_setopt($curl, CURLOPT_URL, $this->_url);

		// Set useragent
		curl_setopt($curl, CURLOPT_USERAGENT, 'CodeIgniter');

		// Send POST data
		curl_setopt($curl, CURLOPT_POST, TRUE);

		// Set POST data
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

		// Stop if an error occurs
		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);

		// Force CURL to verify the certificate
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
		

		// Initiate the request and return the response
		$response = curl_exec($curl);

		// Check if there were any errors
		if($response === FALSE){
			// Log the error
			log_message('error', "reCAPTCHA library: cURL failed with error:". curl_error($curl));

			// Prepare data to return
			$return = array(
				'success' => FALSE,
				'error' => TRUE,
				'error_message' => curl_error($curl)
			);
		}else{
			// Parse the JSON response and prepare to return it
			$return = json_decode($response, TRUE);
		}

		// Close the cURL session
		curl_close($curl);

		return $return;
	}
}
