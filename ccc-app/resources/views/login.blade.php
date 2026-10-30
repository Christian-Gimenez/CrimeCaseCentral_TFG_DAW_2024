@include('cabecera', ['title' => 'Login'])
<style>
    .especial {

        background-color: #2f4f4f;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;

    }
</style>

<body class="logo-crime-case-central-footer">
    <main class="contenedor-login">
        <script type="module" src="{{ asset('js/validarFormularios.js') }}"></script>
        <img class="logo-CCC" src="{{ asset('imgs/LOGO_DEFINITIVO.png') }}" alt="Logotipo Crime Case Central">

        <form action="{{ url('/validar-login') }}" method="post">
            @if (session()->has('error'))
                <div class="alert-error">
                    {{ session('error') }}
                    @if (session()->has('tiempoEspera'))
                        <p>Por favor, espera {{ session('tiempoEspera') }} antes de volver a intentarlo.</p>
                    @endif
                </div>
            @endif

            @csrf
            <label class="label-white" for="Usuario">Usuario:</label>
            <input class="input-white" type="text" name="Usuario" id="Usuario" />

            <label class="label-white" for="Contraseña">Contraseña:</label>
            <div class="password">
                <input class="input-white" type="password" name="Contraseña" id="Contraseña"
                data-ocultar-texto="true"
                />
                <div class="mostrarPassword"></div>
            </div>


            <input type="submit" value="LOGIN" class="btn-login" />
        </form>
    </main>
    <div class="logo-crime-case-central-footer">
        <img class="logo-crime-case-central" src="{{ asset('imgs/Logo-Texto_CCC.png') }}" alt="Logo Crime Case Central">
    </div>
    @include('footer')
