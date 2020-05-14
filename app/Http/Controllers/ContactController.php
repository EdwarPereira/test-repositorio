<?php

namespace App\Http\Controllers;

use App\Repository\ContactRepository;


class ContactController extends Controller
{
    private $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->middleware('auth');
        $this->repository = $repository;
    }

    public function index() { }

    public function show($id)
    {
        $search = strtoupper(\Request::get('search'));

        $contacts = $this->repository->findContactsOfCustomer($id, $search);

        return  response()->json($contacts)->header('Content-Type', 'text/html');
    }
}
