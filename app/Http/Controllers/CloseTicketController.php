<?php

namespace App\Http\Controllers;

use App\Repository\CustomerRepository;
use App\Repository\DepartmentRepository;
use App\Repository\HistoryAttachmentRepository;
use App\Repository\TicketHistoryRepository;
use App\Repository\TicketRepository;
use App\Repository\UserDepartmentRepository;
use App\Service\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CloseTicketController extends Controller
{
    private $customerRepository;
    private $departmentRepository;
    private $emailService;
    private $historyAttachmentRepository;
    private $ticketHistoryRepository;
    private $ticketRepository;
    private $userDepartmentRepository;

    public function __construct(CustomerRepository $customerRepository, DepartmentRepository $departmentRepository,
                                EmailService $emailService, HistoryAttachmentRepository $historyAttachmentRepository,
                                UserDepartmentRepository $userDepartmentRepository,
                                TicketHistoryRepository $ticketHistoryRepository, TicketRepository $ticketRepository)
    {
        $this->middleware('auth');
        $this->customerRepository = $customerRepository;
        $this->departmentRepository = $departmentRepository;
        $this->emailService = $emailService;
        $this->historyAttachmentRepository = $historyAttachmentRepository;
        $this->ticketHistoryRepository = $ticketHistoryRepository;
        $this->ticketRepository = $ticketRepository;
        $this->userDepartmentRepository = $userDepartmentRepository;
    }

    public function create($id)
    {
        $ticket = $this->ticketRepository->findById($id);

        if (!$ticket) {
            return redirect('/protocolo');
        }

        // Protocolo já concluído, não permite gerar mais históricos pra ele
        if ($ticket->status == 1) {
            return redirect('/protocolo');
        }

        $loggedUserId = \Auth::user()->getUserId();

        $userDepartments = $this->userDepartmentRepository->findUserDepartmentsByUserId($loggedUserId);

        // Usuário sem setor? Exibe o erro
        if (sizeof($userDepartments) <= 0) {
            return view('sistema/cad/protocolo/semsetor');
        }

        $isInSector = false;

        // Verifica se os setores do usuário tem haver com o setor do protocolo, caso não tenha não permite a visualização
        foreach ($userDepartments as $userDepartment) {
            if ($ticket->id_setor == $userDepartment->id_setor) {
                $isInSector = true;
                break;
            }
        }

        if (!$isInSector) {
            return redirect('protocolo');
        }

        $title = trans('protocolo-concluir.conclusaodeprotocolo') . ' #' . $ticket->protocolo;

        $mode = 'create';

        $departments = $this->departmentRepository->findActiveDepartments();

        return view('sistema/cad/protocolo/concluir', [
            'id_protocolo' => $id,
            'titulo' => $title,
            'modo' => $mode,
            'usuario_logado' => $loggedUserId,
            'setores' => $departments,
            'id_setor' => $ticket->id_setor,
            'data' => $ticket->data,
            'dum' => $ticket->dum
        ]);
    }

    public function store(Request $request)
    {
        $tolerance = $department = $this->departmentRepository->findById($request->id_setor);

        if (($request->concluido == 1 ) || ($request->id_setor != $request->id_setor_original)) {
            $this->validate($request, [
                'observacao'=>'required',
                'arquivo' => 'array',
                'arquivo.*' => 'file|max:5000',
            ]);
        } else {
            $this->validate($request, [
                'observacao' => 'required',
                'previsao' => 'required|date_format:d/m/Y|range:' . $request->data . "," . $tolerance->tempo,
                'outro' => 'as',
                'arquivo' => 'array',
                'arquivo.*' => 'file|max:5000',
            ]);
        }

        $ticket = $this->ticketRepository->findById($request->id_protocolo);

        $loggedUserId = \Auth::user()->getUserId();

        $userDepartments = $this->userDepartmentRepository->findUserDepartmentsByUserId($loggedUserId);

        // Usuário sem setor? Exibe o erro
        if (sizeof($userDepartments) <= 0) {
            return view('sistema/cad/protocolo/semsetor');
        }

        $isInSector = false;

        // Verifica se os setores do usuário tem haver com o setor do protocolo, caso não tenha não permite a visualização
        foreach ($userDepartments as $userDepartment) {
            if ($ticket->id_setor == $userDepartment->id_setor) {
                $isInSector = true;
                break;
            }
        }

        if (!$isInSector) {
            return redirect('protocolo');
        }

        $ticketHistory = $this->ticketHistoryRepository->insertFromTicketUpdate($ticket, $request);

        // Arquivos selecionados
        $attachmentFiles = Input::file('arquivo');

        if (is_array($attachmentFiles) && sizeof($attachmentFiles) > 0) {
            foreach ($attachmentFiles as $attachmentFile) {
                if (!is_null($attachmentFile)) {
                    $this->generateHistoryAttachment($attachmentFile, $ticketHistory);
                }
            }
        }

        $this->ticketRepository->updateFromHistory($ticket, $ticketHistory, $request);

        $department = $this->departmentRepository->findById($ticketHistory->id_setor);
        $customer = $this->customerRepository->findById($ticket->id_cliente);

        $this->emailService->sendEmailsFromTicketUpdate($ticket, $department, $customer);

        return redirect('/protocolo');
    }

    protected function generateHistoryAttachment($file, $history)
    {
        if ($file->getError() == UPLOAD_ERR_OK) {
            return $this->historyAttachmentRepository->insert($file, $history);
        }
    }
}
