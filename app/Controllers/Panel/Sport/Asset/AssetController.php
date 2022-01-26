<?php namespace App\Controllers\Panel\Sport\Asset;

use App\Controllers\BaseController;

use App\Models\Area\ProvinceModel;
use App\Models\FileModel;
use App\Models\Sport\AssetModel;
use App\Models\UserModel;

/**
 * Class SportController
 *
 * @package App\Controllers
 */
class AssetController extends BaseController
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
		$this->modFile 				= New FileModel();
		$this->modAsset 			= new AssetModel();
		$this->modUser 						= new UserModel();
	}
	
	public function index()
	{
		$data = [
			'modProvince'		=> $this->modProvince,
			'modAsset'			=> $this->modAsset,
		];

		return view('panels/sports/assets/assets', $this->libIonix->appInit($data));
	}

	public function detail()
	{
		// dd(2);
		$parameters = [
			'asset_id'					=> $this->libIonix->Decode(uri_segment(2)),
		];

		if ($this->modAsset->fetchData($parameters)->countAllResults() == true) {
			$data = [
				'modProvince'			=> $this->modProvince,
				'assetData'				=> $this->modAsset->fetchData($parameters)->get()->getRow(),
				'arguments' 			=> explode('_', uri_segment(1))[0],
			];

			return view('panels/sports/assets/assets-detail', $this->libIonix->appInit($data));
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'category') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->libIonix->builderQuery('sport_asset_categorys')->where(['asset_category_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'asset') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(200, NULL, $this->modAsset->fetchData(['asset_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
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
		if (isStakeholder() == false) {
			$parameters = [];
		} else {
			$parameters = [
				'asset_created_by'		=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		if ($this->libIonix->Decode($this->request->getGet('id')) == 'type') {
			$data = (object) [
				'typeN'			=> $this->modAsset->fetchData(array_merge($parameters, ['asset_category_type' => false]))->countAllResults(),
				'typeA'			=> $this->modAsset->fetchData(array_merge($parameters, ['asset_category_type' => 'A']))->countAllResults(),
				'typeB'			=> $this->modAsset->fetchData(array_merge($parameters, ['asset_category_type' => 'B']))->countAllResults(),
				'typeC'			=> $this->modAsset->fetchData(array_merge($parameters, ['asset_category_type' => 'C']))->countAllResults()
			];

			$output = [
				'label' 		=> ['Tidak ada tipe ('.$data->typeN.')', 'Tipe A ('.$data->typeA.')', 'Tipe B ('.$data->typeB.')', 'Tipe C ('.$data->typeC.')'],
				'dataset'   => [
					'color'	=> ['#343a40', '#f46a6a', '#556ee6', '#34c38f'],
					'value' => [$data->typeN, $data->typeA, $data->typeB, $data->typeC],
				],
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'asset') {
			if ($this->request->getGet('format') == 'DT') {
				return $this->listAssetDT();
			}
		}
	}

	private function listAssetDT()
	{
		if (isStakeholder() == false) {
			$parameters = [];
		} else {
			$parameters = [
				'asset_created_by'		=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			];
		}

		$i 		= $this->request->getVar('start') + 1;
		$data = [];
		foreach ($this->modAsset->fetchData($parameters, true)->getResult() as $row) {
			$subArray = [];

			if ($row->asset_created_by) {
				$userData = '<h6 class="text-truncate mb-0">
												<a href="'.panel_url('u/'.$this->libIonix->getUserData(['users.user_id' => $row->asset_created_by], 'object')->username).'" target="_blank" style="color: #'.$this->libIonix->getUserData(['users.user_id' => $row->asset_created_by], 'object')->role_color.';">
														<strong>'.$this->libIonix->getUserData(['users.user_id' => $row->asset_created_by], 'object')->name.'</strong>
												</a>
										 </h6>
										 <p class="text-muted mb-0">'.$this->libIonix->getUserData(['users.user_id' => $row->asset_created_by], 'object')->role_name.'</p>';
			} else {
				$userData = '<i>NULL</i>';
			}

			$subArray[] = '<p class="text-muted text-center mb-0"><strong>' . $i++ . '.</strong></p>';
			$subArray[] = '<h6 class="text-truncate mb-0"><strong>'.$row->asset_name.'</strong></h6>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->asset_type.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0">'.$row->asset_category_name.'</p>';
			$subArray[] = '<p class="text-muted text-center mb-0"><strong>'.parseAssetType($row->asset_category_type)->text.'</strong></p>';
			$subArray[] = '<div class="text-center">'.parseApproveData($row->asset_approve)->badge.'</div>';
			$subArray[] = $userData;
			$subArray[] = '<div class="text-center">
												<div class="dropdown dropstart">
														<a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
																<i class="mdi mdi-dots-horizontal font-size-18"></i>
														</a>
														<div class="dropdown-menu">
																<a class="dropdown-item" href="'.panel_url('sport_assets/'.$this->libIonix->Encode($row->asset_id).'/manage').'"><i class="mdi mdi-vector-link font-size-16 align-middle text-primary me-1"></i> Rincian & Kelola</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item" href="javascript:void(0);" onclick="deleteMethod(false ,\'' . $this->libIonix->Encode('asset') . '\', \'' . $this->libIonix->Encode($row->asset_id) . '\');"><i class="mdi mdi-trash-can-outline font-size-16 align-middle text-danger me-1"></i> Hapus</a>
														</div>
												</div>
										</div>';
			$data[] = $subArray;
		}
		$output = [
			"draw"             => intval($this->request->getVar('draw')),
			"recordsTotal"     => $this->modAsset->fetchData($parameters, false)->countAllResults(),
			"recordsFiltered"  => $this->modAsset->fetchData($parameters)->get()->getNumRows(),
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
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'category') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addCategory();
			} else {
				return $this->updateCategory($this->libIonix->builderQuery('sport_asset_categorys')->where(['asset_category_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'asset') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addAsset();
			} else {
				return $this->updateAsset($this->modAsset->fetchData(['asset_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'image') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addImage($this->modAsset->fetchData(['asset_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'resub') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addResub($this->modAsset->fetchData(['asset_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'verify') {
			if ($this->request->getGet('id') == 'add') {
				return $this->addVerify($this->modAsset->fetchData(['asset_id' => $this->libIonix->Decode($this->request->getGet('params'))])->get()->getRow());
			}
		}
	}

	private function addCategory()
	{
		$request = [
			'asset_category_name'					=> ucwords($this->request->getPost('name')),
		];

		if ($this->libIonix->builderQuery('sport_asset_categorys')->where(['asset_category_name' => $request['asset_category_name']])->countAllResults() > 0) {
			return requestOutput(406, '<strong>Nama Kategori</strong> ini sudah ada, tidak dapat menambahkan <strong>Kategori</strong> dengan nama yang sama.');
		}

		$output = [
			'insert' 	=> $this->libIonix->insertQuery('sport_asset_categorys', $request),
			'flash'   => $this->session->setFlashdata('alertToastr', [
											'type'		=> 'success',
											'header'	=> '201 Created',
											'message'	=> 'Berhasil menambahkan <strong>Kategori</strong> baru',
									 ]),
		];

		return requestOutput(201, NULL, $output);
	}

	private function updateCategory(object $categoryData)
	{
		$request = [
			'asset_category_name'					=> ucwords($this->request->getPost('name')),
		];

		if ($categoryData->asset_category_name != $request['asset_category_name']) {
			if ($this->libIonix->builderQuery('sport_asset_categorys')->where(['asset_category_name' => $request['asset_category_name']])->countAllResults() > 0) {
				return requestOutput(406, '<strong>Nama Kategori</strong> ini sudah ada, tidak dapat menambahkan <strong>Kategori</strong> dengan nama yang sama.');
			}
		}

		$output = [
			'update'	=> $this->libIonix->updateQuery('sport_asset_categorys', ['asset_category_id' => $categoryData->asset_category_id], $request),
			'flash'   => $this->session->setFlashdata('alertToastr', [
											'type'		=> 'success',
											'header'	=> '202 Accepted',
											'message'	=> 'Berhasil merubah informasi pada <strong>Kategori</strong> ini',
									 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addAsset()
	{
		$requestAsset = [
			'district_id'			=> $this->request->getPost('district'),
			'asset_category_type'			=> $this->request->getPost('category_type'),
			'asset_category_id'			=> $this->request->getPost('category'),
			'asset_type'						=> $this->request->getPost('type'),
			'asset_name'						=> ucwords($this->request->getPost('name')),
			'asset_description'			=> $this->request->getPost('description'),
			'asset_production_year'	=> $this->request->getPost('year'),
			'asset_condition'				=> $this->request->getPost('condition'),
			'asset_management'			=> $this->request->getPost('management'),
			'asset_managed_by'			=> ucwords($this->request->getPost('managedby')),
			'asset_approve'					=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'asset_approve_by'			=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
			'asset_created_by'			=> $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		// dd($requestAsset);
		$query = (object) [
			'insert'		=> $this->libIonix->insertQuery('sport_assets', $requestAsset),
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'asset',
				'notification_title'		=> 'Pengajuan Penambahan Data '.$requestAsset['asset_type'],
				'notification_slug'			=> 'sport_assets/'.$this->libIonix->Encode($query->insert).'/manage',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan penambahan Data '.$requestAsset['asset_type'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk dipublikasikan',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'pushNotification'	=> $this->libIonix->pushNotification(),
		];

		return requestOutput(201, 'Berhasil menambahkan <strong>'.$requestAsset['asset_name'].'</strong> sebagai <strong>'.$requestAsset['asset_type'].'</strong> baru', $output);
	}

	private function addResub(object $assetData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			return requestOutput(400);
		}

		$output = [
			'update'								=> $this->libIonix->updateQuery('assets', ['asset_id' => $assetData->asset_id], ['asset_approve' => $assetData->asset_approve+1]),
			'flash'   							=> $this->session->setFlashdata('alertToastr', [
													 					'type'			=> 'success',
													 					'header'		=> '202 Accepted',
													 					'message'		=> 'Berhasil <strong>mendaftarkan ulang</strong> <strong>'.$assetData->asset_name.'</strong> untuk diperbaiki dan diajukan kembali',
										 		 				 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addVerify(object $assetData)
	{
		if (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == true) {
			$action = (object) [
				'title'					=> 'diterima',
				'message'				=> 'menerima',
				'requirement'		=> 'Sekarang Data tersebut sudah tayang pada Halaman Utama',
				'update'				=> $this->libIonix->updateQuery('assets', ['asset_id' => $assetData->asset_id], ['asset_approve' => $assetData->asset_approve+1, 'asset_approve_by' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			];
		} elseif (filter_var($this->request->getPost('action'), FILTER_VALIDATE_BOOLEAN) == false) {
			$action = (object) [
				'title'					=> 'ditolak',
				'message'				=> 'menolak',
				'requirement'		=> 'Silahkan untuk perbaiki data dan mengajukan ulang.',
				'update'				=> $this->libIonix->updateQuery('assets', ['asset_id' => $assetData->asset_id], ['asset_approve' => $assetData->asset_approve-2, 'asset_approve_by' => NULL]),
			];
		}

		$requestNotification 		= [
			'user_id'								=> $assetData->asset_created_by,
			'notification_type'			=> 'asset',
			'notification_title'		=> 'Verifikasi Data '.$assetData->asset_type,
			'notification_slug'			=> 'sport_assets/'.$this->libIonix->Encode($assetData->asset_id).'/manage',
			'notification_content'	=> 'Data '.$assetData->asset_type.' dengan nama <strong>'.$assetData->asset_name.'</strong> yang Anda ajukan telah '.$action->title.'. '.$action->requirement,
		];

		$output = [
			'insertNotification'		=> $this->libIonix->insertQuery('notifications', $requestNotification),
			'pushNotification'			=> $this->libIonix->pushNotification(),
			'flash'   							=> $this->session->setFlashdata('alertToastr', [
													 					'type'			=> 'success',
													 					'header'		=> '202 Accepted',
													 					'message'		=> 'Berhasil <strong>'.$action->message.'</strong> <strong>Data '.$assetData->asset_type.'</strong> yang diajukan',
										 		 				 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	private function addImage(object $assetData)
	{
		$request = [
			'asset_id'							=> $assetData->asset_id,
			'asset_image_source'		=> $this->request->getFile('image')->getRandomName(),
		];

		$config = [
			'directory'	=> $this->configIonix->uploadsFolder['asset'],
			'fileName'	=> $this->request->getFile('image')->isValid() ? $request['asset_image_source'] : NULL,
		];

		if ($this->libIonix->builderQuery('sport_asset_images')->where(['asset_id' => $assetData->asset_id])->countAllResults() >= 3) {
			return requestOutput(406, 'Anda hanya dapat menambahkan <strong>3 Foto/Gambar</strong>. Tidak dapat menggunggah lebih dari <strong>3 Foto/Gambar</strong>');
		}

		$output = [
			'insert'		=> $this->libIonix->insertQuery('sport_asset_images', $request),
			'upload'		=> $this->request->getFile('image')->isValid() ? $this->request->getFile('image')->move($config['directory'], $config['fileName'], true) : NULL,
			'flash'   	=> $this->session->setFlashdata('alertToastr', [
												'type'		=> 'success',
												'header'	=> '201 Created',
												'message'	=> 'Berhasil mengunggah <strong>Foto/Gambar</strong> baru pada <strong>'.$assetData->asset_type.'</strong> ini',
										 ]),
		];

		return requestOutput(201, NULL, $output);
	}

	private function updateAsset(object $assetData)
	{
		$requestAsset = [
			'district_id'			=> $this->request->getPost('district'),
			'asset_category_id'			=> $this->request->getPost('category'),
			'asset_category_type'			=> $this->request->getPost('category_type'),
			'asset_type'						=> $this->request->getPost('type'),
			'asset_name'						=> ucwords($this->request->getPost('name')),
			'asset_description'			=> $this->request->getPost('description'),
			'asset_production_year'	=> $this->request->getPost('year'),
			'asset_condition'				=> $this->request->getPost('condition'),
			'asset_management'			=> $this->request->getPost('management'),
			'asset_managed_by'			=> ucwords($this->request->getPost('managedby')),
			'asset_map'							=> !empty($this->request->getPost('map')) ? strtolower($this->request->getPost('map')) : NULL,
			'asset_approve'					=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? 2 : 3,
			'asset_approve_by'			=> isStakeholder() == true && $this->configIonix->allowVerifycation == true ? NULL : $this->libIonix->getUserData(NULL, 'object')->user_id,
		];

		foreach ($this->modUser->fetchData(['role_access >=' => $this->configIonix->roleController, 'active' => true], false, 'DESC', false)->get()->getResult() as $row) {
			$requestNotification	= [
				'user_id'								=> $row->user_id,
				'notification_type'			=> 'asset',
				'notification_title'		=> 'Pengajuan Perubahan Data '.$requestAsset['asset_type'],
				'notification_slug'			=> 'sport_assets/'.$this->libIonix->Encode($assetData->asset_id).'/manage',
				'notification_content'	=> 'Anda mendapatkan pengajuan persetujuan perubahan Data '.$requestAsset['asset_type'].' dari '.$this->libIonix->getUserData(NULL, 'object')->name.' untuk ditinjau dan dipublikasikan ulang',
			];

			if (isStakeholder() == true) {
				$this->libIonix->insertQuery('notifications', $requestNotification);
			}
		}

		$output = [
			'update'						=> $this->libIonix->updateQuery('sport_assets', ['asset_id' => $assetData->asset_id], $requestAsset),
			'pushNotification'	=> $this->libIonix->pushNotification(),
			'flash'   					=> $this->session->setFlashdata('alertToastr', [
															 'type'			=> 'success',
															 'header'		=> '202 Accepted',
															 'message'	=> 'Berhasil merubah informasi <strong>'.$requestAsset['asset_type'].'</strong> tersebut',
										 				 ]),
		];

		return requestOutput(202, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Upload Method
	 * --------------------------------------------------------------------
	 */

	private function uploadAttachment(object $assetData)
	{
		$request = [
			'user_id'							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'file_type'						=> 'attachment',
			'file_name' 					=> 'Lampiran Sarana dan Prasarana - '.$assetData->asset_participant_name.' - '.$assetData->asset_championship_name,
			'file_source'					=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->getRandomName() : NULL,
			'file_size'						=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->getSize('b') : NULL,
			'file_extension' 			=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->getClientExtension() : NULL,
		];

		$config = [
			'directory'	=> $this->configIonix->uploadsFolder['attachment'],
			'fileName'	=> $this->request->getFile('file')->isValid() ? $request['file_source'] : NULL,
		];

		$upload = (object) [
			'move'			=> $this->request->getFile('file')->isValid() ? $this->request->getFile('file')->move($config['directory'], $config['fileName'], true) : NULL,
			'insert'		=> $request['file_source'] ? $this->libIonix->insertQuery('files', $request) : NULL,
		];

		$output = [
			'update'		=> $this->libIonix->updateQuery('sport_assets', ['asset_id' => $assetData->asset_id], ['file_id' => $upload->insert]),
		];

		return $output;
	}

	private function removeAttachment(object $assetData)
	{
		if ($assetData->file_id) {
			if ($this->modFile->fetchData(['file_id' => $assetData->file_id])->countAllResults() == true) {
				$data = (object) [
					'file' 	=> $this->modFile->fetchData(['file_id' => $assetData->file_id])->get()->getRow(),
				];

				if (file_exists($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source)) {
					unlink($this->configIonix->uploadsFolder[$data->file->file_type].$data->file->file_source);
				}
			}
		}

		$output = [
			'remove'	=> $assetData->file_id ? $this->libIonix->deleteQuery('files', ['file_id' => $data->file->file_id]) : NULL,
		];

		return $output;
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'category') {
			return $this->deleteCategory($this->modAsset->fetchData(['asset_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}

		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'asset') {
			return $this->deleteAsset($this->modAsset->fetchData(['asset_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
		}
	}

	private function deleteAsset(object $assetData)
	{
		$output = [
			'remove'	=> $this->removeAttachment($assetData),
			'delete' 	=> $this->libIonix->deleteQuery('sport_assets', ['asset_id' => $assetData->asset_id]),
		];

		return requestOutput(202, 'Berhasil menghapus <strong>Sarana dan Prasarana</strong> yang dipilih', $output);
	}

	// -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: Sport/SportController.php
 * Location: ./app/Controllers/Panel/Sport/Asset/AssetController.php
 * -----------------------------------------------------------------------
 */
