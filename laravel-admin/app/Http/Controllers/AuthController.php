<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Service\AuthService;
use App\Models\User;
use Facade\FlareClient\Http\Exceptions\NotFound;
use http\Cookie;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected AuthService $authService;

    // auto-wiring classe de servico
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param RegisterRequest $r
     * @return JsonResponse
     *
     * aplica as regras de validacao de Requests/RegisterRequest.php
     */
    public function register(RegisterRequest $r)
    {
        try {
            $user = $this->authService->create($r);

            return response()->json(['message' => 'User criado', 'user' => new UserResource($user)], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Houve um erro ao criar o User.', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $r
     * @return JsonResponse
     */
    public function authenticate(Request $r)
    {
        try {
            $jwt = $this->authService->auth($r);

            // http only cookie contem token jwt
            $cookie = cookie('jwt', $jwt, 60 * 24);

            // if auth ok
            return Response()->json(['jwt' => $jwt], Response::HTTP_OK)->withCookie($cookie);

        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => $e->getMessage(),], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Houve um erro ao autenticar o user', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {

        // destroy token jwt
        $cookie = \Cookie::forget('jwt');

        return response()->json(['message' => 'jwt removido'], Response::HTTP_OK)->withCookie($cookie);
    }

    public function updateInfo(UpdateInfoRequest $r)
    {
        {
            try {
                $user = $this->authService->update($r);

                return response()->json(['message' => 'User atualizado com sucesso', 'User' => $user], Response::HTTP_ACCEPTED);

            } catch (ValidationException $e) {
                return response()->json(['error' => 'Dados incorretos', 'messages' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
            } catch (NotFound $e) {
                return response()->json(['error' => 'User não encontrado', 'messages' => $e->getMessage()], Response::HTTP_NOT_FOUND);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function updatePassword(UpdatePasswordRequest $r)
    {

        try {
            $user = $this->authService->updatePassword($r);

            return response()->json(['message' => 'Senha atualizada com sucesso'], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Dados incorretos', 'messages' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (NotFound $e) {
            return response()->json(['error' => 'User não encontrado', 'messages' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function user(Request $r)
    {
        return new UserResource($r->user()->with('role')->first());
    }
}
