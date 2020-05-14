<?php

namespace App\Repository;

use App\UserDepartment;
use Illuminate\Support\Facades\DB;

class UserDepartmentRepository
{
    public function findAllWithDepartmentAndUser()
    {
        return DB::table('setorusuario')
            ->join('setor','setorusuario.id_setor', '=', 'setor.id_setor')
            ->join('usuario_gt','setorusuario.id_usuario', '=', 'usuario_gt.user_id')
            ->select('setor.nome', 'usuario_gt.display_name', 'setorusuario.id')
            ->orderBy('setor.nome', 'asc')
            ->paginate(20);
    }

    public function findUserDepartmentsByUserId($userId)
    {
        $userDepartments = DB::table('setorusuario')
            ->join('setor', 'setorusuario.id_setor', '=', 'setor.id_setor')
            ->where('id_usuario', '=', $userId)
            ->select('setorusuario.id_usuario', 'setor.nome', 'setor.id_setor')
            ->orderby('setor.nome', 'asc')
            ->get();
        return $userDepartments;
    }

    public function findById($id)
    {
        return UserDepartment::find($id);
    }

    public function insert($data)
    {
        $userDepartment = new UserDepartment();

        $userDepartment->id_setor = strtoupper($data->id_setor);
        $userDepartment->id_usuario = $data->id_usuario;
        return $userDepartment->save();
    }

    public function update(UserDepartment $userDepartment, $data)
    {
        $userDepartment->id_usuario = strtoupper($data->id_usuario);
        $userDepartment->id_setor = $data->id_setor;
        $userDepartment->save();
    }
}