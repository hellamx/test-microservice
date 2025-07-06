<?php

namespace App\Http\Controllers\Api\Internal;

use App\Finders\Entities\UserFinder;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        /** @var UserFinder $userFinder */
        $userFinder = app(UserFinder::class);
        $userFinder->setRelations(['wallets']);
        $users = $userFinder->findByRequest($request);
        $usersData = $userFinder->getMappedData();

        return response()->json([
            'data' => $usersData,
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
        ]);
    }
}
