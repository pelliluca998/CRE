<?php
use Modules\Attributo\Entities\Attributo;
use App\Type;
use Modules\Event\Entities\EventSpec;
use Modules\Event\Entities\Event;
use Modules\Oratorio\Entities\Oratorio;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Segresta') }}</title>

    <!-- Styles -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/segresta-style.css') }}" rel="stylesheet">
	<link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">




    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

	<script src="{{ asset ('/js/tinymce/tinymce.min.js') }}" ></script>
	<script src="https://use.fontawesome.com/390bf8aef1.js"></script>

	<script src="{{ asset('/js/jscolor.js') }}"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="{{ asset('/js/jscolor.js') }}"></script>
	<script type="text/javascript" src="{{ asset ('/js/datepicker-it.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/bootstrap-confirmation.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/jquery.redirect.js') }}"></script>

@include('piwik')

</head>

<body>

@include('header')

    	@if(!Auth::guest())
			@if (Session::get('session_oratorio')!=null)
			<!--logo oratorio//-->
			<div style='text-align: center;'>
				<?php
				$oratorio = Oratorio::where('id', Session::get('session_oratorio'))->first();
				if($oratorio->logo!=''){
					echo "<img src='".url(Storage::url('public/'.$oratorio->logo))."' width='170px' ><br>";
				}
				?>
				<h2>{{$oratorio->nome}}</h2>
			</div>



	    			@if(Entrust::hasRole(['admin']) || Entrust::hasRole(['owner']))
	    				<?php
	    					$event=Event::where('id', Session::get('work_event'))->first();
	    					$buttons_1 = array(
				                  ["label" => "Specifiche evento",
				                   "desc" => "",
				                  "url" => "eventspecs.show",
				                  "class" => "btn-primary",
				                  "icon" => ""],
				                  ["label" => "Iscrizioni",
				                   "desc" => "",
				                  "url" => "subscription.index",
				                  "class" => "btn-primary",
				                  "icon" => ""]
				           );

				           $buttons_2 = array(
				                  ["label" => "Seleziona evento",
				                   "desc" => "",
				                  "url" => "events.index",
				                  "class" => "btn-primary",
				                  "icon" => ""]
				           );
				           $buttons = array();
	    				?>
	    				<div class='panel panel-default'>
	    					<div class='panel-heading' style="height: 55px;">
			    				@if(null!==Session::get('work_event') && Event::find(Session::get('work_event'))->id_oratorio == Session::get('session_oratorio'))
			    						<div style="padding: 5px; float:left; margin-right: 5px; background-color: #FF6347;" ><b>{{$event->nome}}</b> - <i>{{str_limit(strip_tags($event->descrizione), 30, ' ...')}}</i></div>
			    						<?php $buttons=$buttons_1; ?>
			    				@else
			    					@if($oratorio->last_id_event>0 && Event::findOrFail($oratorio->last_id_event)->count()>0)
			    						<?php Session::put('work_event', $oratorio->last_id_event);
			    						$event=Event::where('id', Session::get('work_event'))->first();
			    						?>
			    						<div style="padding: 5px; float:left; margin-right: 5px; background-color: #FF6347;" ><b>{{$event->nome}}</b> - <i>{{strip_tags($event->descrizione)}}</i></div>
			    						<?php $buttons=$buttons_1; ?>
			    					@else
			    						<div style="float:left; margin-right: 5px;">Non hai specificato nessun evento!</div>
				    					<?php $buttons=$buttons_2; ?>
			    					@endif


			    				@endif
			    				<div style="float:left; margin-right: 5px;"> <i class="fa fa-star" aria-hidden="true"></i>
 Accesso rapido: </div>
			    				@foreach ($buttons as $button)
		    						<div style="float:left; margin-right: 5px;">
		    						{!! Form::open(['route' => $button['url'], 'method' => 'GET']) !!}
				                	{!! Form::hidden('id_event', Session::get('work_event'), ['id' => 'id_event']) !!}
				                 	{!! Form::submit($button['label'], ['class' => 'btn '.$button['class']]) !!}
				                 	{{$button['desc']}}
				                 	{!! Form::close() !!}
				                 	</div>
				               @endforeach
		    				</div>
		    			</div>
	    			@endif
    			@endif


    	@endif

@yield('content')

    <!-- Scripts -->
	<script>


tinymce.init({
	  selector: 'textarea',
	  height: 180,
	  width : '100%',
	  theme: 'modern',
	  plugins: [
	    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
	    'searchreplace wordcount visualblocks visualchars code fullscreen',
	    'insertdatetime media nonbreaking save table contextmenu directionality',
	    'emoticons template paste textcolor colorpicker textpattern imagetools'
	  ],
	  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	  toolbar2: 'print preview media | forecolor backcolor emoticons',
	  image_advtab: true,
	  templates: [
	    { title: 'Test template 1', content: 'Test 1' },
	    { title: 'Test template 2', content: 'Test 2' }
	  ],
	  content_css: [
	    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
	    '//www.tinymce.com/css/codepen.min.css'
	  ]
});

$(function() {
	$.datepicker.setDefaults(
		$.extend(
			{'dateFormat':'dd/mm/yy'},
			$.datepicker.regional['it']
		)
	);
	$("#datepicker").datepicker();
	$("#datepicker2").datepicker();
	$("#nato_il").datepicker();
});

function add_eventspec(id_sub, id_event, admin){
	var valid_for = $('#valid_for').val(); //è l'id_week
	var event_spec = $('#event_spec').val();
	var event_spec_text = $('#event_spec option:selected').text();
	var id_type = $('#event_spec').find(':selected').data('type');
	var price = $('#event_spec').find(':selected').data('price');

	var t = parseInt($('#contatore_e').val());
	var row = "<tr style='background-color: #dff0d8;'>";
	row += "<td>";
	row += "<input name='id_eventspecvalue["+t+"]' type='hidden' value='0'/>";
	row += "<input name='id_eventspec["+t+"]' type='hidden' value='"+event_spec+"'/>";
	row += "<input name='id_subscription["+t+"]' type='hidden' value='"+id_sub+"'/>";
	row += "<input name='id_week["+t+"]' type='hidden' value='"+valid_for+"'/>";
	row += event_spec_text+"</td>";
	row += "<td>";

	if(id_type>0){
		row += "<select id='valore"+t+"' name='valore["+t+"]' class='form-control'></select>";
		$.get("{{ url('types/options')}}",
			{id_type: id_type },
		    	function(data2) {
				var model = $("#valore"+t);
				model.empty();
				$.each(data2, function(index_2, element_2) {
					model.append("<option value='"+ element_2.id +"'>" + element_2.option + "</option>");
				});
		});
	}else{
		switch(id_type){
			case -1:
				row += "<input name='valore["+t+"]' type='text' value='' class='form-control'/>";
				break;
			case -2:
				row += "<input name='valore["+t+"]' type='hidden' value='0'/>";
				row += "<input name='valore["+t+"]' type='checkbox' value='1' class='form-control'/>";
				break;
			case -3:
				row += "<input name='valore["+t+"]' type='number' value='' class='form-control'/>";
				break;
			case -4:
				row += "<select id='valore"+t+"' name='valore["+t+"]' class='form-control'></select>";
				$.get("{{ url('admin/groups/dropdown')}}",
					{},
				    	function(data2) {
						var model = $("#valore"+t);
						model.empty();
						$.each(data2, function(index_2, element_2) {
							model.append("<option value='"+ element_2.id +"'>" + element_2.nome + "</option>");
						});
				});
				break;
		}
	}



	row += "</td>";
	if(admin){
		row += "<td><input name='costo["+t+"]' type='number' value='"+price+"' class='form-control' style='width: 70px;' step='0.01'/></td>";
		row += "<td>";
		row += "<input name='pagato["+t+"]' type='hidden' value='0'/>";
		row += "<input name='pagato["+t+"]' type='checkbox' value='1' class='form-control'/>";
		row += "</td>";
	}else{
		row += "<td><input name='costo["+t+"]' type='hidden' value='0' class='form-control' style='width: 70px;' />"+price+"€</td>";
		row += "<td>";
		row += "<input name='pagato["+t+"]' type='hidden' value='0'/>";
		row += "</td>";
	}
	row += "<td></td>"; //cestino
	row += "</tr>";



	if(valid_for==0){//inserisco una riga nella tabella delle specifiche generali
		$('#showeventspecvalue tr:last').after(row);
	}else{ //riga nelle tabelle settimanali
		$('#weektable_'+valid_for+' tr:last').after(row);
	}
	$('#contatore_e').val((t+1));




	$('#eventspecsOp').modal('hide');
}

