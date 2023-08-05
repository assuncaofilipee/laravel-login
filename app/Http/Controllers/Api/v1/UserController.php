<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): JsonResponse
    {
        return response()->success($this->userRepository->all(), JsonResponse::HTTP_OK, true);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userRepository->create(
            $request->only([
                'email',
                'name',
                'cpf',
                'password',
                'password_confirmation',
                'terms_of_use'
            ])
        );
        return response()->success($user, JsonResponse::HTTP_CREATED);
    }

    public function show(int $id)
    {
        return response()->success(
            $this->userRepository->show($id),
            JsonResponse::HTTP_OK
        );
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userRepository->update($id, $request->only([
            'email',
            'name',
            'cpf',
            'password'
        ]));

        return response()->success($user, JsonResponse::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userRepository->delete($id);
        return response()->success('', JsonResponse::HTTP_NO_CONTENT);
    }
}
