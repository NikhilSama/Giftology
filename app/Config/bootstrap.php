<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as 
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, // [optional]
 * 		'mask' => 0666, // [optional] permission mask to use when creating cache files
 *	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *		'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 * Memcache (http://memcached.org/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => true, // [optional] set this to false for non-persistent connections
 * 		'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 *	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Wincache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Redis (http://http://redis.io/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Redis', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *		'server' => '127.0.0.1' // localhost
 *		'port' => 6379 // default port 6379
 *		'timeout' => 0 // timeout in seconds, 0 = unlimited
 *		'persistent' => true, // [optional] set this to false for non-persistent connections
 *	));
 */
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models', '/next/path/to/models'),
 *     'Model/Behavior'            => array('/path/to/behaviors', '/next/path/to/behaviors'),
 *     'Model/Datasource'          => array('/path/to/datasources', '/next/path/to/datasources'),
 *     'Model/Datasource/Database' => array('/path/to/databases', '/next/path/to/database'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions', '/next/path/to/sessions'),
 *     'Controller'                => array('/path/to/controllers', '/next/path/to/controllers'),
 *     'Controller/Component'      => array('/path/to/components', '/next/path/to/components'),
 *     'Controller/Component/Auth' => array('/path/to/auths', '/next/path/to/auths'),
 *     'Controller/Component/Acl'  => array('/path/to/acls', '/next/path/to/acls'),
 *     'View'                      => array('/path/to/views', '/next/path/to/views'),
 *     'View/Helper'               => array('/path/to/helpers', '/next/path/to/helpers'),
 *     'Console'                   => array('/path/to/consoles', '/next/path/to/consoles'),
 *     'Console/Command'           => array('/path/to/commands', '/next/path/to/commands'),
 *     'Console/Command/Task'      => array('/path/to/tasks', '/next/path/to/tasks'),
 *     'Lib'                       => array('/path/to/libs', '/next/path/to/libs'),
 *     'Locale'                    => array('/path/to/locales', '/next/path/to/locales'),
 *     'Vendor'                    => array('/path/to/vendors', '/next/path/to/vendors'),
 *     'Plugin'                    => array('/path/to/plugins', '/next/path/to/plugins'),
 * ));
 *
 */

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */


/**
 * You can attach event listeners to the request lifecyle as Dispatcher Filter . By Default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

CakePlugin::load('Facebook');


// Giftology Constants
define('IMAGE_ROOT', "http://www.giftology.com/img/");
define('UNREGISTERED_GIFT_RECIPIENT_PLACEHODER_USER_ID', 1);

// GIft Statuses
define('GIFT_STATUS_VALID', 1);
define('GIFT_STATUS_EXPIRED', 2);
define('GIFT_STATUS_REDEEMED', 3);
define('GIFT_STATUS_TRANSACTION_PENDING', 4);
define('GIFT_STATUS_SCHEDULED', 5);

// Code Types
define('GENERATED_CODE', 1);
define('UPLOADED_CODE', 2);
define('STATIC_CODE', 1);

//Gender Segments
define('ALL_GENDERS', 1);
define('MALE', 2);
define('FEMALE', 3);

//City Segments
define('ALL_CITIES', 1);

//Age Segments
define('ALL_AGES', 1);

//Transaction Status
define('TX_STATUS_PROCESSING', 1);
define('TX_STATUS_SUCCESS', 2);
define('TX_STATUS_FAIL', 3);
define('TX_STATUS_BATCH', 4);

//CCAV Working Key
define('CCAV_WORKING_KEY', "71bnwtt3evuy3upx8c");
define('CCAV_MERCHANT_ID', "M_dmIndia_13508");

// Product Type
define('SHIPPED',2);
define('DIGITAL',1);

//Campaign Rediredct path
define('CAMPAIGN',2);
define('REMINDER',1);

//code allocation mode

define('GENERAL',1);
define('TEMP_ALLOCATION_CODE_COUNT_RESTRICTED',2);
define('TEMP_ALLOCATION_CODE_COUNT_RESTRICTED_RATE',3);
define('NOT_RESTIRCTED',4);

//Redemption Type
define('ONLINE',0);
define('OFFLINE',1);
define('BOTH',2);
define('SHIPPED_PRODUCT',3);

define('DAILY_MAX_GIFTS_PER_USER', 10);
define('CAROUSEL_CODE', 1);
define('GIFT_CODE_EXPIRY_REMINDER_EMAIL', 'aman.narang@giftology.com');
define('SUSPICIOUS_USER_CHECK', TRUE);
define('MINIMUM_NUMBER_OF_FRIENDS_TO_REDEEM_GIFT', 15);
define('PAID_PRODUCT_DISABLED', TRUE);
define('TYPE_CAMPAIGN', 1);
define('TYPE_CONTEST', 2);
define('SUGGESTED_FRIENDS', TRUE);
define('DEFAULT_EMAIL_VERIFICATION', FALSE);
define('SHOW_HOVER', TRUE);
define('SHOW', TRUE);
define('CITY_SEGMENT_RADIUS', 40); //CITY_SEGMENT_RADIUS in KM.


define('GIFT_REDEEM_WITHOUT_TEMP_GIFT_CODE', FALSE); //old work flow
define('GIFT_REDEEM_WITH_TEMP_GIFT_CODE', TRUE); //new work flow
define('GIFT_CLAIM', TRUE); //new work flow


//Giftology Android App Constants
define('GOOGLE_PLAY_ANDROID_URL', "https://play.google.com/store/apps/details?id=com.giftology");
define('REQUEST_ANDROID_MOBILE_USER_AGENT',"Android");

define('GOOGLE_PLAY_BITLY',"https://bit.ly/GiftologyAndroidApp");


//bitly credentials and URL shortening settings
define('BITLY_CLIENT_ID', '6ae682f18a8e3c7f19ca5e5a5906c3893f9874a5');
define('BITLY_CLIENT_SECRET', 'bcd46f429da48c6edc0e098b9f9b65839f4e3d6f');
define('BITLY_ACCESS_TOKEN_URL', 'https://api-ssl.bitly.com/oauth/access_token');
define('BITLY_USER_NAME', 'giftology');
define('BITLY_PASSWORD', 'giftology010113');
define('BITLY_SHORTEN_URL', 'https://api-ssl.bitly.com/v3/shorten');


//Social Like 

define('SOCIAL_LIKE',TRUE);
define('FB_LIKE',TRUE);
define('TWITTER_LIKE',TRUE);
define('ANDROID_INSTALL',TRUE);
define('GIFT_SENT',TRUE);

define('ENABLE_LOGIN_AFTER_GIFT_SELECTION',TRUE);

//Chat Plugin
define('ZOPIM',FALSE);

//REMINDER EMAIL CONTROL
define('REMINDER_MAIL_SETTING', FALSE);
define('BLACKLISTED_PRODUCT', TRUE);
define('GIFT_TO_MYSELF', TRUE);
define('RESTRICT_GIFTOLOGY_EMPLOYEE_FOR_PRODUCTS', TRUE);

//FEEDBACK
define('FEEDBACK_PLUGIN', FALSE);

//slider landing page
define('ENABLE_SLIDER',TRUE);

CakePlugin::load('Mixpanel');
CakePlugin::load(array('Minify' => array('routes' => true)));
CakePlugin::load('Search');
