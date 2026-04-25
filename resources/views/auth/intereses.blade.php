<x-guest-layout>

    <div class="bloque">

        <div class="bloque-titulo">// Tus intereses</div>
        <p class="texto-suave mb-3">Elige qué áreas quieres mejorar. Usaremos esto para sugerirte hábitos personalizados.</p>

        @include('partials._intereses', [
            'accion'           => $accion,
            'interesesActivos' => old('interests', []),
            'textBoton'        => 'Empezar aventura 🎮',
        ])

    </div>

</x-guest-layout>

@push('scripts')
    <script src="{{ asset('js/intereses.js') }}"></script>
@endpush