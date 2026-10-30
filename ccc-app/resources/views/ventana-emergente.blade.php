<div class="bloquear-pantalla">
    <div class="ventana">
        <header class="header-ventana">
            <h5>
                @if (request('codigo') > 0)
                    ERROR: {{ request('codigo') }}
                @else
                    ÉXITO
                @endif
            </h5>
            <button class="btn-x">✕</button>
        </header>

        <div class="body-ventana">
            <h4>{{ request('titulo') }}</h4>
            <figure class="texto-ventana">
                <img class="img-ventana"
                    src="
                    @if (request('codigo') > 0) {{ asset('imgs/error.png') }}
                    @else {{ asset('imgs/informacion.png') }} @endif
                ">
                <figcaption>{{ request('resultado') }}</figcaption>
            </figure>


            @if (request('prompt'))
                <button class="btn-ok btn-login">SÍ</button>
                <button class="btn-ok btn-login">NO</button>
            @else
                <button class="btn-ok btn-login">OK</button>
            @endif
        </div>
    </div>
</div>
