<?php

namespace App\Repository;

use App\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository
{
    public function findActiveCustomers()
    {
        return DB::table('cliente_gt')
            ->where('active', '=', '1')
            ->orderBy('name', 'asc')
            ->lists('name', 'id_customer');
    }

    public function findCustomersByIds(array $customerIds)
    {
        return DB::table('cliente_gt')
            ->whereIn('cliente_gt.id_customer', $customerIds)
            ->get();
    }

    public function findCustomersWithEmailsByName($search)
    {
        $customers = array();

        if (trim($search) != "") {
            $customers = DB::table('cliente_gt')
                ->join('contato_gt', 'cliente_gt.id_customer', '=', 'contato_gt.customer_id')
                ->where('contato_gt.send_protocol', '=', '1')
                ->where('cliente_gt.name', 'ilike', '%' . $search . '%')
                ->select('cliente_gt.id_customer', 'cliente_gt.name', DB::raw("string_agg(contato_gt.email, ',') AS emails"))
                ->groupBy('cliente_gt.id_customer', 'cliente_gt.name')
                ->paginate(config('app.paginacao_modal'));
        }

        return $customers;
    }

    public function findById($id)
    {
        return Customer::find($id);
    }
}