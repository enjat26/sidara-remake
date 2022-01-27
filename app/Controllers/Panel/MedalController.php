<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\MedalModel;

/**
 * Class MedalController
 *
 * @package App\Controllers
 */
class MedalController extends BaseController
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
		$this->modMedal 		= new MedalModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		return view('panels/medals', $this->libIonix->appInit());
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'medal') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modMedal->fetchData(['sport_medal_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'medal') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listMedalDT();
			}
		}
	}

	private function listMedalDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modMedal->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<div class="text-center">'.$row->sport_medal_name.'</div>';
			$subArray[] = '<div class="text-center">'.$row->sport_medal_point.'</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-medal" onclick="putMedal(\''.$this->libIonix->Encode($row->sport_medal_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('medal').'\', \''.$this->libIonix->Encode($row->sport_medal_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modMedal->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modMedal->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'medal') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addMedal();
			} else {
				return $this->updateMedal($this->modMedal->fetchData(['sport_medal_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addMedal()
	{
		$request = [
			'sport_medal_name'					=> $this->request->getPost('name'),
			'sport_medal_point'				  => $this->request->getPost('point'),
		];

		$output = [
			'insert'	=> $this->libIonix->insertQuery('sport_medals', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$request['sport_medal_name'].'</strong> sebagai Medali baru', $output);
	}

	private function updateMedal(object $medalData)
	{
		$request = [
			'sport_medal_name'					=> $this->request->getPost('name'),
			'sport_medal_point'				  => $this->request->getPost('point'),
		];

		$output = [
			'update'	=> $this->libIonix->updateQuery('sport_medals', ['sport_medal_id' => $medalData->sport_medal_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Medali</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'medal') {
			return $this->deleteMedal($this->modMedal->fetchData(['sport_medal_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteMedal(object $medalData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('sport_medals', ['sport_medal_id' => $medalData->sport_medal_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Medali</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: MedalController.php
 * Location: ./app/Controllers/Panel/Medal/MedalController.php
 * -----------------------------------------------------------------------
 */
