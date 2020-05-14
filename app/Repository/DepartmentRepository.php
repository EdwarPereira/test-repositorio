<?php

namespace App\Repository;

use App\Department;
use Illuminate\Support\Facades\DB;

class DepartmentRepository
{
    public function search($search, $orderBy, $mode)
    {
        $query = Department::query();

        if (trim($search) != '') {
            $query = $query->where('nome', 'like', '%' . $search . '%');
        }

        return $query->orderBy($orderBy, $mode)
            ->paginate(config('app.paginacao'));
    }

    public function findActiveDepartments()
    {
        return DB::table('setor')->where('status', '=', '1')->orderBy('nome', 'asc')->lists('nome', 'id_setor');
    }

    public function findById($id)
    {
        return Department::find($id);
    }

    public function insert($data)
    {
        $department = new Department;

        $department->nome = strtoupper($data->nome);
        $department->status = $data->status;
        $department->email = $data->email;
        $department->responsavel = $data->responsavel;
        $department->tempo = $data->tempo;
        return $department->save();
    }

    public function update(Department $department, $data)
    {
        $department->nome = strtoupper($data->nome);
        $department->status = $data->status;
        $department->email = $data->email;
        $department->responsavel = $data->responsavel;
        $department->tempo = $data->tempo;
        return $department->save();
    }
}