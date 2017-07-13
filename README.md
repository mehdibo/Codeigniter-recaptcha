# Codeigniter-recaptcha
Small library to use the Google reCAPTCHA V2 API
## Contents

  * [Getting Started](#getting-started)
  * [Documentation](#documentation)
  
 ## Getting Started

To use this library you need a Site and a Secret key, You can obtain them from here [Google reCAPTCHA](https://www.google.com/recaptcha/admin).

Start by completing the following steps:

  1. Copy `Recaptcha.php` to the `/application/libraries` folder.
  2. Load the library using the Codeigniter loader `$this->load->library('recaptcha' , $config)`, 
  3. See the [documentation](#documentation) for usage.
	
To use this class outside Codeigniter just remove the following line found in the top:

 ## Documentation
  ### Loading the library
Make sure you followed the steps on [Getting Started](#getting-started) first, after that you can simply load the library using:
`$this->load->library('blockchain' , $config)`

`$config` options are:
  * `$config['site_key']` - Site key provided by Google
  * `$config['secret_key']` - Secret key provided by google
  * `$config['options']` - Associative array with the following options:

		'theme' -  The color theme of the widget. light|dark
		
		'type' -  The type of CAPTCHA to serve. image|audio
		
		'size' -  The size of the widget. normal|compact
		
		'tabindex' -  The tabindex of the widget and challenge. If other elements in your page use tabindex, it should be set to make user navigation easier.
		
		'callback' -  The name of your callback function to be executed when the user submits a successful CAPTCHA response. The user's response, g-recaptcha-response, will be the input for your callback function.
		
		'expired-callback' -  The name of your callback function to be executed when the recaptcha response expires and the user needs to solve a new CAPTCHA.
  
  ### Create box
`$this->recaptcha->create_box($attributes)`
Returns the box's HTML code.

You can set additional attributes, two ways to do so:
	* Passing a string: `id="some_id" onclick="function()"`
	* Associative array: `array('id'=>'some_id', 'onclick'=>'function()')`
 
  ### Validate the reCaptcha
`$this->recaptcha->is_valid()`
Returns TRUE in success, FALSE in failure

Use in the submit page.
