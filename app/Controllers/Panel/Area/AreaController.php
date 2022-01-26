<?php namespace App\Controllers\Panel\Area;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\Area\DistrictModel;
use App\Models\Area\SubDistrictModel;
use App\Models\Area\VillageModel;

/**
 * Class AreaController
 *
 * @package App\Controllers
 */
class AreaController extends BaseController
{
	/**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */

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
		$this->modProvince 			= new ProvinceModel();
		$this->modDistrict 			= new DistrictModel();
		$this->modSubDistrict 	= new SubDistrictModel();
		$this->modVillage 			= new VillageModel();
	}

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'province') {
			if ($this->request->getGet('format') == 'Dropdown') {
				return $this->listProvinceDropdown();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'district') {
			if ($this->request->getGet('format') == 'Dropdown') {
				return $this->listDistrictDropdown();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'subdistrict') {
			if ($this->request->getGet('format') == 'Dropdown') {
				return $this->listSubDistrictDropdown();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'village') {
			if ($this->request->getGet('format') == 'Dropdown') {
				return $this->listVillageDropdown();
			}
		}
	}

	private function listProvinceDropdown()
	{
		foreach ($this->modProvince->fetchData(['countrys.country_id' => $this->request->getGet('id')], false, 'CUSTOM')->orderBy('province_name', 'ASC')->get()->getResult() as $row) {
			echo '<option value="'.$row->province_id.'">'.$row->province_name.'</option>';
		} exit;
	}

	private function listDistrictDropdown()
	{
		foreach ($this->modDistrict->fetchData(['provinces.province_id' => $this->request->getGet('id')], false, 'CUSTOM')->orderBy('district_name', 'ASC')->get()->getResult() as $row) {
			echo '<option value="'.$row->district_id.'">'.$row->district_type.' '.$row->district_name.'</option>';
		} exit;
	}

	private function listSubDistrictDropdown()
	{
		foreach ($this->modSubDistrict->fetchData(['districts.district_id' => $this->request->getGet('id')], false, 'CUSTOM')->orderBy('sub_district_name', 'ASC')->get()->getResult() as $row) {
			echo '<option value="'.$row->sub_district_id.'">'.$row->sub_district_name.'</option>';
		} exit;
	}

	private function listVillageDropdown()
	{
		foreach ($this->modVillage->fetchData(['sub_districts.sub_district_id' => $this->request->getGet('id')], false, 'CUSTOM')->orderBy('village_name', 'ASC')->get()->getResult() as $row) {
			echo '<option value="'.$row->village_id.'">'.$row->village_type.' '.$row->village_name.'</option>';
		} exit;
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: AreaController.php
 * Location: ./app/Controllers/Panel/AreaController.php
 * -----------------------------------------------------------------------
 */
