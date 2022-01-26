<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\YearModel;

/**
 * Class YearController
 *
 * @package App\Controllers
 */
class YearController extends BaseController
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
		$this->modYear 		= new YearModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		return view('panels/years', $this->libIonix->appInit());
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'year') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modYear->fetchData(['year_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'year') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listYearDT();
			}
		}
	}

	private function listYearDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modYear->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if ($row->year_description) {
				$yearDescription = $row->year_description;
			} else {
				$yearDescription = '<i>Tidak ada deskripsi</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<div class="text-center">'.$row->year.'</div>';
			$subArray[] = '<div class="text-center">'.$yearDescription.'</div>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-year" onclick="putYear(\''.$this->libIonix->Encode($row->year_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('year').'\', \''.$this->libIonix->Encode($row->year_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modYear->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modYear->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'year') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addYear();
			} else {
				return $this->updateYear($this->modYear->fetchData(['year_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addYear()
	{
		$request = [
			'year'								=> $this->request->getPost('year'),
			'year_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
		];

		if ($this->modYear->fetchData(['year' => $request['year']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Tahun</strong> ini sudah ada sebelumnya, tidak dapat menambahkan <strong>Tahun</strong> yang sama!');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('years', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>Tahun</strong> baru', $output);
	}

	private function updateYear(object $yearData)
	{
		$request = [
			'year'								=> $this->request->getPost('year'),
			'year_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
		];

		if ($yearData->year != $request['year']) {
			if ($this->modYear->fetchData(['year' => $request['year']])->countAllResults() == true) {
				return requestOutput(406, '<strong>Tahun</strong> ini sudah ada sebelumnya, tidak dapat menambahkan <strong>Tahun</strong> yang sama.');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('years', ['year_id' => $yearData->year_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Tahun</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'year') {
			return $this->deleteYear($this->modYear->fetchData(['year_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteYear(object $yearData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('years', ['year_id' => $yearData->year_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Tahun</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: YearController.php
 * Location: ./app/Controllers/Panel/Year/YearController.php
 * -----------------------------------------------------------------------
 */
