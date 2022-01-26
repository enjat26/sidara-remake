<?php namespace App\Models;

use Config\Services;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class UserModel extends Model
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
  protected $table              = 'users';
  protected $primaryKey         = 'user_id';

  protected $allowedFields      = ['*'];

  protected $allowedSearch      = [
                                    'name',
                                    'uuid',
                                    'username',
                                    'email',
                                    'role_name',
                                    'roles.role_code',
                                  ];

  protected $columnSearch       = [
                                    'role'        => ['role_id'],
                                  ];

  protected $allowedOrder       = [
                                    NULL,
                                    'name',
                                    'username',
                                    'email',
                                    'role_name',
                                  ];

  /**
   * __construct ()
   * --------------------------------------------------------------------
   *
   * Constructor
   *
   * NOTE: Not needed if not setting values or extending a Class.
   *
   */
  private function construct()
  {
    //--------------------------------------------------------------------
    // Preload here.
    //--------------------------------------------------------------------
    // E.g.: session = Services::session();

    return (object) [
      'libIonix'      => new Ionix(),
      'dbDefault' 	  => \Config\Database::connect('default'),
      'session'       => Services::session(),
      'request' 			=> Services::request(),
      'response' 		  => Services::response(),
      'agent' 			  => Services::request()->getUserAgent(),
    ];
  }

  public function fetchData(array $where = NULL, bool $limit = false, string $order = 'DESC', bool $filteredRole = TRUE)
  {
    $query = $this->table($this->table)
                  ->select($this->allowedFields)
                  ->join('user_info', 'user_info.'.$this->primaryKey.' = '.$this->table.'.'.$this->primaryKey)
                  ->join('roles', 'roles.role_code = '.$this->table.'.role_code');

    if (isset($where)) {
      $query->where($where);
    }

    if ($filteredRole == true) {
      $query->where('role_access <=', $this->construct()->libIonix->getUserData(NULL, 'object')->role_access);
    }

    $this->filterData($query);

    if ($order != 'CUSTOM') {
      if(!empty($this->construct()->request->getVar('order'))) {
        $query->orderBy($this->allowedOrder[$this->construct()->request->getVar('order')['0']['column']], $this->construct()->request->getVar('order')['0']['dir']);
      } else {
        $query->orderBy($this->table.'.'.$this->primaryKey, $order);
      }
    }

    if ($limit == true && !empty($this->construct()->request->getVar('length'))) {
      if ($this->construct()->request->getVar('length') != -1) {
        return $query->get($this->construct()->request->getVar('length'), $this->construct()->request->getVar('start'));
      } else {
        return $query->get();
      }
    }

    return $query;
  }

  private function filterData($query)
  {
    if(!empty($this->construct()->request->getVar('search')['value'])) {
       $i = 0;
       foreach ($this->allowedSearch as $item)
       {
         if($this->construct()->request->getVar('search')['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('search')['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('search')['value']);
           }
           if(count($this->allowedSearch) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }

    if(!empty($this->construct()->request->getVar('columns')[4]['search']['value'])) {
       $i = 0;
       foreach ($this->columnSearch['role'] as $item)
       {
         if($this->construct()->request->getVar('columns')[4]['search']['value']) {
           if ($i === 0) {
             $query->groupStart();
             $query->like($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
           } else {
             $query->orLike($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
           }
           if(count($this->columnSearch['role']) - 1 == $i)
           $query->groupEnd();
         }
       $i++;
       }
    }
  }

  // -------------------------------------------------------------------

} // End of Name Model Class.

/**
 * -----------------------------------------------------------------------
 * Filename: UserModel.php
 * Location: ./app/Models/UserModel.php
 * -----------------------------------------------------------------------
 */
