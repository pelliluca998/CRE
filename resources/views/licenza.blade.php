<?php
use App\Week;
use App\Event;
use App\SpecSubscription;
use App\User;
use App\CampoWeek;
use App\Oratorio;
use App\LicenseType;
use App\UserOratorio;
?>

@extends('layouts.app')
@section('content')
<div class="container">
@if(Session::has('flash_message'))
    <div class="alert alert-success">
        {{ Session::get('flash_message') }}
    </div>
@endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Licenza</div>


                <div class="panel-body">                
				<p>La funzione a cui stai tentanto di accedere non è disponibile per la licenza associata al tuo oratorio.<br>Se vuoi sbloccare tutte le funzioni, devi acquistare una licenza completa!<br>Oppure verifica che l'attuale licenza si attiva.
				<a href='http://www.segresta.it'>Clicca sui per maggiori informazioni</a></p>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection
