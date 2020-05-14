<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class VehicleRepository
{
    public function searchVehiclesByIdentificationOrPlate($search = '')
    {
        $query = DB::table('cliente_gt')
            ->join('veiculos', 'cliente_gt.id_customer', '=', 'veiculos.customer_id');
        if (trim($search) != "") {
            $query = $query->where('veiculos.active', '=', 1)
                            ->where(function($query) use($search) {
                                $query->where('veiculos.licplate', 'ilike', '%' . $search . '%')
                                      ->orWhere('veiculos.identification', 'ilike', '%' . strtolower($search) . '%');
                            });
        }
        return $query->select('cliente_gt.id_customer', 'cliente_gt.name', 'veiculos.licplate', 'veiculos.vehicle_id',
            'veiculos.identification')
            ->paginate(6);
    }

    public function searchVehiclesByIdentificationOrPlateAndCustomerId($search = '', $customerId)
    {
        $query = DB::table('cliente_gt')
            ->join('veiculos', 'cliente_gt.id_customer', '=', 'veiculos.customer_id');

        if (trim($search) != "") {
            $query = $query->where(['veiculos.licplate', 'like', '%' . $search . '%'], ['customer_id', '=', $customerId]);
        } else {
            $query = $query->where('cliente_gt.id_customer', '=', $customerId);
        }
        return $query->select('cliente_gt.id_customer', 'cliente_gt.name', 'veiculos.licplate', 'veiculos.vehicle_id',
            'veiculos.identification')
            ->paginate(8);
    }
}