<?php
use Modules\User\Entities\User;
?>

@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<h1>Modifica settimana</h1>
		<p class="lead">Modifica e salva la settimana qui sotto, oppure <a href="{{ route('week.index') }}">torna all'anagrafica completa.</a></p>
		<hr>
	</div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
		<div class="panel-heading">Proprietà settimana</div>
		<div class="panel-body">	
			{!! Form::model($week, ['method' => 'PATCH','route' => ['week.update', $week->id]]) !!}
				{!! Form::hidden('id_week', $week->id) !!}
				<div class="form-group">
				{!! Form::label('from_date', 'Data inizio') !!}
				{!! Form::text('from_date', null, ['class' => 'form-control','id' => 'datepicker']) !!}
				</div>

				<div class="form-group">
				{!! Form::label('to_date', 'Data fine') !!}
				{!! Form::text('to_date', null, ['class' => 'form-control','id' => 'datepicker2']) !!}
				</div>

				<div class="form-group">
				{!! Form::submit('Salva Settimana', ['class' => 'btn btn-primary form-control']) !!}
				</div>
           		{!! Form::close() !!}           		

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
