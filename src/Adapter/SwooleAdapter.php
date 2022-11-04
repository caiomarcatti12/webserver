<?php
namespace CaioMarcatti12\Webserver\Adapter;

use CaioMarcatti12\Data\BodyLoader;
use CaioMarcatti12\Data\HeaderLoader;
use CaioMarcatti12\Data\Request\Objects\Header;
use CaioMarcatti12\Router\Exception\RouteNotFoundException;
use CaioMarcatti12\Router\Objects\RoutesWeb;
use CaioMarcatti12\Validation\Assert;
use CaioMarcatti12\Web\RouterResponseWeb;
use CaioMarcatti12\Webserver\Interfaces\WebServerRunnerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class SwooleAdapter implements WebServerRunnerInterface
{
    public function run(): void
    {
        $server = new Server('0.0.0.0', '80');
        $server->set([
            'enable_coroutine' => true,
            'open_http2_protocol' => true
        ]);

        $context = $this;
        $server->on('request', static function (Request $request, Response $response) use ($context){

            $context->parseBody($request);
            $context->parseHeader($request);
            $context->parseHeaderKong($request);

            $requestUri = $request->server['request_uri'];
            $requestMethod = $request->server['request_method'];

            $responseRoute = new RouterResponseWeb('', 200, []);

            try {

                if($requestMethod !== 'OPTIONS' && $requestUri !== '/favicon.ico'){
                    $route = RoutesWeb::getRoute($requestUri, $requestMethod);

                    if(Assert::isEmpty($route)) throw new RouteNotFoundException($requestUri);

//                    $responseRoute = InvokeRoute::invoke($route->getClass(), $route->getClassMethod());
                }
            }
            catch (\Throwable $throwable){
                $code = $throwable->getCode();
                if($code <= 0) $code = 500;

                $responseRoute = new RouterResponseWeb(['error' => $throwable->getMessage()], $code);
            }

            foreach($responseRoute->headers() as $key => $value){
                $response->header($key, $value);
            }

            $response->status($responseRoute->code());
            $response->end($responseRoute->response());
        });

        $server->start();
    }


    private function parseBody(Request $request): void{
        $bodyLoader = new BodyLoader();
        $bodyLoader->load(json_decode($request->rawContent(), true));
        $bodyLoader->load($request->get);
        $bodyLoader->load($request->post);
        $bodyLoader->load($request->files);
    }

    private function parseHeader(Request $request): void{
        $headerLoader = new HeaderLoader();
        $headerLoader->load($request->header);
    }

    private function parseHeaderKong(Request $request): void{

        if($request->server['request_method'])
            Header::add('x-request-method', $request->server['request_method']);

        if($request->server['request_uri'])
            Header::add('x-request-uri', $request->server['request_uri']);
    }

}