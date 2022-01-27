<?php namespace App\Controllers\Panel\Sport\Certification;

use App\Controllers\BaseController;

use App\Models\CaborModel;
use App\Models\Sport\CertificationModel;

/**
 * Class CertificationController
 *
 * @package App\Controllers
 */
class CertificationController extends BaseController
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
		$this->modCabor 					= new CaborModel();
		$this->modCertification 	= new CertificationModel();
	}

	public function index()
	{
		$data = [
			'modCabor'							=> $this->modCabor,
			'modCertification'			=> $this->modCertification,
		];

		return view('panels/sports/certifications/certifications', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'certification') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modCertification->fetchData(['sport_certification_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'certification') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listCertificationDT();
			}
		}
	}

	private function listCertificationDT()
	{
		$parameters = [
			'sport_certifications.year' 													=> $this->session->year,
		];
		$i 		= $this->request->getVar('start') + 1;
		$data = [];
		foreach ($this->modCertification->fetchData($parameters, true)->getResult() as $row) {
			$subArray = [];

			if ($row->sport_certification_number) {
				$certificationNumber = $row->sport_certification_number;
			} else {
				$certificationNumber = '-';
			}

			if ($row->sport_certification_explanation) {
				$certificationExplanation = $row->sport_certification_explanation;
			} else {
				$certificationExplanation = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<h5 class="text-truncate font-size-14 mb-0">'.$row->sport_certification_name.'</h5>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.parseGender($row->sport_certification_gender).'</p>';
			$subArray[] = '<h5 class="text-truncate font-size-14 mb-0">'.$row->cabor_name.'</h5>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$certificationNumber.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->sport_certification_category.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->sport_certification_level.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->sport_certification_year.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$certificationExplanation.'</p>';
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-certification" onclick="putCertification(\'' . $this->libIonix->Encode('certification') . '\', \'' . $this->libIonix->Encode($row->sport_certification_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('certification') . '\', \'' . $this->libIonix->Encode($row->sport_certification_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modCertification->fetchData()->countAllResults(),
			"recordsFiltered"  => $this->modCertification->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'certification') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addCertification();
			} else {
				return $this->updateCertification($this->modCertification->fetchData(['sport_certification_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addCertification()
	{
		$request = [
			'sport_certification_name'					=> ucwords($this->request->getPost('name')),
			'sport_certification_gender'				=> strtoupper($this->request->getPost('gender')),
			'cabor_id'									=> $this->request->getPost('cabor'),
			'sport_certification_category'			=> $this->request->getPost('category'),
			'sport_certification_level'					=> ucwords($this->request->getPost('level')),
			'sport_certification_year'					=> $this->request->getPost('year'),
			'sport_certification_explanation'		=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
			'year' 													=> $this->session->year,
		];

		$output = [
			'insert'		=> $this->libIonix->insertQuery('sport_certifications', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>Sertifikasi</strong> baru atas nama <strong>'.$request['sport_certification_name'].'</strong>', $output);
	}

	private function updateCertification(object $certificationData)
	{
		$request = [
			'sport_certification_name'					=> ucwords($this->request->getPost('name')),
			'sport_certification_gender'				=> strtoupper($this->request->getPost('gender')),
			'cabor_id'									=> $this->request->getPost('cabor'),
			'sport_certification_category'			=> $this->request->getPost('category'),
			'sport_certification_level'					=> ucwords($this->request->getPost('level')),
			'sport_certification_year'					=> $this->request->getPost('year'),
			'sport_certification_explanation'		=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
		];

		$output = [
			'update'		=> $this->libIonix->updateQuery('sport_certifications', ['sport_certification_id' => $certificationData->sport_certification_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Peserta Sertifikasi</strong> tersebut', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'certification') {
			return $this->deleteCertification($this->modCertification->fetchData(['sport_certification_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteCertification(object $certificationData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('sport_certifications', ['sport_certification_id' => $certificationData->sport_certification_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Peserta Sertifikasi</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: CertificationController.php
 * Location: ./app/Controllers/Panel/Sport/Certification/CertificationController.php
 * -----------------------------------------------------------------------
 */
