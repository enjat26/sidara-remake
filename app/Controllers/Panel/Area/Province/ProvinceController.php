<?php namespace App\Controllers\Panel\Area\Province;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;
use App\Models\Area\ProvinceModel;

/**
 * Class ProvinceController
 *
 * @package App\Controllers
 */
class ProvinceController extends BaseController
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
		$this->modCountry 		= new CountryModel();
		$this->modProvince 		= new ProvinceModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modCountry'	=> $this->modCountry,
		];

		return view('panels/area/provinces/provinces', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'province') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modProvince->fetchData(['province_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'province') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listProvinceDT();
			}
		}
	}

	private function listProvinceDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modProvince->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if (file_exists($this->configIonix->uploadsFolder['flag'].$row->country_iso3.'.jpg')) {
				$flagImage = $this->configIonix->mediaFolder['image'].'flags/'.$row->country_iso3.'.jpg';
			} else {
				$flagImage = $this->configIonix->mediaFolder['image'].'default/country-iso3.jpg';
			}

			if ($row->province_latitude && $row->province_longitude) {
				$provinceMap = '<a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->province_name).'/@'.$row->province_latitude.','.$row->province_longitude.'" target="_blank" class="btn btn-primary waves-effect waves-light btn-sm">Lihat <i class="mdi mdi-arrow-right ms-1"></i></a></div>';
			} else {
				$provinceMap = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$row->province_name.'</strong></p>';
			$subArray[] = '<p class="text-muted text-center mb-0">
												<img src="'.$flagImage.'" alt="'.$row->country_name.'" class="rounded me-2" height="20">'.$row->country_name.'
										</p>';
			$subArray[] = '<div class="text-center">'.$provinceMap.'</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="javascript:void(0);" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-province" onclick="putProvince(\''.$this->libIonix->Encode($row->province_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('province').'\', \''.$this->libIonix->Encode($row->province_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modProvince->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modProvince->fetchData()->get()->getNumRows(),
				"data"             => $data,
		];
		echo json_encode($output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'province') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addProvince();
			} else {
				return $this->updateProvince($this->modProvince->fetchData(['province_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addProvince()
	{
		$request = [
			'country_id'								=> $this->request->getPost('country'),
			'province_name'							=> ucwords($this->request->getPost('name')),
			'province_latitude'					=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'province_longitude'				=> !empty($this->request->getPost('longitude')) ? $this->request->getPost('longitude') : NULL,
		];

		if ($this->modProvince->fetchData(['countrys.country_id' => $request['country_id'], 'province_name' => $request['province_name']])->countAllResults() == true) {
			return requestOutput(406, 'Nama <strong>Provinsi</strong> sudah digunakan sebelumnya dalam <strong>Negara</strong> ini, tidak dapat menggunakan provinsi yang sama');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('provinces', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['province_name'].'</strong> sebagai <strong>Provinsi</strong> baru', $output);
	}

	private function updateProvince(object $provinceData)
	{
		$request = [
			'country_id'								=> $this->request->getPost('country'),
			'province_name'							=> ucwords($this->request->getPost('name')),
			'province_latitude'					=> !empty($this->request->getPost('latitude')) ? $this->request->getPost('latitude') : NULL,
			'province_longitude'				=> !empty($this->request->getPost('longitude')) ? $this->request->getPost('longitude') : NULL,
		];

		if ($request['country_id'] != $provinceData->country_id || $request['province_name'] != $provinceData->province_name) {
			if ($this->modProvince->fetchData(['countrys.country_id' => $request['country_id'], 'province_name' => $request['province_name']])->countAllResults() == true) {
				return requestOutput(406, 'Nama <strong>Provinsi</strong> pada <strong>Negara</strong> tesebut sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Provinsi</strong> pada <strong>Negara</strong> yang sama');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('provinces', ['province_id' => $provinceData->province_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Provinsi</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'province') {
			return $this->deleteProvince($this->modProvince->fetchData(['province_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteProvince(object $provinceData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('provinces', ['province_id' => $provinceData->province_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Provinsi</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ProvinceController.php
 * Location: ./app/Controllers/Panel/Area/Province/ProvinceController.php
 * -----------------------------------------------------------------------
 */
