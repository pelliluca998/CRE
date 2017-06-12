<?php
use App\User;
use App\Group;
use App\TelegramUser;
?>
<script>
	function remove_user(user_id){
		//alert(user_id);
		$("#user_"+user_id).remove();
		$("#user_h_"+user_id).remove();
		$("#user_d_"+user_id).remove();
		$("#user_i_"+user_id).remove();
		$("#user_count").text($("[id^=user_h_]").length);
	}
	
	function conta_lettere(){
		$("#char").text($("#message").val().length);
	}
</script>
@extends('layouts.app')

@section('content')


{!! Form::open(['route' => 'telegram.send']) !!}
<div class="container">
	<div class="row">
		<h1>Invia Messaggio Telegram ai contatti selezionati</h1>
		<p class="lead">oppure <a href="{{ route('user.index') }}">torna all'anagrafica completa.</a></p>
		<hr>
	</div>
	<div class="row">
		<div class="">
			<?php $check_user=json_decode($check_user); 
			$users = User::select('users.id', 'users.name', 'users.cognome')
			->leftJoin('telegram_user', 'users.id', 'telegram_user.id_user')
			->whereIn('telegram_user.id_user', $check_user)
			->get();
			Session::reflash();
			?>
			<div class="panel panel-default panel-left">
				<div class="panel-heading">Contatti selezionati (<span id="user_count">{{count($users)}}</span>)<br>Vengono mostrati solo quelli che hanno un account Telegram attivo</div>
				<div class="panel-body">


				@foreach($users as $user)
					<i class="fa fa-user-o" id="user_i_{{$user->id}}"></i>
					{!! Form::label('user_'.$user->id, $user->cognome.' '.$user->name, ['id' => 'user_'.$user->id]) !!}
					{!! Form::hidden('user[]', $user->id, ['id' => 'user_h_'.$user->id]) !!} 
					<i class="fa fa-trash" onclick="remove_user({{$user->id}});" id="user_d_{{$user->id}}"></i>
					<br>
				@endforeach
				</div>

		</div>
		<div class="panel panel-default panel-right">
			<div class="panel-heading">Messaggio</div>
		<div class="panel-body">
			@if($errors->any())
			<div class="alert alert-danger">
				@foreach($errors->all() as $error)
					<p>{{ $error }}</p>
				@endforeach
			</div>
			@endif				

				<div class="form-group">
				{!! Form::label('message', 'Testo messaggio') !!}
				{!! Form::text('message', null, ['id' => 'message', 'class' => 'form-control']) !!}<br>
				</div>	
				<div class="form-group">
				{!! Form::submit('Invia!', ['class' => 'btn btn-primary form-control']) !!}
				</div>				
				

                   
                </div>
            </div>
        </div>
    </div>
</div>
	{!! Form::close() !!}
@endsection