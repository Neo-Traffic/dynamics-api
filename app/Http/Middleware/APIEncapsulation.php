<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Closure;

class APIEncapsulation
{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);
		$responseJSON = json_decode($response->content(), true) ?: [];

		if(array_key_exists('content', $responseJSON))
			$responseJSON = $responseJSON['content'];

		$formated = [
			"timestamp" => time(),
			"refresh" => env('RECORD_LIFESPAN', 0),
			"content" => $responseJSON,
			"status" => $response->getStatusCode()
		];

		$response->setContent(json_encode($formated));

		return $response;
	}

}