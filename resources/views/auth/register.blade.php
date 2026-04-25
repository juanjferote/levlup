<x-guest-layout>

    <div class="bloque">

        <div class="bloque-titulo">Crear cuenta</div>

        @if ($errors->any())
            <p class="form-error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label-levlup">Nombre</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="form-input-levlup">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label-levlup">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="form-input-levlup">
            </div>

            {{-- selector de avatar --}}
            <div class="mb-3">
                <label class="form-label-levlup">Elige tu avatar</label>
                <div class="avatar-selector-grid">
                    @foreach (['warrior', 'mage', 'archer', 'ninja', 'knight', 'rogue', 'healer', 'bard', 'ranger', 'paladin', 'druid', 'monk'] as $semilla)
                        <label class="avatar-opcion">
                            <input
                                type="radio"
                                name="avatar_seed"
                                value="{{ $semilla }}"
                                {{ old('avatar_seed', 'warrior') === $semilla ? 'checked' : '' }}>
                            <img
                                src="https://api.dicebear.com/9.x/pixel-art/svg?seed={{ $semilla }}&size=64"
                                alt="{{ $semilla }}"
                                width="54"
                                height="54">
                        </label>
                    @endforeach
                </div>
                @error('avatar_seed')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label-levlup">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="form-input-levlup">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label-levlup">Confirmar contraseña</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="form-input-levlup">
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('login') }}" class="form-link">¿Ya tienes cuenta?</a>
                <button type="submit" class="btn-primario">Registrarse →</button>
            </div>

        </form>
    </div>

</x-guest-layout>