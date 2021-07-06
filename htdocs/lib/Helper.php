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

    /**
     * Gets data from an API endpoint
     *
     * @param string $endpoint
     * @param array $data
     * @param bool $returnTransfer
     * @return mixed
     */
    public static function apiRequest(string $endpoint, array $data, bool $returnTransfer = true)
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/API' . $endpoint;

        try {
            $ch = curl_init();

            if ($ch === false) {
                throw new Exception('Failed to initialize cUrl.');
            }

            $curlData = json_encode($data);

            if(self::userAuthenticated()) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type : application/json',
                    'Authorization: Bearer ' . $_SESSION['token']
                ]);
            } else {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type : application/json'
                ]);
            }

            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlData);
            curl_setopt($ch, CURLOPT_PROXY, '172.17.0.17:80');
            $requestData = curl_exec($ch);

            if ($requestData === false) {
                throw new Exception(curl_error($ch), curl_errno($ch));
            }

            curl_close($ch);

            if ($returnTransfer) {
                return json_decode($requestData);
            }
        } catch (Exception $e) {
            trigger_error(sprintf(
                "Curl failed with error #%d: %s",
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);
        }
    }

    /**
     * Checks if the user is authenticated
     *
     * @return bool
     */
    public static function userAuthenticated() : bool
    {
        if (!isset($_SESSION['acc_number'] , $_SESSION['token'])) {
            return false;
        }
        return true;
    }

    /**
     * Encrypts data
     *
     * @param string $data
     * @return string|bool
     */
    public static function encryptData(string $data) : string
    {
        $cipher = "AES-256-CBC";
        $key = "JBj5RNQ2kcjnp1hrCFCqAQDtmlTr18pE";
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            return base64_encode(openssl_encrypt($data, $cipher, $key, 0, $iv));
        }
        return false;
    }

    /**
     * Decrypts data
     *
     * @param string $data
     * @return string|bool
     */
    public static function decryptData(string $data) : string
    {
        $cipher = "AES-256-CBC";
        $key = "JBj5RNQ2kcjnp1hrCFCqAQDtmlTr18pE";
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            return openssl_decrypt(base64_decode($data), $cipher, $key, 0, $iv);
        }
        return false;
    }
}