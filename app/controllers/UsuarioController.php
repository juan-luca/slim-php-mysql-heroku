<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function Login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['usuario'];
        $clave = $parametros['clave'];
        $usuario = Usuario::obtenerUsuario($nombre);
        $retorno="error";
        
        if(password_verify($clave,$usuario->clave))
        {
          $retorno="Usuario verificado";
        }else
        {
          $retorno="clave incorrecta";
        }
        

        $payload = json_encode(array("mensaje" => $retorno));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      
        $parametros = $request->getParsedBody();
        
        
        $id = $args['usuarioId'];
        
        
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        
        
        if(Usuario::modificarUsuario($id,$nombre,$clave))
        {
          $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        }else
        {
          $payload = json_encode(array("mensaje" => "Usuario NO SE PUDO MODIFICAR"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        

        $usuarioId = $args['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
