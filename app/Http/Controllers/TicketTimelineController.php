<?php

namespace App\Http\Controllers;

use App\Repository\TicketHistoryRepository;
use App\Repository\TicketRepository;
use App\Repository\UserDepartmentRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use DB;

class TicketTimelineController extends Controller
{
    private $userDepartmentRepository;
    private $userRepository;
    private $ticketHistoryRepository;
    private $ticketRepository;

    public function __construct(UserDepartmentRepository $userDepartmentRepository, UserRepository $userRepository,
                                TicketHistoryRepository $ticketHistoryRepository, TicketRepository $ticketRepository)
    {
        $this->middleware('auth');
        $this->userDepartmentRepository = $userDepartmentRepository;
        $this->userRepository = $userRepository;
        $this->ticketHistoryRepository = $ticketHistoryRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function index($id)
    {
        $protocol = $this->ticketRepository->findById($id);

        if (!$protocol) {
            return redirect('protocolo');
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
            if ($protocol->id_setor == $userDepartment->id_setor) {
                $isInSector = true;
                break;
            }
        }

        if (!$isInSector) {
            return redirect('protocolo');
        }

        $protocolHistory = $this->ticketHistoryRepository->findByTicketId($id);

        // TODO: Quando migrar pra postgres, usar JOIN
        // FDW não suporta JOINS, portanto obtém os dados dos usuários presentes nos históricos
        $usersWithId = [];

        foreach ($protocolHistory as $history) {
            if (!key_exists($history->id_usuario, $usersWithId)) {
                $usersWithId[$history->id_usuario] = null;
            }
        }

        $users = $this->userRepository->findUsersByIds(array_keys($usersWithId));

        foreach ($users as $user) {
            $usersWithId[$user->user_id] = $user;
        }

        $customer = $protocol->cliente;

        $titulo = trans('protocolo-timeline.dadosdeprotocolo');

        if (strpos(config('app.locale'), 'es') !== false) {
            Carbon::setLocale('es');
            setlocale(LC_TIME, 'es_ES');
        } else {
            Carbon::setLocale('pt_BR');
            setlocale(LC_TIME, 'pt_BR');
        }

        return view('sistema/cad/protocolo/timeline', [
            'customer' => $customer,
            'users' => $usersWithId,
            'historicos'=> $protocolHistory,
            'protocolo' => $protocol,
            'titulo' => $titulo
        ]);
    }
}
