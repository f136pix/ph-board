<?php
namespace App\Http\Service;


use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;

class UserService
{
    /**
     * @param Request $r
     * @return User
     *
     */
    public function create(Request $r)
    {
        try {
            $validatedData = $r->validate([
                'name' => 'required|string',
                'surname' => 'required|string',
                'email' => 'required|email',
            ]);

            $user = User::create([
                'name' => $r['name'],
                'surname' => $r['surname'],
                'email' => $r['email'],
                'password' => $r['password'],
                'role_id' => $r['role_id']
            ]);



            return $user;

        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(),$e->getCode());
        }
    }

    /**
     * @param Request $r
     * @return User
     * @throws ValidationException
     */
    public function update(Request $r, Int $id)
    {
        try {


            $user = User::find($id);

            if(!$user) {
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

            $user->update($r->only('first_name', 'surname', 'email', 'role_id'));

            return $user;

        } catch (ValidationException $e) {
            throw new ValidationException($e->getMessage());
        } catch (NotFound $e) {
            throw new NotFound($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(),$e->getCode());
        }
    }
}
