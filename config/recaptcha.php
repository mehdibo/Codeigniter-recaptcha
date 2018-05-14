<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Trle94
 * Date: 11/05/2018
 * Time: 9:53 PM
 */

// To use reCAPTCHA, you need to sign up for an API key pair for your site.
// link: http://www.google.com/recaptcha/admin
$config['site_key'] = 'INSERT_HERE_YOUR_PUBLIC_KEY';
$config['secret_key'] = 'INSERT_HERE_YOUR_SECRET_KEY';

// reCAPTCHA theme(dark/light):
$config['parameters'] = array( 'theme' => 'light' );


/* End of file recaptcha.php */
/* Location: ./application/config/recaptcha.php */