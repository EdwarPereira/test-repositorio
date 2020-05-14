<?php

namespace App\Http\Controllers;

use App\Repository\CustomerRepository;

class CustomerController extends Controller
{
    private $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->middleware('auth');
        $this->repository = $repository;
    }

    public function index()
    {
        $search = strtolower(\Request::get('search'));

        $customers = $this->repository->findCustomersWithEmailsByName($search);

        $title = trans('cliente-lista.consultadecliente');

        return view('sistema/cad/cliente/lista', [
            'customers' => $customers,
            'titulo' => $title,
            'search' => $search
        ]);
    }
}
