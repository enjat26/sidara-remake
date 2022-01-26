<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;
use App\Models\NavigationGroupModel;
use App\Models\NavigationModel;

/**
 * Class NavigationController
 *
 * @package App\Controllers
 */
class NavigationController extends BaseController
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
		$this->modNavigation 					= new NavigationModel();
		$this->modNavigationGroup 		= new NavigationGroupModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modNavigation' 				=> $this->modNavigation,
			'modNavigationGroup' 		=> $this->modNavigationGroup,
		];

		return view('panels/navigations', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modNavigation->fetchData(['menu_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listNavigationDT();
			}
		}
	}

	private function listNavigationDT()
	{
		$i 		= $this->request->getVar('start') + 1;
		$data = [];

		foreach ($this->modNavigation->fetchData(NULL, true)->getResult() as $row) {
			$subArray 	= [];

			if ($row->menu_icon) {
				$menuIcon = '<i class="' . $row->menu_icon . ' me-2"></i>' . $row->menu_icon;
			} else {
				$menuIcon = '-';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $row->menu_id . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0">' . $row->group_title . '</h6>
										<p class="text-muted mb-0">' . $row->group_code . '</p>';
			$subArray[] = '<p class="text-muted mb-0">' . $row->menu_title . '</p>';
			$subArray[] = '<p class="text-muted mb-0">' . $row->menu_link . '</p>';
			$subArray[] = '<p class="text-muted mb-0">'.$menuIcon.'</p>';
			$subArray[] = '<div class="dropdown text-center dropstart">
												<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="mdi mdi-dots-horizontal font-size-18"></i>
												</a>
												<div class="dropdown-menu">
														<a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-navigations" onclick="putNavigation(\'' . $this->libIonix->Encode($row->menu_id) . '\');"><i class="mdi mdi-circle-edit-outline font-size-16 align-middle text-primary me-1"></i>Ubah Informasi</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('navigation') . '\', \'' . $this->libIonix->Encode($row->menu_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i>Hapus</a>
												</div>
										</div>';
			$data[] 		= $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modNavigation->fetchData()->countAllResults(),
			"recordsFiltered"  => $this->modNavigation->fetchData()->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addNavigation();
			} else {
				return $this->updateNavigation($this->modNavigation->where(['menu_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addNavigation()
	{
		$request = [
			'group_id'					=> $this->request->getPost('group'),
			'menu_order'				=> $this->request->getPost('order'),
			'menu_title'				=> ucwords($this->request->getPost('title')),
			'menu_link'					=> !empty($this->request->getPost('link')) ? strtolower($this->request->getPost('link')) : 'javascript:void(0)',
			'menu_icon'					=> !empty($this->request->getPost('icon')) ? strtolower($this->request->getPost('icon')) : NULL,
			'menu_parent'				=> !empty($this->request->getPost('parent')) ? $this->request->getPost('parent') : false,
			'menu_previlege'		=> $this->request->getPost('previlege'),
		];

		if (regexUsername($request['menu_link']) == false) {
      return requestOutput(411, 'Format <strong>Link</strong> yang Anda gunakan tidak benar');
    }

		$output = [
			'insert'	=> $this->libIonix->insertQuery('menu_page', $request),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>' . $request['menu_title'] . '</strong> sebagai <strong>Navigasi</strong> baru', $output);
	}

	private function updateNavigation(object $navigationData)
	{
		$request = [
			'group_id'					=> $this->request->getPost('group'),
			'menu_order'				=> $this->request->getPost('order'),
			'menu_title'				=> ucwords($this->request->getPost('title')),
			'menu_link'					=> !empty($this->request->getPost('link')) ? strtolower($this->request->getPost('link')) : 'javascript:void(0)',
			'menu_icon'					=> !empty($this->request->getPost('icon')) ? strtolower($this->request->getPost('icon')) : NULL,
			'menu_parent'				=> !empty($this->request->getPost('parent')) ? $this->request->getPost('parent') : false,
			'menu_previlege'		=> $this->request->getPost('previlege'),
		];

		if (regexUsername($request['menu_link']) == false) {
      return requestOutput(411, 'Format <strong>Link</strong> yang Anda gunakan tidak benar');
    }

		$output = [
			'update'	=> $this->libIonix->updateQuery('menu_page', ['menu_id' => $navigationData->menu_id], $request),
		];

		return requestOutput(202, 'Berhasil merubah informasi pada <strong>Navigasi</strong> ini', $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'navigation') {
			return $this->deleteNavigation($this->modNavigation->fetchData(['menu_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteNavigation(object $navigations)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('menu_page', ['menu_id' => $navigations->menu_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Navigasi</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: NavigationController.php
 * Location: ./app/Controllers/Panel/Navigation/NavigationController.php
 * -----------------------------------------------------------------------
 */
