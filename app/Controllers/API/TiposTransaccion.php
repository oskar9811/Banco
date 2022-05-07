<?php namespace App\Controllers\API;

use App\Models\TiposTransaccionModel;
use CodeIgniter\RESTful\ResourceController;

class TiposTransaccion extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new TiposTransaccionModel());
    }

    public function index()
    {
        $tiposTransaccion = $this->model->findAll();
        return $this->respond($tiposTransaccion);
    }

    public function create()
    {
        try {

            $tiposTransaccion = $this->request->getJSON();
            if ($this->model->insert($tiposTransaccion)):
                $tiposTransaccion->id = $this->model->insertID();
                return $this->respondCreated($tiposTransaccion);
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

            $tiposTransaccion = $this->model->find($id);
            if($tiposTransaccion == null)
                return $this->failNotFound('No se ha encontrado un cliente con el id:',$id);

            return $this->respond($tiposTransaccion);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function update($id = null)
    {
        try {

            if($id == null)
                return $this->failValidationErrors('No se ha pasado un ID valido');

            $tiposTransaccionVerificado = $this->model->find($id);
            if($tiposTransaccionVerificado == null)
                return $this->failNotFound('No se ha encontrado un cliente con el id:',$id);

            $tiposTransaccion = $this->request->getJSON();

            if ($this->model->update($id, $tiposTransaccion)):
                $tiposTransaccion->id = $id;
                return $this->respondCreated($tiposTransaccion);
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

            $tiposTransaccionVerificado = $this->model->find($id);
            if($tiposTransaccionVerificado == null)
                return $this->failNotFound('No se ha encontrado un cliente con el id:',$id);

            $cliente = $this->request->getJSON();

            if ($this->model->delete($id)):
                return $this->respondDeleted($tiposTransaccionVerificado);
            else:
                return $this->failServerError('Nose ha podido eliminar el registro');
        endif;

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
}

