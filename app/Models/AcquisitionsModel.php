<?php

namespace App\Models;

use CodeIgniter\Model;

class AcquisitionsModel extends Model
{
  protected $table      = 'acquisitions_data';
  protected $primaryKey = 'id';
  protected $returnType     = 'array';

  protected $allowedFields = ['nip', 'id_project', 'id_product', 'nominal', 'customer_name', 'cif', 'rekening', 'no_handphone', 'acquisitions_dates', 'created_at', 'deleted_at', 'visitation', 'lead_sources', 'customer_name', 'status'];

  //setup timestamps
  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function getAcquisitions()
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, p.points, acquisitions_data.*')->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project');
  }

  public function getAllAcquisitions()
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, acquisitions_data.*, u.username, u.fullname, u.id_unit, uk.unit_name')->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project')->join('users as u', 'acquisitions_data.nip = u.nip')->join('units as uk', 'uk.id = u.id_unit');
  }

  public function getUserAcquisitions($NIP)
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, p.points, acquisitions_data.*')->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project')->where('nip', $NIP);
  }

  public function getAcquisitionsId($id)
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, acquisitions_data.*')->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project')->where('acquisitions_data.id', $id)->get()->getRowArray();
  }

  public function countAcquisitions($NIP)
  {
    return $this->builder($this->table)->select('*')->where('nip', $NIP)->countAllResults();
  }

  public function deleteAcquisitions($id)
  {
    $this->builder('acquisitions_data')->delete(['id' => $id]);
  }
  
  public function searchAcquisitions($NIP, $keyword)
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, acquisitions_data.*')->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project')->like('customer_name', $keyword)->orLike('cif', $keyword)->orLike('rekening', $keyword)->orLike('product_name', $keyword)->orLike('project_name', $keyword)->where('nip', $NIP);
  }
  
  //untuk admin
  public function searchAll($keyword)
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, acquisitions_data.*, u.username, u.fullname, u.id_unit, uk.unit_name')->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project')->join('users as u', 'acquisitions_data.nip = u.nip')->join('units as uk', 'uk.id = u.id_unit')->like('customer_name', $keyword)->orLike('cif', $keyword)->orLike('rekening', $keyword)->orLike('product_name', $keyword)->orLike('project_name', $keyword);
  }

  public function getAllAcquisitionsPaginate($limit, $offset)
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, acquisitions_data.*, u.username, u.fullname, u.id_unit, uk.unit_name')->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project')->join('users as u', 'acquisitions_data.nip = u.nip')->join('units as uk', 'uk.id = u.id_unit')->get($limit, $offset)->getResult('array');
  }
  
  public function getAcquisitionsPaginate($limit, $offset, $NIP)
  {
    return $this->builder($this->table)->select('p.product_name, j.project_name, acquisitions_data.*')->where('nip', $NIP)->join('product as p', 'p.id = acquisitions_data.id_product')->join('project as j', 'j.id = acquisitions_data.id_project')->get($limit, $offset)->getResult('array');
  }
  
  public function deleteAcquisitionsAll(){
      $this->builder($this->table)->truncate();
  }
}
