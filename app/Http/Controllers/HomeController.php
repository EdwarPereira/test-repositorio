<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Gate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('/protocolo/?status=pendentes');
    }

    public function es()
    {
        Session::put('locale', 'es');
        return redirect('/');
    }

    public function pt_BR()
    {
        Session::put('locale', 'pt-BR');
        return redirect('/');
    }
}
