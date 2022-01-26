<?php namespace App\Controllers\Panel\Youth\Scout;

use App\Controllers\BaseController;

use App\Models\Youth\ScoutModel;
use App\Models\Area\DistrictModel;
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
		$this->modScout 	= new ScoutModel();
		$this->modDistrict 					= new DistrictModel();
		$this->modProvince 					= new ProvinceModel();
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
		$districtParameters = [];

		if (!empty($this->request->getGet('filter-district'))) {
			if ($this->modDistrict->fetchData(['provinces.province_id' => $this->configIonix->defaultProvince, 'district_id' => $this->request->getGet('filter-district')])->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$districtParameters	= [
				'districts.district_id'			=> $this->request->getGet('filter-district'),
			];

			$filterData = (object) [
				'type'		=> $this->modDistrict->fetchData(['provinces.province_id' => $this->configIonix->defaultProvince, 'district_id' => $this->request->getGet('filter-district')])->get()->getRow()->district_type,
				'name'		=> $this->modDistrict->fetchData(['provinces.province_id' => $this->configIonix->defaultProvince, 'district_id' => $this->request->getGet('filter-district')])->get()->getRow()->district_name,
			];
		}

		if (isStakeholder() == false) {
			$parameters = [
				'year' 										=> $this->session->year,
			];
		} else {
			$parameters = [
				'year' 										=> $this->session->year,
				'scout_created_by'				=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		if ($this->modScout->fetchData(array_merge($parameters, $districtParameters))->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = (object) [
			'title'				=> !empty($this->request->getGet('filter-district')) ? 'Data Prestasi Pramuka di '.$filterData->type.' '.$filterData->name : 'Data Prestasi Pramuka SeProvinsi '.$this->modProvince->fetchData(['province_id' => $this->configIonix->defaultProvince])->get()->getRow()->province_name,
			'subTitle'		=> 'Pada Tahun '.$parameters['year'],
			'parameters'	=> array_merge($parameters, $districtParameters),
		];

		$data = [
			'paperSize'								=> '8.5in 13in',
			'fileName'								=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$data->title.' ('.parseDate(time()).')',
			'modScout'								=> $this->modScout,
			'title'										=> $data->title,
			'subTitle'								=> $data->subTitle,
			'parameters'							=> $data->parameters,
			'qrData'									=> core_url('youths/scouts?year='.$this->session->year),
		];

		return view('panels/youths/scouts/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Youth/Scout/ExportController.php
 * -----------------------------------------------------------------------
 */
