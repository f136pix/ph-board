<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Service\AuthService;
use App\Http\Service\UserService;
use App\Models\User;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    protected UserService $userService;

    // auto-wiring classe de servico
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        \Gate::authorize('view', 'users');

        try {

            // show role
            //$users = UserResource::collection(User::with('role')->paginate());

            // sem userResource para retornar os atributos relacionados ao pagination
            $users = User::with('role')->paginate();

            //$users = UserResource::collection(User::paginate());

            return response()->json($users, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Houve um erro ao acessar os dados', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $r
     * @return JsonResponse
     */
    public function store(UserCreateRequest $r)
    {
        \Gate::authorize('edit', 'users');
        try {

            $user = $this->userService->create($r);

            return response()->json(['message' => 'User criado com sucesso', 'User' => new UserResource($user)], Response::HTTP_OK);

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Dados incorretos', 'messages' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        \Gate::authorize('view', 'users');

        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json(['user' => '', 'message' => 'nenhum user foi encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['user' => new UserResource($user), 'message' => 'user encontrado'], Response::HTTP_OK);
    }

    /**
     * @param Request $r
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $r, $id)
    {
        \Gate::authorize('edit', 'users');
        try {
            $user = $this->userService->update($r, $id);

            return response()->json(['message' => 'User atualizado com sucesso', 'User' => $user], Response::HTTP_ACCEPTED);

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Dados incorretos', 'messages' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (NotFound $e) {
            return response()->json(['error' => 'User não encontrado', 'messages' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        \Gate::authorize('edit', 'users');
        try {
            $user = User::find($id);
            if (!$user) {
                throw new NotFound("User não encontrado");
            }

            $user->delete();
            return response()->json(['message' => 'User deletado com sucesso', 'user' => $user], Response::HTTP_OK);

        } catch (NotFound $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
