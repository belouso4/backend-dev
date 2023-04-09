<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Resources\Admin\User\UserRolesResource;
use App\Models\Role;
use App\Repositories\Contracts\IUserRoles;
use Illuminate\Http\Request;

class UserRolesController extends AdminController
{

    protected $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = app(IUserRoles::class);
    }

    public function index()
    {
        $users = $this->userRepository->getUserWhereHasRoles();
        return UserRolesResource::collection($users);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $users = $this->userRepository->search($request['search']);

        return UserRolesResource::collection($users);
    }
}
