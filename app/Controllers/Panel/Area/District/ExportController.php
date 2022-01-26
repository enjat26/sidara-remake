<?php namespace App\Controllers\Panel\Area\District;

use App\Controllers\BaseController;

use App\Models\Area\DistrictModel;

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
		$this->modDistrict		= new DistrictModel();
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
		if (!empty($this->request->getGet('filter-province'))) {
			$parameters = [
				'provinces.province_id'	=> $this->request->getGet('filter-province'),
			];

			if ($this->modDistrict->fetchData($parameters)->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
			}

			$data = (object) [
				'title'				=> 'Data Kota/Kabupaten di '.$this->modDistrict->fetchData($parameters)->get()->getRow()->province_name,
				'parameters'	=> $parameters,
			];
		} else {
			$data = (object) [
				'title'				=> 'Data Kota/Kabupaten',
				'parameters'	=> NULL,
			];
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$data->title.' ('.parseDate(time()).')',
			'modDistrict'				=> $this->modDistrict,
			'title'							=> $data->title,
			'parameters'				=> $data->parameters,
		];

		return view('panels/area/districts/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Area/District/ExportController.php
 * -----------------------------------------------------------------------
 */
