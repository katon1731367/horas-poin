<?php namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table      = 'project';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['project_name', 'information'];
    protected $skipValidation = false;

    public function deleteProject($id)
    {
      $this->builder($this->table)->delete(['id' => $id]);
    }
}