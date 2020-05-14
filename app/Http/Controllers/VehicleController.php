<?php

namespace App\Http\Controllers;

use App\Repository\VehicleRepository;

class VehicleController extends Controller
{
    private $repository;

    public function __construct(VehicleRepository $repository)
    {
        $this->middleware('auth');
        $this->repository = $repository;
    }

    public function index()
    {
        $search = strtoupper(\Request::get('search'));

        $vehicles = $this->repository->searchVehiclesByIdentificationOrPlate($search);

        $titulo = trans('placa-lista.consultadeplaca');

        return view('sistema/cad/placa/lista', [
            'vehicles' => $vehicles,
            'titulo' => $titulo,
            'search' => $search,
            'id' => ''
        ]);

    }
    /**
     * Exibe os veículos de um cliente específico
     *
     * @param $id ID do cliente
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $search = strtoupper(\Request::get('search'));

        $vehicles = $this->repository->searchVehiclesByIdentificationOrPlateAndCustomerId($search, $id);

        $titulo = trans('placa-lista.consultadeplaca');

        return view('sistema/cad/placa/lista', [
            'vehicles' => $vehicles,
            'titulo' => $titulo,
            'search' => $search,
            'id' => $id
        ]);
    }
}
