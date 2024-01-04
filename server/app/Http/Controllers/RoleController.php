<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(RoleResource::collection(Role::all()), Response::HTTP_OK);
    }

    public function store(Request $r)
    {
        $role = Role::create($r->only('name'));

        $role->permissions()->attach($r['permissions']);

        return response()->json(new RoleResource($role->load('permissions')), Response::HTTP_OK);

    }

    public function show(int $id)
    {
        return response()->json(new RoleResource(Role::with('permissions')->find($id)), Response::HTTP_OK);
    }

    public function update(Request $r, int $id)
    {
        $role = Role::find($id);

        $role->update($r->only('name'));

        $role->permissions() ->sync($r['permissions']);

        return response()->json(['message' => 'role atualizado', 'role' => new RoleResource($role->load('permissions'))], Response::HTTP_ACCEPTED);
    }

    public function destroy(int $id)
    {
        Role::destroy($id);

        return response()->json(['message' => 'role destruido'], Response::HTTP_ACCEPTED);
    }

}
