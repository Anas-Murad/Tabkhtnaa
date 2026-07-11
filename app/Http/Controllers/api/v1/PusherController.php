<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Traits\HelperTrait;

class PusherController extends Controller
{
    use HelperTrait;

    public function config()
    {
        $key = config('broadcasting.connections.pusher.key');
        $cluster = config('broadcasting.connections.pusher.options.cluster');

        return $this->returnDataArray([
            'enabled' => config('broadcasting.default') === 'pusher' && filled($key) && filled($cluster),
            'key' => $key,
            'cluster' => $cluster,
        ]);
    }
}
