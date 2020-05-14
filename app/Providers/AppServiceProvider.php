<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Validator::extend('range', function($attribute, $value, $parameters, $validator) {



            $tolerancia = $parameters[1];

           if  (! is_numeric($tolerancia) ) {
               $tolerancia = 5;
           }

           // tive que fazer isso porque a data estava vindo com a hora também.
           $compara = explode('/',$value);

           $compara[2] = explode(' ',$compara[2]);
           $compara[2] = $compara[2][0];

           $compara = Carbon\Carbon::createFromDate($compara[2],$compara[1],$compara[0]);


           $now = Carbon\Carbon::now();
           $now->addDay($tolerancia);
           $now = strToTime($now);


           //echo $compara;
           //echo "<br>";
           //echo $now;
           //exit;

           //$data = explode('/',$value);
           //$data = Carbon\Carbon::createFromDate($data[2],$data[1],$data[0]);
           //$data = strtotime($data);

           //comparar com a data do dia, ao invez de comparar com a data que foi
           //gerado o ultimo protocolo (MAUREN)
           $data = strtotime($compara);




           if ($data <= $now){
               return true;
           }else{
               return false;
           }


        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