function change_eventspec(sel, id_event){
	$.get("{{ url('eventspec/dropdown')}}",
	{id_week: sel.value,
	id_event: id_event },
	function(data){
		var model = $("#event_spec");
		model.empty();
		$.each(data, function(index, element) {
			var prices = JSON.parse(element.price);
			model.append("<option value='"+ element.id +"' data-price='"+prices[sel.value]+"' data-type='"+element.id_type+"'>" + element.label + "</option>");
		});
	});
}

/**
Funzione che viene richiamata quando un select cambia valore; viene popolato lo span (#span_type)
con un input del tipo corretto (seelct, testo, checkbox).

sel: il select
multiple: se viene generato un select, indica se sono possibili scelte multiple
name: il name da dare all'input generato
id: l'id da dare all'input generato
**/
function change_type(sel, multiple='', name='valore', id='valore', show_checkbox_hidden=true, span_id="span_type"){
	$.get("{{ url('types/type')}}",
		{id_eventspec: sel.value},
	    	function(data) {
			$.each(data, function(index, element) {
				var row = "";
				if(element.id>0){
					row = "<select id='"+id+"' name='"+name+"' "+multiple+" class='form-control'></select>";
					$.get("{{ url('types/options')}}",
						{id_type: element.id },
					    	function(data2) {
							var model = $("#"+id+"");
							model.empty();
							$.each(data2, function(index_2, element_2) {
								model.append("<option value='"+ element_2.id +"'>" + element_2.option + "</option>");
							});
					});
				}else{
					switch(element.id){
						case -1:
							row = "<input name='"+name+"' type='text' value='' class='form-control' style='width: 300px'/>";
							break;
						case -2:
							if(show_checkbox_hidden){
								row = "<input name='"+name+"' type='hidden' value='0'/>";
							}
							row += "<input name='"+name+"' type='checkbox' value='1' />";
							break;
						case -3:
							row = "<input name='"+name+"' type='number' value='' class='form-control' style='width: 300px'/>";
							break;
						case -4:
							row = "<select id='"+id+"' name='"+name+"'></select>";
							$.get("{{ url('admin/groups/dropdown')}}",
								{},
							    	function(data2) {
									var model = $("#"+id+"");
									model.empty();
									$.each(data2, function(index_2, element_2) {
										model.append("<option value='"+ element_2.id +"'>" + element_2.nome + "</option>");
									});
							});
							break;
					}
				}
				$("#"+span_id).html(row);
			});
	});
}

function change_attrib(sel, t){
	$.get("{{ url('types/type_attrib')}}",
		{id_attrib: sel.value },
	    	function(data) {
			$.each(data, function(index, element) {
				var row = "";
				if(element.label=="text"){
					row = "<input name='valore["+t+"]' type='text' value='' class='form-control' style='width: 300px'/>";
				}else if(element.label=="checkbox"){
					row = "<input name='valore["+t+"]' type='hidden' value='0'/>";
					row += "<input name='valore["+t+"]' type='checkbox' value='1'/>";
				}else{
					row = "<select id='valore"+t+"' name='valore["+t+"]'></select>";
					$.get("{{ url('types/options')}}",
						{id_type: element.id },
					    	function(data2) {
							var model = $("#valore"+t);
							model.empty();
							$.each(data2, function(index_2, element_2) {
								model.append("<option value='"+ element_2.id +"'>" + element_2.option + "</option>");
							});
					});
				}
				$("#span_type"+t).html(row);
			});
	});
}


