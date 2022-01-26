<?php namespace App\Controllers\Panel\Area\Country;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;

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
		$this->modCountry		= new CountryModel();
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
		$data = [
			'paperSize'				=> 'A4 landscape',
			'headerTitle'			=> 'Data Negara',
			'fileName'				=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - Data Negara ('.parseDate(time()).')',
			'modCountry'			=> $this->modCountry,
		];

		return view('panels/area/countrys/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Area/Country/ExportController.php
 * -----------------------------------------------------------------------
 */
