<?php namespace App\Controllers\API;

use App\Models\ClienteModel;
use App\Models\CuentaModel;
use App\Models\TransaccionModel;
use CodeIgniter\RESTful\ResourceController;

class Transacciones extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new TransaccionModel());
        helper('access_rol');
    }

    public function index()
    {
        try {
            if (!validateAccess(array('admin', 'cajero'), $this->request->getServer('HTTP_AUTHORIZATION')));
            return $this->failServerError('El rol no tiene acceso a este recurso');
 
            $transacciones = $this->model->findAll();
            return $this->respond($transacciones);
 
         } catch (\Exception $e) {
             return $this->failServerError('Ha ocurrido un error');
         }


        $transacciones = $this->model->findAll();
        return $this->respond($transacciones);
        
    }

    public function create()
    {
        try {

            $transaccion = $this->request->getJSON();
            if ($this->model->insert($transaccion)):
                $transaccion->id = $this->model->insertID();
                $transaccion->resultado = $this->actualizarFondoCuenta($transaccion->tipo_transaccion_id, $transaccion->monto, $transaccion->cuenta_id);
                return $this->respondCreated($transaccion);
            else:
                return $this->failValidationErrors($this->model->validation->listErrors());
        endif;

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function edit($id = null)
    {
        try {

            if($id == null)
                return $this->failValidationErrors('No se ha pasado un ID valido');

            $transaccion = $this->model->find($id);
            if($transaccion == null)
                return $this->failNotFound('No se ha encontrado un cliente con el id:',$id);

            return $this->respond($transaccion);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function update($id = null)
    {
        try {

            if($id == null)
                return $this->failValidationErrors('No se ha pasado un ID valido');

            $transaccionVerificado = $this->model->find($id);
            if($transaccionVerificado == null)
                return $this->failNotFound('No se ha encontrado una transaccion con el id:',$id);

            $transaccion = $this->request->getJSON();

            if ($this->model->update($id, $transaccion)):
                $transaccion->id = $id;
                return $this->respondCreated($transaccion);
            else:
                return $this->failValidationErrors($this->model->validation->listErrors());
        endif;

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }


    public function delete($id = null)
    {
        try {

            if($id == null)
                return $this->failValidationErrors('No se ha pasado un ID valido');

            $transaccionVerificado = $this->model->find($id);
            if($transaccionVerificado == null)
                return $this->failNotFound('No se ha encontrado un cliente con el id:',$id);

            $cliente = $this->request->getJSON();

            if ($this->model->delete($id)):
                return $this->respondDeleted($transaccionVerificado);
            else:
                return $this->failServerError('Nose ha podido eliminar el registro');
        endif;

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function getTransaccionesByCliente($id = null)
    {
        try {
            $modelCliente = new ClienteModel();

            if ($id== null)
                return $this->failValidationErrors('No se ha pasado un Id valido');
        
            $cliente = $modelCliente->find($id);
            if ($cliente== null)
                return $this->failNotFound('No se ha encontrado un cliente con el id:' .$id);

            $transacciones= $this->model->getTransaccionesPorCliente($id);
            return $this->respond($transacciones);
    
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    private function actualizarFondoCuenta($tipoTransaccionId, $monto, $cuentaId)
    {
        $modelCuenta = new CuentaModel();
        $cuenta = $modelCuenta->find($cuentaId);

        switch ($tipoTransaccionId){
            case 1:
                $cuenta["fondo"] += $monto;
                break;
            case 2:
                $cuenta["fondo"] -= $monto;
                break;
        }

        if ($modelCuenta->update($cuentaId, $cuenta)) :
            return array('TransaccionExitosa' => true, 'NuevoFondo' => $cuenta["fondo"]);
        else:
            return array('TransaccionExitosa' => false, 'NuevoFondo' => $cuenta["fondo"]);
        endif;
            # code..
    }
}


