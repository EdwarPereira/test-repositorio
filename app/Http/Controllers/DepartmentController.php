<?php

namespace App\Http\Controllers;

use App\Repository\DepartmentRepository;
use Illuminate\Http\Request;


class DepartmentController extends Controller
{
    private $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->middleware('auth');
        $this->departmentRepository = $departmentRepository;
    }

    public function index()
    {
        $status  = \Request::get('status');
        $order   = \Request::get('ordem');
        $mode    = \Request::get('modo');
        $orderBy = 'setor.nome';

        if ($order == 'id') {
            $orderBy = 'setor.id_setor';
        } else if ($order == 'nome') {
            $orderBy = 'setor.nome';
        } else if ($order == 'responsavel') {
            $orderBy = 'setor.responsavel';
        } else if ($order == 'status') {
            $orderBy = 'setor.status';
        } else if ($order == 'tolerancia') {
            $orderBy = 'setor.tempo';
        } else if ($order == 'email') {
            $orderBy = 'setor.email';
        }

        if ($mode == 'asc') {
            $mode = 'desc';
        } else if ($mode == 'desc') {
            $mode = 'asc';
        } else {
            $mode = 'asc';
        }

        $search = strtoupper(\Request::get('search'));

        $departments = $this->departmentRepository->search($search, $orderBy, $mode);

        $title = trans('setor-lista.consultadesetor');

        return view('sistema/cad/setor/lista', [
            'search' => $search,
            'setores' => $departments,
            'titulo' => $title,
            'modo' => $mode,
            'filtrostatus' => $status
        ]);

        
    }

    public function create()
    {
        $modo = 'create';
        $titulo = trans('setor-cad.cadastrarnovosetor');

        return view('sistema/cad/setor/cad', [
            'modo' => $modo,
            'titulo' => $titulo,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nome'        => 'required|max:255',
            'email'       => 'required',
            'responsavel' => 'required',
            'tempo'       => 'required',
        ]);

        $this->departmentRepository->insert($request);

        return redirect('/setor');
    }
    public function show($id)
    {
        return redirect('/setor');
    }

    public function edit($id)
    {
        $department = $this->departmentRepository->findById($id);

        if (!$department) {
            return redirect('/setor');
        }

        $modo = 'edit';
        $titulo = trans('setor-cad.alterarsetor') . $id;

        return  view('sistema/cad/setor/cad',[
            'setor' => $department,
            'id_setor' => $department->id_setor,
            'modo' => $modo,
            'titulo' => $titulo
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'nome'        => 'required|max:255',
            'email'       => 'required',
            'responsavel' => 'required',
            'tempo'       => 'required',
        ]);

        $department = $this->departmentRepository->findById($id);

        if (!$department) {
            return redirect('/setor');
        }

        $this->departmentRepository->update($department, $request);

        return redirect('/setor');
    }
}
