<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
//			'dsn'        => 'mysql:host=localhost;dbname=fuel_dev',
//			'username'   => 'root',
//			'password'   => 'root',

            'hostname'   => 'localhost',
            'port'       => '3306',
            'database'   => 'fuel_dev',
            'username'   => 'username',
            'password'   => 'password',
        ),
        'profiling' => true,
	),
);
