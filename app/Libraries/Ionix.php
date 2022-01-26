<?php namespace App\Libraries;

use Config\Services;

use Michalsn\Uuid\Uuid;
use Hashids\Hashids;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Pusher\Pusher;
use Telegram;

use App\Models\FileModel;

/**
 * Class IonixLibrary
 *
 * @package App\Libraries
 */
class Ionix
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
   protected $helpers = ['array', 'cookie', 'date', 'filesystem', 'form', 'html', 'inflector', 'number', 'security', 'text', 'url', 'xml', 'ionix'];

  // -------------------------------------------------------------------

  /**
   * __construct ()
   * -------------------------------------------------------------------
   *
   * Class    Constructor
   *
   * NOTE: Not needed if not setting values or extending a Class.
   *
   */
  public function __construct()
  {
    // load config
    $this->configApp    = config('App');
    $this->configIonix  = config('Ionix');
    $this->configEmail  = config('Email');

    // load database
    $this->dbDefault    = \Config\Database::connect('default');

    // load parameters
    $this->session      = Services::session();
    $this->security     = Services::security();
    $this->typography   = Services::typography();
    $this->request      = Services::request();
    $this->uri          = $this->request->uri;
    $this->view         = Services::renderer();
    $this->validation   = Services::validation();
    $this->email        = Services::email();
    $this->cache        = Services::cache();
    $this->curl         = Services::curlrequest();
    $this->agent        = $this->request->getUserAgent();

    // load vendor
    $this->uuid					= service('uuid');
    $this->hashids      = New Hashids();
    $this->QRWriter     = New PngWriter();
    $this->telegram     = New Telegram(config('Ionix')->telegramToken);

    // Load model
    $this->modFile 		  = New FileModel();
  }

  public function appInit(array $data = NULL)
  {
    return [
      'dbDefault'     => $this->dbDefault,
      'configApp'     => $this->configApp,
      'configIonix'   => $this->configIonix,
      'libIonix'      => new Ionix,
      'session'       => $this->session,
      'uri'           => $this->uri,
      'request'       => $this->request,
      'title'         => strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$this->getCompanyData()->name,
      'companyData'   => $this->getCompanyData(),
      'userData'      => isLoggedIn() == true ? $this->getUserData(NULL, 'object') : NULL,
      'data'          => $data,
    ];
  }

  // ============================================================================= DEFAULT SQL QUERY

  public function sqlPlatforms()
  {
    return $this->dbDefault->getPlatforms();
  }

  public function sqlVersion()
  {
    return $this->dbDefault->getVersion();
  }

  public function builderQuery(string $table)
  {
    return $this->dbDefault->table($table);
  }

  public function sumQuery(string $table, string $column, array $where = NULL)
  {
    $query = $this->dbDefault->table($table)
                             ->selectSum($column);

    if (isset($where)) {
      $query->where($where);
    }

    return $query->get();
  }

  public function getQuery(string $table, array $join = NULL, array $where = NULL, int $limit = 0, int $offset = 0)
  {
    $query = $this->builderQuery($table);

    if (isset($where)) {
      $query->where($where);
    }

    if (isset($join)) {
      foreach ($join as $key => $value) {
        if (is_array($value)) {
          if (count($value) == 3) {
              $query->join($value[0], $value[1], $value[2]);
          } else {
            foreach ($value as $key1 => $value1) {
              $query->join($key1, $value1);
            }
          }
        } else {
          $query->join($key, $value);
        }
      }
    }

    return $query->get($limit, $offset);
  }

  public function insertQuery(string $table, array $data)
  {
    $query = $this->dbDefault->table($table)->insert($data);

    return $this->dbDefault->insertID();
  }

  public function updateQuery(string $table, array $where = NULL, array $data)
  {
    if (!isset($where)) {
      $query = $this->dbDefault->table($table)->update($data);
    } else {
      $query = $this->dbDefault->table($table)->where($where)->update($data);
    }

    return $query;
  }

  public function deleteQuery(string $table, array $where, $forge = TRUE, string $deletedField = 'deleted_at')
  {
    if ($forge == false) {
      return $this->updateQuery($table, $where, [$deletedField => date('Y-m-d h:m:s')]);
    }

    return $this->dbDefault->table($table)->delete($where);;
  }

  public function emptyTable(string $table)
  {
    return $this->dbDefault->emptyTable($table);
  }

  // ======================================================================= CORE

  public function Encode(string $value, bool $purge = FALSE)
  {
    if (ENVIRONMENT !== 'development' || $purge == true) {
      return rtrim(base64_encode(openssl_encrypt($value, $this->configIonix->encryptionMechanism, hash("sha256",  $this->configIonix->encryptionKey), 0, substr(hash("sha256", $this->configIonix->encryptionIV), 0, 16))), "09==");
    }

    return $value;
  }

  public function Decode(string $value, bool $purge = FALSE)
  {
    $decodeValue = openssl_decrypt(base64_decode($value), $this->configIonix->encryptionMechanism, hash("sha256",  $this->configIonix->encryptionKey), 0, substr(hash("sha256", $this->configIonix->encryptionIV), 0, 16));

    if (ENVIRONMENT !== 'development' || $purge == true) {
      if ($decodeValue == false) {
        return -1;
      }

      return $decodeValue;
    }

    return $value;
  }

  public function EncodeID(int $value)
  {
    return $this->hashids->encode($value);
  }

  public function DecodeID(string $value)
  {
    return $this->hashids->decode($value);
  }

  public function obfuscateEmail(string $email)
  {
    $em   = explode("@",$email);
    $name = implode('@', array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
  }

  public function getInfoIPAddress($ip = NULL, $purpose = "location", $deep_detect = TRUE, $output = NULL)
  {
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @strtolower($ipdat->geoplugin_city);
                    break;
                case "state":
                    $output = @strtolower($ipdat->geoplugin_regionName);
                    break;
                case "region":
                    $output = @strtolower($ipdat->geoplugin_regionName);
                    break;
                case "country":
                    $output = @strtolower($ipdat->geoplugin_countryName);
                    break;
                case "countrycode":
                    $output = @strtolower($ipdat->geoplugin_countryCode);
                    break;
            }
        }
    }

    return $output;
  }

  // ======================================================================= Generate

  public function generateAutoNumber(string $table, string $column, string $prefix, int $length, $zero = '')
  {
    $query = $this->dbDefault->query("SELECT IFNULL(MAX(CONVERT(MID($column, ".(strlen($prefix) + 1).", ".($length - strlen($prefix))."), UNSIGNED INTEGER)),0)+1 AS Num FROM $table WHERE LEFT($column, ".(strlen($prefix)).")='$prefix'")
                             ->getRow();

    $num   = $length - strlen($prefix) - strlen($query->Num);

    for ($i = 0; $i < $num; $i++) {
      $zero = $zero.'0';
    }

    return $prefix.$zero.$query->Num;
  }

  public function generateUUID(string $value = NULL)
  {
    if (isset($this->configIonix->UUID) && isset($value)) {
      return $this->uuid->uuid5($this->configIonix->UUID, $value)->toString();
    }

    return $this->uuid->uuid4()->toString();
  }

  public function generateUniqCode(string $prefix = 'SP')
  {
    for($i = 1; $i < 10; $i++)
    {
      $key = strtoupper(substr(sha1(microtime() . $i), rand(0, 5), 10));
    }

    return $prefix.$key;
  }

  public function generateQRCode(string $data, string $imagePath = NULL, int $imageSize = 60)
  {
    $QRCode = QrCode::create($data)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                    ->setSize(300)
                    ->setMargin(10)
                    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                    ->setForegroundColor(new Color(46, 51, 117))
                    ->setBackgroundColor(new Color(255, 255, 255));

    if (isset($imagePath)) {
      $QRLogo = Logo::create($imagePath)
                    ->setResizeToWidth($imageSize);
    } else {
      $QRLogo = Logo::create($this->configIonix->uploadsFolder['logo'].'qr.png')
                    ->setResizeToWidth($imageSize);
    }

    return $this->QRWriter->write($QRCode, $QRLogo);
  }

  public function generateFileLink(int $value, string $output = 'download')
  {
    $data = [
      'file_id'   => $this->EncodeID($value),
      'expire'    => $this->EncodeID(time()+$this->configIonix->downloadLength),
    ];

    return core_url('file/d/'.$this->Encode(implode('.', $data), true).'/'.$output);
  }

  public function validateFileToken(string $token = NULL)
  {
    $data = (object) [
      'token' => $this->Decode($token, true),
    ];

    if ($data->token < 0) {
      return FALSE;
    }

    $tokenData = (object) [
      'file_id'   => $this->DecodeID(explode('.', $this->Decode($token, true))[0], true)[0],
      'expire'    => $this->DecodeID(explode('.', $this->Decode($token, true))[1], true)[0],
    ];

    if (time() > $tokenData->expire || $this->modFile->fetchData(['file_id' => $tokenData->file_id])->countAllResults() == false) {
      return FALSE;
    }

    return $this->modFile->fetchData(['file_id' => $tokenData->file_id])->get()->getRow();
  }

  // ======================================================================= GET DATA

  public function getCompanyData()
  {
    $join = [
      'villages'        => 'villages.village_id = company.village_id',
      'sub_districts'   => 'sub_districts.sub_district_id = company.sub_district_id',
      'districts'       => 'districts.district_id = company.district_id',
      'provinces'       => 'provinces.province_id = company.province_id',
      'countrys'        => 'countrys.country_id = company.country_id',
    ];

    return $this->getQuery('company', $join)->getRow();
  }

  public function getUserData(array $params = NULL, string $output = NULL)
  {
    $join = [
      'user_info'     => 'user_info.user_id = users.user_id',
      'roles'         => 'roles.role_code = users.role_code',
    ];

    if ($output == 'object') {
      if (isset($params)) {
        return $this->getQuery('users', $join, $params)->getRow();
      }

      return $this->getQuery('users', $join, ['uuid' => $this->session->uuid])->getRow();
    }

    if ($output == 'array') {
      if (isset($params)) {
        return $this->getQuery('users', $join, $params)->getRowArray();
      }

      return $this->getQuery('users', $join, ['uuid' => $this->session->uuid])->getRowArray();
    }

    if (isset($params)) {
      return $this->getQuery('users', $join, $params);
    }

    return $this->getQuery('users', $join, ['uuid' => $this->session->uuid]);
  }

  // ============================================================================= EMAIL

  public function sendEmail(array $data, $attachment = NULL)
  {
    $this->email->setFrom($this->configEmail->fromEmail, strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType))
                ->setTo($data['to'])
                ->setSubject($data['subject'])
                ->setMessage($data['body']);

    if (isset($attachment)) {
      foreach ($attachment as $row) {
        $this->email->attach($this->configIonix->uploadsFolder['attachment'].$row->file_source, $row->file_type, $row->file_name.'.'.$row->file_extension);
      }
    }

    if (!$this->email->send()) {
      return (object) [
        'status'  => FALSE,
        'message' => requestOutput(500, $this->email->printDebugger(['headers'])),
        'debug'   => $this->email->printDebugger(['headers']),
      ];
    }

    return (object) [
      'status'  => TRUE,
      'message' => requestOutput(200),
      'debug'   => $this->email->printDebugger(['headers']),
    ];
  }

  // ======================================================================= Notification

  public function pushNotification(string $message = NULL)
  {
    if ($this->configIonix->notificationRealtime == true) {
      try {
        $options = array(
          'cluster' => $this->configIonix->pusherAppCluster,
          'useTLS'  => $this->configIonix->pusherAppTLS,
        );

        $pusher = new Pusher(
          $this->configIonix->pusherAppKey,
          $this->configIonix->pusherAppSecret,
          $this->configIonix->pusherAppId,
          $options
        );

        return $pusher->trigger($this->configIonix->appCode, 'notification', NULL);
      } catch (\Exception $e) {
        return FALSE;
      }
    }

    return FALSE;
  }

  public function pushDirectTelegram(int $user_id, string $message = NULL)
  {
    $query = (object) [
      'telegram'    => $this->builderQuery('notification_telegrams')->where(['user_id' => $user_id, 'notification_telegram_token' => NULL, 'notification_telegram_pair' => true])->orderBy('notification_telegram_id', 'DESC'),
    ];

    try {
      if ($this->configIonix->notificationTelegram == true && $query->telegram->countAllResults() != false) {
        $output = $this->telegram->sendMessage([
          'chat_id'       => $query->telegram->get(1)->getRow()->notification_telegram_chat_id,
          'text'          => $message,
        ]);

        return $output;
      }

      return FALSE;
    } catch (\Exception $e) {
      return FALSE;
    }
  }

  public function pushGroupTelegram(string $message = NULL)
  {
    try {
      if ($this->configIonix->notificationTelegram == true && isset($this->configIonix->telegramGroupId)) {
        $messageData  = $message . PHP_EOL . PHP_EOL;
        $messageData .= $this->getUserData(NULL, 'object')->name . PHP_EOL;
        $messageData .= parseJobPosition($this->getUserData(NULL, 'object'));

        $output = $this->telegram->sendMessage([
          'chat_id'       => $this->configIonix->telegramGroupId,
          'text'          => $messageData,
        ]);

        return $output;
      }

      return FALSE;
    } catch (\Exception $e) {
      return FALSE;
    }
  }

  // public function pushTelegramFile($file = NULL)
  // {
  //   if ($this->configIonix->notificationTelegram == true) {
  //     $output = $this->telegram->sendPhoto([
  //       'chat_id'       => $this->configIonix->telegramId,
  //       'photo'         => curl_file_create($file, $file->getClientMimeType()),
  //     ]);
  //
  //     return $output;
  //   }
  //
  //   return FALSE;
  // }

  // -------------------------------------------------------------------

}   // End of Name Library Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Ionix.php
 * Location: ./app/Libraries/Ionix.php
 * -----------------------------------------------------------------------
 */
