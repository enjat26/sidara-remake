<?php namespace App\Models\Auth;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;

use App\Libraries\Ionix;

use App\Models\NotificationModel;

class LoginModel extends Model
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */

  /**
   * __construct ()
   * --------------------------------------------------------------------
   *
   * Constructor
   *
   * NOTE: Not needed if not setting values or extending a Class.
   *
   */
  public function __construct()
  {
    //--------------------------------------------------------------------
    // Preload any models, libraries, etc, here.
    //--------------------------------------------------------------------
    // E.g.: $this->session = \Config\Services::session();

    // Load the config
    $this->configApp 	        = config('App');
    $this->configIonix 	      = config('Ionix');

    // Load the library
		$this->libIonix 		      = new Ionix();

    // Load Database
		$this->dbDefault 	        = \Config\Database::connect('default');

    // load parameters
    $this->session            = Services::session();
    $this->request 			      = Services::request();
    $this->response 			    = Services::response();
    $this->agent 			        = $this->request->getUserAgent();

    // Load models
    $this->modNotification		= New NotificationModel();
  }

  public function attemptLogin(object $userData)
  {
    if (!password_verify($this->request->getPost('password'), $userData->password)) {
      $output = [
        'message' => requestOutput(401, 'Kata sandi yang Anda gunakan salah atau tidak benar'),
        'insert'  => $this->attemptSetLogin($userData, $userData->name.' gagal login karena kesalahan kata sandi'),
      ];

      return $output['message'];
    }

    if ($userData->active == false) {
      $output = [
        'message' => requestOutput(400, 'Akun Anda telah <strong>dinonaktifkan</strong> atau <strong>dibanned</strong> dari <strong>Sistem</strong> kami'),
        'insert'  => $this->attemptSetLogin($userData, 'Login gagal karena '.$userData->name.' telah dibanned dari sistem'),
      ];

      return $output['message'];
    }

    $output = [
      'insert'   => $this->attemptSetLogin($userData, NULL, true),
      'remember' => $this->attemptSetCookie($userData),
      'session'  => $this->session->set([
                      'isLoggedIn'    => TRUE,
                      'uuid'          => $userData->uuid,
                      'year'          => $this->request->getPost('year'),
                      'notification'  => $this->modNotification->fetchData(['user_id' => $userData->user_id, 'notification_status' => 'unread'])->countAllResults(),
                    ]),
      'flash'    => $this->session->setFlashdata('welcome', 'Selamat datang <strong>'.$userData->name.'</strong>! semoga harimu menyenangkan...'),
      'url'      => panel_url('dashboard'),
    ];

    return requestOutput(200, NULL, $output);
  }

  private function attemptSetLogin(object $userData, string $message = NULL, bool $result = false)
  {
    $data = (object) [
      'clientIPAddress'   => $this->request->getIPAddress(),
      'clientLastLogin'   => $this->libIonix->builderQuery('auth_login')
                                            ->where(['user_id' => $userData->user_id, 'login_success' => true])
                                            ->orderBy('login_created_at', 'DESC')
                                            ->get(1, 0)
                                            ->getRow(),
    ];

    $request = [
      'user_id'               => $userData->user_id,
      'login_ip'              => $data->clientIPAddress,
      'login_success'         => $result,
      'login_message'         => isset($message) ? $message : $userData->name.' telah berhasil login',
      'login_country_code'	  => !$this->request->isValidIP($data->clientIPAddress) ? $this->libIonix->getInfoIPAddress($data->clientIPAddress, 'countrycode') : NULL,
      'login_country'				  => !$this->request->isValidIP($data->clientIPAddress) ? ucwords($this->libIonix->getInfoIPAddress($data->clientIPAddress, 'country')) : NULL,
      'login_location'			  => !$this->request->isValidIP($data->clientIPAddress) ? $this->libIonix->getInfoIPAddress($data->clientIPAddress, 'location')['continent'].'/'.$this->libIonix->getInfoIPAddress($data->clientIPAddress, 'location')['state'] : NULL,
      'login_browser'         => $this->agent->getBrowser().'|'.$this->agent->getVersion(),
      'login_os'              => $this->agent->getPlatform(),
    ];

    $output = [
      'insert' => $this->libIonix->insertQuery('auth_login', $request),
    ];

    return $output;
  }

  private function attemptSetCookie(object $userData)
  {
    if ($this->request->getPost('remember') == 'on' && !get_cookie($this->configIonix->cookieRememberName)) {
      $data = [
        'user_id'        => $userData->user_id,
        'cookie_name'    => $this->configIonix->cookieRememberName,
        'cookie_value'   => random_string('sha1'),
        'cookie_secret'  => $this->libIonix->Encode($this->request->getPost('identity').'|'.$this->request->getPost('password'), true),
        'cookie_expired' => date('Y-m-d H:i:s', time()+$this->configIonix->rememberLength),
      ];

      $output = [
        'insert' => $this->libIonix->insertQuery('auth_cookie', $data),
        'set'    => $this->response->setCookie($data['cookie_name'], $data['cookie_value'], $this->configIonix->rememberLength),
      ];
      return $output;
    }

    if (empty($this->request->getPost('remember'))) {
      return $this->response->deleteCookie($this->configIonix->cookieRememberName);
    }

    return FALSE;
  }

  // -------------------------------------------------------------------

} // End of Name Model Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Auth/LoginModel.php
 * Location: ./app/Models/Auth/LoginModel.php
 * -----------------------------------------------------------------------
 */
