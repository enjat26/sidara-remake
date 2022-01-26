<?php namespace App\Controllers\Panel;

use App\Controllers\BaseController;

use App\Models\NotificationModel;

/**
 * Class NotificationController
 *
 * @package App\Controllers
 */
class NotificationController extends BaseController
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
		$this->modNotification			= New NotificationModel();
	}

	public function index()
	{
		$data = [
			'modNotification'		=> $this->modNotification,
		];

		return view('panels/notifications', $this->libIonix->appInit($data));
	}

	/*
	 * --------------------------------------------------------------------
	 * Count Method
	 * --------------------------------------------------------------------
	 */

	public function count()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'notification') {
			if ($this->request->getGet('format') == 'JSON') {
				return requestOutput(202, NULL, $this->modNotification->fetchData(['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id, 'notification_status' => 'unread'])->countAllResults());
			}
		}
	}

	/*
	 * --------------------------------------------------------------------
	 * Get Method
	 * --------------------------------------------------------------------
	 */

	public function get()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'notification') {
			if ($this->request->getGet('format') == 'Javascript') {
				return $this->getNotificationJavascript();
			}
		}
	}

	private function getNotificationJavascript() {
		$parameters = [
			'user_id' 							=> $this->libIonix->getUserData(NULL, 'object')->user_id,
			'notification_status' 	=> 'unread',
		];

		$notification = (object) [
			'count'		=> $this->modNotification->fetchData($parameters)->countAllResults(),
			'data'		=> $this->modNotification->fetchData($parameters)->get(1)->getRow(),
		];

		if ($notification->count > $this->session->notification) {
			$output = [
				'title'			=> '('.strtoupper($this->configIonix->appCode).') '.$notification->data->notification_title,
				'message'		=> $notification->data->notification_content ? $notification->data->notification_content : 'Tidak ada catatan',
				'url'				=> panel_url($notification->data->notification_slug),
			];
		} else {
			$output = NULL;
		}

		$action = [
			'update'	=> $this->session->set(['notification' => $notification->count]),
		];

		return requestOutput(200, NULL, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * List Method
	 * --------------------------------------------------------------------
	 */

	public function list()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'notification') {
			if ($this->request->getGet('format') == 'HTML') {
				return $this->listNotificationHTML();
			}
		}
	}

	private function listNotificationHTML()
	{
		foreach ($this->modNotification->fetchData(['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id])->get()->getResult() as $row) {
			if (strlen($row->notification_title) > 35) {
				$notificationTitle = substr($row->notification_title, 0, 35).'...';
			} else {
				$notificationTitle = $row->notification_title;
			}

			if ($row->notification_content) {
				if (strlen($row->notification_content) > 80) {
					$notificationContent = substr($row->notification_content, 0, 80).'...';
				} else {
					$notificationContent = $row->notification_content;
				}
			} else {
				$notificationContent = '<i>Tidak ada catatan</i>';
			}

			echo '<a href="'.panel_url($row->notification_slug).'" class="text-reset notification-item '.$row->notification_status.'"  onclick="updateNotification(false, \''.$this->libIonix->Encode('notification').'\', \''.$this->libIonix->Encode($row->notification_id).'\');">
								<div class="media">
										<div class="avatar-xs me-3">
												'.parseNotificationIcon($row->notification_status).'
										</div>
										<div class="media-body">
												<h6 class="mt-0 mb-1" >'.$notificationTitle.'</h6>
												<div class="font-size-12 text-muted">
														<p class="text-muted text-justify">'.$notificationContent.'</p>
														<div class="text-end">
																<p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>'.parseDateDiff($row->notification_created_at)->getRelative().'</span></p>
														</div>
												</div>
										</div>
								</div>
						</a>';
		} exit;
	}

	/*
	 * --------------------------------------------------------------------
	 * Update Method
	 * --------------------------------------------------------------------
	 */

	public function update()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'notification') {
			if ($this->request->getGet('id') == 'all') {
				return $this->updateNotification();
			} else {
				return $this->updateNotification($this->modNotification->fetchData(['notification_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function updateNotification(object $notificationData = NULL)
	{
		if (isset($notificationData)) {
			if ($notificationData->notification_status == 'unread') {
				$output = (object) [
					'code'					=> 202,
					'message'				=> '<strong>Notifikasi</strong> ini telah diubah menjadi telah dibaca',
					'notification'	=> $this->libIonix->pushNotification(),
					'update'				=> $this->libIonix->updateQuery('notifications', ['notification_id' => $notificationData->notification_id], ['notification_status' => NULL]),
				];
			} else {
				return requestOutput(202);
			}
		} else {
			if ($this->modNotification->fetchData(['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id, 'notification_status' => 'unread'])->countAllResults() == false) {
				$output = (object) [
					'code'		=> 406,
					'message'	=> 'Tidak ada <strong>Notifikasi</strong> yang belum dibaca oleh Anda',
				];
			} else {
				$output = (object) [
					'code'						=> 202,
					'message'					=> 'Seluruh <strong>Notifikasi</strong> yang belum dibaca telah diubah menjadi dibaca',
					'notification'		=> $this->libIonix->pushNotification(),
					'update'					=> $this->libIonix->updateQuery('notifications', ['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id], ['notification_status' => NULL]),
				];
			}
		}

		return requestOutput($output->code, $output->message, $output);
	}

	/*
	 * --------------------------------------------------------------------
	 * Delete Method
	 * --------------------------------------------------------------------
	 */

	public function delete()
	{
		if ($this->libIonix->Decode($this->request->getGet('scope')) == 'notification') {
			if ($this->request->getGet('id') == 'all') {
				return $this->deleteNotification();
			} else {
				return $this->deleteNotification($this->modNotification->fetchData(['notification_id' => $this->libIonix->Decode($this->request->getGet('id'))])->get()->getRow());
			}
		}
	}

	private function deleteNotification(object $notificationData = NULL)
	{
		if (!isset($notificationData) && $this->modNotification->fetchData(['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id])->countAllResults() == false) {
			return requestOutput(406, 'Anda tidak memiliki <strong>Notifikasi</strong> saat ini');
		}

		$output = [
			'delete' 				=> isset($notificationData) ? $this->libIonix->deleteQuery('notifications', ['notification_id' => $notificationData->notification_id]) : $this->libIonix->deleteQuery('notifications', ['user_id' => $this->libIonix->getUserData(NULL, 'object')->user_id]),
			'flash'   			=> $this->session->setFlashdata('alertSwal', [
													 'type'			=> 'success',
													 'header'		=> '202 Accepted',
													 'message'	=> isset($notificationData) ? 'Berhasil menghapus <strong>Notifikasi</strong> yang dipilih' : 'Berhasil menghapus seluruh <strong>Notifikasi</strong> pada Akun Anda',
									 			 ]),
		];

		return requestOutput(202, NULL, $output);
	}

  // -------------------------------------------------------------------

} // End of Name Controller Class.

/**
 * -----------------------------------------------------------------------
 * Filename: NotificationController.php
 * Location: ./app/Controllers/Panel/NotificationController.php
 * -----------------------------------------------------------------------
 */
