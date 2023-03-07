<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Resources\Admin\User\UserRolesResource;
use App\Models\Role;
use App\Models\User;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->getUserWhereHasRoles();
        return UserRolesResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show(Role $role)
//    {
//        $users = User::whereHas('roles', function ($query) use ($role) {
//                    $query->where('id', $role->id);
//                })
//            ->with('roles')
//            ->get();
//
//        return response()->json($users);
//    }

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

//        $search = $request->query('search');
//        $roles = Role::select(['id', 'name'])->where('name', 'like', "$search%")->get();

        if(isset($request['filter-list'])) {
            $filter = $request['filter-list'];
            $roles = Role::select(['id', 'name'])
                ->where('name', 'like', "$filter%")
                ->get();
        } elseif (isset($request['search'])) {
            $search = $request['search'];
            $users = User::whereHas('roles')
                ->where('name', 'like', "$search%")
                ->with('roles')
                ->paginate();

            return UserRolesResource::collection($users);
        } elseif (isset($request['filter'])) {
            return $request['filter'];
            $filter = $request['filter'];
            $users = User::whereHas('roles', function ($query) use ($filter) {
                $query->whereIn('name', explode(',', $filter));
            })->with('roles')->paginate();

            return UserRolesResource::collection($users);
        }

        return response()->json($roles);
    }
}
