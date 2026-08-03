<?php

namespace App\Http\Controllers\adm;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminDashboardAccess;
use App\Support\AdminModules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $busqueda = trim((string) $request->query('q', ''));

        if ($busqueda !== '') {
            $query->where(function ($subquery) use ($busqueda) {
                $subquery->where('username', 'LIKE', "%{$busqueda}%")
                    ->orWhere('name', 'LIKE', "%{$busqueda}%")
                    ->orWhere('email', 'LIKE', "%{$busqueda}%");
            });
        }

        if ($request->filled('role') && array_key_exists((int) $request->query('role'), AdminModules::roleOptions())) {
            $query->where('role', $request->query('role'));
        }

        $usuarios = $query->orderBy('id', 'ASC')
            ->paginate(12)
            ->appends($request->query());
        $roles = AdminModules::roleOptions();
        $moduleCatalog = AdminModules::moduleCatalog();

        return view('adm.usuario.index', compact('usuarios', 'roles', 'moduleCatalog'));
    }

    public function create(){
        $roles = AdminModules::roleOptions();
        $moduleCatalog = AdminModules::moduleCatalog();
        $roleModules = AdminModules::roleModules();
        $dashboardOptions = AdminDashboardAccess::options();
        $dashboardType = old('dashboard_type', AdminDashboardAccess::WEB_TRAFFIC);
        $canManageDashboardAccess = AdminDashboardAccess::canManage(auth()->user());

        return view('adm.usuario.crear', compact('roles', 'moduleCatalog', 'roleModules', 'dashboardOptions', 'dashboardType', 'canManageDashboardAccess'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:1,2,3',
            'dashboard_type' => 'nullable|in:'.implode(',', array_keys(AdminDashboardAccess::options())),
        ]);

        $user = new User ();
        $user->name     = $request->name;
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->role  = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        if (AdminDashboardAccess::canManage(auth()->user())) {
            AdminDashboardAccess::setDashboardTypeFor($user, $request->input('dashboard_type', AdminDashboardAccess::WEB_TRAFFIC));
        }
        return redirect()->route('usuarios')->with('success', "Usuario registrado exitósamente" );
    }

    public function edit($id){
        $usuarios         = User::findOrFail($id);
        $roles = AdminModules::roleOptions();
        $moduleCatalog = AdminModules::moduleCatalog();
        $roleModules = AdminModules::roleModules();
        $userModules = AdminModules::modulesForUser($usuarios);
        $dashboardOptions = AdminDashboardAccess::options();
        $dashboardType = old('dashboard_type', AdminDashboardAccess::dashboardTypeFor($usuarios));
        $canManageDashboardAccess = AdminDashboardAccess::canManage(auth()->user());

        return view('adm.usuario.editar', compact('usuarios', 'roles', 'moduleCatalog', 'roleModules', 'userModules', 'dashboardOptions', 'dashboardType', 'canManageDashboardAccess'));
    }

    public function update(Request $request, $id){
        $user           = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:1,2,3',
            'dashboard_type' => 'nullable|in:'.implode(',', array_keys(AdminDashboardAccess::options())),
        ]);

        $user->name     = $request->name;
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->role  = $request->role;
        if ($request->password){
            $user->password = Hash::make($request->password);
        }else{
            $user->password = $user->password;
        }

        $user->update();
        if (AdminDashboardAccess::canManage(auth()->user())) {
            AdminDashboardAccess::setDashboardTypeFor($user, $request->input('dashboard_type', AdminDashboardAccess::WEB_TRAFFIC));
        }
        return redirect()->route('usuarios')->with('success', "Usuario actualizado exitósamente" );
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('usuarios')->with('danger', "Usuario eliminado exitósamente" );
    }
}
