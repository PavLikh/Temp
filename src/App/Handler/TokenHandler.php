<?php

declare(strict_types=1);

namespace App\Handler;

use AmoCRM\Client\AmoCRMApiClient;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TokenHandler implements RequestHandlerInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $clientSecret = $this->config['clientSecret'];
        $redirectUri = $this->config['redirectUri'];

        if ($request->getQueryParams()['client_id']) {
            $clientId = $request->getQueryParams()['client_id'];
        } else {
            $clientId = $this->config['clientId'];
        }

        $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        if (isset($request->getQueryParams()['referer'])) {
            $apiClient->setAccountBaseDomain($request->getQueryParams()['referer']);
        }

        /**
         * Ловим обратный код
         */

        try {

            $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($request->getQueryParams()['code']);

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
            return new JsonResponse($e->getMessage());
        }

        $ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);

        $a = sprintf('Hello, %s!', $ownerDetails->getName());

        return new JsonResponse($a);
    }
}