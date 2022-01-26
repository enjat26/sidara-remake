<?php namespace App\Controllers\Panel\Area\Country;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;

/**
 * Class CountryController
 *
 * @package App\Controllers
 */
class CountryController extends BaseController
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
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		return view('panels/area/countrys/countrys', $this->libIonix->appInit());
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'country') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modCountry->fetchData(['country_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'country') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listCountryDT();
			}
		}
	}

	private function listCountryDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modCountry->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if (file_exists($this->configIonix->uploadsFolder['flag'].$row->country_iso3.'.jpg')) {
				$flagImage = $this->configIonix->mediaFolder['image'].'flags/'.$row->country_iso3.'.jpg';
			} else {
				$flagImage = $this->configIonix->mediaFolder['image'].'default/country-iso3.jpg';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<div class="media">
												<div class="align-self-center me-3">
														<img src="'.$flagImage.'" alt="'.$row->country_name.'" class="rounded" height="20" key="avatar-'.$row->country_iso3.'">
												</div>
                        <div class="media-body overflow-hidden my-auto">
                            <h5 class="text-truncate font-size-14 mb-1">'.$row->country_name.'</h5>
                            <p class="text-muted mb-0">'.strtoupper($row->country_iso2).' - '.strtoupper($row->country_iso3).'</p>
                        </div>
                    </div>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$row->country_capital.'</strong></p>';
			$subArray[] = '<div class="media">
                        <div class="media-body overflow-hidden my-auto">
														<p class="text-muted text-center mb-1"><strong>'.$row->country_region.'</strong></p>
														<p class="text-muted text-center mb-0">'.$row->country_sub_region.'</p>
                        </div>
                    </div>';
			$subArray[] = '<div class="media">
                        <div class="media-body overflow-hidden my-auto">
														<ul class="list-unstyled product-list mb-0">
																<li><i class="mdi mdi-chevron-right me-1"></i> Mata Uang: <strong>'.$row->country_currency.'</strong> (<strong>'.$row->country_currency_symbol.'</strong>)</li>
																<li><i class="mdi mdi-chevron-right me-1"></i> Kode Telepon: <strong>+'.$row->country_phone_code.'</strong></li>
														</ul>
                        </div>
                    </div>';
			$subArray[] = '<div class="text-center">
											<a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->country_name).'/@'.$row->country_latitude.','.$row->country_longitude.'" target="_blank" class="btn btn-primary waves-effect waves-light btn-sm">Lihat <i class="mdi mdi-arrow-right ms-1"></i></a>
										</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-country" onclick="putCountry(\''.$this->libIonix->Encode($row->country_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('country').'\', \''.$this->libIonix->Encode($row->country_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modCountry->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modCountry->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'country') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addCountry();
			} else {
				return $this->updateCountry($this->modCountry->fetchData(['country_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addCountry()
	{
		$request = [
			'country_iso2'							=> strtolower($this->request->getPost('iso2')),
			'country_iso3'							=> strtolower($this->request->getPost('iso3')),
			'country_name'							=> ucwords($this->request->getPost('name')),
			'country_capital'						=> ucwords($this->request->getPost('capital')),
			'country_region'						=> ucwords($this->request->getPost('region')),
			'country_sub_region'				=> ucwords($this->request->getPost('subregion')),
			'country_latitude'					=> $this->request->getPost('latitude'),
			'country_longitude'				  => $this->request->getPost('longitude'),
			'country_currency'					=> strtoupper($this->request->getPost('currency')),
			'country_currency_symbol'		=> ucwords($this->request->getPost('symbol')),
			'country_phone_code'				=> $this->request->getPost('phone'),
		];

		$config = (object) [
			'directory'						=> $this->configIonix->uploadsFolder['flag'],
			'fileName'						=> $this->request->getFile('image')->isValid() ? $request['country_iso3'].'.jpg' : NULL,
		];

		if ($this->modCountry->fetchData(['country_iso2' => $request['country_iso2']])->countAllResults() == true) {
			return requestOutput(406, 'Kode <strong>ISO 2</strong> sudah digunakan sebelumnya, tidak dapat menggunakan kode yang sama');
		}

		if ($this->modCountry->fetchData(['country_iso3' => $request['country_iso3']])->countAllResults() == true) {
			return requestOutput(406, 'Kode <strong>ISO 3</strong> sudah digunakan sebelumnya, tidak dapat menggunakan kode yang sama');
		}

		if ($this->modCountry->fetchData(['country_phone_code' => $request['country_phone_code']])->countAllResults() == true) {
			return requestOutput(406, 'Kode <strong>Kode Telepon</strong> sudah digunakan sebelumnya, tidak dapat menggunakan kode yang sama');
		}

		$output = [
			'upload'	=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->move($config->directory, $config->fileName, true) : NULL,
			'insert'	=> $this->libIonix->insertQuery('countrys', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['country_name'].'</strong> sebagai Negara baru', $output);
	}

	private function updateCountry(object $countryData)
	{
		$request = [
			'country_iso2'							=> strtolower($this->request->getPost('iso2')),
			'country_iso3'							=> strtolower($this->request->getPost('iso3')),
			'country_name'							=> ucwords($this->request->getPost('name')),
			'country_capital'						=> ucwords($this->request->getPost('capital')),
			'country_region'						=> ucwords($this->request->getPost('region')),
			'country_sub_region'				=> ucwords($this->request->getPost('subregion')),
			'country_latitude'					=> $this->request->getPost('latitude'),
			'country_longitude'				  => $this->request->getPost('longitude'),
			'country_currency'					=> strtoupper($this->request->getPost('currency')),
			'country_currency_symbol'		=> ucwords($this->request->getPost('symbol')),
			'country_phone_code'				=> $this->request->getPost('phone'),
		];

		$config = (object) [
			'directory'						=> $this->configIonix->uploadsFolder['flag'],
			'fileName'						=> $this->request->getFile('image')->isValid() ? $request['country_iso3'].'.jpg' : NULL,
		];

		if ($request['country_iso2'] != $countryData->country_iso2) {
			if ($this->modCountry->fetchData(['country_iso2' => $request['country_iso2']])->countAllResults() == true) {
				return requestOutput(406, 'Kode <strong>ISO 2</strong> sudah digunakan sebelumnya, tidak dapat menggunakan kode yang sama');
			}
		}

		if ($request['country_iso3'] != $countryData->country_iso3) {
			if ($this->modCountry->fetchData(['country_iso3' => $request['country_iso3']])->countAllResults() == true) {
				return requestOutput(406, 'Kode <strong>ISO 3</strong> sudah digunakan sebelumnya, tidak dapat menggunakan kode yang sama');
			}
		}

		if ($request['country_phone_code'] != $countryData->country_phone_code) {
			if ($this->modCountry->fetchData(['country_phone_code' => $request['country_phone_code']])->countAllResults() == true) {
				return requestOutput(406, 'Kode <strong>Kode Telepon</strong> sudah digunakan sebelumnya, tidak dapat menggunakan kode yang sama');
			}
		}

		$output = [
			'upload'	=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->move($config->directory, $config->fileName, true) : NULL,
			'update'	=> $this->libIonix->updateQuery('countrys', ['country_id' => $countryData->country_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Negara</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'country') {
			return $this->deleteCountry($this->modCountry->fetchData(['country_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteCountry(object $countryData)
	{
		if (file_exists($this->configIonix->uploadsFolder['flag'].$countryData->country_iso3.'.jpg')) {
			unlink($this->configIonix->uploadsFolder['flag'].$countryData->country_iso3.'.jpg');
		}

		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('countrys', ['country_id' => $countryData->country_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Negara</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: CountryController.php
 * Location: ./app/Controllers/Panel/Area/Country/CountryController.php
 * -----------------------------------------------------------------------
 */
