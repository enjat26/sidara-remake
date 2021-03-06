<?php namespace App\Controllers\Panel\Sport\Club;

use App\Controllers\BaseController;

use App\Models\Area\DistrictModel;
use App\Models\Area\ProvinceModel;
use App\Models\Sport\ClubModel;

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
		$this->modDistrict 				= new DistrictModel();
		$this->modClub 						= new ClubModel();
		$this->modProvince 				= new ProvinceModel();
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
		$subProvince						= ['province' => NULL];
		$subDistrict						= ['district' => NULL];
		$parameters 						= [];

		if (!empty($this->request->getGet('filter-province'))) {
			if ($this->request->getGet('filter-province') != $this->configIonix->defaultProvince) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$parameters = [
				'provinces.province_id'	=> $this->request->getGet('filter-province'),
			];

			$subProvince = [
				'province'	=> $this->modProvince->fetchData(['province_id' => $parameters['provinces.province_id']])->get()->getRow()->province_name,
			];
		}

		if (!empty($this->request->getGet('filter-district'))) {
			if ($this->modDistrict->fetchData(['district_id' => $this->request->getGet('filter-district')])->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$parameters = [
				'districts.district_id'	=> $this->request->getGet('filter-district'),
			];

			$subDistrict = [
				'district'	=> $this->modDistrict->fetchData(['district_id' => $parameters['districts.district_id']])->get()->getRow()->district_type.' '.$this->modDistrict->fetchData(['district_id' => $parameters['districts.district_id']])->get()->getRow()->district_name,
			];
		}

		if ($this->modClub->fetchData($parameters)->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - Data Klub Olahraga ('.parseDate(time()).')',
			'modClub'						=> $this->modClub,
			'title'							=> 'Data Klub Olahraga',
			'subTitle'					=> (object) array_merge($subProvince, $subDistrict),
			'parameters'				=> $parameters,
			'qrData'						=> core_url('sports/clubs'),
		];

		return view('panels/sports/clubs/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Club/ExportController.php
 * Location: ./app/Controllers/Panel/Club/ExportController.php
 * -----------------------------------------------------------------------
 */
