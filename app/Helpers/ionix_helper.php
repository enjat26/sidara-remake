<?php

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use App\Libraries\Ionix;

use App\Models\Area\CountryModel;
use App\Models\Area\DistrictModel;
use App\Models\Area\ProvinceModel;
use App\Models\Area\SubDistrictModel;
use App\Models\Area\VillageModel;

use Moment\Moment;

Moment::setLocale('id_ID');

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * CodeIgniter Array Helpers
 */

 if (! function_exists('isLoggedIn'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function isLoggedIn()
   {
     // load parameters
     $session  = Services::session();

     if ($session->isLoggedIn == true) {
       return TRUE;
     }

     return FALSE;
   }
 }

 if (! function_exists('siteStatus'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function siteStatus()
   {
     if (ENVIRONMENT == 'soon') {
  			throw \CodeIgniter\Exceptions\PageNotImplementedException::forPageComingSoon();
  	 }

     if (ENVIRONMENT == 'maintenance') {
  			throw \CodeIgniter\Exceptions\PageServiceUnavaibleException::forPageMaintenance();
  	 }

     if (isLoggedIn() == true) {
       return redirect()->to(panel_url('dashboard'));
     }
   }
 }

 if (! function_exists('rolePermission'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function rolePermission()
   {
     // load library
     $libIonix = new Ionix();

     if ($libIonix->getQuery('menu_page', NULL, ['menu_link' => uri_segment(1)])->getNumRows() == true) {
       if ($libIonix->getQuery('menu_access', NULL, ['menu_id' => $libIonix->getQuery('menu_page', NULL, ['menu_link' => uri_segment(1)])->getRow()->menu_id, 'role_access' => $libIonix->getUserData(NULL, 'object')->role_access])->getNumRows() == true) {
         return TRUE;
       }
     }

     if (in_array(uri_segment(1), config('ionix')->excludeUriPermission)) {
       return TRUE;
     }

     if (config('ionix')->rolePermission == true) {
       throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
     }
   }
 }

 if (! function_exists('protectAJAXRequest'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function protectAJAXRequest()
   {
     // load parameters
     $request  = Services::request();

     if (ENVIRONMENT !== 'development' && $request->isAJAX() == false) {
       throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
     }

     return TRUE;
   }
 }

 if (! function_exists('core_url'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function core_url(string $url = NULL)
   {
      return BASE . PUBLICURL . $url;
   }
 }

 if (! function_exists('panel_url'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function panel_url(string $url = NULL)
   {
     if (config('Ionix')->homePage == true) {
       return core_url('panel/'.$url);
     }

      return core_url($url);
   }
 }

 if (! function_exists('uri_segment'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function uri_segment(int $value = NULL)
   {
     // load parameters
     $request  = Services::request();
     $uri      = $request->uri;

     if (config('Ionix')->homePage == true) {
       return $uri->getSegment($value+1);
     }

      return $uri->getSegment($value);
   }
 }

 if (! function_exists('customFormOpen'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function customFormOpen(string $id, string $class = '')
   {
     return '<form id="form-'.$id.'" class="needs-validation '.$class.'" action="javascript:void(0);" method="POST" novalidate>';
   }
 }

 if (! function_exists('customFormClose'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function customFormClose()
   {
     return '</form>';
   }
 }

 if (! function_exists('inputIdentity'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function inputIdentity(bool $purge = TRUE)
   {
     // load library
     $libIonix = new Ionix();

     $data = (object) [
       'cookie' => $libIonix->getQuery('auth_cookie', NULL, ['cookie_value' => get_cookie(config('ionix')->cookieRememberName)])
                            ->getRow(),
     ];

     if (config('Ionix')->allowRemembering === true && $purge == true && $data->cookie && $data->cookie->cookie_expired > date('Y-m-d H:i:s')) {
       return '<label for="identity">Username atau Email</label>
              <input type="text" name="identity" class="form-control" placeholder="Masukan username atau email" value="'.explode('|', $libIonix->Decode($data->cookie->cookie_secret, true))[0].'" required>';
     }

     if (ENVIRONMENT == 'demo') {
       return '<label for="identity">Username atau Email</label>
               <input type="text" name="identity" class="form-control" placeholder="Masukan username atau email" value="admin" required autofocus>';
     }

     return '<label for="identity">Username atau Email</label>
             <input type="text" name="identity" class="form-control" placeholder="Masukan username atau email" required autofocus>';
   }
 }

 if (! function_exists('inputPassword'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function inputPassword(bool $purge = TRUE, bool $forgot = TRUE)
   {
     // load library
     $libIonix = new Ionix();

     $data = (object) [
       'cookie' => $libIonix->getQuery('auth_cookie', NULL, ['cookie_value' => get_cookie(config('ionix')->cookieRememberName)])
                            ->getRow(),
     ];

     if (config('Ionix')->allowForgot === true && $forgot === true) {
       $forgotURL = '<div class="float-end">
                       <a href="'.core_url('forgot').'" class="text-small" tabindex="-1">Lupa Kata Sandi?</a>
                     </div>';
     } else {
       $forgotURL = '';
     }

     if (config('Ionix')->allowRemembering === true && $purge == true && $data->cookie && $data->cookie->cookie_expired > date('Y-m-d H:i:s')) {
       return '<div class="d-block">
                 <label for="password">Kata sandi</label>
                 '.$forgotURL.'
               </div>
               <div class="input-group">
                   <input type="password" class="form-control" name="password" placeholder="Masukan kata sandi" minlength="'.config('ionix')->minimumPasswordLength.'" value="'.explode('|', $libIonix->Decode($data->cookie->cookie_secret, true))[1].'" aria-label="Password" aria-describedby="password-show" autocomplete="off" required>
                   <button id="password-show" type="button" class="btn btn-light" tabindex="-1"><i class="mdi mdi-eye-outline"></i></button>
               </div>';
     }

     if (ENVIRONMENT == 'demo') {
       if ($purge == true) {
         return '<div class="d-block">
                   <label for="password">Kata sandi</label>
                   '.$forgotURL.'
                 </div>
                 <div class="input-group">
                     <input type="password" class="form-control" name="password" placeholder="Masukan kata sandi" value="'.config('ionix')->passwordDefault.'" minlength="'.config('ionix')->minimumPasswordLength.'" aria-label="Password" aria-describedby="password-show" autocomplete="off" required>
                     <button id="password-show" type="button" class="btn btn-light" tabindex="-1"><i class="mdi mdi-eye-outline"></i></button>
                 </div>';
       } else {
         return '<div class="d-block">
                   <label for="password">Kata sandi</label>
                   '.$forgotURL.'
                 </div>
                 <div class="input-group">
                     <input type="password" class="form-control" name="password" placeholder="Masukan kata sandi" minlength="'.config('ionix')->minimumPasswordLength.'" aria-label="Password" aria-describedby="password-show" autocomplete="off" required>
                     <button id="password-show" type="button" class="btn btn-light" tabindex="-1"><i class="mdi mdi-eye-outline"></i></button>
                 </div>';
       }
     }

     return '<div class="d-block">
               <label for="password">Kata sandi</label>
               '.$forgotURL.'
             </div>
             <div class="input-group">
                 <input type="password" class="form-control" name="password" placeholder="Masukan kata sandi" minlength="'.config('ionix')->minimumPasswordLength.'" aria-label="Password" aria-describedby="password-show" autocomplete="off" required>
                 <button id="password-show" type="button" class="btn btn-light" tabindex="-1"><i class="mdi mdi-eye-outline"></i></button>
             </div>';
   }
 }

 if (! function_exists('inputPasswordConfirmation'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function inputPasswordConfirmation()
   {
     return '<label for="repassword">Ulangi Kata Sandi</label>
             <div class="input-group">
                 <input type="password" class="form-control" name="repassword" placeholder="Ketik ulang kata sandi" minlength="'.config('ionix')->minimumPasswordLength.'" aria-label="Password" aria-describedby="repassword-show" autocomplete="off" required>
                 <button id="repassword-show" type="button" class="btn btn-light" tabindex="-1"><i class="mdi mdi-eye-outline"></i></button>
             </div>';
   }
 }

 if (! function_exists('inputRememberMe'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function inputRememberMe(bool $purge = TRUE)
   {
     // load library
     $libIonix = new Ionix();

     $data = (object) [
       'cookie' => $libIonix->getQuery('auth_cookie', NULL, ['cookie_value' => get_cookie(config('ionix')->cookieRememberName)])
                            ->getRow(),
     ];

     if (config('Ionix')->allowRemembering === true && $purge == true && $data->cookie && $data->cookie->cookie_expired > date('Y-m-d H:i:s')) {
       return '<div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember" checked>
                  <label class="form-check-label" for="remember">Ingatkan saya</label>
               </div>';
     }

     return '<div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingatkan saya</label>
             </div>';
   }
 }

 if (! function_exists('checkedStatus'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function toggleStatus(bool $value)
   {
     if ($value == true) {
       return 'checked';
     }

     return '';
   }
 }

 if (! function_exists('regexIdentity'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function regexIdentity(string $value = null)
   {
     if (regexUsername($value) == true) {
       return (object) [
         'status'   => TRUE,
         'message'  => $value.' teridentifikasi sebagai Username',
         'scope'    => 'username',
         'caption'  => 'Username',
         'rules'    => 'required',
       ];
     } elseif (regexEmail($value) == true) {
       return (object) [
         'status'   => TRUE,
         'message'  => $value.' teridentifikasi sebagai Email',
         'scope'    => 'email',
         'caption'  => 'Email',
         'rules'    => 'required|valid_email[identity]',
       ];
     } else {
       return (object) [
         'status'   => FALSE,
         'message'  => $value.' tidak teridentifikasi',
         'scope'    => NULL,
         'caption'  => NULL,
         'rules'    => 'required',
       ];
     }
   }
 }

 if (! function_exists('regexUsername'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function regexUsername(string $value = null): bool
   {
     // @see https://regex101.com/r/0AZDME/1
     return (bool) preg_match('/^[a-z\d_]{'.config('Ionix')->minimumUsernameLength.','.config('Ionix')->maximumUsernameLength.'}$/i', $value);
   }
 }

 if (! function_exists('regexEmail'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function regexEmail(string $value = null): bool
   {
     // @see https://regex101.com/r/wlJG1t/1/
     if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46') && preg_match('#\A([^@]+)@(.+)\z#', $value, $matches)) {
       $value = $matches[1] . '@' . idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46);
     }

     return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
   }
 }

 if (! function_exists('regexNumeric'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function regexNumeric(string $str = null): bool
   {
     // @see https://regex101.com/r/bb9wtr/1
     return (bool) preg_match('/\A[\-+]?[0-9]*\.?[0-9]+\z/', $str);
   }
 }

 if (! function_exists('regexPassword'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function regexPassword($request)
   {
     if (preg_match('#[0-9]#', $request) && preg_match('#[a-z]#', $request) && preg_match('#[A-Z]#', $request)) {
       return TRUE;
     }

     return FALSE;
   }
 }

 if (! function_exists('parseFullName'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseFullName(string $value, int $level = NULL, int $font = 18)
   {
     if (isset($level)) {
       return $value.' '.parseRoleIcon($level, $font);
     }

     return $value;
   }
 }

 if (! function_exists('parseJobPosition'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseJobPosition(object $userData)
   {
     if ($userData->role_access <= config('Ionix')->roleOfficial) {
       return $userData->role_name;
     }

     return $userData->role_name;
   }
 }

 if (! function_exists('parseAddress'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseAddress(object $data, bool $output = true, bool $country = true)
   {
     // load Model
     $modCountry      = new CountryModel;
     $modDistrict     = new DistrictModel;
     $modProvince     = new ProvinceModel;
     $modSubdistrict  = new SubdistrictModel;
     $modVillage      = new VillageModel;

     if (isset($data->address)) {
       $addressData = $data->address;
     } else {
       $addressData = '-';
     }

     if ($country == true && isset($data->country_id)) {
       if (file_exists(config('Ionix')->uploadsFolder['flag'].$modCountry->fetchData(['countrys.country_id' => $data->country_id])->get()->getRow()->country_iso3.'.jpg')) {
 				 $flagImage = config('Ionix')->mediaFolder['image'].'flags/'.$modCountry->fetchData(['countrys.country_id' => $data->country_id])->get()->getRow()->country_iso3.'.jpg';
 			 } else {
 				 $flagImage = config('Ionix')->mediaFolder['image'].'default/country-iso3.jpg';
 			 }

       $countryData = '<br/><img src="'.$flagImage.'" alt="" class="rounded" height="20"> '.$modCountry->fetchData(['countrys.country_id' => $data->country_id])->get()->getRow()->country_name;
     } else {
       $countryData = '';
     }

     if (isset($data->province_id)) {
       $provinceData = $modProvince->fetchData(['provinces.province_id' => $data->province_id])->get()->getRow()->province_name;
     } else {
       $provinceData = '-';
     }

     if (isset($data->district_id)) {
       $districtData = $modDistrict->fetchData(['districts.district_id' => $data->district_id])->get()->getRow()->district_type.' '.$modVillage->fetchData(['districts.district_id' => $data->district_id])->get()->getRow()->district_name;
     } else {
       $districtData = '-';
     }

     if (isset($data->sub_district_id)) {
       $subdistrictData = $modSubdistrict->fetchData(['sub_districts.sub_district_id' => $data->sub_district_id])->get()->getRow()->sub_district_name;
     } else {
       $subdistrictData = '-';
     }

     if (isset($data->village_id)) {
       $villageData = $modVillage->fetchData(['village_id' => $data->village_id])->get()->getRow()->village_type.' '.$modVillage->fetchData(['village_id' => $data->village_id])->get()->getRow()->village_name;
     } else {
       $villageData = '-';
     }

     if (isset($data->zip_code)) {
       $zipData = $data->zip_code;
     } else {
       $zipData = '-';
     }

     if (!isset($data->address) && !isset($data->village_id) && !isset($data->sub_district_id) && !isset($data->district_id) && !isset($data->province_id) && !isset($data->zip_code)) {
       return '-';
     }

     if ($output == true) {
       return $addressData.', '.$villageData.', Kec. '.$subdistrictData.', '.$districtData.', '.$provinceData.' - '.$zipData. $countryData;
     }

     return $districtData.', '.$provinceData;
   }
 }

 if (! function_exists('parseGender'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseGender(string $value = NULL)
   {
     switch ($value) {
       case 'L':
         return 'Laki-laki';
         break;
       case 'P':
         return 'Perempuan';
         break;

       default:
         return '-';
         break;
     }
   }
 }

 if (! function_exists('parseAge'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseAge(string $date = '0000-00-00')
   {
     if ($date == '0000-00-00') {
       return '-';
     }

     // Count result of different date
     $dateDifferent   = abs(strtotime($date) - strtotime(date('Y-m-d')));

     // Count year from now
     $countYear       = floor($dateDifferent / (365*60*60*24));

     // Count month from now
     $countMonth      = floor(($dateDifferent - $countYear * 365*60*60*24) / (30*60*60*24));

     // count day from now
     $countDay        = floor(($dateDifferent - $countYear * 365*60*60*24 - $countMonth*30*60*60*24)/ (60*60*24));

     return (object) [
       'years'    => $countYear,
       'months'   => $countMonth,
       'days'     => $countDay,
     ];
   }
 }

 if (! function_exists('parsePhoneNumber'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parsePhoneNumber(int $id, string $value)
   {
     // Declare variable
     $matches   = array();

 		 if (preg_match('/^(\d{3})(\d{4})(\d{4})$/', $value, $matches)) {
 		   return '+('.$id.') '.$matches[1].'-'.$matches[2].'-'.$matches[3];
 		 }

     return '-';
   }
 }

 if (! function_exists('parseDate'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseDate(string $date = '0000-00-00', $format = 'dS F Y')
   {
     if ($date == '0000-00-00') {
       return '-';
     }

     $moment  = new Moment($date, config('App')->appTimezone);

     return $moment->format($format);
   }
 }

 if (! function_exists('parseDateDiff'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseDateDiff(string $date = '0000-00-00', $diff = NULL)
   {
     if ($date == '0000-00-00') {
       return '-';
     }

     $moment  = new Moment($date, config('App')->appTimezone);

     if (isset($diff)) {
       return $moment->from($diff);
     }

     return $moment->fromNow();
   }
 }

 if (! function_exists('parseCurrency'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseCurrency(string $value, string $format = 'IDR')
   {
     switch ($format) {
       case 'IDR':
         return 'Rp ' . number_format($value, 2, ',', '.');
         break;

       default:
         return number_format($value, 2, ',', '.');
         break;
     }
   }
 }

 if (! function_exists('parseRoleIcon'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseRoleIcon(int $value = NULL, int $font = 18)
   {
     if (isset($value) && $value > config('Ionix')->roleController) {
       return '<i class="mdi mdi-check-decagram text-info align-middle font-size-'.$font.'"></i>';
     }

     return '';
   }
 }

 if (! function_exists('parseFileIcon'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseFileIcon(string $value)
   {
     switch ($value) {
       case 'pdf':
         return (object) [
           'color'  => 'danger',
           'icon'   => 'fas fa-file-pdf text-danger',
         ];
         break;
       case 'doc':
         return (object) [
           'color'  => 'primary',
           'icon'   => 'fas fa-file-word text-primary',
         ];
         break;
       case 'docx':
         return (object) [
           'color'  => 'primary',
           'icon'   => 'fas fa-file-word text-primary',
         ];
         break;
       case 'xls':
         return (object) [
           'color'  => 'success',
           'icon'   => 'fas fa-file-excel text-success',
         ];
         break;
       case 'xlsx':
         return (object) [
           'color'  => 'success',
           'icon'   => 'fas fa-file-excel text-success',
         ];
         break;
       case 'jpg':
         return (object) [
           'color'  => 'info',
           'icon'   => 'fas fa-file-image text-info',
         ];
         break;
       case 'jpeg':
         return (object) [
           'color'  => 'info',
           'icon'   => 'fas fa-file-image text-info',
         ];
         break;
       case 'png':
         return (object) [
           'color'  => 'info',
           'icon'   => 'fas fa-file-image text-info',
         ];
         break;

       default:
         return '-';
         break;
     }
   }
 }

 if (! function_exists('parseNotificationIcon'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseNotificationIcon(string $value = NULL)
   {
     if (isset($value)) {
       return '<span class="avatar-title bg-primary bg-soft rounded-circle font-size-16"><i class="mdi mdi-email-outline text-primary"></i></span>';
     }

     return '<span class="avatar-title bg-success bg-soft rounded-circle font-size-16"><i class="mdi mdi-email-open-outline text-success"></i></span>';
   }
 }

 if (! function_exists('parseFileSize'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseFileSize($bytes = 0)
   {
     if ($bytes > 0) {
       $i      = floor(log($bytes) / log(1024));
       $sizes  = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

       return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
     }

     return 0;
   }
 }

 if (! function_exists('hexToRGB'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function hexToRGB(string $color = NULL, int $opacity = NULL)
   {
     if (!isset($color)) {
       return 'rgb(0, 0, 0)';
     }

     //Sanitize $color if "#" is provided
     if ($color[0] == '#' ) {
       $color = substr( $color, 1 );
     }

     //Check if color has 6 or 3 characters and get values
     if (strlen($color) == 6) {
       $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
     } elseif ( strlen( $color ) == 3 ) {
       $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
     } else {
       return 'rgb(0, 0, 0)';
     }

     //Convert hexadec to rgb
     $rgb   = array_map('hexdec', $hex);

     //Check if opacity is set(rgba or rgb)
     if(isset($opacity)){
       // if(abs($opacity) > 1)
       // $opacity = 1.0;
       $output = 'rgba('.implode(",", $rgb).', .'.$opacity.')';
     } else {
       $output = 'rgb('.implode(",", $rgb).')';
     }

     //Return rgb(a) color string
     return $output;
   }
 }

 if (! function_exists('showCopyright'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function showCopyright()
   {
     // load library
     $libIonix = new Ionix();

     return '<strong>'.strtoupper(config('Ionix')->appCode).' '.ucwords(config('Ionix')->appType).'</strong> &copy; <script>document.write(new Date().getFullYear())</script> <a href="https://'.$libIonix->getCompanyData()->domain.'" target="_blank" class="text-'.config('Ionix')->colorPrimary.'">'.$libIonix->getCompanyData()->name.'</a>. All right reserved.';
   }
 }

 if (! function_exists('showVersion'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function showVersion()
   {
     switch (config('Ionix')->viewEnvironment) {
       case false:
         return 'App Version '.config('Ionix')->appVersion;
         break;
       default:
         return 'App Version '.config('Ionix')->appVersion.' | '.ucwords(ENVIRONMENT);
         break;
     }
   }
 }

 if (! function_exists('requestOutput'))
 {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function requestOutput($code, $message = NULL, $data = NULL, $output = false)
   {
     $request 		 = Services::request();
     $response     = Services::response();

     switch ($code) {
       case 200:
         $body = [
           'status' 		=> $code,
           'type'       => 'success',
           'header'   	=> '200 OK',
           'message'	  => [
                              'success'	=> !$message ? 'Permintaan berhasil' : $message,
                           ],
           'data'       => $data,
         ];
         break;
       case 201:
         $body = [
           'status' 		=> $code,
           'type'       => 'success',
           'header'   	=> '201 Created',
           'message'	  => [
                              'success'	=> !$message ? 'Permintaan berhasil dibuat' : $message,
                           ],
           'data'       => $data,
         ];
         break;
       case 202:
         $body = [
           'status' 		=> $code,
           'type'       => 'success',
           'header'   	=> '202 Accepted',
           'message'	  => [
                              'success'	=> !$message ? 'Permintaan diterima' : $message,
                           ],
           'data'       => $data,
         ];
         break;
       case 400:
         $body = [
           'status' 		=> $code,
           'type'       => 'error',
           'header'   	=> '400 Bad Request',
           'message'	  => [
                              'error'	=> !$message ? 'Tidak dapat memproses permintaan karena terjadi kesalahan' : $message,
                           ],
         ];
         break;
       case 401:
         $body = [
           'status' 		=> $code,
           'type'       => 'warning',
           'header'   	=> '401 Unauthorized',
           'message'	  => [
                              'error'	=> !$message ? 'Terjadi kegagalan Authentikasi, akses ditolak!' : $message,
                           ],
         ];
         break;
       case 403:
         $body = [
           'status' 		=> $code,
           'type'       => 'error',
           'header'   	=> '403 Forbidden',
           'message'	  => [
                              'error'	=> !$message ? 'Akses ditolak, Anda tidak memiliki akses untuk halaman ini!' : $message,
                           ],
         ];
         break;
       case 404:
         $body = [
           'status' 		=> $code,
           'type'       => 'error',
           'header' 	  => '404 Not Found',
           'message'	  => [
                              'error'	=> !$message ? 'Kami tidak dapat menemukan permintaan yang Anda kirim' : $message,
                           ],
         ];
         break;
       case 405:
         $body = [
           'status' 		=> $code,
           'type'       => 'warning',
           'header' 	  => '405 Method Not Allowed',
           'message'	  => [
                              'error'	=> !$message ? 'Metode yang Anda minta tidak dapat diproses' : $message,
                           ],
         ];
         break;
       case 406:
         $body = [
           'status' 		=> $code,
           'type'       => 'warning',
           'header' 	  => '406 Not Acceptable',
           'message'	  => [
                              'error'	=> !$message ? 'Permintaan tersebut tidak diperbolehkan' : $message,
                           ],
         ];
         break;
       case 411:
         $body = [
           'status' 		=> $code,
           'type'       => 'warning',
           'header'   	=> '411 Length Required',
           'message'	  => [
                              'error'	=> !$message ? 'Sepertinya ada bidang yang belum diisi atau tidak sesuai' : $message,
                           ],
         ];
         break;
       case 500:
         $body = [
           'status' 		=> $code,
           'type'       => 'error',
           'header'   	=> '500 Internal Server Error',
           'message'	  => [
                              'error'	=> !$message ? 'Oppss..! Sepertinya terjadi kesalahan pada Internal Server' : $message,
                           ],
         ];
         break;
       case 501:
         $body = [
           'status' 		=> $code,
           'type'       => 'error',
           'header'   	=> '501 Not Implemented',
           'message'	  => [
                              'error'	=> !$message ? 'Server tidak mendukung fungsionalitas yang diperlukan untuk memenuhi permintaan' : $message,
                           ],
         ];
         break;
       case 503:
         $body = [
           'status' 		=> $code,
           'type'       => 'error',
           'header'   	=> '503 Service Unavaible',
           'message'	  => [
                              'error'	=> !$message ? 'Server saat ini tidak dapat menangani permintaan karena kelebihan beban sementara atau pemeliharaan terjadwal, yang kemungkinan akan dikurangi setelah beberapa penundaan' : $message,
                           ],
         ];
         break;
     }

     if ($output == false) {
       return $response->setJSON($body)->setStatusCode($code);
     } else {
       return $body;
     }
   }
 }

 // =================================================================================================================== Additional

 if (!function_exists('isStakeholder')) {
   /**
    * Custom Function form application developing
    * By @enjat
    *
    */
   function isStakeholder()
   {
     $libIonix = new Ionix();

     if ($libIonix->getUserData(NULL, 'object')->role_access < config('Ionix')->roleController) {
       return true;
     }

     return false;
   }
 }

 if (!function_exists('parseApproveData')) {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseApproveData(int $value)
   {
     switch ($value) {
       case -1:
         return (object) [
           'text'      => 'Menunggu Dihapus',
           'badge'     => '<span class="badge badge-pill badge-soft-dark font-size-11">Menunggu Dihapus</span>',
           'edit'      => false,
         ];
         break;
       case 1:
         return (object) [
           'text'      => 'Perlu Tindakan',
           'badge'     => '<span class="badge badge-pill badge-soft-secondary font-size-11">Perlu Tindakan</span>',
           'edit'      => true,
         ];
         break;
       case 2:
         return (object) [
           'text'      => 'Menunggu Disetujui',
           'badge'     => '<span class="badge badge-pill badge-soft-warning font-size-11">Menunggu Disetujui</span>',
           'edit'      => false,
         ];
         break;
       case 3:
         return (object) [
           'text'      => 'Dipublikasikan',
           'badge'     => '<span class="badge badge-pill badge-soft-success font-size-11">Dipublikasikan</span>',
           'edit'      => true,
         ];
         break;

       default:
         return (object) [
           'text'      => 'Pengajuan Ditolak',
           'badge'     => '<span class="badge badge-pill badge-soft-danger font-size-11">Pengajuan Ditolak</span>',
           'edit'      => false,
         ];
         break;
     }
   }
 }

 if (!function_exists('parseAssetType')) {
   /**
    * Custom Function form application developing
    * By @ubenwisnu
    *
    */
   function parseAssetType($value)
   {
     switch ($value) {
       case 'A':
         return (object) [
           'text'      => 'Tipe A',
         ];
         break;
       case 'B':
         return (object) [
           'text'      => 'Tipe B',
         ];
         break;
       case 'C':
         return (object) [
           'text'      => 'Tipe C',
         ];
         break;

       default:
         return (object) [
           'text'      => 'Tidak ada tipe',
         ];
         break;
     }
   }
 }

 if (!function_exists('parseStatusData')) {
  /**
   * Custom Function form application developing
   * By @ubenwisnu
   *
   */
  function parseStatusData(int $value)
  {
    switch ($value) {
      case -1:
        return (object) [
          'text'      => 'Menunggu Dihapus',
          'badge'     => '<span class="badge badge-pill badge-soft-dark font-size-11">Menunggu Dihapus</span>',
          'edit'      => false,
        ];
        break;
      case 1:
        return (object) [
          'text'      => 'Perlu Tindakan',
          'badge'     => '<span class="badge badge-pill badge-soft-secondary font-size-11">Perlu Tindakan</span>',
          'edit'      => true,
        ];
        break;
      case 2:
        return (object) [
          'text'      => 'Menunggu Disetujui',
          'badge'     => '<span class="badge badge-pill badge-soft-warning font-size-11">Menunggu Disetujui</span>',
          'edit'      => false,
        ];
        break;
      case 3:
        return (object) [
          'text'      => 'Dipublikasikan',
          'badge'     => '<span class="badge badge-pill badge-soft-success font-size-11">Dipublikasikan</span>',
          'edit'      => true,
        ];
        break;

      default:
        return (object) [
          'text'      => 'Pengajuan Ditolak',
          'badge'     => '<span class="badge badge-pill badge-soft-danger font-size-11">Pengajuan Ditolak</span>',
          'edit'      => false,
        ];
        break;
    }
  }
}
