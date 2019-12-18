@extends('open.layouts.template1')
@section('css')
    <link rel="stylesheet" href="{{asset('css/main.css') }}" />
@stop

@section('js')
@stop
  
@section('nav')
    <header class="smell-header">
      <div class="header-nav">
        <h1 class="logo">气味王国-注册</h1></div>
    </header>
@stop

@section('content')
    <section class="smell-doc">
      <h2 class="title">
      </h2>
      <div class="passworld-alter">
        <div id="alter-success" class="alter-success" style="display: block">
          <div class="bg-img">
<!--             <img src="/img/cg.png" /> -->
            
          </div>
          <h2>{{$error}}</h2>
        </div>
      </div>
    </section>
@stop
