<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;

use App\Libraries\Ionix;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['array', 'cookie', 'date', 'filesystem', 'form', 'html', 'inflector', 'number', 'security', 'text', 'url', 'xml', 'ionix'];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
		// Load the config
		$this->configApp 		= config('App');
		$this->configIonix 	= config('Ionix');

		// Load the library
		$this->libIonix 		= new Ionix();

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();

		// Database
		$this->dbDefault 			= \Config\Database::connect('default');

		// Parameters
		$this->session 				= Services::session();
		$this->security 			= Services::security();
		$this->typography 		= Services::typography();
		$this->request 				= Services::request();
		$this->uri						= $this->request->uri;
		$this->view 					= Services::renderer();
		$this->validation 		= Services::validation();
		$this->email 					= Services::email();
		$this->cache 					= Services::cache();
		$this->curl 					= Services::curlrequest();
		$this->agent 					= $this->request->getUserAgent();
	}

}
