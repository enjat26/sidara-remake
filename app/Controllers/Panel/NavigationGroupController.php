<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\NavigationGroupModel;

/**
 * Class NavigationGroupController
 *
 * @package App\Controllers
 */
class NavigationGroupController extends BaseController
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
		$this->modNavigationGroup 		= new NavigationGroupModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		return view('panels/navigation-group', $this->libIonix->appInit());
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation_group') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modNavigationGroup->fetchData(['group_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation_group') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listNavigationGroupDT();
			}
		}
	}

	private function listNavigationGroupDT()
	{
		$i 		= $this->request->getVar('start') + 1;
		$data = [];
		foreach ($this->modNavigationGroup->fetchData(NULL, true)->getResult() as $row) {
			$subArray = [];

			if ($row->group_description) {
				$groupDescription = $row->group_description;
			} else {
				$groupDescription = '<i>Tidak ada deskripsi</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<p class="text-muted mb-0">' . $row->group_code . '</p>';
			$subArray[] = '<p class="text-muted mb-0">' . $row->group_title . '</p>';
			$subArray[] = '<p class="text-muted mb-0">' . $groupDescription . '</p>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-navigation-groups" onclick="putNavigationGroup(\'' . $this->libIonix->Encode($row->group_id) . '\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('navigation_group') . '\', \'' . $this->libIonix->Encode($row->group_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modNavigationGroup->fetchData()->countAllResults(),
			"recordsFiltered"  => $this->modNavigationGroup->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation_group') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addNavigationGroup();
			} else {
				return $this->updateNavigationGroup($this->modNavigationGroup->fetchData(['group_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addNavigationGroup()
	{
		$request = [
			'group_code'				=> strtolower($this->request->getPost('code')),
			'group_title'				=> ucwords($this->request->getPost('title')),
			'group_description'	=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
		];

		if ($this->modNavigationGroup->fetchData(['group_code' => $request['group_code']])->countAllResults() == true) {
			return requestOutput(406, 'Kode <strong>Grup Navigasi</strong> sudah digunakan sebelumnya, tidak dapat menggunakan kode <strong>Grup Navigasi</strong> yang sama');
		}

		$output = [
			'insert'	=> $this->libIonix->insertQuery('menu_group', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>' . $request['group_title'] . '</strong> sebagai <strong>Grup Navigasi</strong> baru', $output);
	}

	private function updateNavigationGroup(object $navigationGroups)
	{
		$request = [
			'group_code'				=> strtolower($this->request->getPost('code')),
			'group_title'				=> ucwords($this->request->getPost('title')),
			'group_description'	=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
		];

		$output = [
			'update'	=> $this->libIonix->updateQuery('menu_group', ['group_id' => $navigationGroups->group_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Grup Navigasi</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation_group') {
			return $this->deleteNavigationGroup($this->modNavigationGroup->fetchData(['group_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteNavigationGroup(object $navigationGroups)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('menu_group', ['group_id' => $navigationGroups->group_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Grup Navigasi</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: NavigationGroupController.php
 * Location: ./app/Controllers/Panel/NavigationGroup/NavigationGroupController.php
 * -----------------------------------------------------------------------
 */
