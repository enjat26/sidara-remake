<?php

namespace App\Models;

use Config\Services;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class MedalModel extends Model
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
  protected $table              = 'sport_medals';
  protected $primaryKey         = 'sport_medal_id';

  protected $returnType         = 'object';

  protected $allowedFields      = ['*'];

  protected $allowedSearch      = [
                                    'sport_medal_name',
                                  ];

  protected $columnSearch       = NULL;

  protected $allowedOrder       = [
                                    NULL,
                                    'sport_medal_name',
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
      'dbDefault'     => \Config\Database::connect('default'),
      'session'       => Services::session(),
      'request'       => Services::request(),
      'response'       => Services::response(),
      'agent'         => Services::request()->getUserAgent(),
    ];
  }

  public function fetchData(array $where = NULL, bool $limit = false, string $order = 'DESC')
  {
    $query = $this->table($this->table)
                  ->select($this->allowedFields);


    if (isset($where)) {
      $query->where($where);
    }

    $this->filterData($query);

    if ($order != 'CUSTOM') {
      if (!empty($this->construct()->request->getVar('order'))) {
        $query->orderBy($this->allowedOrder[$this->construct()->request->getVar('order')['0']['column']], $this->construct()->request->getVar('order')['0']['dir']);
      } else {
        $query->orderBy($this->table . '.' . $this->primaryKey, $order);
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
    if (!empty($this->construct()->request->getVar('search')['value'])) {
      $i = 0;
      foreach ($this->allowedSearch as $item) {
        if ($this->construct()->request->getVar('search')['value']) {
          if ($i === 0) {
            $query->groupStart();
            $query->like($item, $this->construct()->request->getVar('search')['value']);
          } else {
            $query->orLike($item, $this->construct()->request->getVar('search')['value']);
          }
          if (count($this->allowedSearch) - 1 == $i)
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
 * Filename: MedalModel.php
 * Location: ./app/Modelss/Sport/MedalModel.php
 * -----------------------------------------------------------------------
 */
