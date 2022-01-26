<?php namespace App\Controllers\Panel\Sport\Cabor;

use App\Controllers\BaseController;

use App\Models\Area\DistrictModel;
use App\Models\Area\ProvinceModel;
use App\Models\Sport\CaborModel;

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
		$this->modCabor 						= new CaborModel();
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
				'district'	=> $this->modDistrict->fetchData(['district_id' => $parameters['districts.district_id'],'sport_cabor_id !=' 	=> '1'],true)->get()->getRow()->district_type.' '.$this->modDistrict->fetchData(['district_id' => $parameters['districts.district_id']])->get()->getRow()->district_name,
			];
		}

		// ======================================================================== Breakdown

		if (isStakeholder() == true) {
			$combineParameters = array_merge($parameters, ['youth_cabor_created_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]);
		} else {
			$combineParameters = array_merge($parameters);
		}

		if ($this->modCabor->fetchData($combineParameters)->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = [
			'paperSize'					=> 'A4 landscape',
			'fileName'					=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - Data Cabang Olahraga Olahraga ('.parseDate(time()).')',
			'modCabor'		=> $this->modCabor,
			'title'							=> 'Data Cabang Olahraga Olahraga',
			'subTitle'					=> (object) array_merge($subProvince, $subDistrict),
			'parameters'				=> $combineParameters,
			'qrData'						=> core_url('sports/cabors'),
		];

		return view('panels/sports/cabors/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Cabor/ExportController.php
 * Location: ./app/Controllers/Panel/Cabor/ExportController.php
 * -----------------------------------------------------------------------
 */
