<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\RoleModel;
use App\Models\UserModel;

/**
 * Class RoleController
 *
 * @package App\Controllers
 */
class RoleController extends BaseController
{
	/**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
	protected $allowedMethod = ['access'];

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
		$this->modRole 		= New RoleModel();
		$this->modUser 		= New UserModel();
	}

	/*
	 * --------------------------------------------------------------------
	 * View Method
	 * --------------------------------------------------------------------
	 */

	public function index()
	{
		$data = [
			'modRole'			=> $this->modRole,
			'modUser'			=> $this->modUser,
			'resultRole'	=> $this->modRole->fetchData()->paginate(12, 'roles'),
			'pageRole'		=> $this->modRole->fetchData()->pager,
			'params'			=> [
												 'manage'		=> FALSE,
												 'role'			=> NULL,
												 'roleData'	=> NULL,
											 ],
		];

		return view('panels/roles', $this->libIonix->appInit($data));
	}

	public function manage()
	{
		$parameters = [
			'role_code' => $this->libIonix->Decode(uri_segment(2)),
		];

		if (!in_array($this->request->getGet('scope'), $this->allowedMethod)) {
			throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
		}

		if ($this->modRole->fetchData($parameters)->countAllResults() == true) {
			$data = [
				'modRole'			=> $this->modRole,
				'modUser'			=> $this->modUser,
				'resultRole'	=> $this->modRole->fetchData()->paginate(12, 'roles'),
				'pageRole'		=> $this->modRole->fetchData()->pager,
				'params'			=> [
												   'manage'		=> TRUE,
													 'role'			=> uri_segment(2),
													 'roleData'	=> $this->modRole->where($parameters)->first(),
												 ],
			];

			return view('panels/roles', $this->libIonix->appInit($data));
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'role') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modRole->fetchData(['role_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * Store Method
	 * --------------------------------------------------------------------
	 */

	public function store()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'role') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addRole();
			} else {
				return $this->updateRole($this->modRole->fetchData(['role_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function addRole()
	{
		$request = [
			'role_code'						=> strtolower($this->request->getPost('code')),
			'role_name'						=> ucwords($this->request->getPost('name')),
			'role_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
			'role_access'					=> $this->request->getPost('access'),
			'role_color'					=> explode('#', $this->request->getPost('color'))[1],
		];

		// if (regexUsername($request['role_code']) == false || regexNumeric($request['role_access']) == false) {
		// 	return requestOutput(411, 'Format <strong>Kode Hak Akses</strong> yang Anda gunakan tidak benar');
		// }

		if ($this->modRole->fetchData(['role_code' => $request['role_code']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Kode Akses</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode Akses</strong> yang sama');
		}

		if ($request['role_access'] < 1 || $request['role_access'] > 90) {
			return requestOutput(400, '<strong>Tipe Akses</strong> tidak boleh <strong>0</strong> atau melebihi angka <strong>90</strong>');
		}

		if ($this->modRole->fetchData(['role_access' => $request['role_access']])->countAllResults() == true) {
			return requestOutput(406, '<strong>Tipe Akses</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Tipe Akses</strong> yang sama');
		}

		$output = [
			'insert'		=> $this->libIonix->insertQuery('roles', $request),
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '200 Created',
												'message'	=> 'Berhasil menambahkan <strong>Hak Akses</strong> baru',
										 ]),
		];

		return requestOutput(201, NULL, $output);
	}

	private function updateRole(object $roleData)
	{
		$request = [
			'role_code'						=> strtolower($this->request->getPost('code')),
			'role_name'						=> ucwords($this->request->getPost('name')),
			'role_description'		=> !empty($this->request->getPost('description')) ? $this->request->getPost('description') : NULL,
			'role_access'					=> $this->request->getPost('access'),
			'role_color'					=> explode('#', $this->request->getPost('color'))[1],
		];

		if (regexUsername($request['role_code']) == false || regexNumeric($request['role_access']) == false) {
			return requestOutput(411, 'Format <strong>Kode Hak Akses</strong> yang Anda gunakan tidak benar');
		}

		if ($request['role_code'] != $roleData->role_code) {
			if ($this->modRole->fetchData(['role_code' => $request['role_code']])->get()->getNumRows() == true) {
				return requestOutput(406, '<strong>Kode Akses</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Kode Akses</strong> yang sama');
			}
		}

		if ($request['role_access'] != $roleData->role_access) {
			if ($request['role_access'] < 1 || $request['role_access'] > 90) {
				return requestOutput(400, '<strong>Tipe Akses</strong> tidak boleh <strong>0</strong> atau melebihi angka <strong>90</strong>');
			}

			if ($this->modRole->fetchData(['role_access' => $request['role_access']])->get()->getNumRows() == true) {
				return requestOutput(406, '<strong>Tipe Akses</strong> sudah digunakan sebelumnya, tidak dapat menggunakan <strong>Tipe Akses</strong> yang sama');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('roles', ['role_id' => $roleData->role_id], $request),
			'flash'   => $this->session->setFlashdata('alertToastr', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil merubah informasi <strong>Hak Akses</strong> tersebut',
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'access') {
			return $this->updateAccess($this->modRole->fetchData(['role_code' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function updateAccess(object $roleData)
	{
		$request = [
			'role_access'		=> $roleData->role_access,
			'menu_id'				=> $this->libIonix->Decode($this->request->getPost('value')),
		];

		if ($this->libIonix->getQuery('menu_access', NULL, ['role_access' => $request['role_access'], 'menu_id' => $request['menu_id']])->getNumRows() == false) {
			$output = [
				'insert'		=> $this->libIonix->insertQuery('menu_access', $request),
				'message'		=> 'Berhasil menambahkan kewenangan <strong>'.$this->libIonix->getQuery('menu_page', NULL, ['menu_id' => $request['menu_id']])->getRow()->menu_title.'</strong> pada Hak Akses ini',
			];
		} else {
			$output = [
				'delete'		=> $this->libIonix->deleteQuery('menu_access', ['role_access' => $request['role_access'], 'menu_id' => $request['menu_id']]),
				'message'		=> 'Berhasil menghapus kewenangan pada <strong>Menu</strong> ini',
			];
		}

		return requestOutput(202, $output['message'], $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'role') {
			return $this->deleteRole($this->modRole->fetchData(['role_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteRole(object $roleData)
	{
		$output = [
			'delete' 	=> $this->libIonix->deleteQuery('roles', ['role_id' => $roleData->role_id]),
			'flash'   => $this->session->setFlashdata('alertSwal', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil menghapus <strong>Hak Akses</strong> yang dipilih',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: RoleController.php
 * Location: ./app/Controllers/Panel/RoleController.php
 * -----------------------------------------------------------------------
 */
