<?php

namespace App\Http\Controllers;

use App\Repository\ContactRepository;
use App\Repository\CustomerRepository;
use App\Repository\DepartmentRepository;
use App\Repository\TicketRepository;
use App\Repository\UserDepartmentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketDepartmentController extends Controller
{
    private $contactRepository;
    private $customerRepository;
    private $departmentRepository;
    private $ticketRepository;
    private $userDepartmentRepository;

    public function __construct(ContactRepository $contactRepository, CustomerRepository $customerRepository,
                                DepartmentRepository $departmentRepository, TicketRepository $ticketRepository,
                                UserDepartmentRepository $userDepartmentRepository)
    {
        $this->middleware('auth');
        $this->contactRepository = $contactRepository;
        $this->customerRepository = $customerRepository;
        $this->departmentRepository = $departmentRepository;
        $this->ticketRepository = $ticketRepository;
        $this->userDepartmentRepository = $userDepartmentRepository;
    }

    public function index($id)
    {
        $status = \Request::get('status');
        $order = \Request::get('ordem');
        $mode = \Request::get('modo');
        $orderBy = 'protocolo.data';

        if ($order == 'id') {
            $orderBy = 'protocolo.protocolo';
        } else if ($order == 'data') {
            $orderBy = 'protocolo.data';
        } else if ($order == 'cliente') {
            $orderBy = 'id_cliente';
        } else if ($order == 'setor') {
            $orderBy = 'setor.nome';
        } else if ($order == 'status') {
            $orderBy = 'protocolo.status';
        } else if ($order == 'previsao') {
            $orderBy = 'protocolo.dum';
        }

        if ($mode == 'asc') {
            $mode = 'desc';
        } else if ($mode == 'desc') {
            $mode = 'asc';
        } else {
            $mode = 'asc';
        }

        $search = strtoupper(\Request::get('search'));

        $loggedUserId = \Auth::user()->getUserId();

        $userDepartments = $this->userDepartmentRepository->findUserDepartmentsByUserId($loggedUserId);

        $departmentsList = '';

        for ($i = 0; $i < count($userDepartments); $i++) {
            $objeto = get_object_vars($userDepartments[$i]);

            $departmentsList = $objeto['id_setor'] . "," . $departmentsList;

            if ($i == (count($userDepartments) - 1)) {
                $departmentsList = substr($departmentsList, 0, -1);
            }
        }

        $tickets = $this->ticketRepository->searchByDepartmentIdAndProtocolNumber($id, $search, $status, $orderBy, $mode);

        $customerIds = array();

        foreach ($tickets as $protocoloToFetchCustomer) {
            if (!in_array($protocoloToFetchCustomer->id_cliente, $customerIds)) {
                array_push($customerIds, $protocoloToFetchCustomer->id_cliente);
            }
        }

        $customers = $this->customerRepository->findCustomersByIds($customerIds);

        for ($i = 0; $i < sizeof($tickets); $i++) {
            $protocol = $tickets[$i];
            for ($j = 0; $j < sizeof($customers); $j++) {
                $customer = $customers[$j];
                if ($customer->id_customer == $protocol->id_cliente) {
                    $protocol->name = $customer->name;
                }
            }
        }

        $filtroURLpendentes = '/protocolo/' . $id . '/setor/?status=pendentes';
        $filtroURLtodos = '/protocolo/' . $id . '/setor/?status=todos';
        $filtroURLconcluidos = '/protocolo/' . $id . '/setor/?status=concluidos';

        $title = trans('protocolo-lista.consultadeprotocolo');

        $filtrosetor = $id . '/setor/';

        return view('sistema/cad/protocolo/lista', [
            'protocolos' => $tickets,
            'titulo' => $title,
            'url' => 'protocolo/' . $id . '/setor',
            'setoresusuario' => $userDepartments,
            'aba' => $id,
            'now' => Carbon::now(),
            'filtropendente' => $filtroURLpendentes,
            'filtroconcluido' => $filtroURLconcluidos,
            'filtrotodos' => $filtroURLtodos,
            'modo' => $mode,
            'filtrostatus' => $status,
            'filtrosetor' => $filtrosetor,
        ]);
    }

    public function create($id, Request $request)
    {
        $mode = 'create';
        $title = trans('protocolo-cad.cadastrarnovoprotocolo');

        $customers = $this->customerRepository->findActiveCustomers();
        $departments = $this->departmentRepository->findActiveDepartments();
        $department = $this->departmentRepository->findById($id);

        $emails = array();
        $requestEmail = $request->old('email');
        $oldEmail = array();

        $oldCustomerId = $request->old('id_cliente');

        if ($oldCustomerId > 0) {
            $contacts = $this->contactRepository->findContactsOfCustomer($oldCustomerId, '');
            // converter para array simples, porque nao aceita se for objeto
            //ou se estiver um array dentro do outro
            for ($i = 0; $i < count($contacts); $i++) {
                $contact = $contacts[$i];
                if (!in_array($contact->email, $emails)) {
                    array_push($emails, $contact->email);
                }
                if ($requestEmail != null) {
                    if (in_array($contact->email, $requestEmail) && !in_array($contact->email, $oldEmail)) {
                        array_push($oldEmail, $contact->email);
                    }
                }
            }
        } else {
            $oldEmail = array();
        }

        $loggedUserId = \Auth::user()->getUserId();

        return view('sistema/cad/protocolo/cad', [
            'modo' => $mode,
            'titulo' => $title,
            'clientes' => $customers,
            'setores' => $departments,
            'usuario_logado' => $loggedUserId,
            'id_setor' => $id,
            'nome_setor' => $department->nome,
            'origem' => 'setor',
            'now' => Carbon::now(),
            'old_email' => $oldEmail,
            'emails' => $emails,
        ]);
    }
}
