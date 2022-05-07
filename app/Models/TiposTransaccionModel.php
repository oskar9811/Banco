<?php namespace App\Models;

use CodeIgniter\Model;

class TiposTransaccionModel extends Model

{
    protected $table         = 'tiposTransaccion';
    protected $primaryKey    ='id';

    protected $returnType    = 'array';
    protected $allowedFields = ['descripcion'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'descripcion'   =>'required|alpha_space|min_length[3]max_length[75]',
    ];

    protected $skipValidation = false;
}