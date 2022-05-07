<?php namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model

{
    protected $table         = 'rol';
    protected $primaryKey    ='id';

    protected $returnType    = 'array';
    protected $allowedFields = ['nombre'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nombre'   =>'required|alpha_space|min_length[3]max_length[75]',
        
    ];

    protected $validationMessages = [
        'correo' =>[
                'valid_email' => 'Estimado usuario, debe ingresar un email valido'

    ]
    ];
    protected $skipValidation = false;
}