<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function time;

class SumParamHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
    	$target = $request->getQueryParams();
        $sum = 0;
        foreach ($target as $key => $value) {
        	if (is_numeric($value)) {
        		$sum += $value;
        	} else {
        		return new JsonResponse(
        			[
            			'status' => 'unsuccessful',
            			'reason' => '400 Bad Request'
        			],
        			400
    			);
        	}
        }
        $sum = round($sum, 2);
        
        return new JsonResponse($sum);
    }
}
