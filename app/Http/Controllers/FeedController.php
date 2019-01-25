<?php
/**
 * lel since 2019-01-25
 */

namespace App\Http\Controllers;

use App\Domain\FeedService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedController extends Controller
{
    public function index(Request $request, FeedService $feedService)
    {
        $availableFeeds = array_keys(config('feeds'));

        $this->validate($request, [
            'name' => ['required', 'string', Rule::in($availableFeeds)],
        ]);

        return response()
            ->json($feedService->fetch($request->input('name')));
    }
}
