<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\Area\CountryModel;

/**
 * Class CompanyController
 *
 * @package App\Controllers
 */
class CompanyController extends BaseController
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
		$data = [
			'modCountry'		=> $this->modCountry,
		];

		return view('panels/company', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'company') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getCompanyData());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getCompanyHTML($this->libIonix->getCompanyData());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'sosprov') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getQuery('social_provider', NULL, ['sosprov_id' => $this->libIonix->Decode($this->request->getGet('id'))])->getRow());
			}
		}
	}

	private function getCompanyHTML(object $companyData)
	{
		$output = [
			'logo_square_light'				=> $this->configIonix->appLogo['square_light'],
			'logo_square_dark'				=> $this->configIonix->appLogo['square_dark'],
			'logo_landscape_light'		=> $this->configIonix->appLogo['landscape_light'],
			'logo_landscape_dark'			=> $this->configIonix->appLogo['landscape_dark'],
			'logo_qr'									=> $this->configIonix->appLogo['qr'],
			'background_abstract'			=> base_url('image/background/abstract.png'),
			'background_hero'					=> base_url('image/background/hero.png'),
			'background_page'					=> base_url('image/background/page.png'),
			'code'										=> strtoupper($companyData->code),
			'name'										=> $companyData->name,
			'type'										=> $companyData->type,
			'description'							=> $companyData->description,
			'domain'									=> $companyData->domain,
			'address'									=> parseAddress($companyData, true, false),
			'email'										=> $companyData->email,
			'phone'										=> $companyData->phone,
			'tags'										=> $companyData->tags,
		];

		return requestOutput(200, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => NULL])->getResult());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getSocialHTML($this->libIonix->getQuery('social_media', ['social_provider' => 'social_provider.sosprov_id = social_media.sosprov_id'], ['user_id' => NULL])->getResult());
			}
		}
	}

	private function getSocialHTML(array $socialData)
	{
		foreach ($socialData as $row) {
			echo '<div class="col-4">
								<div class="social-source text-center mt-3">
										<div class="float-end">
											<button type="button" class="btn-close protected" aria-label="Close" data-scope="'.$this->libIonix->Encode('social').'" data-val="'.$this->libIonix->Encode($row->sosmed_id).'" key="del-social"></button>
										</div>
										<div class="avatar-xs mx-auto mb-3">
												<span class="avatar-title rounded-circle font-size-16" style="background-color: #'.$row->sosprov_color.'">
														<i class="mdi mdi-'.$row->sosprov_name.' text-white"></i>
												</span>
										</div>
										<a href="'.$row->sosprov_url.$row->sosmed_key.'" target="_blank">
											<h5 class="font-size-15 mb-0">'.ucwords($row->sosprov_name).'</h5>
											<p class="text-muted mb-0">@'.$row->sosmed_key.'</p>
										</a>
								</div>
						</div>';
		} exit;
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'company') {
			return $this->updateCompany();
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addSocial();
			} else {
				return $this->updateSocial($this->libIonix->getQuery('social_media', NULL, ['sosmed_id' => $this->libIonix->Decode($this->request->getGet('id'))])->getRow());
			}
		}
	}

	private function updateCompany()
	{
		$request = [
			'code' 						=> strtolower($this->request->getPost('code')),
			'name'						=> ucwords($this->request->getPost('name')),
			'type' 						=> ucwords($this->request->getPost('type')),
			'tags' 						=> implode(', ', array_column(json_decode($this->request->getPost('tags')), 'value')),
			'description' 		=> $this->request->getPost('description'),
			'domain' 					=> $this->configIonix->viewCopyright == true ? $this->request->getPost('domain') : NULL,
			'address' 				=> $this->request->getPost('address'),
			'country_id'			=> $this->request->getPost('country'),
			'province_id'			=> $this->request->getPost('province'),
			'district_id'			=> $this->request->getPost('district'),
			'sub_district_id'	=> $this->request->getPost('subdistrict'),
			'village_id'			=> $this->request->getPost('village'),
			'zip_code' 				=> $this->request->getPost('zipcode'),
			'email' 					=> strtolower($this->request->getPost('email')),
			'phone' 					=> $this->request->getPost('phone'),
		];

		if (regexEmail($request['email']) == false) {
			return requestOutput(411, 'Format <strong>Email</strong> yang Anda gunakan tidak benar');
		}

		$output = [
			'update'		=> $this->libIonix->updateQuery('company', NULL, $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Instansi/Badan Usaha</strong> Anda', $output);
	}

	private function addSocial()
	{
		$request = [
			'sosprov_id'	=> $this->libIonix->Decode($this->request->getPost('sosprov')),
			'user_id'			=> NULL,
			'sosmed_key'	=> strtolower($this->request->getPost('sosmed')),
		];

		if (regexUsername($request['sosmed_key']) == false) {
			return requestOutput(411, 'Format <strong>Username</strong> yang Anda gunakan tidak benar');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('social_media', $request),
		];

		return requestOutput(201, 'Permintaan dibuat, berhasil menambahkan <strong>Media Sosial</strong> baru', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Update Method
	 * --------------------------------------------------------------------
	 */

	public function update()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			return $this->updateImage(explode('|', $this->libIonix->Decode($this->request->getGet('id'))));
		}
	}

	private function updateImage(array $params)
	{
		$output = [
			'upload'	=> $this->request->getFile('image')->move($this->configIonix->uploadsFolder[$params[0]], $params[1].'.'.$this->request->getFile('image')->getClientExtension(), true),
		];

		return requestOutput(202, 'Berhasil mengunggah gambar yang dipilih', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			return $this->deleteSocial();
		}
	}

	private function deleteSocial()
	{
		$output = [
			'delete' => $this->libIonix->deleteQuery('social_media', ['sosmed_id' => $this->libIonix->Decode($this->request->getGet('id'))]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Media Sosial</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: CompanyController.php
 * Location: ./app/Controllers/Panel/CompanyController.php
 * -----------------------------------------------------------------------
 */
