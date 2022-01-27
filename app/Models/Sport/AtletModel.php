<?php

namespace App\Models\Sport;

use Config\Services;
use CodeIgniter\Model;

use App\Libraries\Ionix;

class AtletModel extends Model
{
  /**
   * Class properties go here.
   * -------------------------------------------------------------------
   * public, private, protected, static and const.
   */
  protected $table              = 'sport_atlets';
  protected $primaryKey         = 'sport_atlet_id';

  protected $returnType         = 'object';

  protected $allowedFields      = ['*'];

  protected $allowedSearch      = [
                                    'sport_atlet_name',
                                    'sport_atlet_gender',
                                    'cabor_name',
                                    'sport_atlet_level',
                                  ];

  protected $columnSearch        = [
                                      'gender'     => ['sport_atlet_gender'],
                                      'district'   => ['districts.district_id'],
                                      'cabor'      => ['cabors.cabor_id'],
                                    ];

  protected $allowedOrder        = [
                                      NULL,
                                      'sport_atlet_name',
                                      'sport_atlet_gender',
                                      'cabor_name',
                                      'sport_atlet_level',
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
      ->select($this->allowedFields)
      ->join('sport_atlet_info', 'sport_atlet_info.' . $this->primaryKey . ' = ' . $this->table . '.' . $this->primaryKey)
      ->join('cabors', 'cabors.cabor_id = '.$this->table.'.cabor_id')
      ->join('districts', 'districts.district_id = sport_atlet_info.sport_atlet_district_id')
      ->join('provinces', 'provinces.province_id = districts.province_id');

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

  public function fetchDataParticipantTournament($id,array $where = NULL, bool $limit = false, string $order = 'DESC')
  {
    $query = $this->table($this->table)
      ->select($this->allowedFields)
      ->join('sport_atlet_info', 'sport_atlet_info.' . $this->primaryKey . ' = ' . $this->table . '.' . $this->primaryKey)
      ->join('cabors', 'cabors.cabor_id = '.$this->table.'.cabor_id')
      ->join('districts', 'districts.district_id = sport_atlet_info.sport_atlet_district_id')
      ->join('provinces', 'provinces.province_id = districts.province_id')
      ->join('sport_tournaments_participants', "sport_atlets.sport_atlet_id = sport_tournaments_participants.sport_atlet_id")
      ->join('sport_tournaments', "sport_tournaments.tournament_id = sport_tournaments_participants.tournament_id");

    if (isset($where)) {
      $query->where(array_merge($where,['sport_tournaments_participants.tournament_id' => $id]));
    }

    $this->filterData($query);

    if ($order != 'CUSTOM') {
      if (!empty($this->construct()->request->getVar('order'))) {
        $query->orderBy($this->allowedSearch[$this->construct()->request->getVar('order')['0']['column']], $this->construct()->request->getVar('order')['0']['dir']);
      } else {
        $query->orderBy($this->table . '.' . $this->primaryKey, $order);
      }
    }

    if ($limit == true && !empty($this->construct()->request->getVar('length')) && $this->construct()->request->getVar('length') != -1) {
      return $query->get($this->construct()->request->getVar('length'), $this->construct()->request->getVar('start'));
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

    if (!empty($this->construct()->request->getVar('columns')[2]['search']['value'])) {
      $i = 0;
      foreach ($this->columnSearch['gender'] as $item) {
        if ($this->construct()->request->getVar('columns')[2]['search']['value']) {
          if ($i === 0) {
            $query->groupStart();
            $query->like($item, $this->construct()->request->getVar('columns')[2]['search']['value']);
          } else {
            $query->orLike($item, $this->construct()->request->getVar('columns')[2]['search']['value']);
          }
          if (count($this->columnSearch['gender']) - 1 == $i)
            $query->groupEnd();
        }
        $i++;
      }
    }

    if (!empty($this->construct()->request->getVar('columns')[3]['search']['value'])) {
      $i = 0;
      foreach ($this->columnSearch['cabor'] as $item) {
        if ($this->construct()->request->getVar('columns')[3]['search']['value']) {
          if ($i === 0) {
            $query->groupStart();
            $query->like($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
          } else {
            $query->orLike($item, $this->construct()->request->getVar('columns')[3]['search']['value']);
          }
          if (count($this->columnSearch['cabor']) - 1 == $i)
            $query->groupEnd();
        }
        $i++;
      }
    }

    if (!empty($this->construct()->request->getVar('columns')[4]['search']['value'])) {
      $i = 0;
      foreach ($this->columnSearch['district'] as $item) {
        if ($this->construct()->request->getVar('columns')[4]['search']['value']) {
          if ($i === 0) {
            $query->groupStart();
            $query->like($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
          } else {
            $query->orLike($item, $this->construct()->request->getVar('columns')[4]['search']['value']);
          }
          if (count($this->columnSearch['district']) - 1 == $i)
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
 * Filename: AtletModel.php
 * Location: ./app/Models/Sport/AtletModel.php
 * -----------------------------------------------------------------------
 */
