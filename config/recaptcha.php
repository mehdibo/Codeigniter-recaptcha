<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| reCAPTCHA keys
|--------------------------------------------------------------------------
| You can get a pair of keys by going here: https://www.google.com/recaptcha/admin
| And registering a new website, choose "reCAPTCHA V2"
|
| 'site_key'
|
|	The site key provided by Google
|
| 'secret_key'
|
|	The secret key provided by Google. Make sure you keep it SECRET.
|
|
*/
$config['re_keys'] = array(
	'site_key'		=> '',
	'secret_key'	=> ''
);

/*
|--------------------------------------------------------------------------
| reCAPTCHA parameters
|--------------------------------------------------------------------------
| reCAPTCHA parameters, a table of parameters and values can be found here: https://developers.google.com/recaptcha/docs/display#render_param
| When adding a parameter, omit the "data-" part.
| e.g.,to add the 'data-size' parameter, only add 'size' as the key:
| 'size' => 'compact'
|
*/
$config['re_parameters'] = array(
	'theme'				=> 'light',
);