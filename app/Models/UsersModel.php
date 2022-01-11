<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
  protected $table      = 'users';
  protected $primaryKey = 'id';
  protected $returnType = 'array';

  protected $allowedFields = ['NIP', 'id_unit_kerja', 'email', 'username', 'fullname', 'user_image', 'password_hash', 'reset_at', 'reset_expires', 'activate_hash', 'status', 'active', 'force_pass_reset', 'created_at', 'updated_at', 'deleted_at'];

  // setup timestamps
  protected $useTimestamps = false;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function getUserGroup($id)
  {
    return $this->builder($this->table)->select('group_id')->join('auth_groups_users as g', 'g.user_id = users.id')->where('g.user_id', $id)->get()->getRowArray();
  }

  public function countAdmin()
  {
    return $this->builder($this->table)->select('group_id')->join('auth_groups_users as g', 'g.user_id = users.id')->where('g.group_id =', 1)->countAllResults();
  }

  public function getUsers()
  {
    return $this->builder($this->table)->select('users.id as userid, users.id_unit, users.nip, users.username, users.user_image, users.email, users.fullname, users.active, ag.name')->join('auth_groups_users as as', 'as.user_id = users.id')->join('auth_groups as ag', 'ag.id =  as.group_id')->get()->getResult('array');
  }

  public function getUser($id)
  {
   return $this->builder($this->table)->select('users.id as userid, users.id_unit, users.nip, users.username, users.user_image, users.email, users.fullname, users.active, ag.name')->join('auth_groups_users as as', 'as.user_id = users.id')->join('auth_groups as ag', 'ag.id =  as.group_id')->where('users.id', $id)->get()->getRow();
  }

  public function username($id)
  {
    return $this->builder('users')->select('username')->where('id', $id)->get()->getRowArray();
  }

  public function deleteUser($id)
  {
    $this->builder('users')->delete(['id' => $id]);
  }

  public function active($id)
  {
    $this->builder('users')->where([
      'id' => $id,
    ])->update([
      'active' => 1,
    ]);
  }

  public function diactive($id)
  {
    $this->builder('users')->where([
      'id' => $id,
    ])->update([
      'active' => 0,
    ]);
  }

  public function passUpdate($id, $pass){
    $this->builder('users')->where([
      'id' => $id,
    ])->update([
      'password_hash' => $pass,
    ]);
  }

  public function search($keyword){
    return $this->table('users')->like('username', $keyword)->orLike('NIP',$keyword)->orLike('email', $keyword)->orLike('id_unit', $keyword);
  }
}
