# Codeigniter-recaptcha
This library makes it easy to use [Google's reCAPTCHA V2](https://developers.google.com/recaptcha/intro)

## Contents

* [Installation](#installation)
  * [Via composer](#via-composer)
  * [Manually](#manually)
* [Documentation](#documentation)
  * [Getting the keys](#getting-the-keys)
  * [Setting the keys](#setting-the-keys)
  * [Loading the library](#loading-the-library)
  * [Setting parameters](#setting-parameters)
  * [Creating the reCAPTCHA box](#creating-the-recaptcha-box)
  * [Validating the reCAPTCHA](#validating-the-recaptcha)
* [Example](#example)
* [Contributing](#contributing)

## Installation

### Via composer
If you have composer installed you can run

```sh
composer require mehdibo/codeigniter-recaptcha
```

Copy the content of [`config/recaptcha.php`](/config/recaptcha.php) to `application/config/recaptcha.php`

**First time using composer**

Open a terminal (commands in windows), the commands here are for linux but you can find the equivalent in windows.

1. First thing you should do is [install composer](https://getcomposer.org/doc/00-intro.md)

2. Go to your application folder: `cd application`

3. Install the library: `composer require mehdibo/codeigniter-recaptcha`

4. Copy the content of [`config/recaptcha.php`](/config/recaptcha.php) to your `application/config/recaptcha.php`

5. Go to `application/config/config.php` and set `composer_autoload` to `TRUE`

6. That's it! check the [Documentation](#documentation) for more details.

### Manually

1. Download the [latest release](https://github.com/mehdibo/Codeigniter-recaptcha/releases).

2. Copy `libraries/Recaptcha.php` to `application/libraries` and `config/recaptcha.php` to `application/config`.

3. Load the library using the Codeigniter loader `$this->load->library('recaptcha')`, check the [example](#example).

4. See the [documentation](#documentation) for usage.

## Documentation

### Getting the keys
To use the reCAPTCHA you need a pair of keys (A secret and site keys), these can be obtained from Google by going to:
https://www.google.com/recaptcha/admin

And registering a new website, make sure you tick the "reCAPTCHA V2" option.

### Setting the keys
There are three ways to pass the keys to the library
  
**In the config file**

You can set the keys by editing the `config/recaptcha.php` config file

**Using the CodeIgniter loader**

By passing an array of configs to the CodeIgniter loader, more details in the "[Loading the library](#loading-the-library)" section.

**Using the `set_keys` method**

You can pass the keys to the `set_keys` methods (after loading the library) like this:

```php
$this->recaptcha->set_keys('site_key', 'secret_key');
```

### Loading the library
You can load the library like any other library:
```php
$this->load->library('recaptcha', $config);
```

Or if installed via composer:
```php
$recaptcha = new Recaptcha($config);
```
And you can access the methods like this:
```php
$recaptcha->method_name();
```


The `$config` argument is *optional*, It can have an array of configs to the library.

`$config` options are:
* `$config['site_key']` - Site key provided by Google
* `$config['secret_key']` - Secret key provided by Google
* `$config['parameters']` - An associative array of parameters and their value, `'parameter-name' => 'value'`, more details about parameters in the "[Setting parameters](#setting-parameters)" section.

### Setting parameters
You can set the parameters ([g-recaptcha tag attributes and grecaptcha.render parameters](https://developers.google.com/recaptcha/docs/display#render_param)) by using the `set_parameter` or `set_parameters` methods.

To set a parameter you can do it by calling:
```php
$this->recaptcha->set_parameter('parameter_name', 'value');
```

Or by passing an array to `set_parameters`:

```php
$this->recaptcha->set_parameters($params);
```

Where `$params` is an associative array of `param_name => value`.

When passing a parameter, omit the `data-` part, for example,
If you want to set the `data-theme` parameter to `dark` you will do it like this:
```php
$this->recaptcha->set_parameter('theme', 'dark');
```

### Creating the reCAPTCHA box
To create the reCAPTCHA box's HTML code call the `create_box` method:

```php
$this->recaptcha->create_box($attributes)
```

This method takes one optional parameter, an array of custom attributes, for example:
```php
$attributes = array(
    'class' => 're-box',
    'id' => 'an-id'
)
```

**Notice:** You need to have the reCAPTCHA JS code included in your code:

```html
<script src='https://www.google.com/recaptcha/api.js'></script>
```

### Validating the reCAPTCHA
The `is_valid` method can be called to verify that the user passed the reCAPTCHA's puzzle.

```php
$this->recaptcha->is_valid($response, $ip)
```
this method takes two optional parameters:

`$response` - the response submitted by the user, set to `NULL` so that it'll be taken automatically from the POST data

`$ip` - the user IP to be sent to Google's server

Set to `FALSE` to not send the IP

Set to `NULL` to get the user's IP automatically
  
And it returns an array:

```
'success' => TRUE if the recaptcha was passed,

'error' => TRUE if there was an error connecting to the server,

'error_message' => If error is true, this contains the message returned by curl,

'challenge_ts' =>  timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)

'hostname' => the hostname of the site where the reCAPTCHA was solved

'error-codes' => error codes returned by Google if there are any
```

## Example
Here is a quick example to use the **Codeigniter-recaptcha** library.

### Installed via composer

**The Controller**
```php
<?php

class Form extends CI_Controller {

	public function index()
	{
		/*
		 Load the reCAPTCHA library.
		 You can pass the keys here by passing an array to the class.
		 Check the "Setting the keys" section for more details
		*/
		$recaptcha = new Recaptcha();

		/*
		 Create the reCAPTCHA box.
		 You can pass an array of attributes to this method.
		 Check the "Creating the reCAPTCHA box" section for more details
		*/
		$box = $recaptcha->create_box();

		// Check if the form is submitted
		if($this->input->post('action') === 'submit')
		{
			/*
			 Check if the reCAPTCHA was solved
			 You can pass arguments to the `is_valid` method,
			 but it should work fine without any.
			 Check the "Validating the reCAPTCHA" section for more details
			*/
			$is_valid =$recaptcha->is_valid();

			if($is_valid['success'])
			{
				echo "reCAPTCHA solved";
			}
			else
			{
				echo "reCAPTCHA not solved/an error occured";
			}
		}

		$this->load->view('form', ['recaptcha' => $box]);
	}
```

### Installed manually

**The Controller**
```php
<?php

class Form extends CI_Controller {

	public function index()
	{
		/*
		 Load the reCAPTCHA library.
		 You can pass the keys here by passing an array to the loader.
		 Check the "Setting the keys" section for more details
		*/
		$this->load->library('recaptcha');

		/*
		 Create the reCAPTCHA box.
		 You can pass an array of attributes to this method.
		 Check the "Creating the reCAPTCHA box" section for more details
		*/
		$recaptcha = $this->recaptcha->create_box();

		// Check if the form is submitted
		if($this->input->post('action') === 'submit')
		{
			/*
			 Check if the reCAPTCHA was solved
			 You can pass arguments to the `is_valid` method,
			 but it should work fine without any.
			 Check the "Validating the reCAPTCHA" section for more details
			*/
			$is_valid = $this->recaptcha->is_valid();

			if($is_valid['success'])
			{
				echo "reCAPTCHA solved";
			}
			else
			{
				echo "reCAPTCHA not solved/an error occured";
			}
		}

		$this->load->view('form', ['recaptcha' => $recaptcha]);
	}
```
---

**The view**

```html
<!DOCTYPE html>
<html>
<head>
	<title>CodeIgniter reCAPTCHA</title>
	<!-- reCAPTCHA JavaScript API -->
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
	<form action="/path/to/controller">
		<?=$recaptcha?>
		<button type="submit" name="action" value="submit">Submit</button>
	</form>
</body>

</html>
```

## Contributing 
All contributions are welcome! Just make sure you read [How to contribute](https://github.com/mehdibo/Codeigniter-recaptcha/blob/master/CONTRIBUTING.md)
