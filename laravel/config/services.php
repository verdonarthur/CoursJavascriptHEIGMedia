<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/
        // GOOGLE console: https://console.developers.google.com
        'google' => [
                'client_id' => '752178833705-eju22f3dknu1oqdkdbjngnfrethvkjkb.apps.googleusercontent.com',
                'client_secret' => 'D07HhwPl1FKp3bCdmicFe4G5',
                'redirect' => 'https://onivers.com/laravel/auth/googleCallback',
        ],

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'secret' => '',
	],

];
