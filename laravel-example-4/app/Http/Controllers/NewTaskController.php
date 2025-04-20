<?php

namespace App\Http\Controllers;

use App\Google\Tasks;
use Illuminate\Http\Request;
use App\Sync\APIRequestTrack;

class NewTaskController extends Controller
{
    use APIRequestTrack;

    /**
     * Adds a new task.
     *
     * @todo Handle testing.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $content = json_decode($request->getContent());

        $this->logAPIRequestCount();

        if (! isset($content->item)) {
            return response('Item is required', 422);
        }

        if (! isset($content->list)) {
            return response('List is required', 422);
        }

        if ('testing' === env('APP_ENV', 'production')) {
            return 'test';
        }

        return (new Tasks())->addTask($content->list, $content->item);
    }
}
