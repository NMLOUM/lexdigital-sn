@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<div style="min-height:calc(100vh - 100px);display:flex;align-items:center;justify-content:center;padding:2rem 1rem;background:var(--paper);">
    <div style="width:100%;max-width:420px;">

        <div style="margin-bottom:2rem;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:.6rem;">
                Inscription
            </div>
            <h1 style="font-family:'Playfair Display',serif;font-size:1.6rem;color:var(--ink);margin-bottom:.3rem;">
                Lex<span style="color:var(--gold);">Digital</span> SN
            </h1>
            <p style="font-size:.85rem;color:var(--ink-muted);">Créez votre espace d'analyse des risques juridiques.</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <div class="card" style="padding:2rem;">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field">
                    <label for="name">Nom complet</label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                           autocomplete="name" autofocus required>
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="votre@adresse.com"
                           autocomplete="email" required>
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password"
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Minimum 8 caractères"
                           autocomplete="new-password" required>
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn btn-primary">Créer mon compte</button>
            </form>
        </div>

        <div style="text-align:center;margin-top:1.25rem;font-size:.82rem;color:var(--ink-muted);">
            Déjà un compte ?
            <a href="{{ route('login') }}" style="color:var(--accent);font-weight:600;">Se connecter</a>
        </div>

    </div>
</div>
@endsection
