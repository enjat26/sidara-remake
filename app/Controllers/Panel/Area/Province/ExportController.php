<?php namespace App\Controllers\Panel\Area\Province;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;
use App\Models\Area\ProvinceModel;

/**
 * Class ExportController
 *
 * @package App\Controllers
 */
class ExportController extends BaseController
{
	/**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */

	 protected $allowedExport = ['print'];

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
		$this->modCountry			= new CountryModel();
		$this->modProvince		= new ProvinceModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		if (!in_array(uri_segment(3), $this->allowedExport)) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		switch (uri_segment(3)) {
			case 'print':
				return $this->print();
				break;
		}
	}

	private function print()
	{
		if (!empty($this->request->getGet('filter-country'))) {
			$parameters = [
				'countrys.country_id'	=> $this->request->getGet('filter-country'),
			];

			if ($this->modCountry->fetchData($parameters)->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}

			$data = (object) [
				'title'				=> 'Data Provinsi di '.$this->modCountry->fetchData($parameters)->get()->getRow()->country_name,
				'parameters'	=> $parameters,
			];
		} else {
			$data = (object) [
				'title'				=> 'Data Provinsi',
				'parameters'	=> NULL,
			];
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$data->title.' ('.parseDate(time()).')',
			'modProvince'				=> $this->modProvince,
			'title'							=> $data->title,
			'parameters'				=> $data->parameters,
		];

		return view('panels/area/provinces/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Area/Province/ExportController.php
 * -----------------------------------------------------------------------
 */
