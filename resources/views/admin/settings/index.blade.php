@extends('adminlte::page')

@section('title', 'Configurações')

@section('content_header')

    <h1>Configurações do site</h1>
@endsection


@section('content')
      @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                <h5>
                    <i class="icon fas fa-ban"></i>
                    Ocorreu um erro
                </h5>
                @foreach ($errors->all() as $error )
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>

    @endif

    @if(session('warning'))
        <div class="alert alert-success">
                {{session('warning')}}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{route('settings.save')}}" method="POST"  class="form-horizontal">
            @method('PUT')
            @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Titulo do site</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" value="{{$settings['title']}}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Subtitulo do site</label>
                    <div class="col-sm-10">
                        <input type="text" name="subtitle" value="{{$settings['subtitle']}}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" name="email" value="{{$settings['e-mail']}}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Cor do fundo</label>
                    <div class="col-sm-10">
                        <input type="color" name="bgcolor" value="{{$settings['bgcolor']}}"  class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Cor da fonte</label>
                    <div class="col-sm-10">
                        <input type="color" name="textcolor"  value="{{$settings['textcolor']}}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" value="Salvar" class="btn btn-success">
                    </div>
                </div>



            </form>
        </div>

    </div>
@endsection
