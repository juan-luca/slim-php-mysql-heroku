<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggerMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $parametros = $request->getParsedBody();


        $retorno = "";

        if ($parametros != null) {
            if (count($parametros) == 2) {

                $response = $handler->handle($request);
            } else {
                foreach ($parametros as $item  => $value) {
                    if ($item == "clave") {
                        $retorno = "Falta ingresar el usuario";
                    } else {
                        $retorno = "Falta ingresar la clave";
                    }
                }
            }
        } else {
            $retorno = "No hay parametros";
        }










        $payload = json_encode($retorno);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