function eventspecs_add(id_event){
	//var t = parseInt($('#contatore').val());
	var row = "<tr>";
	row += "<input name='id_spec[]' type='hidden' value='0'/>";
	row += "<input name='hidden[]' type='hidden' value='0'/>";
	row += "<input name='event[]' type='hidden' value='"+id_event+"'/>";
  //Sposta
  row += "<td><i class='fa fa-arrows fa-2x'></td>";
	//Nome
	var form = ('{{ Form::text("label[]", "", ["class" => "form-control", "style" => "width: 100%"]) }}').replace(/"/g, '\'');
	//form = form.replace("label[]", "label["+t+"]");
	row += "<td>"+form+"</td>";
	//Descrizione
	form = ('{{ Form::text("descrizione[]", "", ["class" => "form-control", "style" => "width: 100%"]) }}').replace(/"/g, '\'');
	//form = form.replace("descrizione[]", "descrizione["+t+"]");
	row += "<td>"+form+"</td>";
	//tipo
	var select = ('{{ Form::select("id_type[]", Type::getTypes(), null, ["class" => "form-control"]) }}').replace(/"/g, '\'');
	//select = select.replace("id_type[]", "id_type["+t+"]");
	row += "<td>"+select+"</td>";
	//Generale
	//form = ('{{ Form::hidden("general[]", "0") }} {{ Form::checkbox("general[]", "1", false,  ["class" => "form-control"]) }}').replace(/"/g, '\'');
	//form = form.replace("general[]", "general["+t+"]");
	row += "<td></td>";
	row += "<td>";

	row += "</td>";
	row += "<td></td>";
	row += "</tr>";

	$('#showeventspecs tr:last').after(row);
	//$('#contatore').val((t+1));

}

function typeselect_add(id_type){
	var t = parseInt($('#contatore_e').val());
	var row = "<tr>";
	var form = ('{{ Form::text("option[]", "", ["style" => "width: 100%"]) }}').replace(/"/g, '\'');
	form = form.replace("option[]", "option["+t+"]");
	row += "<input name='id_option["+t+"]' type='hidden' value='0'/>";
	row += "<input name='id_type["+t+"]' type='hidden' value='"+id_type+"'/>";
	row += "<td>"+form+"</td>";
	row += "<td><input type='number' min='0' name='ordine["+t+"]' value='0'</td>";
	row += "<td>E</td>";
	row += "</tr>";

	$('#showoptions tr:last').after(row);
	$('#contatore_e').val((t+1));

}

function add_cassa(){
	var t = parseInt($('#contatore_c').val());
	var row = "<tr>";
	row += "<td>#<input type='hidden' value='0' name='id["+t+"]'></td>";
	row += "<td><input type='text' name='label["+t+"]' class='form-control'/></td>";
	// row += "<td></td>";
	row += "<td></td>";
	row += "</tr>";

	$('#table_casse tr:last').after(row);
	$('#contatore_c').val((t+1));

}

function add_modo_pagamento(){
	var t = parseInt($('#contatore_m').val());
	var row = "<tr>";
	row += "<td>#<input type='hidden' value='0' name='id["+t+"]'></td>";
	row += "<td><input type='text' name='label["+t+"]' class='form-control'/></td>";
	// row += "<td></td>";
	row += "<td></td>";
	row += "</tr>";

	$('#table_modo tr:last').after(row);
	$('#contatore_m').val((t+1));

}

function add_tipo_pagamento(){
	var t = parseInt($('#contatore_t').val());
	var row = "<tr>";
	row += "<td>#<input type='hidden' value='0' name='id["+t+"]'></td>";
	row += "<td><input type='text' name='label["+t+"]' class='form-control'/></td>";
	// row += "<td></td>";
	row += "<td></td>";
	row += "</tr>";

	$('#table_tipo tr:last').after(row);
	$('#contatore_t').val((t+1));

}


//A seconda dell'attributo selezionato, cambio la casella dove inserire il valore (testo, checkbox, ...)
function change_attributo_type(sel){
   if(sel.value<0) return;
   $.get("{{ url('attributos/type')}}",
		{id_attributo: sel.value },
	    	function(data){
           if(data.length>0){
               var row = "";
               $.each(data, function(index, element) {

               	if(element.id_type>0){
					row = "<select id='valore' name='valore' class='form-control'></select>";
					$.get("{{ url('types/options')}}",
						{id_type: element.id_type },
					    	function(data2) {
							var model = $("#valore");
							model.empty();
							$.each(data2, function(index_2, element_2) {
								model.append("<option value='"+ element_2.id +"'>" + element_2.option + "</option>");
							});
					});
				}else{
					switch(element.id_type){
						case -1:
							row = "<input name='valore' type='text' value='' class='form-control'/>";
							break;
						case -2:
							row = "<input name='valore' type='hidden' value='0'/>";
							row += "<input name='valore' type='checkbox' value='1' class='form-control'/>";
							break;
						case -3:
							row = "<input name='valore' type='number' value='' class='form-control' />";
							break;
						case -4:
							row = "<select id='valore' name='valore' class='form-control'></select>";
							$.get("{{ url('admin/groups/dropdown')}}",
								{},
							    	function(data2) {
									var model = $("#valore");
									model.empty();
									$.each(data2, function(index_2, element_2) {
										model.append("<option value='"+ element_2.id +"'>" + element_2.nome + "</option>");
									});
							});
							break;
					}
				}

			});
               $("#attrib_value").html(row);
           }
   });


}

function load_attrib_registration(sel){
	var body = "";
	$.get("{{ url('attributos/dropdown')}}",
		{id_oratorio: sel.value },
	    	function(data) {
	    	if(data.length>0){
               var t = 0;
           body+= "INFORMAZIONI AGGIUNTIVE";
			$.each(data, function(index, element) {
				body += "<div class='form-group'>";
				body += "<label for='attrib_"+element.id+"' class='col-md-4 control-label'>"+element.nome+"</label>";
				body += "<div class='col-md-6'>";
				body += "<input type='hidden' name='id_attributo["+t+"]' value='"+element.id+"'>";

				var row = "";

				if(element.id_type>0){
					body += "<select class='form-control' id='valore"+t+"' name='attributo["+t+"]'>";
					$.ajax({
						async: false,
						data: {id_type: element.id_type},
						type: "GET",
						url: "{{ url('types/options')}}",
						success: function(data2) {
							$.each(data2, function(index_2, element_2) {
								body += "<option value='"+ element_2.id +"'>" + element_2.option + "</option>";
							});
                           body += "</select>";
						}
					});
				}else{
					switch(element.id_type){
						case -1:
							body += "<input name='attributo["+t+"]' type='text' value='' class='form-control' required autofocus style='width: 300px'/>";
							break;
						case -2:
							body += "<input name='attributo["+t+"]' type='hidden' value='0'/>";
							body += "<input class='form-control' name='attributo["+t+"]' type='checkbox' value='1' required />";
							break;
						case -3:
							body += "<input name='attributo["+t+"]' type='number' value='' class='form-control' required style='width: 300px'/>";
							break;
						case -4:
							body += "<select class='form-control' id='valore"+t+"' name='attributo["+t+"]'>";
							$.ajax({
								async: false,
								type: "GET",
								data: {id_oratorio: sel.value},
								url: "{{ url('groups/dropdown')}}",
								success: function(data2) {
									$.each(data2, function(index_2, element_2) {
										body += "<option value='"+ element_2.id +"'>" + element_2.nome + "</option>";
									});
			                      body += "</select>";
								}
							});
							break;
					}
				}
				body += "</div>";
				body += "</div>";
               t++;
			});
       }
			$("#attributes").html(body);
	});
	//t++;



}
function load_spec_subscription(id_subscription){
	$('#spec1').load("eventspecvalues/"+id_subscription);
	$('html,body').animate({
		scrollTop: $("#nome_sub").offset().top},
		'slow');
}

function load_spec_usersubscription(id_subscription, id_event){
	$('#spec1').load("usereventspecvalues?id_sub="+id_subscription+"&id_event="+id_event);
	$('#spec2').load("userspecsubscriptions?id_sub="+id_subscription+"&id_event="+id_event);
	$('#id_event').val(id_event);
}

function eventspec_destroy(id_eventspec, index){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		dataType: 'html',
		data: {id_spec: id_eventspec,
			_token: CSRF_TOKEN},
		url: "{{route('eventspecs.destroy')}}",
		success: function(response) {
			//alert(response);
			$('#row_'+index).remove();
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + XMLHttpRequest.responseText + "\n" + exception); },
		async: true
	});
}

function elencovalue_destroy(id_v){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: 'POST',
		dataType: 'html',
		data: {id_value: id_v,
			_token: CSRF_TOKEN},
		url: "{{route('elenco.destroy_value')}}",
		success: function(response) {
			//alert(response);
			$('#row_'+id_v).remove();
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + XMLHttpRequest + "\n" + exception); },
		async: true
	});
}

