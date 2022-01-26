<?php

namespace App\Controllers\Panel\Sport\Atlet;

use App\Controllers\BaseController;

use App\Models\Area\DistrictModel;
use App\Models\Area\ProvinceModel;
use App\Models\Sport\CaborModel;
use App\Models\Sport\AtletModel;
use App\Models\UserModel;

/**
 * Class AtletController
 *
 * @package App\Controllers
 */
class AtletController extends BaseController
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
		$this->modAtlet 		= new AtletModel();
		$this->modDistrict 	= new DistrictModel();
		$this->modCabor 		= new CaborModel();
		$this->modProvince 	= new ProvinceModel();
		$this->modUser 			= new UserModel();
	}

	public function index()
	{
		$data = [
			'modAtlet'			=> $this->modAtlet,
			'modCabor'			=> $this->modCabor,
			'modDistrict'		=> $this->modDistrict,
			'modProvince'		=> $this->modProvince,
		];

		return view('panels/sports/atlets/atlets', $this->libIonix->appInit($data));
	}

	public function detail()
	{
		if (isStakeholder() == true) {
			$parameters = [
				'sport_atlets.sport_atlet_id'					=> $this->libIonix->Decode(uri_segment(2)),
				'sport_atlet_created_by'				=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = [
				'sport_atlets.sport_atlet_id'					=> $this->libIonix->Decode(uri_segment(2)),
			];
		}

		if ($this->modAtlet->fetchData($parameters)->countAllResults() == true) {
			$data = [
				'modAtlet'				=> $this->modAtlet,
				'modCabor'				=> $this->modCabor,
				'modProvince'			=> $this->modProvince,
				'atletData'				=> $this->modAtlet->fetchData($parameters)->get()->getRow(),
			];

			return view('panels/sports/atlets/atlet-detail', $this->libIonix->appInit($data));
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	/*
	 * --------------------------------------------------------------------
	 * Count Method
	 * --------------------------------------------------------------------
	 */

	public function count()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'atlet') {
			if ($this->request->getGet('format') == 'JSON') {
				return $this->countAtletJSON();
			}
		}
	}

	private function countAtletJSON()
	{
		if ($this->libIonix->Decode($this->request->getGet('id')) == 'total') {
			if (isStakeholder() == true) {
				$parameters = [
					'sport_atlet_created_by'		=> $this->libIonix->getUserData(NULL, 'object')->user_id,
				];
			} else {
				$parameters = NULL;
			}

			return requestOutput(200, NULL, $this->modAtlet->fetchData($parameters)->countAllResults());
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'atlet') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->getAtletHTML($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function getAtletHTML(object $atletData)
	{
		$output = [
			'avatar'		=> $atletData->sport_atlet_avatar ? core_url('content/atlet/' . $this->libIonix->Encode($atletData->sport_atlet_id) . '/' . $this->libIonix->Encode($atletData->sport_atlet_avatar)) : $this->configIonix->mediaFolder['image'] . 'default/avatar.jpg',
			'name'			=> $atletData->sport_atlet_name,
			'code'			=> $atletData->sport_atlet_code,
			'bio'				=> $atletData->sport_atlet_bio ? $atletData->sport_atlet_bio : '<i>Atlet ini belum memiliki biografi</i>',
			'cabor'			=> $atletData->sport_cabor_name,
			'type'			=> $atletData->sport_cabor_type_name,
			// 'address'		=> $atletData->sport_atlet_address ? $atletData->sport_atlet_address.', kel. '.$atletData->sport_atlet_village.', kec. '.$atletData->sport_atlet_sub_district.', '.$atletData->sport_atlet_district.', '.$atletData->sport_atlet_province.' - '.$atletData->sport_atlet_zip_code : '-',
			'gender'		=> $atletData->sport_atlet_gender ? parseGender($atletData->sport_atlet_gender) : '-',
			'age'				=> $atletData->sport_atlet_dob ? parseAge($atletData->sport_atlet_dob)->years . ' Tahun' : '-',
			'birthday'	=> $atletData->sport_atlet_pob && $atletData->sport_atlet_dob ? $atletData->sport_atlet_pob . ', ' . parseDate($atletData->sport_atlet_dob) : '-',
			'religion'	=> $atletData->sport_atlet_religion ? $atletData->sport_atlet_religion : '-',
			'email'			=> $atletData->sport_atlet_email ? $atletData->sport_atlet_email : '-',
			'phone'			=> $atletData->sport_atlet_phone_number ? parsePhoneNumber($atletData->sport_atlet_phone_id, $atletData->sport_atlet_phone_number) : '-',
		];

		return requestOutput(200, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Show Method
	 * --------------------------------------------------------------------
	 */

	public function show()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'chart') {
			if ($this->request->getGet('format') == 'JSON') {
				return $this->getChartJSON();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'percentage') {
			if ($this->request->getGet('format') == 'HTML') {
				return $this->getPercentageHTML();
			}
		}
	}

	private function getChartJSON()
	{
		if (isStakeholder() == true) {
			$parameters = [
				'sport_atlet_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = [];
		}

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'gender') {
			$output = [
				'label' 		=> ['Laki-laki', 'Perempuan'],
				'dataset'   => [
					'color'	=> ['#556ee6', '#e83e8c'],
					'value' => [$this->modAtlet->fetchData(array_merge($parameters, ['sport_atlet_gender' => 'L']))->countAllResults(), $this->modAtlet->fetchData(array_merge($parameters, ['sport_atlet_gender' => 'P']))->countAllResults()],
				],

			];

			return requestOutput(200, NULL, $output);
		}

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'district') {
			foreach ($this->modDistrict->fetchData(['provinces.province_id' => $this->configIonix->defaultProvince])->get()->getResult() as $row) {
				$districtData[] = [
					'id'					=> $row->district_id,
					'name' 				=> $row->district_type . ' ' . $row->district_name,
					'value'				=> $this->modAtlet->fetchData(array_merge($parameters, ['districts.district_id' => $row->district_id]))->countAllResults(),
					'color'				=> '#556ee6',
					'latitude'		=> floatval($row->district_latitude),
					'longitude'		=> floatval($row->district_longitude),
				];
			}

			$output = [
				'provinceData'		=> $this->modDistrict->fetchData(['provinces.province_id' => $this->configIonix->defaultProvince])->get()->getRow(),
				'districtData' 		=> $districtData,
			];

			return requestOutput(200, NULL, $output);
		}
	}

	private function getPercentageHTML()
	{
		if (isStakeholder() == true) {
			$parameters = [
				'sport_atlet_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = [];
		}

		foreach ($this->modDistrict->fetchData(['provinces.province_id' => $this->configIonix->defaultProvince])->get()->getResult() as $row) {
			if ($this->modAtlet->fetchData(array_merge($parameters, ['districts.district_id' => $row->district_id]))->get()->getNumRows() == true) {
				$countAtlet = ($this->modAtlet->fetchData(array_merge($parameters, ['districts.district_id' => $row->district_id]))->get()->getNumRows() / $this->modAtlet->fetchData($parameters)->countAllResults()) * 100;
			} else {
				$countAtlet = 0;
			}

			echo '<div class="mb-3">
								<h4 class="card-title">' . $row->district_type . ' ' . $row->district_name . '</h4>
								<p class="card-title-desc mb-1">Wilayah ini memiliki <strong>' . $this->modAtlet->fetchData(array_merge($parameters, ['districts.district_id' => $row->district_id]))->countAllResults() . ' Atlet</strong> dari keseluruhan.</p>

								<div class="progress progress-xl">
										<div class="progress-bar" role="progressbar" style="width: ' . floor($countAtlet) . '%;" aria-valuenow="' . floor($countAtlet) . '" aria-valuemin="0" aria-valuemax="100">' . floor($countAtlet) . '%</div>
								</div>
						</div>';
		}
		exit;
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'atlet') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listAtletDT();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'type') {
			if ($this->request->getGet('format') == 'Dropdown') {
				return $this->listTypeDropdown();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->getQuery('sport_atlet_social_media', ['social_provider' => 'social_provider.sosprov_id = sport_atlet_social_media.sport_atlet_sosprov_id'], ['sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('id'))])->getResult());
			} elseif ($this->request->getGet('format') == 'HTML') {
				return $this->listSocialHTML($this->libIonix->getQuery('sport_atlet_social_media', ['social_provider' => 'social_provider.sosprov_id = sport_atlet_social_media.sport_atlet_sosprov_id'], ['sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('id'))])->getResult());
			}
		}
	}

	private function listTypeDropdown()
	{
		foreach ($this->libIonix->getQuery('sport_cabor_types', ['sport_cabors' => 'sport_cabors.sport_cabor_id = sport_cabor_types.sport_cabor_id'], ['sport_cabors.sport_cabor_id' => $this->request->getGet('id')])->getResult() as $row) {
			echo '<option value="' . $row->sport_cabor_type_id . '">' . $row->sport_cabor_type_name . '</option>';
		}
		exit;
	}

	private function listAtletDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];

		$btnDelete 		= '';

		if (isStakeholder() == true) {
			$parameters = [
				'sport_atlet_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = NULL;
		}

		foreach ($this->modAtlet->fetchData($parameters, true)->getResult() as $row) {
			$subArray 		= [];

			if ($row->sport_cabor_avatar) {
				$caborAvatar	= core_url('content/cabor/' . $this->libIonix->Encode($row->sport_cabor_id) . '/' . $this->libIonix->Encode($row->sport_cabor_avatar));
			} else {
				$caborAvatar  = $this->configIonix->mediaFolder['image'] . 'default/logo.jpg';
			}

			if ($row->sport_atlet_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="' . panel_url('u/' . $this->libIonix->getUserData(['users.user_id' => $row->sport_atlet_created_by], 'object')->username) . '" target="_blank" style="color: #' . $this->libIonix->getUserData(['users.user_id' => $row->sport_atlet_created_by], 'object')->role_color . ';">
														<strong>' . $this->libIonix->getUserData(['users.user_id' => $row->sport_atlet_created_by], 'object')->name . '</strong>
												</a>
										</h6>
										<p class="text-muted mb-0">' . $this->libIonix->getUserData(['users.user_id' => $row->sport_atlet_created_by], 'object')->role_name . '</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			if (isStakeholder() == true) {
				if ($row->sport_atlet_approve == 1 || $row->sport_atlet_approve == 3) {
					$btnDelete 	= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('atlet') . '\', \'' . $this->libIonix->Encode($row->sport_atlet_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
				}
			} else {
				$btnDelete 		= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('atlet') . '\', \'' . $this->libIonix->Encode($row->sport_atlet_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h5 class="text-truncate mb-0">' . $row->sport_atlet_name . '</h5>
										<p class="text-muted mb-0">' . $row->sport_atlet_code . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseGender($row->sport_atlet_gender) . '</p>';
			$subArray[] = '<div class="media">
												<div class="align-self-center me-3">
													<img src="' . $caborAvatar . '" alt="' . $row->sport_cabor_name . '" class="rounded-circle avatar-sm">
												</div>
                        <div class="media-body overflow-hidden my-auto">
                            <h5 class="text-truncate font-size-14 mb-0">' . $row->sport_cabor_name . '</h5>
														<p class="text-muted mb-0">Jenis: ' . $row->sport_cabor_type_name . '</p>
                        </div>
                    </div>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->sport_atlet_level . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . ', ' . $row->province_name . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseStatusData($row->sport_atlet_approve)->badge . '</p>';
			$subArray[] = '<div class="d-flex align-items-center">
											<div class="form-check form-switch mx-auto mb-0">
													<input type="checkbox" name="status" class="form-check-input" onclick="updateMethod(false ,\''.$this->libIonix->Encode('active').'\', \''.$this->libIonix->Encode($row->sport_atlet_id).'\');" '.toggleStatus($row->sport_atlet_status).'>
											</div>
										</div>';
			$subArray[] = $userData;
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="' . panel_url('sport_atlets/' . $this->libIonix->Encode($row->sport_atlet_id) . '/manage') . '"><i class="mdi mdi-vector-link font-size-16 align-middle text-primary me-1"></i> Rincian & Kelola</a>
																' . $btnDelete . '
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modAtlet->fetchData()->countAllResults(),
			"recordsFiltered"  => $this->modAtlet->fetchData()->get()->getNumRows(),
			"data"             => $data,
		];
		echo json_encode($output);
	}

	private function listSocialHTML(array $socialData)
	{
		foreach ($socialData as $row) {
			echo '<div class="col-4">
								<div class="social-source text-center mt-3">
										<div class="float-end">
											<button type="button" class="btn-close" aria-label="Close" data-scope="' . $this->libIonix->Encode('social') . '" data-val="' . $this->libIonix->Encode($row->sport_atlet_sosmed_id) . '" key="del-social"></button>
										</div>
										<div class="avatar-xs mx-auto mb-3">
												<span class="avatar-title rounded-circle font-size-16" style="background-color: #' . $row->sosprov_color . '">
														<i class="mdi mdi-' . $row->sosprov_name . ' text-white"></i>
												</span>
										</div>
										<a href="' . $row->sosprov_url . $row->sport_atlet_sosmed_key . '" target="_blank">
											<h5 class="font-size-15 mb-0">' . ucwords($row->sosprov_name) . '</h5>
											<p class="text-muted mb-0">@' . $row->sport_atlet_sosmed_key . '</p>
										</a>
								</div>
						</div>';
		}
		exit;
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'atlet') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addAtlet();
			} else {
				return $this->updateAtlet($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'resub') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addResub($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'verify') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addVerify($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addSocial($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}
	}

	private function addAtlet()
	{
		$requestAtlet = [
			'sport_cabor_type_id'					=> $this->request->getPost('type'),
			'sport_atlet_code'						=> $this->libIonix->generateAutoNumber('sport_atlets', 'sport_atlet_code', strtoupper($this->modCabor->fetchData(['sport_cabors.sport_cabor_id' => $this->request->getPost('cabor')])->get()->getRow()->sport_cabor_code) . '-', 10),
			'sport_atlet_name'						=> ucwords($this->request->getPost('name')),
			'sport_atlet_email'						=> !empty($this->request->getPost('email')) ? strtolower($this->request->getPost('email')) : NULL,
			'sport_atlet_level'						=> $this->request->getPost('level'),
			'sport_atlet_explanation'			=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
			'sport_atlet_avatar'					=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->getRandomName() : NULL,
			'sport_atlet_approve'					=> isStakeholder() == false ? 3 : 2,
			'sport_atlet_approve_by'			=> isStakeholder() == false ? $this->libIonix->getUserData(NULL, 'object')->user_id : NULL,
			'sport_atlet_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'sport_atlet_ownership'				=> isStakeholder() ? $this->libIonix->getUserData(NULL, 'object')->name : $this->libIonix->getCompanyData()->name,
		];

		$requestInfo = [
			'sport_atlet_province_id'					=> $this->request->getPost('province'),
			'sport_atlet_district_id'					=> $this->request->getPost('district'),
			'sport_atlet_pob'									=> ucwords($this->request->getPost('pob')),
			'sport_atlet_dob'									=> date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getPost('dob')))),
			'sport_atlet_gender'							=> $this->request->getPost('gender'),
			'sport_atlet_religion'						=> $this->request->getPost('religion'),
			'sport_atlet_phone_id'						=> !empty($this->request->getPost('phoneid')) ? $this->request->getPost('phoneid') : NULL,
			'sport_atlet_phone_number'				=> !empty($this->request->getPost('phone')) ? $this->request->getPost('phone') : NULL,
		];

		if (!empty($this->request->getPost('email')) && regexEmail($requestAtlet['sport_atlet_email']) == false) {
			return requestOutput(411, 'Format <strong>Email</strong> yang Anda gunakan tidak benar');
		}

		if (!empty($this->request->getPost('email')) && in_array(explode('@', $requestAtlet['sport_atlet_email'])[0], $this->configIonix->blockedUsername)) {
			return requestOutput(400, '<strong>Email</strong> mengandung unsur kata-kata yang dilarang');
		}

		if (!empty($this->request->getPost('email')) && $this->modAtlet->fetchData(['sport_atlet_email' => $requestAtlet['sport_atlet_email']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Email</strong> sudah digunakan oleh <strong>Atlet Lain</strong>. Tidak dapat menggunakan <strong>Email</strong> yang sama');
		}

		$query = (object) [
			'insert'	=> $this->libIonix->insertQuery('sport_atlets', $requestAtlet),
		];

		$config = [
			'directory'	=> $this->configIonix->uploadsFolder['atlet'] . $query->insert,
			'fileName'	=> $requestAtlet['sport_atlet_avatar'],
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'atlet',
				'notification_title'		=> 'Pengajuan Penambahan Data Atlet',
				'notification_slug'			=> 'sport_atlets/' . $this->libIonix->Encode($query->insert) . '/manage',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan penambahan Data Atlet atas nama ' . $requestAtlet['sport_atlet_name'] . ' dari ' . $this->libIonix->getUserData(NULL, 'object')->name . ' untuk dipublikasikan',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'create'						=> !is_dir($config['directory']) ? mkdir($config['directory'], 0777, true) : NULL,
			'update'						=> $this->libIonix->updateQuery('sport_atlet_info', ['sport_atlet_id' => $query->insert], $requestInfo),
			'upload'						=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->move($config['directory'], $config['fileName'], true) : NULL,
			'pushNotification'	=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>' . $requestAtlet['sport_atlet_name'] . '</strong> sebagai Atlet baru pada <strong>Cabang Olahraga</strong> ini', $output);
	}

	private function addResub(object $atletData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			return requestOutput(400);
		}

		$output = [
			'update'								=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $atletData->sport_atlet_id], ['sport_atlet_approve' => $atletData->sport_atlet_approve + 1]),
			'flash'   							=> $this->session->setFlashdata('alertToastr', [
				'type'			=> 'success',
				'header'		=> '202 Accepted',
				'message'		=> 'Berhasil <strong>mendaftarkan ulang</strong> <strong>' . $atletData->sport_atlet_name . '</strong> untuk diperbaiki dan diajukan kembali',
			]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addVerify(object $atletData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == true) {
			$action = (object) [
				'title'					=> 'diterima',
				'message'				=> 'menerima',
				'requirement'		=> 'Sekarang Data tersebut sudah tayang pada Halaman Utama',
				'update'				=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $atletData->sport_atlet_id], ['sport_atlet_approve' => $atletData->sport_atlet_approve + 1, 'sport_atlet_approve_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			];
		} elseif (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			$action = (object) [
				'title'					=> 'ditolak',
				'message'				=> 'menolak',
				'requirement'		=> 'Silahkan untuk perbaiki data dan mengajukan ulang.',
				'update'				=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $atletData->sport_atlet_id], ['sport_atlet_approve' => $atletData->sport_atlet_approve - 2, 'sport_atlet_approve_by' => NULL]),
			];
		}

		$requestNotification 		= [
			'user_id'								=> $atletData->sport_atlet_created_by,
			'notification_type'			=> 'atlet',
			'notification_title'		=> 'Verifikasi Data Atlet',
			'notification_slug'			=> 'sport_atlets/' . $this->libIonix->Encode($atletData->sport_atlet_id) . '/manage',
			'notification_content'	=> 'Data Atlet dengan nama <strong>' . $atletData->sport_atlet_name . '</strong> yang Anda ajukan telah ' . $action->title . '. ' . $action->requirement,
		];

		$output = [
			'insertNotification'		=> $this->libIonix->insertQuery('notifications', $requestNotification),
			'pushNotification'			=> $this->libIonix->pushNotification(),
			'flash'   							=> $this->session->setFlashdata('alertToastr', [
				'type'			=> 'success',
				'header'		=> '202 Accepted',
				'message'		=> 'Berhasil <strong>' . $action->message . '</strong> <strong>Data Atlet</strong> yang diajukan',
			]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addSocial(object $atletData)
	{
		$request = [
			'sport_atlet_sosprov_id'		=> $this->libIonix->Decode($this->request->getPost('sosprov')),
			'sport_atlet_id'						=> $atletData->sport_atlet_id,
			'sport_atlet_sosmed_key'		=> strtolower($this->request->getPost('sosmed')),
		];

		if (regexUsername($request['sport_atlet_sosmed_key']) == false) {
			return requestOutput(411, 'Format <strong>Username</strong> yang Anda gunakan tidak benar');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('sport_atlet_social_media', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>Media Sosial</strong> pada <strong>Atlet</strong> ini', $output);
	}

	private function updateAtlet(object $atletData)
	{
		$requestAtlet = [
			'sport_cabor_type_id'					=> $this->request->getPost('type'),
			'sport_atlet_name'						=> ucwords($this->request->getPost('fullname')),
			'sport_atlet_email'						=> !empty($this->request->getPost('email')) ? strtolower($this->request->getPost('email')) : NULL,
			'sport_atlet_level'						=> $this->request->getPost('level'),
			'sport_atlet_explanation'			=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
			'sport_atlet_approve'					=> isStakeholder() == false ? 3 : 2,
			'sport_atlet_approve_by'			=> isStakeholder() == false ? $this->libIonix->getUserData(NULL, 'object')->user_id : NULL,
			'sport_atlet_ownership'				=> implode(', ', array_column(json_decode(ucwords($this->request->getPost('atlet_ownership'))), 'value')),
		];

		$requestInfo = [
			'sport_atlet_bio'									=> !empty($this->request->getPost('bio')) ? $this->request->getPost('bio') : NULL,
			'sport_atlet_address'							=> !empty($this->request->getPost('address')) ? $this->request->getPost('address') : NULL,
			'sport_atlet_province_id'					=> $this->request->getPost('province'),
			'sport_atlet_district_id'					=> $this->request->getPost('district'),
			'sport_atlet_sub_district_id'			=> $this->request->getPost('subdistrict'),
			'sport_atlet_village_id'					=> $this->request->getPost('village'),
			'sport_atlet_zip_code'						=> !empty($this->request->getPost('zipcode')) ? $this->request->getPost('zipcode') : NULL,
			'sport_atlet_pob'									=> ucwords($this->request->getPost('pob')),
			'sport_atlet_dob'									=> date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getPost('dob')))),
			'sport_atlet_gender'							=> $this->request->getPost('gender'),
			'sport_atlet_religion'						=> $this->request->getPost('religion'),
			'sport_atlet_phone_id'						=> !empty($this->request->getPost('phoneid')) ? $this->request->getPost('phoneid') : NULL,
			'sport_atlet_phone_number'				=> !empty($this->request->getPost('phone')) ? $this->request->getPost('phone') : NULL,
		];

		if (!empty($this->request->getPost('email')) && $atletData->sport_atlet_email != $requestAtlet['sport_atlet_email']) {
			if (regexEmail($requestAtlet['sport_atlet_email']) == false) {
				return requestOutput(411, 'Format <strong>Email</strong> yang Anda gunakan tidak benar');
			}
		}

		if (!empty($this->request->getPost('email')) && $atletData->sport_atlet_email != $requestAtlet['sport_atlet_email']) {
			if (in_array(explode('@', $requestAtlet['sport_atlet_email'])[0], $this->configIonix->blockedUsername)) {
				return requestOutput(400, '<strong>Email</strong> mengandung unsur kata-kata yang dilarang');
			}
		}

		if (!empty($this->request->getPost('email')) && $atletData->sport_atlet_email != $requestAtlet['sport_atlet_email']) {
			if ($this->modAtlet->fetchData(['sport_atlet_email' => $requestAtlet['sport_atlet_email']])->countAllResults() == true) {
				return requestOutput(406, '<strong>Email</strong> sudah digunakan oleh <strong>Atlet Lain</strong>. Tidak dapat menggunakan <strong>Email</strong> yang sama');
			}
		}

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'atlet',
				'notification_title'		=> 'Pengajuan Perubahan Data Altet',
				'notification_slug'			=> 'sport_atlets/' . $this->libIonix->Encode($atletData->sport_atlet_id) . '/manage',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan perubahan Data Atlet dari ' . $this->libIonix->getUserData(NULL, 'object')->name . ' untuk ditinjau dan dipublikasikan ulang',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'updateAtlet'				=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $atletData->sport_atlet_id], $requestAtlet),
			'updateInfo'				=> $this->libIonix->updateQuery('sport_atlet_info', ['sport_atlet_id' => $atletData->sport_atlet_id], $requestInfo),
			'pushNotification'	=> $this->libIonix->pushNotification(),
			'flash'   					=> $this->session->setFlashdata('alertToastr', [
				'type'			=> 'success',
				'header'		=> '202 Accepted',
				'message'	=> 'Berhasil merubah informasi <strong>Atlet</strong> tersebut',
			]),
		];

		return requestOutput(202, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Update Method
	 * --------------------------------------------------------------------
	 */

	public function update()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			return $this->updateImage($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => explode('|', $this->libIonix->Decode($this->request->getGet('id')))[1]])->get()->getRowArray());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'active') {

			return $this->updateStatus($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function updateStatus(object $dataAtlete)
	{
		if ($dataAtlete->sport_atlet_status == 0) {
			$output = [
				'status'  => 'Aktif',
				'update'	=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $dataAtlete->sport_atlet_id], ['sport_atlet_status' => 1]),
			];
		} elseif ($dataAtlete->sport_atlet_status == 1) {
			$output = [
				'status'  			=> 'Tidak Aktif',
				'update'				=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $dataAtlete->sport_atlet_id], ['sport_atlet_status' => 0]),
			];
		}

		return requestOutput(202, 'Berhasil merubah <strong>'.$dataAtlete->sport_atlet_name.'</strong> menjadi <strong>'.$output['status'].'</strong>', $output);
	}

	private function updateImage(array $atletData)
	{
		$parameters = (object) [
			'image'		=> $atletData['sport_atlet_' . explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0]],
		];

		$data = [
			'directory' 	=> $this->configIonix->uploadsFolder['atlet'] . $atletData['sport_atlet_id'],
			'fileName'		=> $this->request->getFile('image')->getRandomName(),
		];

		if (isset($parameters->image) && file_exists($this->configIonix->uploadsFolder['atlet'] . $atletData['sport_atlet_id'] . '/' . $parameters->image)) {
			unlink($this->configIonix->uploadsFolder['atlet'] . $atletData['sport_atlet_id'] . '/' . $parameters->image);
		}

		$output = [
			'upload'	=> $this->request->getFile('image')->move($data['directory'], $data['fileName'], true),
			'update'	=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $atletData['sport_atlet_id']], ['sport_atlet_' . explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0] => $data['fileName']]),
		];

		return requestOutput(202, 'Berhasil mengunggah <strong>Gambar</strong> yang dipilih sebagai <strong>' . ucwords(explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0]) . '</strong>', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'atlet') {
			return $this->deleteAtlet($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			return $this->deleteImage($this->modAtlet->fetchData(['sport_atlets.sport_atlet_id' => explode('|', $this->libIonix->Decode($this->request->getGet('id')))[1]])->get()->getRowArray());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'social') {
			return $this->deleteSocial();
		}
	}

	private function deleteAtlet(object $atletData)
	{
		if (isStakeholder() == true) {
			foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
				$request	= [
					'user_id'								=> $row->user_id,
					'notification_type'			=> 'atlet',
					'notification_title'		=> 'Pengajuan Penghapusan Data Atlet',
					'notification_slug'			=> 'sport_atlets/' . $this->libIonix->Encode($atletData->sport_atlet_id) . '/manage',
					'notification_content'	=> 'Anda mendapatkan pengajuan penghapusan Data atas nama ' . $atletData->sport_atlet_name . ' dari Atlet yang diajukan ' . $this->libIonix->getUserData(NULL, 'object')->name . ' untuk dihapus sepenuhnya',
				];

				$this->libIonix->insertQuery('notifications', $request);
			}

			$output = [
				'delete' 						=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlets.sport_atlet_id' => $atletData->sport_atlet_id], ['sport_atlet_approve' => -1, 'sport_atlet_deleted_at' => date('Y-m-d h:m:s')]),
				'pushNotification'	=> $this->libIonix->pushNotification(),
			];

			return requestOutput(202, 'Berhasil menghapus <strong>Atlet</strong> yang dipilih, Anda harus menunggu Data ini dihapus sepenuhnya', $output);
		} else {
			if (is_dir($this->configIonix->uploadsFolder['atlet'] . $atletData->sport_atlet_id)) {
				delete_files($this->configIonix->uploadsFolder['atlet'] . $atletData->sport_atlet_id, TRUE);
				rmdir($this->configIonix->uploadsFolder['atlet'] . $atletData->sport_atlet_id);
			}

			$output = [
				'delete' 	=> $this->libIonix->deleteQuery('sport_atlets', ['sport_atlets.sport_atlet_id' => $atletData->sport_atlet_id]),
				'url'			=> !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? panel_url('sport_atlets') : NULL,
				'flash'   => !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? $this->session->setFlashdata('alertSwal', [
												'type'		=> 'success',
												'header'	=> '202 Accepted',
												'message'	=> 'Berhasil menghapus <strong>Atlet</strong> tersebut dari sistem',
											]) : NULL,
			];

			return requestOutput(202, 'Berhasil menghapus <strong>Atlet</strong> yang dipilih', $output);
		}
	}

	private function deleteImage(array $atletData)
	{
		$parameters = (object) [
			'image'		=> explode('|', $this->libIonix->Decode($this->request->getGet('id')))[0],
		];

		if (!$atletData['sport_atlet_' . $parameters->image]) {
			return requestOutput(406, '<strong>Atlet</strong> ini tidak memiliki <strong>' . ucwords($parameters->image) . '</strong>, tidak ada yang dapat dihapus');
		}

		if (file_exists($this->configIonix->uploadsFolder['atlet'] . $atletData['sport_atlet_id'] . '/' . $atletData['sport_atlet_' . $parameters->image])) {
			unlink($this->configIonix->uploadsFolder['atlet'] . $atletData['sport_atlet_id'] . '/' . $atletData['sport_atlet_' . $parameters->image]);
		}

		$output = [
			'delete' 	=> $this->libIonix->updateQuery('sport_atlets', ['sport_atlet_id' => $atletData['sport_atlet_id']], ['sport_atlet_' . $parameters->image => NULL]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>' . ucwords($parameters->image) . '</strong> pada <strong>Atlet</strong> ini', $output);
	}

	private function deleteSocial()
	{
		$output = [
			'delete'  => $this->libIonix->deleteQuery('sport_atlet_social_media', ['sport_atlet_sosmed_id' => $this->libIonix->Decode($this->request->getGet('id'))]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Media Sosial</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: AtletController.php
 * Location: ./app/Controllers/Panel/Sport/Atlet/AtletController.php
 * -----------------------------------------------------------------------
 */
