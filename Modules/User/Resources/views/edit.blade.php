<?php
use Modules\User\Entities\User;
use App\Role;
use App\RoleUser;
use Modules\Attributo\Entities\Attributo;
use Modules\Attributo\Entities\AttributoUser;
use App\TypeSelect;
use Modules\User\Entities\Group;
?>

@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<h1>Modifica utente</h1>
		<p class="lead">Modifica e salva l'utente qui sotto, oppure <a href="{{ route('user.index') }}">torna all'anagrafica completa.</a></p>
		<hr>	
	</div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
		<div class="panel-heading">Anagrafica Utenti</div>
		<div class="panel-body">
			
			{!! Form::model($user, ['method' => 'PATCH','files' => true,'route' => ['user.update', $user->id]]) !!}
				{!! Form::hidden('id_user', $user->id) !!}
				<div class="form-group">
				{!! Form::label('name', 'Nome') !!}
				{!! Form::text('name', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
				{!! Form::label('cognome', 'Cognome') !!}
				{!! Form::text('cognome', null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
				{!! Form::label('sesso', 'Sesso') !!}
				{!! Form::select('sesso', array('M' => 'Uomo', 'F' => 'Donna'), null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
				{!! Form::label('nato_il', 'Data di Nascita') !!}
				{!! Form::text('nato_il', null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
				{!! Form::label('nato_a', 'Luogo di Nascita') !!}
				{!! Form::text('nato_a', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
				{!! Form::label('residente', 'Residente a') !!}
				{!! Form::text('residente', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
				{!! Form::label('via', 'Indirizzo') !!}
				{!! Form::text('via', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
				{!! Form::label('email', 'Email') !!}
				{!! Form::text('email', null, ['class' => 'form-control']) !!}
				</div>
			
				<div class="form-group">
				{!! Form::label('cell_number', 'Numero cellulare') !!}
				{!! Form::text('cell_number', null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="form-group">
				{!! Form::label('username', 'Username') !!}
				{!! Form::text('username', null, ['class' => 'form-control']) !!}
				</div>
			
				<div class="form-group">
					<div class="form-group" style="width: 48%; float: left;">
						{!! Form::label('photo', 'Foto Profilo') !!}
						{!! Form::file('photo', null, ['class' => 'form-control']) !!}
					</div>

					<div class="form-group" style="width: 48%; float: left;">
						Foto attuale:<br>
						<?php
						if($user->photo!=''){
							echo "<img src='".url(Storage::url('public/'.$user->photo))."' width=200px/>";
						}else{
							echo "Nessuna immagine!<br><br>";
						}
						?>
					</div>
				</div>
			
				<div class="form-group">
				{!! Form::label('id_role', 'Ruolo') !!}
				{!! Form::select('id_role', Role::where('id_oratorio', Session::get('session_oratorio'))->orderBy('id')->pluck('display_name', 'id'), $user->roles[0]->id, ['class' => 'form-control']) !!}
				</div>
				
				<h3>Cambia password</h3>
				  <div class="form-group">
					  <p>Se vuoi cambiare la tua password, inseriscila qui sotto</p>
					  {!! Form::label('password', 'Nuova password') !!}
					  {!! Form::password('password', null, ['class' => 'form-control']) !!}
				  </div>
				  
				<!--ATTRIBUTI//-->
				  <?php
				  $attributos = Attributo::where([['id_oratorio', Session::get('session_oratorio')]])->orderBy('ordine', 'ASC')->get();
				  ?>
				  
				  
				  
				  @if(count($attributos)>0)
				  <h3>Informazioni aggiuntive</h3>
				  @endif
				  @foreach($attributos as $a)
				  	<?php
				  	$valore = AttributoUser::where([['id_user', $user->id],['id_attributo', $a->id]])->get();
				  	if(count($valore)>0){
				  		$attributo_val = $valore[0]->valore;
				  		$attributo_id = $valore[0]->id;
				  	}else{
				  		$attributo_val = null;
				  		$attributo_id = 0;
				  	}
				  	?>
					<div class="form-group">
						{!! Form::hidden('id_attributo['.$loop->index.']', $a->id) !!}
						{!! Form::hidden('id_attributouser['.$loop->index.']', $attributo_id) !!}
						{!! Form::label('nome', $a->nome) !!}
					  	@if($a->id_type>0)
							{!! Form::select('valore['.$loop->index.']', TypeSelect::where('id_type', $a->id_type)->orderBy('ordine', 'ASC')->pluck('option', 'id'), $attributo_val , ['class' => 'form-control'])!!}
						@else
							@if($a->id_type==-1)
								{!! Form::text('valore['.$loop->index.']', $attributo_val , ['class' => 'form-control']) !!}
							@elseif($a->id_type==-2)
								{!! Form::hidden('valore['.$loop->index.']', 0) !!}
								{!! Form::checkbox('valore['.$loop->index.']', 1, $attributo_val , ['class' => 'form-control']) !!}
							@elseif($a->id_type==-3)
								{!! Form::number('valore['.$loop->index.']', $attributo_val , ['class' => 'form-control']) !!}
							@elseif($a->id_type==-4)
								{!! Form::select('valore['.$loop->index.']', Group::where('id_oratorio', Session::get('session_oratorio'))->orderBy('nome', 'ASC')->pluck('nome', 'id'), $attributo_val , ['class' => 'form-control'])!!}				
							@endif
						@endif
					
					
						

					</div>
				  @endforeach

				<div class="form-group">
				{!! Form::submit('Salva Utente', ['class' => 'btn btn-primary form-control']) !!}
				</div>
           		{!! Form::close() !!}           		
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