function colonneelenco_add(){
	var t = parseInt($('#contatore').val());
	t = t+1;
	var row = "<tr>";
	row += "<td>";
	row += "<input type='text' name='colonna["+t+"]' class='form-control'/></td>";
	row += "</tr>";

	$('#colonne_elenco tr:last').after(row);
	$('#contatore').val(t);
}

function elencovalues_add(num_colonne, keys){
	var key = jQuery.parseJSON(keys);
	var t = parseInt($('#contatore').val());
	t = t+1;
	var row = "<tr>";
	row += "<input name='id_values["+t+"]' type='hidden' value='0'/>";
	row += "<td>#</td>";
	var select = "<select class='form-control' id='id_user["+t+"]' name='id_user["+t+"]'>";
	$.ajax({
		async: false,
		type: "GET",
		data: {},
		url: "{{ url('user/dropdown')}}",
		success: function(data) {
			$.each(data, function(index, element) {
				select += "<option value='"+ element.id +"'>" + element.cognome + " "+element.name+"</option>";
			});
       select += "</select>";
		}
	});

	row += "<td>"+select+"</td>";
	for(var i=0; i<num_colonne; i++){
		row += "<td>";
		row += "<input name='colonna["+t+"]["+key[i]+"]' type='hidden' value='0'/>";
		row += "<input class='form-control' name='colonna["+t+"]["+key[i]+"]' type='checkbox' value='1' />";
	}
	row += "</tr>";

	$('#elenco_values tr:last').after(row);
}

function redirect_check(route, method='POST', send_param=true){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var selected = [];
	$('input[type=checkbox]').each(function() {
		if ($(this).is(":checked")){
			selected.push($(this).attr('value'));
		}
	});
	if(send_param){
		$.redirect(route, {check: JSON.stringify(selected), _token: CSRF_TOKEN}, method);
	}else{
		$.redirect(route, {}, method);
	}

}

function disable_select(checkbox, id_select, inverse=false){
	if(inverse){
		$('#'+id_select).prop('disabled', !checkbox.checked);
	}else{
		$('#'+id_select).prop('disabled', checkbox.checked);
	}

}

	</script>

</body>
</html>
