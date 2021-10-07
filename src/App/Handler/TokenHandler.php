<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\OAuth2\Client\Token\AccessToken;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\AccountModel;
use League\OAuth2\Client\Token\AccessTokenInterface;



use function time;

class TokenHandler implements RequestHandlerInterface
{


    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        
        $clientSecret = 't8iOfzbyqIoT2Ymlvarf3TfbILHaxk3ZDd3jaPDQiOXEex8SrSyIGWZ2rIXh29gE';
        $redirectUri = 'http://9809-80-250-213-62.ngrok.io/api/token';       

        if ($_GET['client_id']){
            $clientId = $_GET['client_id'];
        } else {
            $clientId = '49baced8-d07a-4e45-ab12-33849aa9b43a';
        }

        $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        if (isset($_GET['referer'])) {
            $apiClient->setAccountBaseDomain($_GET['referer']);
        }



        if (!isset($_GET['code'])) {
            $state = bin2hex(random_bytes(16));

            $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
                'state' => $state,
                'mode' => 'post_message',
            ]);
            header('Location: ' . $authorizationUrl);
            die;
        } 




/**
 * Ловим обратный код
 */

        try {

            $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);

             if (!$accessToken->hasExpired()) {
                $data = [
                    'accessToken' => $accessToken->getToken(),
                    'expires' => $accessToken->getExpires(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'baseDomain' => $apiClient->getAccountBaseDomain(),
                ];

                file_put_contents(DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'token_info.json', json_encode($data));
             }
            
         } catch (Exception $e) {
             die((string)$e);
         }

        // $apiClient->setAccessToken($accessToken);
            // ->setAccountBaseDomain($accessToken->getValues()['baseDomain']);
            // ->onAccessTokenRefresh(
                // function (AccessTokenInterface $accessToken, string $baseDomain) {
                    // saveToken(
                    //     [
                    //         'accessToken' => $accessToken->getToken(),
                    //         'refreshToken' => $accessToken->getRefreshToken(),
                    //         'expires' => $accessToken->getExpires(),
                    //         'baseDomain' => $baseDomain,
                    //     ]
                    // );
                // }
        // );
        $ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);

        //$account = $apiClient->account()->getCurrent(AccountModel::getAvailableWith());


        // $a = sprintf('Hello, %s!', $ownerDetails->getName());

        // return new JsonResponse($a);

        return new HtmlResponse(sprintf('<h2>Hello, %s!<h2>', $ownerDetails->getName()));
    }
}