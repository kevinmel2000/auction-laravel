<?php

namespace App\Http\Middleware;

use Closure;

class NodeJSAuthenticated
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
        if($request->header('User-Agent') != $this->getTimeToken()) {
            exit;
        }

        return $next($request);
    }

    private function getTimeToken()
    {
        $date = strtotime('now');
        $second = date('s', $date);
        $string = strval((($date - $second) / 60) + 35734308);
        $code = [];

        for($i = 0; $i < strlen($string); $i++) {
            $tmp = strval(ord($string[$i]));
            $tmp = intval(strval(ord($tmp[0])) . strval(ord($tmp[1])));
            $code[] = dechex($tmp + $tmp * 100);
        }

        return implode('a', $code);
    }
}
