<?php namespace App\Controllers\Panel\Sport\Asset;

use App\Controllers\BaseController;

use App\Models\Sport\AssetModel;
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
		$this->modAsset 	= new AssetModel();
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
		$typeParameters = [];

		if (!empty($this->request->getGet('filter-type'))) {
			if ($this->modAsset->fetchData(['asset_category_type' => $this->request->getGet('filter-type')])->countAllResults() == false) {
				throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
			}

			$typeParameters	= [
				'asset_category_type'			=> $this->request->getGet('filter-type'),
			];

			$filterData = (object) [
				'asset'		=> $this->modAsset->fetchData(['asset_category_type' => $this->request->getGet('filter-type')])->get()->getRow(),
			];
		}

		if (isStakeholder() == false) {
			$parameters = [];
		} else {
			$parameters = [
				'asset_created_by'				=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		if ($this->modAsset->fetchData(array_merge($parameters, $typeParameters))->countAllResults() == false) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$data = (object) [
			'title'				=> !empty($this->request->getGet('filter-type')) ? 'Data Sarana & Prasarana ('.parseAssetType($filterData->asset->asset_category_type).')' : 'Data Sarana & Prasarana',
			'subTitle'		=> 'Pada Bidang Pemuda',
			'parameters'	=> array_merge($parameters, $typeParameters),
		];

		$data = [
			'paperSize'								=> '8.5in 13in',
			'fileName'								=> strtoupper($this->configIonix->appCode).' '.ucwords($this->configIonix->appType).' - '.$data->title.' ('.parseDate(time()).')',
			'modAsset'								=> $this->modAsset,
			'title'										=> $data->title,
			'subTitle'								=> $data->subTitle,
			'parameters'							=> $data->parameters,
			'qrData'									=> core_url('sports/assets'),
		];

		return view('panels/sports/assets/export/print', $this->libIonix->appInit($data));
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ExportController.php
 * Location: ./app/Controllers/Panel/Sport/Asset/ExportController.php
 * -----------------------------------------------------------------------
 */
