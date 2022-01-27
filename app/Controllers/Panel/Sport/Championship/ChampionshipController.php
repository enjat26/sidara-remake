<?php

namespace App\Controllers\Panel\Sport\Championship;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\Sport\AtletModel;
use App\Models\Sport\Championship\ChampionshipModel;
use App\Models\Sport\Championship\ParticipantModel;
use App\Models\UserModel;

/**
 * Class ChampionshipController
 *
 * @package App\Controllers
 */
class ChampionshipController extends BaseController
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
		$this->modAtlet 						= new AtletModel();
		$this->modChampionship 			= new ChampionshipModel();
		$this->modParticipant 			= new ParticipantModel();
		$this->modProvince 					= new ProvinceModel();
		$this->modUser 						  = new UserModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modChampionship'			=> $this->modChampionship,
			'modProvince' 				=> $this->modProvince,
		];

		return view('panels/sports/championships/championships', $this->libIonix->appInit($data));
	}

	public function detail()
	{
		if (isStakeholder() == true) {
			$parameters = [
				'sport_championship_id'					=> $this->libIonix->Decode(uri_segment(2)),
				'sport_championship_created_by'	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = [
				'sport_championship_id'					=> $this->libIonix->Decode(uri_segment(2)),
			];
		}

		if ($this->modChampionship->fetchData($parameters)->countAllResults() == true) {
			$data = [
				'championshipData'	=> $this->modChampionship->fetchData($parameters)->get()->getRow(),
			];

			return view('panels/sports/championships/championship-detail', $this->libIonix->appInit($data));
		}

		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'championship') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modChampionship->fetchData(['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'championship') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listChampionshipDT();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'atlet') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listAtletDT();
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'participant') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listChampionshipParticipantDT();
			}
		}
	}

	private function listChampionshipDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];
		$btnUpdate 		= '';
		$btnDelete 		= '';

		if (isStakeholder() == true) {
			$parameters = [
				'sport_championship_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = NULL;
		}

		foreach ($this->modChampionship->fetchData($parameters, true)->getResult() as $row) {
			$subArray 	= [];

			if ($row->sport_championship_code) {
				$championshipCode = strtoupper($row->sport_championship_code);
			} else {
				$championshipCode = '-';
			}

			if ($row->sport_championship_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="' . panel_url('u/' . $this->libIonix->getUserData(['users.user_id' => $row->sport_championship_created_by], 'object')->username) . '" target="_blank" style="color: #' . $this->libIonix->getUserData(['users.user_id' => $row->sport_championship_created_by], 'object')->role_color . ';">
														<strong>' . $this->libIonix->getUserData(['users.user_id' => $row->sport_championship_created_by], 'object')->name . '</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">' . $this->libIonix->getUserData(['users.user_id' => $row->sport_championship_created_by], 'object')->role_name . '</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			if (isStakeholder() == true) {
				if ($row->sport_championship_approve == 1 || $row->sport_championship_approve == 3) {
					$btnUpdate  = '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-championship" onclick="putChampionship(\'' . $this->libIonix->Encode('championship') . '\', \'' . $this->libIonix->Encode($row->sport_championship_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
					$btnDelete 	= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('championship') . '\', \'' . $this->libIonix->Encode($row->sport_championship_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
				}
			} else {
				$btnUpdate  = '<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-championship" onclick="putChampionship(\'' . $this->libIonix->Encode('championship') . '\', \'' . $this->libIonix->Encode($row->sport_championship_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>';
				$btnDelete 		= '<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('championship') . '\', \'' . $this->libIonix->Encode($row->sport_championship_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
			}

			if ($row->sport_championship_location) {
				$championshipLocation = $row->sport_championship_location;
			} else {
				$championshipLocation = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">' . $row->sport_championship_name . '</h6>
										<p class="text-muted mb-0">' . $championshipCode . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->sport_championship_level . ' > ' . $row->sport_championship_category . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->sport_championship_year . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $championshipLocation . '</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseStatusData($row->sport_championship_approve)->badge . '</p>';
			$subArray[] = $userData;
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="' . panel_url('sport_championships/' . $this->libIonix->Encode($row->sport_championship_id) . '/manage') . '"><i class="mdi mdi-vector-link font-size-16 align-middle text-primary me-1"></i> Rincian & Kelola</a>
																' . $btnUpdate . '
																' . $btnDelete . '
														</div>
												</div>
										</div>';
			$data[] 	= $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modChampionship->fetchData($parameters)->countAllResults(),
			"recordsFiltered"  => $this->modChampionship->fetchData($parameters)->get()->getNumRows(),
			"data"             => $data,
		];
		echo json_encode($output);
	}

	private function listAtletDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];

		if (isStakeholder() == true) {
			$parameters = [
				'sport_atlet_created_by' 	=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		} else {
			$parameters = NULL;
		}

		foreach ($this->modAtlet->fetchData($parameters, true)->getResult() as $row) {
			$subArray 			= [];
			$checkedStatus 	= $this->libIonix->builderQuery('sport_championship_participants')->where(['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('params')), 'sport_atlet_id' => $row->sport_atlet_id])->countAllResults() == true ? 'checked' : '';

			if ($row->sport_atlet_avatar) {
				$atletAvatar	= base_url('content/atlet/' . $this->libIonix->Encode($row->sport_atlet_id) . '/' . $this->libIonix->Encode($row->sport_atlet_avatar));
			} else {
				$atletAvatar = $this->configIonix->mediaFolder['image'] . 'default/avatar.jpg';
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

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<div class="media">
												<div class="align-self-center me-3">
													<img src="' . $atletAvatar . '" alt="' . $row->sport_atlet_name . '" class="rounded avatar-sm" style="height: 4rem!important">
												</div>
                        <div class="media-body overflow-hidden my-auto">
                            <h5 class="text-truncate font-size-14 mb-1">' . $row->sport_atlet_name . '</h5>
                        </div>
                    </div>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseGender($row->sport_atlet_gender) . '</p>';
			$subArray[] = '<div class="media-body overflow-hidden my-auto">
                            <h5 class="text-truncate font-size-14 mb-0">' . $row->cabor_name . '</h5>
														<p class="text-muted mb-0">Kode: ' . $row->cabor_code . '</p>
                        </div>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . ', ' . $row->province_name . '</p>';
			$subArray[] = $userData;
			$subArray[] = '<div class="text-center">
												<div class="form-check">
														<input class="form-check-input chooseAtlet" type="checkbox" data-scope="' . $this->libIonix->Encode('participant') . '" data-val="' . $this->libIonix->Encode($row->sport_atlet_id) . '" ' . $checkedStatus . '>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modAtlet->fetchData($parameters)->countAllResults(),
			"recordsFiltered"  => $this->modAtlet->fetchData($parameters)->get()->getNumRows(),
			"data"             => $data,
		];
		echo json_encode($output);
	}

	private function listChampionshipParticipantDT()
	{
		$i 						= $this->request->getVar('start') + 1;
		$data 				= [];
		$btnDelete 		= '';

		$parameters = [
			'sport_championships.sport_championship_id'		=> $this->libIonix->Decode($this->request->getGet('params')),
		];

		foreach ($this->modParticipant->fetchData($parameters, true)->getResult() as $row) {
			$subArray = [];

			if ($row->sport_atlet_avatar) {
				$atletAvatar	= base_url('content/atlet/' . $this->libIonix->Encode($row->sport_atlet_id) . '/' . $this->libIonix->Encode($row->sport_atlet_avatar));
			} else {
				$atletAvatar = $this->configIonix->mediaFolder['image'] . 'default/avatar.jpg';
			}

			if (isStakeholder() == false || $row->sport_championship_participant_created_by == $this->libIonix->getUserData(NULL, 'object')->user_id) {
				$btnDelete 	= '<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('participant') . '\', \'' . $this->libIonix->Encode($row->sport_championship_participant_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>';
			} else {
				$btnDelete 		= '';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<div class="media">
												<div class="align-self-center me-3">
													<img src="' . $atletAvatar . '" alt="' . $row->sport_atlet_name . '" class="rounded avatar-sm" style="height: 4rem!important">
												</div>
                        <div class="media-body overflow-hidden my-auto">
                            <h5 class="text-truncate font-size-14 mb-1">' . $row->sport_atlet_name . '</h5>
                        </div>
                    </div>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . parseGender($row->sport_atlet_gender) . '</p>';
			$subArray[] = '<div class="media-body overflow-hidden my-auto">
							<h5 class="text-truncate font-size-14 mb-0">' . $row->cabor_name . '</h5>
							<p class="text-muted mb-0">Kode: ' . $row->cabor_code . '</p>
						</div>';
			$subArray[] = '<p class="text-muted text-center mb-0">' . $row->district_type . ' ' . $row->district_name . ', ' . $row->province_name . '</p>';
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																' . $btnDelete . '
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modParticipant->fetchData($parameters)->countAllResults(),
			"recordsFiltered"  => $this->modParticipant->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'championship') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addChampionship();
			} else {
				return $this->updateChampionship($this->modChampionship->fetchData(['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'resub') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addResub($this->modChampionship->fetchData(['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'verify') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addVerify($this->modChampionship->fetchData(['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'participant') {
			return $this->addChampionshipParticipant($this->modChampionship->fetchData(['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
		}
	}

	private function addChampionship()
	{
		$requestChampionship = [
			'sport_championship_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : NULL,
			'sport_championship_year'						=> strtolower($this->request->getPost('year')),
			'sport_championship_name'						=> ucwords($this->request->getPost('name')),
			'sport_championship_level'					=> ucwords($this->request->getPost('level')),
			'sport_championship_category'				=> ucwords($this->request->getPost('category')),
			'sport_championship_location'				=> !empty($this->request->getPost('location')) ? ucwords($this->request->getPost('location')) : NULL,
			'sport_championship_explanation'		=> !empty($this->request->getPost('explanation')) ? $this->request->getPost('explanation') : NULL,
			'sport_championship_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'sport_championship_approve_by'			=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
			'sport_championship_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		if (!empty($this->request->getPost('code'))) {
			if (regexUsername($requestChampionship['sport_championship_code']) == false) {
				return requestOutput(411, 'Format <strong>Kode Kejuaraan Olahraga</strong> yang Anda gunakan tidak benar');
			}
		}

		$query = (object) [
			'insert'		=> $this->libIonix->insertQuery('sport_championships', $requestChampionship),
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'championship',
				'notification_title'		=> 'Pengajuan Penambahan Data Kejuaraan Olahraga',
				'notification_slug'			=> 'sport_championships/' . $this->libIonix->Encode($query->insert) . '/manage',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan penambahan Data ' . $requestChampionship['sport_championship_name'] . ' dari ' . $this->libIonix->getUserData(NULL, 'object')->name . ' untuk dipublikasikan',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'pushNotification'	=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil menambahkan <strong>Kejuaraan Olahraga</strong> baru', $output);
	}

	private function addResub(object $championshipData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			return requestOutput(400);
		}

		$output = [
			'update'								=> $this->libIonix->updateQuery('sport_championships', ['sport_championship_id' => $championshipData->sport_championship_id], ['sport_championship_approve' => $championshipData->sport_championship_approve + 1]),
			'flash'   							=> $this->session->setFlashdata('alertToastr', [
				'type'			=> 'success',
				'header'		=> '202 Accepted',
				'message'		=> 'Berhasil <strong>mendaftarkan ulang</strong> <strong>' . $championshipData->sport_championship_name . '</strong> untuk diperbaiki dan diajukan kembali',
			]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addVerify(object $championshipData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == true) {
			$action = (object) [
				'title'					=> 'diterima',
				'message'				=> 'menerima',
				'requirement'		=> 'Sekarang Data tersebut sudah tayang pada Halaman Utama',
				'update'				=> $this->libIonix->updateQuery('sport_championships', ['sport_championship_id' => $championshipData->sport_championship_id], ['sport_championship_approve' => $championshipData->sport_championship_approve + 1, 'sport_championship_approve_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			];
		} elseif (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			$action = (object) [
				'title'					=> 'ditolak',
				'message'				=> 'menolak',
				'requirement'		=> 'Silahkan untuk perbaiki data dan mengajukan ulang.',
				'update'				=> $this->libIonix->updateQuery('sport_championships', ['sport_championship_id' => $championshipData->sport_championship_id], ['sport_championship_approve' => $championshipData->sport_championship_approve - 2, 'sport_championship_approve_by' => NULL]),
			];
		}

		$requestNotification 		= [
			'user_id'								=> $championshipData->sport_championship_created_by,
			'notification_type'			=> 'championship',
			'notification_title'		=> 'Verifikasi Data Kejuaraan Olahraga',
			'notification_slug'			=> 'sport_championships/' . $this->libIonix->Encode($championshipData->sport_championship_id) . '/manage',
			'notification_content'	=> 'Data Kejuaraan Olahraga dengan nama <strong>' . $championshipData->sport_championship_name . '</strong> yang Anda ajukan telah ' . $action->title . '. ' . $action->requirement,
		];

		$output = [
			'insertNotification'		=> $this->libIonix->insertQuery('notifications', $requestNotification),
			'pushNotification'			=> $this->libIonix->pushNotification(),
			'flash'   							=> $this->session->setFlashdata('alertToastr', [
				'type'			=> 'success',
				'header'		=> '202 Accepted',
				'message'		=> 'Berhasil <strong>' . $action->message . '</strong> <strong>Data Kejuaraan Olahraga</strong> yang diajukan',
			]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addChampionshipParticipant(object $championshipData)
	{
		$parameters = [
			'sport_championship_id' => $championshipData->sport_championship_id,
			'sport_atlet_id' 				=> $this->libIonix->Decode($this->request->getPost('value')),
		];

		if ($this->libIonix->builderQuery('sport_championship_participants')->where($parameters)->countAllResults() == false) {
			$output = [
				'insert'		=> $this->libIonix->insertQuery('sport_championship_participants', array_merge($parameters, ['sport_championship_participant_created_by' => $this->libIonix->getUserData(NULL, 'object')->user_id])),
			];
		} else {
			$output = [
				'delete'		=> $this->libIonix->deleteQuery('sport_championship_participants', $parameters),
			];
		}

		return requestOutput(202, NULL, $output);
	}

	private function updateChampionship(object $championshipData)
	{
		$request = [
			'sport_championship_code'						=> !empty($this->request->getPost('code')) ? strtolower($this->request->getPost('code')) : NULL,
			'sport_championship_year'						=> strtolower($this->request->getPost('year')),
			'sport_championship_name'						=> ucwords($this->request->getPost('name')),
			'sport_championship_level'					=> ucwords($this->request->getPost('level')),
			'sport_championship_category'				=> ucwords($this->request->getPost('category')),
			'sport_championship_location'				=> !empty($this->request->getPost('location')) ? ucwords($this->request->getPost('location')) : NULL,
			'sport_championship_explanation'			=> !empty($this->request->getPost('explanation')) ? $this->request->getPost('explanation') : NULL,
			'sport_championship_approve'				=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
		];

		if (!empty($this->request->getPost('code'))) {
			if ($championshipData->sport_championship_code != $request['sport_championship_code']) {
				if (regexUsername($request['sport_championship_code']) == false) {
					return requestOutput(411, 'Format <strong>Kode Kejuaraan Olahraga</strong> yang Anda gunakan tidak benar');
				}
			}
		}

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'championship',
				'notification_title'		=> 'Pengajuan Perubahan Data Kejuaraan Olahraga',
				'notification_slug'			=> 'sport_championships/' . $this->libIonix->Encode($championshipData->sport_championship_id) . '/manage',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan perubahan Data Kejuaraan Olahraga dengan nama ' . $request['sport_championship_name'] . ' dari ' . $this->libIonix->getUserData(NULL, 'object')->name . ' untuk ditinjau dan dipublikasikan ulang',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'update'							=> $this->libIonix->updateQuery('sport_championships', ['sport_championship_id' => $championshipData->sport_championship_id], $request),
			'pushNotification'		=> $this->libIonix->pushNotification(),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Kejuaraan Olahraga</strong> tersebut', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'championship') {
			return $this->deleteChampionship($this->modChampionship->fetchData(['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'participant') {
			return $this->deleteChampionshipParticipant($this->modParticipant->fetchData(['sport_championship_participant_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'allparticipant') {
			return $this->deleteChampionshipAllParticipant();
		}
	}

	private function deleteChampionship(object $championshipData)
	{
		if (isStakeholder() == true) {
			foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
				$request	= [
					'user_id'								=> $row->user_id,
					'notification_type'			=> 'championship',
					'notification_title'		=> 'Pengajuan Penghapusan Data Kejuaraan Olahraga',
					'notification_slug'			=> 'sport_championships/' . $this->libIonix->Encode($championshipData->sport_championship_id) . '/manage',
					'notification_content'	=> 'Anda mendapatkan pengajuan penghapusan Data ' . $championshipData->sport_championship_name . ' dari Kejuaraan Olahraga yang diajukan ' . $this->libIonix->getUserData(NULL, 'object')->name . ' untuk dihapus sepenuhnya',
				];

				$this->libIonix->insertQuery('notifications', $request);
			}

			$output = [
				'delete' 						=> $this->libIonix->updateQuery('sport_championships', ['sport_championship_id' => $championshipData->sport_championship_id], ['sport_championship_approve' => -1, 'sport_championship_deleted_at' => date('Y-m-d h:m:s')]),
				'pushNotification'	=> $this->libIonix->pushNotification(),
			];

			return requestOutput(202, 'Berhasil menghapus <strong>Kejuaraan Olahraga</strong> yang dipilih, Anda harus menunggu Data ini dihapus sepenuhnya', $output);
		} else {
			$output = [
				'delete' 	=> $this->libIonix->deleteQuery('sport_championships', ['sport_championship_id' => $championshipData->sport_championship_id]),
				'url'			=> !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? panel_url('sport_championships') : NULL,
				'flash'   => !empty($this->request->getGet('params')) && $this->request->getGet('params') == 'purge' ? $this->session->setFlashdata('alertSwal', [
					'type'		=> 'success',
					'header'	=> '202 Accepted',
					'message'	=> 'Berhasil menghapus <strong>Data</strong> dari sistem',
				]) : NULL,
			];

			return requestOutput(202, 'Berhasil menghapus <strong>Kejuaraan Olahraga</strong> yang dipilih', $output);
		}
	}

	private function deleteChampionshipParticipant(object $participantData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('sport_championship_participants', ['sport_championship_participant_id' => $participantData->sport_championship_participant_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Atlet</strong> yang dipilih pada <strong>Kejuaraan</strong>', $output);
	}

	private function deleteChampionshipAllParticipant()
	{
		if ($this->modParticipant->fetchData(['sport_championships.sport_championship_id' => $this->libIonix->Decode($this->request->getGet('id'))])->countAllResults() == false) {
			return requestOutput(406, '<strong>Kejuaraan</strong> ini belum memiliki <strong>Peserta</strong>, tidak ada yang dapat dihapus');
		}

		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('sport_championship_participants', ['sport_championship_id' => $this->libIonix->Decode($this->request->getGet('id'))]),
			'flash'   => $this->session->setFlashdata('alertSwal', [
				'type'		=> 'success',
				'header'	=> '202 Accepted',
				'message'	=> 'Berhasil menghapus <strong>Seluruh Atlet</strong> pada <strong>Kejuaraan</strong> ini',
			]),
		];

		return requestOutput(202, NULL, $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: ChampionshipController.php
 * Location: ./app/Controllers/Panel/Sport/Championship/ChampionshipController.php
 * -----------------------------------------------------------------------
 */
