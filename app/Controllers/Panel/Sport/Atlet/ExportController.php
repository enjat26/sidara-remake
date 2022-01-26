<?php namespace App\Controllers\Panel\Sport\Atlet;

use App\Controllers\BaseController;

use App\Models\Sport\AtletModel;

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
		$this->modAtlet 		= new AtletModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		if (!in_array(uri_segment(3), $this->allowedExport)) {
			throw \CodeIgniter\Exceptions\pageNotFoundException::forPageNotFound();
		}

		switch (uri_segment(3)) {
			case 'print':
				return $this->print();
				break;
		}
	}

	private function print()
	{
		$subTitle								= '';
		$filterGender 					= [];
		$filterCabor						= [];
		$filterArea							= [];

		if (!empty($this->request->getGet('filter-gender'))) {
			$filterGender = [
				'sport_atlet_gender'	=> $this->request->getGet('filter-gender'),
			];
		}

		if (!empty($this->request->getGet('filter-cabor'))) {
			$filterCabor = [
				'sport_cabors.sport_cabor_id'	=> $this->request->getGet('filter-cabor'),
			];
		}

		if (!empty($this->request->getGet('filter-area'))) {
			$filterArea = [
				'districts.district_id'	=> $this->request->getGet('filter-area'),
			];
		}

		if (isStakeholder() == true) {
			$combineParameters = array_merge($filterGender, $filterCabor, $filterArea, ['sport_atlet_created_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]);
		} else {
			$combineParameters = array_merge($filterGender, $filterCabor, $filterArea);
		}

		if ($this->modAtlet->fetchData($combineParameters)->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - Data Atlet ('.parseDate(time()).')',
			'modAtlet'					=> $this->modAtlet,
			'title'							=> 'Data Atlet',
			'parameters'				=> $combineParameters,
			'qrData'						=> core_url('sports/atlets'),
		];

		return view('panels/sports/atlets/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Sport/Atlet/ExportController.php
 * -----------------------------------------------------------------------
 */
