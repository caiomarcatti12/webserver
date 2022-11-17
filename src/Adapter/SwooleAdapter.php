<?php
namespace CaioMarcatti12\Webserver\Adapter;

use CaioMarcatti12\Core\Bean\Objects\BeanCache;
use CaioMarcatti12\Core\Bean\Objects\BeanProxy;
use CaioMarcatti12\Core\Factory\InstanceFactory;
use CaioMarcatti12\Core\Factory\Invoke;
use CaioMarcatti12\Core\Modules\Modules;
use CaioMarcatti12\Core\Modules\ModulesEnum;
use CaioMarcatti12\Core\Validation\Assert;
use CaioMarcatti12\Data\BodyLoader;
use CaioMarcatti12\Data\HeaderLoader;
use CaioMarcatti12\Data\Request\Objects\Header;
use CaioMarcatti12\Event\Interfaces\EventManagerInterface;
use CaioMarcatti12\Webserver\Annotation\Presenter;
use CaioMarcatti12\Webserver\Exception\ResponseTypeException;
use CaioMarcatti12\Webserver\Exception\RouteNotFoundException;
use CaioMarcatti12\Webserver\Interfaces\WebServerRunnerInterface;
use CaioMarcatti12\Webserver\Objects\RoutesWeb;
use CaioMarcatti12\Webserver\RouterResponseWeb;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use ReflectionMethod;
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
            $context->parseCorrelationId($request);
            $context->parseHeaderKong($request);

            $requestUri = $request->server['request_uri'];
            $requestMethod = $request->server['request_method'];

            $responseRoute = new RouterResponseWeb('', 200, []);
            $route = null;

            try {
                if($requestMethod !== 'OPTIONS' && $requestUri !== '/favicon.ico'){
                    $route = RoutesWeb::getRoute($requestUri, $requestMethod);

                    if(Assert::isEmpty($route)) throw new RouteNotFoundException($requestUri);

                    $responseRoute = Invoke::new($route->getClass(), $route->getClassMethod());

                    $responseRoute = $context->parseResponse($route->getClass(), $route->getClassMethod(), $responseRoute);
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

            if (Modules::isEnabled(ModulesEnum::EVENT)&& $route !== null) {
                /** @var EventManagerInterface $eventManager */
                $eventManager = InstanceFactory::createIfNotExists(EventManagerInterface::class);
                $eventManager->dispatch();
            }

        });

        $server->start();
    }

    private function parseResponse(string $class, string $method, mixed $response): mixed {
        $reflectionClass = new ReflectionClass($class);

        /** @var ReflectionMethod $reflectionMethod */
        $reflectionMethod = $reflectionClass->getMethod($method);

        $returnTypeName = $this->getReturnTypeName($reflectionMethod);
        $presenter = $this->getPresenter($reflectionMethod);

        return $this->makeResponse($returnTypeName, $presenter, $response);
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

    private function parseCorrelationId(Request $request): void{
        if(isset($request->server['x-correlation-id']))
            Header::add('x-correlation-id', $request->server['x-correlation-id']);
        else
            Header::add('x-correlation-id', Uuid::uuid4()->toString());
    }

    private function parseHeaderKong(Request $request): void{
        if(isset($request->server['request_method']))
            Header::add('x-request-method', $request->server['request_method']);

        if(isset($request->server['request_uri']))
            Header::add('x-request-uri', $request->server['request_uri']);
    }


    private function getReturnTypeName(ReflectionMethod $reflectionMethod): string
    {
        $returnType = $reflectionMethod->getReturnType();

        if (Assert::isEmpty($returnType)) throw new ResponseTypeException();

        return $returnType->getName();
    }

    private function getPresenter(ReflectionMethod $reflectionMethod): string
    {
        /** @var \ReflectionAttribute $reflectionAttribute */
        foreach($reflectionMethod->getAttributes(Presenter::class) as $reflectionAttribute){
            /** @var Presenter $instanceAttribute */
            $instanceAttribute = $reflectionAttribute->newInstance();

            if(Assert::equalsIgnoreCase($instanceAttribute->getContentTypeEnum()->value, Header::get('Content-Type', ''))){
                return $instanceAttribute->getPresenterClass();
            }
        }

        return '';
    }

    private function makeResponse(string $returnTypeName, string $presenter, mixed $response): mixed
    {
        BeanCache::destroy(RouterResponseWeb::class);

        if (Assert::inArray($returnTypeName, [RouterResponseWeb::class])) {
            return $response;
        } else if (Assert::isNotEmpty($presenter)) {
            return InstanceFactory::createIfNotExists($presenter, [$response, 200], false);
        } else if (!Assert::equals($returnTypeName, "void")) {
            return InstanceFactory::createIfNotExists(RouterResponseWeb::class, [$response, 200], false);
        }

        $classProxyRouterInterface = BeanProxy::get(RouterResponseWeb::class);

        if (Assert::inArray($classProxyRouterInterface, [RouterResponseWeb::class])) {
            return null;
        }

        return InstanceFactory::createIfNotExists($classProxyRouterInterface, ['', 200], false);
    }

}