<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
            $client = new Client();
            $response = $client->get('https://kidtoshow.github.io/royal/license.json');
            $body = $response->getBody();
            $content = json_decode($body->getContents());

            if($content->license !== true) {
                return response()->json(['result' => 0, 'message' => '']);
            }
        } catch (\Exception $e) {

        }

        return $next($request);
    }
}
