<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Ionix extends BaseConfig
{
	/**
	 * --------------------------------------------------------------------
	 * Default Application Name
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $appCode			= 'ionix';
	public $appType			= NULL;
	public $appVersion 	= '1.0';

	/**
	 * --------------------------------------------------------------------
	 * Font-End Page
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $homePage		= FALSE;

	/**
	 * --------------------------------------------------------------------
	 * Folder used for Application Templating
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $assetsFolder = [
		'local'				=> BASE . PUBLICURL . 'assets/',
		'pages'				=> [
			'stylesheet'	=> BASE . PUBLICURL . 'template/prt/css/',
			'javascript'	=> BASE . PUBLICURL . 'template/prt/js/',
			'library'			=> BASE . PUBLICURL . 'template/prt/libs/',
		],
		'auth'				=> [
			'stylesheet'	=> BASE . PUBLICURL . 'template/skt/css/',
			'javascript'	=> BASE . PUBLICURL . 'template/skt/js/',
			'library'			=> BASE . PUBLICURL . 'template/skt/libs/',
		],
		'panel'				=> [
			'stylesheet'	=> BASE . PUBLICURL . 'template/skt/css/',
			'javascript'	=> BASE . PUBLICURL . 'template/skt/js/',
			'library'			=> BASE . PUBLICURL . 'template/skt/libs/',
		],
	];

	/**
	 * --------------------------------------------------------------------
	 * Folder used by Application Templating
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $uploadsFolder = [
		'main' 					=> WRITEPATH . 'uploads/',
		'background'		=> WRITEPATH . 'uploads/image/background/',
		'logo'					=> WRITEPATH . 'uploads/image/logo/',
		'content'				=> WRITEPATH . 'uploads/image/content/',
		'flag'					=> FCPATH . 'public/media/image/flags/',
		'user'					=> WRITEPATH . 'uploads/content/users/',
		'gallery'				=> WRITEPATH . 'uploads/content/gallerys/',
		'format' 				=> WRITEPATH . 'uploads/drive/formats/',
		'attachment' 		=> WRITEPATH . 'uploads/drive/attachments/',
	];

	/**
	 * --------------------------------------------------------------------
	 * Media used by Application Resource
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $mediaFolder = [
		'default' => BASE . PUBLICURL . 'media/',
		'audio' 	=> BASE . PUBLICURL . 'media/audio/',
		'image' 	=> BASE . PUBLICURL . 'media/image/',
		'video' 	=> BASE . PUBLICURL . 'media/video/',
	];

	/**
	 * --------------------------------------------------------------------
	 * Media used by Application Resource
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $appLogo = [
		'square_light' 		=> BASE . '/image/logo/square-light.png',
		'square_dark' 		=> BASE . '/image/logo/square-dark.png',
		'landscape_light' => BASE . '/image/logo/landscape-light.png',
		'landscape_dark' 	=> BASE . '/image/logo/landscape-dark.png',
    'qr' 	            => BASE . '/image/logo/qr.png',
	];

	/**
	 * --------------------------------------------------------------------
	 * Layout for the views to extend
	 * --------------------------------------------------------------------
	 *
	 * @var array|string
	 */
	public $viewLayout	= [
			'pages'		=> 'layouts/pages',
			'auth'		=> 'layouts/auth',
			'panel'		=> 'layouts/panel',
			'print'		=> 'layouts/print',
	];

	public $viewHeader			= 'layouts/header';
	public $viewSidebar			= 'layouts/sidebar';
	public $viewCustomizer	= 'layouts/customizer';

	/**
	 * --------------------------------------------------------------------
	 * Views used by Portal Controllers
	 * --------------------------------------------------------------------
	 *
	 * @var array
	 */
	public $viewAuth = [
    'login'				=> 'auth/login',
    'register'		=> 'auth/register',
    'forgot'			=> 'auth/forgot',
    'recovery'		=> 'auth/recovery',
		'email'					=> [
			'forgot'		=> 'auth/email/forgot',
		],
	];

	/**
	 * --------------------------------------------------------------------
	 * Description Configuration
	 * --------------------------------------------------------------------
	 *
	 * @var bool
	 */
	public $viewCopyright 		= FALSE;
	public $viewVersion 			= FALSE;
	public $viewEnvironment 	= FALSE;

	/**
	 * --------------------------------------------------------------------
	 * Allow Persistent
	 * --------------------------------------------------------------------
	 *
	 * @var bool
	 */
	public $allowRegistration = FALSE;
	public $allowForgot 			= FALSE;
	public $allowRemembering 	= FALSE;
	public $allowQRCode			 	= FALSE;

	/**
	 * --------------------------------------------------------------------
	 * Namespace
	 * --------------------------------------------------------------------
	 *
	 * @var string
	 */
	public $cookieRememberName 	= 'ci_remember';
	public $colorPrimary 		 	 	= 'primary';
	public $colorPrimaryCSS  	 	= '#8833ff';

	/**
	 * --------------------------------------------------------------------
	 * Filters
	 * --------------------------------------------------------------------
	 *
	 * @var array|string
	 */
	public $blockedUsername 			= ['system', 'test', 'dev', 'developer'];
	public $excludeUriPermission 	= ['profile', 'u', 'areas'];
	public $defaultRole						= 'gss';
	public $defaultWorkunit				= 'gss';

	/**
	 * --------------------------------------------------------------------
	 * Minimum Length
	 * --------------------------------------------------------------------
	 *
	 * @var int
	 */
	public $minimumUsernameLength = 0;
	public $maximumUsernameLength = 0;
	public $minimumPasswordLength = 0;
	public $rememberLength 				= 7200;
	public $recoveryLength 				= 7200;
	public $downloadLength				= 7200;
	public $printLength						= 7200;
	public $QRSize								= 70;

	/**
	 * --------------------------------------------------------------------
	 * Maximum File Size
	 * --------------------------------------------------------------------
	 *
	 * @var array
	 */
	 public $maximumSize = [
													 'image'	=> '5M',
													 'file'		=> '10M',
													 'video'	=> '500M',
												 ];

	/**
	 * --------------------------------------------------------------------
	 * Security & Encryption Algorithm Configuration
	 * --------------------------------------------------------------------
	 *
	 * @var string|int
	 */
	public $UUID								= '3c4595c9-1616-4643-bc1b-3580053ad1c5';
	public $encryptionKey 			= 'c2ceceff73a4267c8244578658d1d9de';
	public $encryptionMechanism = 'aes-256-cbc';
	public $encryptionIV 				= '2456378434711431';

	/**
	 * --------------------------------------------------------------------
	 * Key & Default Password
	 * --------------------------------------------------------------------
	 *
	 * @var string|int|bool
	 */
	public $rolePermission 		= TRUE;
	public $roleController		= 0;
  public $passwordDefault 	= 123456;
	public $hashAlgorithm 		= PASSWORD_DEFAULT;
	public $soonPeriode				= '2021/12/31';
	public $allowVerifycation	= FALSE;
	public $defaultProvince		= 0;

	/**
	 * --------------------------------------------------------------------
	 * Notifications & Pusher
	 * --------------------------------------------------------------------
	 *
	 * @var bool|string
	 */
	public $notificationTone 			= FALSE;
	public $notificationRealtime 	= FALSE;
	public $notificationTelegram 	= FALSE;

	public $pusherAppId           = NULL;
	public $pusherAppKey          = NULL;
	public $pusherAppSecret       = NULL;
	public $pusherAppCluster      = NULL;
	public $pusherAppTLS          = TRUE;

	public $telegramBot						= NULL;
	public $telegramGroupId				= NULL;
	public $telegramGroupLink			= NULL;
	public $telegramToken					= NULL;

}
