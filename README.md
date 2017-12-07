# Codeigniter-recaptcha
 A library to implement Google reCAPTCHA V2
 
 https://developers.google.com/recaptcha/intro
## Contents

  * [Getting Started](#getting-started)
  * [Documentation](#documentation)
  
 ## Getting Started

To use this library you need a Site and a Secret key, You can get them from [here](https://www.google.com/recaptcha/admin).

After you have got the keys:

  1. Copy `library/Recaptcha.php` to the `/application/libraries` folder.
  2. Load the library using the Codeigniter loader `$this->load->library('recaptcha' , $config)`, 
  3. See the [documentation](#documentation) for usage.

 ## Documentation
  ### Loading the library
Make sure you followed the steps on [Getting Started](#getting-started) first, after that you can simply load the library using:
`$this->load->library('recaptcha' , $config)`

*optional* `$config` options are:
  * `$config['site_key']` - Site key provided by Google
  * `$config['secret_key']` - Secret key provided by Google
  * `$config['parameters']` - An associative array of parameters and their value, `'parameter-name' => 'value'`
  
  ### Setting secret and site keys
  You can either set the keys by passing the `$config` array to the CodeIgniter loader or by calling the `set_keys` method:
  `$this->recaptcha->set_keys($site_key, $secret_key)`
  
  ### Setting parameters
  You can set parameters by calling the `set_parameter` or `set_parameters` methods,list of available parameters is available here: https://developers.google.com/recaptcha/docs/display#render_param
  
  To set a parameter you can do it by calling:
  
  `$this->recaptcha->set_parameter('parameter-name', 'parameter-value')`
  
  Or by passing an array to `set_parameters`:
  
  `$this->recaptcha->set_parameters(array(
  	'parameter-name' => 'parameter-value'
  ))`
  
  ### Creating the reCAPTCHA box
  To create the reCAPTCHA box's HTML code call the `create_box` method:
  
  `$this->recaptcha->create_box($attributes)`
  
  This method takes one optional parameter, an array of custom attributes, example:
  
  `$attributes = array(
  	'class' => 're-box',
	'id' => 'an-id'
  )
  `
 
  ### Validate the reCaptcha
  The `is_valid` method can be called to verify that the user passed the reCAPTCHA test.
  `$this->recaptcha->is_valid($response, $ip)`
  
  It returns an array:
	
	'success' => TRUE if the recaptcha was passed,
	
	'error' => TRUE if there was an error connecting to the server,
	
	'error_message' => If error is true, this contains the message returned by curl,
	
	'challenge_ts' =>  timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
	
	'hostname' => the hostname of the site where the reCAPTCHA was solved
	
	'error-codes' => error codes returned by Google if there are any
	
  
  this method takes two optional parameters:
  
  `$response` - the response submitted by the user, set to `NULL` so that it'll be taken automatically from the POST data
  
  `$ip` - the user IP to be sent to Google's server
  
  Set to `FALSE` to not send the IP
  
  Set to `NULL` to get the user's IP automatically

## Contributing 
All contributions are welcome!
