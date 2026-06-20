@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div style="min-height:calc(100vh - 100px);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;background:var(--paper);">
    <div style="width:100%;max-width:400px;">

        <div style="margin-bottom:2rem;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:.6rem;">
                Accès sécurisé
            </div>
            <h1 style="font-family:'Playfair Display',serif;font-size:1.6rem;color:var(--ink);margin-bottom:.3rem;">
                Lex<span style="color:var(--gold);">Digital</span> SN
            </h1>
            <p style="font-size:.85rem;color:var(--ink-muted);">Connectez-vous à votre espace d'analyse juridique.</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card" style="padding:2rem;">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="votre@adresse.com"
                           autocomplete="email" autofocus required>
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                           autocomplete="current-password" required>
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <label style="display:flex;align-items:center;gap:.4rem;cursor:pointer;font-size:.82rem;font-weight:400;letter-spacing:0;text-transform:none;color:var(--ink-2);">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                               style="accent-color:var(--accent);width:auto;">
                        Se souvenir de moi
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:.78rem;color:var(--ink-muted);">Mot de passe oublié ?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>

        <div style="text-align:center;margin-top:1.25rem;font-size:.82rem;color:var(--ink-muted);">
            Pas encore de compte ?
            <a href="{{ route('register') }}" style="color:var(--accent);font-weight:600;">Créer un compte</a>
        </div>

    </div>
</div>
@endsection
