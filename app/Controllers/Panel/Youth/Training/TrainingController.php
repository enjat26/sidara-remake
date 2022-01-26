<?php namespace App\Controllers\Panel\Youth\Training;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\Youth\TrainingModel;

/**
 * Class TrainingController
 *
 * @package App\Controllers
 */
class TrainingController extends BaseController
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
		$this->modProvince 		= new ProvinceModel();
		$this->modTraining 		= new TrainingModel();
	}

	public function index()
	{
		$data = [
			'modProvince'			=> $this->modProvince,
			'modTraining'			=> $this->modTraining,
		];

		return view('panels/youths/trainings/trainings', $this->libIonix->appInit($data));
	}

	public function detail()
	{
		$parameters = [
			'youth_training_id'			=> $this->libIonix->Decode(uri_segment(2)),
		];

		if ($this->modTraining->fetchData($parameters)->countAllResults() == true) {
			$data = [
				'modProvince'					=> $this->modProvince,
				'trainingData'				=> $this->modTraining->fetchData($parameters)->get()->getRow(),
			];

			return view('panels/youths/trainings/training-detail', $this->libIonix->appInit($data));
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'training') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modTraining->fetchData(['youth_training_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
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
 	}

 	private function getChartJSON()
 	{
		$trainingData 			= [];
		$participantData  	= [];

		$parameters = [
			'youth_training_year' => $this->request->getGet('year')
		];

 		if ($this->libIonix->Decode($this->request->getGet('id')) == 'training') {
 			foreach ($this->modTraining->fetchData($parameters)->get()->getResult() as $row) {
 				$trainingData[] 		= $row->youth_training_name;
 				$participantData[] 	= $this->libIonix->getQuery('youth_training_participants', NULL, ['youth_training_id' => $row->youth_training_id])->getNumRows();
 			}

 			$output = [
 				'trainingData' 				=> $trainingData,
 				'participantData'			=> $participantData,
 			];

 			return requestOutput(200, NULL, $output);
 		}
 	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'training') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listTrainingDT();
			}
		}
	}

	private function listTrainingDT()
	{
		$parameters = [
			'year' 	=> $this->session->year,
		];
		$i 		= $this->request->getVar('start') + 1;
		$data = [];
		foreach ($this->modTraining->fetchData($parameters, true)->getResult() as $row) {
			$subArray = [];

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">'.$row->youth_training_name.'</h6>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->youth_training_year.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.parseDate($row->youth_training_date).'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->district_type.' '.$row->district_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.number_format($this->libIonix->builderQuery('youth_training_participants')->where(['youth_training_id' => $row->youth_training_id])->countAllResults(), 0, ",", ".").'</strong></p>';
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="'.panel_url('youth_trainings/'.$this->libIonix->Encode($row->youth_training_id).'/manage').'"><i class="mdi mdi-vector-link font-size-16 align-middle text-primary me-1"></i> Rincian & Kelola</a>
																<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-training" onclick="putTraining(\'' . $this->libIonix->Encode('training') . '\', \'' . $this->libIonix->Encode($row->youth_training_id) . '\');"><i class="mdi mdi-circle-edit-outline  font-size-16 align-middle text-primary me-1"></i> Ubah Informasi</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('training') . '\', \'' . $this->libIonix->Encode($row->youth_training_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modTraining->fetchData()->countAllResults(),
			"recordsFiltered"  => $this->modTraining->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'training') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addTraining();
			} else {
				return $this->updateTraining($this->modTraining->fetchData(['youth_training_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addTraining()
	{
		$request = [
			'year' 								=> $this->session->year,
			'youth_training_district_id'		=> $this->request->getPost('district'),
			'youth_training_name'						=> ucwords($this->request->getPost('name')),
			'youth_training_year'						=> $this->request->getPost('year'),
			'youth_training_date'						=> date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getPost('date')))),
			'youth_training_explanation'		=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
		];

		$output = [
			'insert'		=> $this->libIonix->insertQuery('youth_trainings', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['youth_training_name'].'</strong> sebagai <strong>Pelatihan</strong> baru', $output);
	}

	private function updateTraining(object $trainingData)
	{
		$request = [
			'youth_training_district_id'		=> $this->request->getPost('district'),
			'youth_training_name'						=> ucwords($this->request->getPost('name')),
			'youth_training_year'						=> $this->request->getPost('year'),
			'youth_training_date'						=> date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getPost('date')))),
			'youth_training_explanation'		=> !empty($this->request->getPost('explanation')) ? ucwords($this->request->getPost('explanation')) : NULL,
		];

		$output = [
			'update'		=> $this->libIonix->updateQuery('youth_trainings', ['youth_training_id' => $trainingData->youth_training_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi <strong>Pelatihan</strong> tersebut', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'training') {
			return $this->deleteTraining($this->modTraining->fetchData(['youth_training_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'allparticipant') {
			if ($this->libIonix->builderQuery('youth_training_participants')->where(['youth_training_id' => $this->libIonix->Decode($this->request->getGet('id'))])->countAllResults() == false) {
				return requestOutput(406, 'Pelatihan ini tidak memiliki <strong>Peserta</strong> saat ini');
			}
			
			return $this->deleteTrainingAllParticipant($this->libIonix->builderQuery('youth_training_participants')->where(['youth_training_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'participant') {
			return $this->deleteTrainingParticipant($this->libIonix->builderQuery('youth_training_participants')->where(['youth_training_participant_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteTraining(object $trainingData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('youth_trainings', ['youth_training_id' => $trainingData->youth_training_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Pelatihan</strong> pada <strong>Daerah</strong> dan <strong>Tahun</strong> yang dipilih', $output);
	}

	private function deleteTrainingAllParticipant(object $trainingData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('youth_training_participants', ['youth_training_id' => $trainingData->youth_training_id]),
			'flash'   => $this->session->setFlashdata('alertToastr', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil menghapus <strong>Seluruh Peserta</strong> yang dipilih pada <strong>Pelatihan</strong> ini',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function deleteTrainingParticipant(object $participantData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('youth_training_participants', ['youth_training_participant_id' => $participantData->youth_training_participant_id]),
			'flash'   => $this->session->setFlashdata('alertToastr', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil menghapus <strong>Peserta</strong> yang dipilih pada <strong>Pelatihan</strong> ini',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: TrainingController.php
 * Location: ./app/Controllers/Panel/Youth/Training/TrainingController.php
 * -----------------------------------------------------------------------
 */
