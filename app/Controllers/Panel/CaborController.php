<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\CaborModel;

/**
 * Class CaborController
 *
 * @package App\Controllers
 */
class CaborController extends BaseController
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
		$this->modCabor 		= new CaborModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		return view('panels/cabors', $this->libIonix->appInit());
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modCabor->fetchData(['cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listCaborDT();
			}
		}
	}

	private function listCaborDT()
	{
		$i 		= $this->request->getVar('start')+1;
		$data = [];
		foreach ($this->modCabor->fetchData(NULL, true)->getResult() as $row)
		{
			$subArray = [];

			if ($row->cabor_description) {
				$caborDescription = $row->cabor_description;
			} else {
				$caborDescription = '<i>Tidak ada deskripsi</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.$i++.'.</strong></p>';
			$subArray[] = '<h6 class="text-truncate text-center mb-0">'.$row->cabor_code.'</h6>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->cabor_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$caborDescription.'</p>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-cabor" onclick="putCabor(\''.$this->libIonix->Encode($row->cabor_id).'\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\''.$this->libIonix->Encode('cabor').'\', \''.$this->libIonix->Encode($row->cabor_id).'\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
				"draw"             => intval($this->request->getVar('draw')),
				"recordsTotal"     => $this->modCabor->fetchData()->countAllResults(),
				"recordsFiltered"  => $this->modCabor->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addCabor();
			} else {
				return $this->updateCabor($this->modCabor->fetchData(['cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addCabor()
	{
		$request = [
			'cabor_code'					=> strtoupper($this->request->getPost('code')),
			'cabor_name'					=> ucwords($this->request->getPost('name')),
			'cabor_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
		];

		if (regexUsername($request['cabor_code']) == false) {
			return requestOutput(400, 'Format <strong>Kode</strong> Cabang Olahraga ini tidak diizinkan, silahkan gunakan yang lain.');
		}

		if ($this->modCabor->fetchData(['cabor_code' => $request['cabor_code']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Kode</strong> Cabang Olahraga ini sudah ada sebelumnya, tidak dapat menambahkan <strong>Cabang Olahraga</strong> yang sama!');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('cabors', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>Cabang Olahraga</strong> baru', $output);
	}

	private function updateCabor(object $caborData)
	{
		$request = [
			'cabor_code'					=> strtoupper($this->request->getPost('code')),
			'cabor_name'					=> ucwords($this->request->getPost('name')),
			'cabor_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
		];

		if ($caborData->cabor_code != $request['cabor_code']) {
			if (regexUsername($request['cabor_code']) == false) {
				return requestOutput(400, 'Format <strong>Kode</strong> Cabang Olahraga ini tidak diizinkan, silahkan gunakan yang lain.');
			}

			if ($this->modCabor->fetchData(['cabor_code' => $request['cabor_code']])->countAllResults() == true) {
				return requestOutput(406, '<strong>Kode</strong> Cabang Olahraga ini sudah ada sebelumnya, tidak dapat menambahkan <strong>Cabang Olahraga</strong> yang sama!');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('cabors', ['cabor_id' => $caborData->cabor_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Cabang Olahraga</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'cabor') {
			return $this->deleteCabor($this->modCabor->fetchData(['cabor_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteCabor(object $caborData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('cabors', ['cabor_id' => $caborData->cabor_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Cabang Olahraga</strong> yang dipilih', $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: CaborController.php
 * Location: ./app/Controllers/Panel/Cabor/CaborController.php
 * -----------------------------------------------------------------------
 */
