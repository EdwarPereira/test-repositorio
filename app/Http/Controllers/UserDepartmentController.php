<?php

namespace App\Http\Controllers;

use App\Repository\DepartmentRepository;
use App\Repository\UserDepartmentRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class UserDepartmentController extends Controller
{
    private $userDepartmentRepository;
    private $departmentRepository;
    private $userRepository;

    public function __construct(UserDepartmentRepository $userDepartmentRepository, DepartmentRepository $departmentRepository,
                                UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->userDepartmentRepository = $userDepartmentRepository;
        $this->departmentRepository = $departmentRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $search = strtoupper(\Request::get('search'));

        $userDepartments = $this->userDepartmentRepository->findAllWithDepartmentAndUser();

        $title = trans('setorusuario-lista.consultadesetorusuario');

        return view('sistema/cad/setorusuario/lista', [
            'setoresusuarios' => $userDepartments,
            'titulo' => $title,
            'search' => $search
        ]);
    }

    public function create()
    {
        $mode = 'create';

        $title = trans('setorusuario-cad.cadastrarnovosetorusuario');

        $users = $this->userRepository->findActiveUsers();
        $departments = $this->departmentRepository->findActiveDepartments();

        return view('sistema/cad/setorusuario/cad', [
            'modo' => $mode,
            'titulo' => $title,
            'usuarios' => $users,
            'setores' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_usuario' => 'required',
            'id_setor' => 'required',
            'id_usuario' => 'unique_with:setorusuario,id_usuario,id_setor',
        ]);

        $this->userDepartmentRepository->insert($request);

        return redirect('/setorusuario');
    }

    public function show($id)
    {
        return redirect('/setorusuario');
    }

    public function edit($id)
    {
        $mode = 'edit';
        $title = trans('setorusuario-cad.alterarsetorusuario') . $id;

        $userDepartment = $this->userDepartmentRepository->findById($id);

        if (!$userDepartment) {
            return redirect('/setorusuario');
        }

        $users = $this->userRepository->findActiveUsers();
        $departments = $this->departmentRepository->findActiveDepartments();

        return view('sistema/cad/setorusuario/cad', [
            'setorusuario' => $userDepartment,
            'id' => $userDepartment->id,
            'modo' => $mode,
            'titulo' => $title,
            'setores' => $departments,
            'usuarios' => $users
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'id_usuario' => 'required',
            'id_setor' => 'required',
            'id_usuario' => "unique_with:setorusuario,id_usuario,id_setor",
        ]);

        $userDepartment = $this->userDepartmentRepository->findById($id);

        if (!$userDepartment) {
            return redirect('/setorusuario');
        }

        $this->userDepartmentRepository->update($userDepartment, $request);

        return redirect('/setorusuario');
    }
}
