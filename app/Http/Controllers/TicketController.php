<?php

namespace App\Http\Controllers;

use App\Repository\ContactRepository;
use App\Repository\CustomerRepository;
use App\Repository\DepartmentRepository;
use App\Repository\TicketAttachmentRepository;
use App\Repository\TicketHistoryRepository;
use App\Repository\TicketRepository;
use App\Repository\UserDepartmentRepository;
use App\Service\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon;

class TicketController extends Controller
{
    private $contactRepository;
    private $customerRepository;
    private $departmentRepository;
    private $emailService;
    private $ticketAttachmentRepository;
    private $ticketHistoryRepository;
    private $ticketRepository;
    private $userDepartmentRepository;

    public function __construct(ContactRepository $contactRepository, CustomerRepository $customerRepository,
                                DepartmentRepository $departmentRepository, EmailService $emailService,
                                TicketAttachmentRepository $ticketAttachmentRepository,
                                TicketHistoryRepository $ticketHistoryRepository, TicketRepository $repository,
                                UserDepartmentRepository $userDepartmentRepository)
    {
        $this->middleware('auth');
        $this->contactRepository = $contactRepository;
        $this->customerRepository = $customerRepository;
        $this->departmentRepository = $departmentRepository;
        $this->emailService = $emailService;
        $this->ticketAttachmentRepository = $ticketAttachmentRepository;
        $this->ticketHistoryRepository = $ticketHistoryRepository;
        $this->ticketRepository = $repository;
        $this->userDepartmentRepository = $userDepartmentRepository;
    }

    public function index()
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

        if (sizeof($userDepartments) <= 0) {
            return view('sistema/cad/protocolo/semsetor');
        }

        $departmentsList = '';

        for ($i = 0; $i < count($userDepartments); $i++) {
            $departmentObject = get_object_vars($userDepartments[$i]);

            $departmentsList = $departmentObject['id_setor'] . "," . $departmentsList;

            if ($i == (count($userDepartments) - 1)) {
                $departmentsList = substr($departmentsList, 0, -1);
            }
        }

        $tickets = $this->ticketRepository->searchByDepartmentsAndProtocolNumber($departmentsList, $search, $status,
            $orderBy, $mode);

        if (trim($status) == '') {
            $status = 'todos';
        }

        $customerIds = array();

        foreach ($tickets as $protocolToFetchCustomer) {
            if (!in_array($protocolToFetchCustomer->id_cliente, $customerIds)) {
                array_push($customerIds, $protocolToFetchCustomer->id_cliente);
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

        $filtroURLpendente = '/protocolo/?status=pendentes';
        $filtroURLconcluido = '/protocolo/?status=concluidos';
        $filtroURLtodos = '/protocolo/?status=todos';

        $title = trans('protocolo-lista.consultadeprotocolo');

        $departmentFilter = '';

        return view('sistema/cad/protocolo/lista', [
            'protocolos' => $tickets,
            'titulo' => $title,
            'url' => 'protocolo',
            'setoresusuario' => $userDepartments,
            'nome_setor' => '',
            'origem' => 'protocolo',
            'now' => Carbon\Carbon::now(),
            'aba' => 0,
            'filtropendente' => $filtroURLpendente,
            'filtroconcluido' => $filtroURLconcluido,
            'filtrotodos' => $filtroURLtodos,
            'modo' => $mode,
            'filtrostatus' => $status,
            'filtrosetor' => $departmentFilter,
            'search' => $search,
        ]);
    }

    public function create(Request $request)
    {
        $mode = 'create';
        $title = trans('protocolo-cad.cadastrarnovoprotocolo');

        $customers = $this->customerRepository->findActiveCustomers();
        $departments = $this->departmentRepository->findActiveDepartments();

        $oldCustomerId = $request->old('id_cliente');
        $requestEmail = $request->old('email');
        $oldEmail = array();
        $emails = array();

        if ($oldCustomerId > 0) {
            $contacts = $this->contactRepository->findContactsOfCustomer($oldCustomerId, '');

            // converter para array simples, porque nao aceita se for objeto
            // ou se estiver um array dentro do outro
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
            'selecionasetor' => 1,
            'id_setor' => 0,
            'nome_setor' => '',
            'origem' => 'protocolo',
            'emails' => $emails,
            'old_email' => $oldEmail,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_cliente' => 'required',
            'id_setor' => 'required',
            'solicitante' => 'required',
            'solicitacao' => 'required',
            'email' => 'required|array',
            'placa' => 'min:3|max:50',
            'arquivo' => 'array',
            'arquivo.*' => 'file|max:5000',
        ];

        $this->validate($request, $rules);

        $emailList = '';

        for ($i = 0; $i < count($request->email); $i++) {
            if ($i == (count($request->email) - 1)) {
                $emailList = $emailList . $request->email[$i];
            } else {
                $emailList = $emailList . $request->email[$i] . ',';
            }
        }

        // Formato: Ano mês dia hora minuto segundo
        $currentDate = Carbon\Carbon::now();
        $idProtocol = date_format($currentDate, 'YmdHis');

        $ticket = $this->ticketRepository->insert($request, $currentDate, $idProtocol, $emailList);

        // Gera os arquivos
        $attachmentFiles = Input::file('arquivo');

        if (is_array($attachmentFiles) && sizeof($attachmentFiles) > 0) {
            foreach ($attachmentFiles as $attachmentFile) {
                if (!is_null($attachmentFile)) {
                    $this->generateTicketAttachment($attachmentFile, $ticket);
                }
            }
        }

        // Gera o histórico
        $this->ticketHistoryRepository->insert($ticket);

        $department = $this->departmentRepository->findById($request->id_setor);
        $customer = $this->customerRepository->findById($request->id_cliente);

        $this->emailService->sendEmails($ticket, $department, $customer);

        if ($request->origem == 'protocolo') {
            return redirect('/protocolo');
        } else {
            return redirect('/protocolo/' . $request->id_setor . '/setor');
        }
    }

    public function show($id)
    {
        $title = trans('protocolo-exibe.exibedadosdoprotocolo');

        $ticket = $this->ticketRepository->findByIdWithCustomerAndDepartment($id);

        return view('sistema/cad/protocolo/exibir', [
            'protocolos' => $ticket,
            'titulo' => $title,
            'url' => 'protocolo'
        ]);
    }

    public function edit($id)
    {
        $mode = 'edit';
        $title = trans('protocolo-cad.alterarprotocolo') . $id;

        $ticket = $this->ticketRepository->findById($id);

        if (!$ticket) {
            return redirect('/protocolo');
        }

        return view('sistema/cad/protocolo/cad', [
            'protocolo' => $ticket,
            'id_protocolo' => $ticket->id_protocolo,
            'modo' => $mode,
            'titulo' => $title
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'id_cliente' => 'required',
            'id_setor' => 'required',
            'solicitante' => 'required',
            'solicitacao' => 'required',
        ]);

        $ticket = $this->ticketRepository->findById($id);

        if (!$ticket) {
            return redirect('/protocolo');
        }

        $this->ticketRepository->update($ticket, $request);

        return redirect('/protocolo');
    }

    protected function generateTicketAttachment($file, $ticket)
    {
        if ($file->getError() == UPLOAD_ERR_OK) {
            return $this->ticketAttachmentRepository->insert($file, $ticket);
        }
    }
}
