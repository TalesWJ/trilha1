<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;
use App\DependencyInjection\Builder;

class Helper
{
    /**
     * Get url for a route by using either name/alias, class or method name.
     *
     * The name parameter supports the following values:
     * - Route name
     * - Controller/resource name (with or without method)
     * - Controller class name
     *
     * When searching for controller/resource by name, you can use this syntax "route.name@method".
     * You can also use the same syntax when searching for a specific controller-class "MyController@home".
     * If no arguments is specified, it will return the url for the current loaded route.
     *
     * @param string|null $name
     * @param string|array|null $parameters
     * @param array|null $getParams
     * @return Url
     * @throws InvalidArgumentException
     */
    public static function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
    {
        return Router::getUrl($name, $parameters, $getParams);
    }

    /**
     * @return Response
     */
    public static function response(): Response
    {
        return Router::response();
    }

    /**
     * @return Request
     */
    public static function request(): Request
    {
        return Router::request();
    }

    /**
     * Get input class
     * @param string|null $index Parameter index name
     * @param string|mixed|null $defaultValue Default return value
     * @param array ...$methods Default methods
     * @return Pecee\Http\Input\InputHandler|array|string|null
     */
    public static function input(?string $index = null, ?string $defaultValue = null, ...$methods)
    {
        if ($index !== null) {
            return request()->getInputHandler()->value($index, $defaultValue, ...$methods);
        }

        return request()->getInputHandler();
    }

    /**
     * @param string $url
     * @param int|null $code
     */
    public static function redirect(string $url, ?int $code = null): void
    {
        if ($code !== null) {
            response()->httpCode($code);
        }

        response()->redirect($url);
    }

    /**
     * Get current csrf-token
     * @return string|null
     */
    public static function csrf_token(): ?string
    {
        $baseVerifier = Router::router()->getCsrfVerifier();
        if ($baseVerifier !== null) {
            return $baseVerifier->getTokenProvider()->getToken();
        }

        return null;
    }

    /**
     * Returns a new container depending on the dependency
     * @param string $dependency
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function getContainer(string $dependency)
    {
        return Builder::buildContainer()->get($dependency);
    }

    public static function apiResponse(string $message, string $optParam = null, $optValue = null)
    {
        $data = [
            'statusCode' => http_response_code(),
            'message' => $message
        ];
        if(isset($optParam, $optValue)) {
            $data = [
                'statusCode' => http_response_code(),
                'message' => $message,
                $optParam => $optValue
            ];
        }
        self::response()->json($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}