<?php
// session_start();
declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function time;

class Test1Handler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {


		$clientId = '49baced8-d07a-4e45-ab12-33849aa9b43a';
		$clientSecret = 'Tg6KbPiaopU888qUa9xts8TRbcX3jEs7UziRjl9Gif0maOvNGRRWwceWYQAsiWko';
		$redirectUri = 'http://2997-80-250-213-62.ngrok.io';

        $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        

        //$_GET['referer'] = 'practicemailchimp.amocrm.ru';

        //$_GET['code'] = 'def50200288ef41291e2ba29b5d857673250294b0de4129769a95038332c9c625e15159ba8659a862a19b9dbce301d7f5efc44e16c35c0a9cefad02610d03ee21149776c5abf51152a94a9df822048be33f651cabc036a1131fcbcd9ed64d2fd4c7d0dc9a3dbf86abd462a74fe9b33439af59dcef0601ed6d70b2fcc600bb78bb81deab4e3a6696bc6a0d8ae395c1e5f2aedf2358d63530eed0e9401131f1cb4e22d8eb0c67aa103d732a3dae55629583ba22e73bbaeab38a7d83fef359ce6934aa62e4c01c2cb7218454889f9f80538f835ccfe21aa9f1303359c3d4ce17c72caa583f8d2b0cb58c0897ff7f8e75d7c8ca866abd415592876c751907713b69f529c7f778f49d90a847bc8aa1f79e9d57d44320af4d63be4e9a5c9fef5aeb24b8746838e7b25bab46ef8dc3284cd65b8de1ccf131bb6bb84ccab00c78e7470802f6e98b9754eec3c5e885602ffcddb45f1f7f05cc706713b43ccb93e7d5369e054ab6845fb485ce82cf333d0e634d30b17eb36bfa5a5dccb853c841aa84eebbc469ba43906b3661f1a98686214cdc100bd9d9ee0f50ebe861634cc36a50666cd3a96ed6d730474f0dcf59ba894831195a6285e25d4aafcb683daf78d410e2cd5f1c4bf6876793f61b1ab0bc4a59c21c0d722ced78c20feb1c01bc85ef2167edecb4065dc';



		if (isset($_GET['referer'])) {
    		$apiClient->setAccountBaseDomain($_GET['referer']);
		}


		if (!isset($_GET['code'])) {
    		$state = bin2hex(random_bytes(16));
    		$_SESSION['oauth2state'] = $state;
    	if (isset($_GET['button'])) {
        	echo $apiClient->getOAuthClient()->getOAuthButton(
            	[
                	'title' => 'Установить интеграцию',
                	'compact' => true,
                	'class_name' => 'className',
                	'color' => 'default',
                	'error_callback' => 'handleOauthError',
                	'state' => $state,
            	]
        	);
        	die;
    	} else {
        	$authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
            	'state' => $state,
            	'mode' => 'post_message',
        	]);
        	header('Location: ' . $authorizationUrl);
        	die;
    	}
		} elseif (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    		unset($_SESSION['oauth2state']);
    		exit('Invalid state');
		}
        var_dump($_GET);
		/**
 		* Ловим обратный код
 		*/
		try {
    		$accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);

    		if (!$accessToken->hasExpired()) {
        		saveToken([
            		'accessToken' => $accessToken->getToken(),
            		'refreshToken' => $accessToken->getRefreshToken(),
            		'expires' => $accessToken->getExpires(),
            		'baseDomain' => $apiClient->getAccountBaseDomain(),
        		]);
    		}
		} catch (Exception $e) {
    		die((string)$e);
		}

		$ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);

		// var_dump($accessToken->getToken());

		printf('Hello, %s!', $ownerDetails->getName());

        return new JsonResponse();
    }
}
