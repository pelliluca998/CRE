<?php
use App\Role;
?>

@extends('event::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('event.name') !!}
    </p>
    
    
@stop
