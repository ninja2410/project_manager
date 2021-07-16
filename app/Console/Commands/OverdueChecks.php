<?php

namespace App\Console\Commands;

use App\Notification;
use App\Route;
use App\Traits\NotificationTrait;
use Illuminate\Console\Command;

class OverdueChecks extends Command
{
    use NotificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overdue:checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica los comandos con 6 meses de antiguedad y genera las alertas para mostrarle al cliente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Notification::truncate();
        $list = json_decode(json_encode($this->verifyOverdueChecksCommand()));
        foreach($list as $value){
            $nt = new Notification();
            $nt->message = $value->message;
            $nt->status = 1;
            $nt->account_id = $value->cliente;
            $nt->module = $value->module;
            $nt->url = $value->url;
            $nt->save();
        }
    }
}
