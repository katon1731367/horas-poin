<?php

namespace App\Models;

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\Model;

class AuthGroupModel extends Model
{
  protected $builder;
  public function __construct()
  {
    $db      = \Config\Database::connect();
    $this->builder = $db->table('auth_groups_users');
  }

  protected $table      = 'auth_groups_users';

  protected $returnType     = 'array';
  //  protected $useSoftDeletes = true;

  protected $allowedFields = ['id_user', 'id_group'];

  public function makeAdmin($id)
  {
    $this->builder->join('auth_groups', 'auth_groups.id =  auth_groups_users.group_id');
    $this->builder->where([
      'user_id' => $id,
      'group_id' => 2
    ]);
    $this->builder->update([
      'user_id' => $id,
      'group_id' => 1
    ]);
  }

  public function makeUser($id)
  {
    $this->builder->join('auth_groups', 'auth_groups.id =  auth_groups_users.group_id');
    $this->builder->where([
      'user_id' => $id,
      'group_id' => 1
    ]);
    $this->builder->update([
      'user_id' => $id,
      'group_id' => 2
    ]);
  }

  public function activation($id, $active)
  {
    $this->builder->where(['id' => $id]);
    $this->builder->update(['active' => $active]);
  }
}
