<div class="container">
    @if(session('error'))
        <div id="alertSuccess" class="card-panel red lighten-4 red-text text-darken-4">
            <span class="fechar" onclick="document.getElementById('alertSuccess').style.display='none'">X</span>
            <b>
                <i class="small material-icons left">done</i>
            </b> {!! session('error') !!}
        </div>
    @endif

    @if(session('info'))
        <div id="alertInfo" class="card-panel blue lighten-4 blue-text text-darken-4">
            <span class="fechar" onclick="document.getElementById('alertInfo').style.display='none'">X</span>
            <b><i class="small material-icons left">info</i>Informação!</b> {{session('info')}}
        </div>
    @endif

    @if(session('aviso'))
        <div id="alertAviso" class="card-panel yellow lighten-4 yellow-text text-darken-4">
            <span class="fechar" onclick="document.getElementById('alertAviso').style.display='none'">X</span>
            <b><i class="small material-icons left">warning</i>Aviso!</b> {{session('aviso')}}
        </div>
    @endif

    @if($errors->any())
        @for($i = 0; $i < $errors->count(); $i++)
            <div id="alertErro{{$i}}" class="card-panel red lighten-4 red-text text-darken-4">
                <span class="fechar" onclick="document.getElementById('alertErro{{$i}}').style.display='none'">X</span>
                <b><i class="small material-icons left">error</i>Erro!</b> {{$errors->all()[$i]}}
            </div>
        @endfor
    @endif

    @if(Session('success'))
        <div id="alertSuccess" class="card-panel green lighten-4 green-text text-darken-4">
            <span class="fechar" onclick="document.getElementById('alertSuccess').style.display='none'">X</span>
            <b>
                <i class="small material-icons left">done</i>
            </b> {!! Session('success') !!}
        </div>
    @endif
</div>
