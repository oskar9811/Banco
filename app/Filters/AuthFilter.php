<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Models\RolModel;

class AuthFilters implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        //se ejecuta antes del controlador
        try {
            $key = Services::getSecretKey();
            $authHeader = $request->getServer('HTTP_AUTHORIZATION');

            if ($authHeader == null)
                return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'No se ha enviado el JWT requerido');
            $arr = explode(' ', $authHeader);
            $jwt = $arr[1];

            JWT::decode($jwt, $key, ['HS256']);

            $rolModel = new RolModel();
            $rol = $rolModel->find($jwt->data->rol);

            if ($rol == null) 
                return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'El rol de JWT es invalido');
            
                return true;

        } catch (ExpiredException $ee) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'Su token a expirado');
        }catch (\Exception $e) {     
            return Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Ocurrio un error en el servidor al validar el Token');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //se ejecuta despues del controlador 
    }
}
