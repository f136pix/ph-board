<?php

namespace App\Http\Service;

use App\Models\Role;
use App\Models\User;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function create(Request $r)
    {

        /**
         * @param Request $r
         * @return User
         */

        $user = User::create([
            'name' => $r->input('name'),
            'surname' => $r->input('surname'),
            'email' => $r->input('email'),
            'password' => Hash::make($r->input('password')),
            'role_id' => 1
        ]);

        return $user;
    }

    /**
     * @param Request $r
     * @return string
     * @throws AuthorizationException
     */
    public function auth(Request $r)
    {
        $auth = (Auth::attempt($r->only('email', 'password')));

        if (!$auth) {
            throw new AuthorizationException('Credenciais incorretas');
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        return $token;
    }

    /**
     * @param Request $r
     * @return User
     * @throws ValidationException
     */
    public function update(Request $r)
    {
        try {

            $user = $r->user();

            if (!$user) {
                throw new NotFound("User não encontrado");
            }

            $validatedData = $r->validate([
                'name' => 'string',
                'surname' => 'string',
                'email' => 'email',
            ]);

            /*$user->update([
                'name' => $r['name'],
                'surname' => $r['surname'],
                'email' => $r['email'],
            ]);*/

            $user->update($r->only('name', 'surname', 'email'));

            return $user;

        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage());
        } catch (NotFound $e) {
            throw new NotFound($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updatePassword(Request $r)
    {
        try {

            $user = $r->user();

            if (!$user) {
                throw new NotFound("User não encontrado");
            }

            $user->update(['password' => Hash::make($r['password'])]);

            return $user;

        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage());
        } catch (NotFound $e) {
            throw new NotFound($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
