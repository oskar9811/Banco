<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model

{
    protected $table         = 'Usuario';
    protected $primaryKey    ='id';

    protected $returnType    = 'array';
    protected $allowedFields = ['nombre','username', 'passwork'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nombre'   =>'required|alpha_space|min_length[3]max_length[75]',
        'username' =>'required|alpha_space|min_length[3]max_length[75]',
        'passwork' =>'required|alpha_numeric_space|min_length[8]max_length[8]',
    ];

    protected $validationMessages = [
        'correo' =>[
                'valid_email' => 'Estimado usuario, debe ingresar un email valido'

    ]
    ];
    protected $skipValidation = false;
}