<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — LexDigital SN</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=IBM+Plex+Mono:wght@400;500&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    <style>
    :root {
        --ink:       #0D1117;
        --ink-2:     #2D3748;
        --ink-muted: #718096;
        --paper:     #F7F5F0;
        --rule:      #E2DDD5;
        --surface:   #FFFFFF;
        --accent:    #1A4E8A;
        --gold:      #C8993A;
        --danger:    #B83232;
        --warn:      #C97A1A;
        --ok:        #2A7A50;
        --radius:    3px;
        --shadow:    0 1px 4px rgba(13,17,23,.07), 0 4px 16px rgba(13,17,23,.05);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--paper);
        color: var(--ink);
        font-size: 16px;
        line-height: 1.65;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    a { color: var(--accent); text-decoration: none; }
    a:hover { text-decoration: underline; }

    /* ── HEADER ──────────────────────────────────────────── */
    header {
        background: var(--ink);
        border-bottom: 2px solid var(--gold);
        padding: 0 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 58px;
        position: sticky;
        top: 0;
        z-index: 100;
        flex-shrink: 0;
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        color: #fff;
        text-decoration: none;
        letter-spacing: .01em;
    }
    .logo em { font-style: normal; color: var(--gold); }
    .logo sup {
        font-family: 'IBM Plex Mono', monospace;
        font-size: .55rem;
        font-weight: 400;
        color: rgba(255,255,255,.3);
        letter-spacing: .1em;
        text-transform: uppercase;
        vertical-align: super;
        margin-left: .3rem;
    }
    .logo:hover { text-decoration: none; }

    .header-nav { display: flex; align-items: center; gap: 1.5rem; }

    .nav-link {
        font-size: .875rem;
        color: rgba(255,255,255,.5);
        text-decoration: none;
        letter-spacing: .02em;
        transition: color .15s;
        border: none;
        background: none;
        cursor: pointer;
        font-family: inherit;
        padding: 0;
    }
    .nav-link:hover { color: rgba(255,255,255,.9); text-decoration: none; }

    .nav-user {
        font-size: .78rem;
        font-family: 'IBM Plex Mono', monospace;
        color: rgba(255,255,255,.28);
        border-right: 1px solid rgba(255,255,255,.1);
        padding-right: 1.5rem;
    }

    .btn-header {
        font-size: .83rem;
        font-weight: 600;
        color: var(--gold);
        border: 1px solid rgba(200,153,58,.35);
        background: rgba(200,153,58,.07);
        padding: .3rem .85rem;
        border-radius: var(--radius);
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
        transition: border-color .15s, background .15s;
        letter-spacing: .02em;
        display: inline-block;
    }
    .btn-header:hover { border-color: var(--gold); background: rgba(200,153,58,.14); text-decoration: none; color: var(--gold); }

    /* ── MAIN ────────────────────────────────────────────── */
    .site-main { flex: 1; }
    .container { max-width: 1060px; margin: 0 auto; padding: 2.5rem 1.5rem; }

    /* ── FORMULAIRE ──────────────────────────────────────── */
    .field { margin-bottom: 1.4rem; }

    label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--ink-muted);
        margin-bottom: .4rem;
    }

    select, textarea, input[type=text], input[type=email], input[type=password] {
        width: 100%;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: .97rem;
        background: #fff;
        border: 1px solid #D1CCC4;
        border-radius: var(--radius);
        padding: .72rem .9rem;
        color: var(--ink);
        transition: border-color .15s, box-shadow .15s;
        appearance: none;
        -webkit-appearance: none;
    }
    select:focus, textarea:focus, input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(26,78,138,.08);
    }
    .is-invalid { border-color: var(--danger) !important; }
    textarea { resize: vertical; min-height: 130px; line-height: 1.6; }
    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%23718096' stroke-width='1.5' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .9rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .field-hint { margin-top: .35rem; font-size: .78rem; color: var(--ink-muted); }
    .invalid-feedback { display: block; margin-top: .3rem; font-size: .78rem; color: var(--danger); }

    .form-section {
        font-size: .73rem;
        font-family: 'IBM Plex Mono', monospace;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--ink-muted);
        margin: 2rem 0 1.1rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid var(--rule);
    }

    .check-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: .4rem;
    }
    .check-item {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .55rem .8rem;
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        background: #fff;
        cursor: pointer;
        font-size: .9rem;
        font-weight: 400;
        letter-spacing: 0;
        text-transform: none;
        color: var(--ink-2);
        transition: border-color .15s;
        user-select: none;
    }
    .check-item:hover { border-color: #B0ABA3; }
    .check-item.checked { border-color: var(--accent); color: var(--accent); background: rgba(26,78,138,.04); }
    .check-item input { accent-color: var(--accent); width: auto; cursor: pointer; }

    /* Alertes */
    .alert {
        padding: .9rem 1.1rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        font-size: .85rem;
        border-left: 3px solid;
        line-height: 1.5;
    }
    .alert-danger { background: #fdf2f2; border-color: var(--danger); color: #7b1c1c; }
    .alert-danger ul { margin: .4rem 0 0 1.1rem; }
    .alert-success { background: #f0faf4; border-color: var(--ok); color: #1a4a30; }

    /* ── BOUTONS ─────────────────────────────────────────── */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        letter-spacing: .02em;
        border-radius: var(--radius);
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
        border: none;
    }
    .btn:hover { text-decoration: none; }
    .btn:disabled { opacity: .45; cursor: not-allowed; }

    .btn-primary {
        background: var(--accent);
        color: #fff;
        padding: .82rem 1.75rem;
        font-size: .95rem;
        width: 100%;
    }
    .btn-primary:hover { background: #163d6e; color: #fff; }

    .btn-secondary {
        background: transparent;
        border: 1px solid var(--rule);
        color: var(--ink-2);
        padding: .48rem 1rem;
        font-size: .87rem;
        font-weight: 500;
    }
    .btn-secondary:hover { border-color: var(--ink-muted); color: var(--ink); }

    .btn-sm { padding: .3rem .7rem; font-size: .78rem; font-weight: 500; }

    /* ── CARTE ───────────────────────────────────────────── */
    .card {
        background: var(--surface);
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    /* ── TABLEAU ─────────────────────────────────────────── */
    table { width: 100%; border-collapse: collapse; font-size: .92rem; }
    thead th {
        background: #F0EDE8;
        color: var(--ink-2);
        padding: .65rem .9rem;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        text-align: left;
        border-bottom: 2px solid var(--rule);
    }
    tbody tr { border-bottom: 1px solid var(--rule); }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFAF8; }
    tbody td { padding: .75rem .9rem; color: var(--ink); vertical-align: middle; }

    /* ── BADGES ──────────────────────────────────────────── */
    .badge {
        display: inline-block;
        padding: .18rem .55rem;
        border-radius: 2px;
        font-size: .73rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        font-family: 'IBM Plex Mono', monospace;
    }
    .badge-danger  { background: #fde8e8; color: #8B1A1A; }
    .badge-warn    { background: #fef0dc; color: #7A4A00; }
    .badge-ok      { background: #e4f5ec; color: #1A5A35; }

    /* ── PAGINATION ──────────────────────────────────────── */
    .pagination { display: flex; gap: .3rem; list-style: none; justify-content: flex-end; }
    .page-item .page-link {
        display: flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 .5rem;
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        font-size: .8rem;
        color: var(--ink-muted);
        background: var(--surface);
        transition: all .15s;
        cursor: pointer;
        text-decoration: none;
    }
    .page-item .page-link:hover { border-color: var(--accent); color: var(--accent); text-decoration: none; }
    .page-item.active .page-link { background: var(--accent); color: #fff; border-color: var(--accent); }
    .page-item.disabled .page-link { opacity: .35; cursor: default; pointer-events: none; }

    /* ── RAPPORT ─────────────────────────────────────────── */
    .risk-strip {
        padding: 1rem 1.5rem;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2.25rem;
    }
    .risk-strip.high   { background: #FDF0EF; border: 1px solid #E8BFBF; }
    .risk-strip.medium { background: #FEF7EC; border: 1px solid #E8D4A0; }
    .risk-strip.low    { background: #EFF8F3; border: 1px solid #A8D4BC; }
    .risk-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .risk-strip.high .risk-dot   { background: var(--danger); }
    .risk-strip.medium .risk-dot { background: var(--warn); }
    .risk-strip.low .risk-dot    { background: var(--ok); }
    .risk-label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
    }
    .risk-strip.high .risk-label   { color: var(--danger); }
    .risk-strip.medium .risk-label { color: var(--warn); }
    .risk-strip.low .risk-label    { color: var(--ok); }

    .report-section { margin-bottom: 2.25rem; }
    .report-label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: .72rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--ink-muted);
        margin-bottom: .9rem;
        padding-bottom: .45rem;
        border-bottom: 1px solid var(--rule);
    }

    .law-ref {
        display: inline-block;
        font-family: 'IBM Plex Mono', monospace;
        font-size: .77rem;
        color: var(--accent);
        background: rgba(26,78,138,.06);
        border: 1px solid rgba(26,78,138,.15);
        padding: .2rem .55rem;
        border-radius: 2px;
        margin: .2rem .2rem .2rem 0;
        line-height: 1.5;
    }

    .list-report { list-style: none; padding: 0; }
    .list-report li {
        padding: .6rem 0 .6rem 1.1rem;
        position: relative;
        border-bottom: 1px solid var(--rule);
        font-size: .93rem;
        line-height: 1.6;
        color: var(--ink-2);
    }
    .list-report li:last-child { border-bottom: none; padding-bottom: 0; }
    .list-report li::before {
        content: '';
        position: absolute;
        left: 0; top: 1.05rem;
        width: 4px; height: 4px;
        border-radius: 50%;
        background: var(--ink-muted);
    }
    .list-report.danger li::before { background: var(--danger); }
    .list-report.ok li::before    { background: var(--ok); }

    /* Stats dashboard */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1px;
        background: var(--rule);
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }
    .stat-cell { background: var(--surface); padding: 1.35rem 1.5rem; }
    .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: .3rem;
        color: var(--ink);
    }
    .stat-num.danger { color: var(--danger); }
    .stat-num.warn   { color: var(--warn); }
    .stat-num.ok     { color: var(--ok); }
    .stat-lbl { font-size: .8rem; color: var(--ink-muted); }

    /* ── FOOTER ──────────────────────────────────────────── */
    footer {
        border-top: 1px solid var(--rule);
        padding: 1.35rem 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: .78rem;
        color: var(--ink-muted);
        flex-shrink: 0;
        gap: 1rem;
        flex-wrap: wrap;
    }
    footer strong { color: var(--ink-2); }

    /* ── RESPONSIVE ──────────────────────────────────────── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media (max-width: 700px) {
        .grid-2 { grid-template-columns: 1fr; }
        header { padding: 0 1.25rem; }
        .nav-user { display: none; }
        .container { padding: 1.5rem 1rem; }
        footer { flex-direction: column; gap: .4rem; }
        .stat-row { grid-template-columns: 1fr 1fr; }
    }
    </style>
    @stack('styles')
</head>
<body>

<header>
    <a href="{{ auth()->check() ? route('home') : route('login') }}" class="logo">
        Lex<em>Digital</em> SN<sup>beta</sup>
    </a>
    <nav class="header-nav">
        @auth
            <span class="nav-user">{{ auth()->user()->name }}</span>
            <a href="{{ route('home') }}" class="nav-link">Nouvelle analyse</a>
            <a href="{{ route('dashboard') }}" class="nav-link">Mes analyses</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link">Connexion</a>
            <a href="{{ route('register') }}" class="btn-header">Créer un compte</a>
        @endauth
    </nav>
</header>

<div class="site-main">
    @yield('content')
</div>

<footer>
    <strong>LexDigital SN</strong> — Analyse des risques juridiques des plateformes numériques · Sénégal
    <span>Projet académique · ESITEC L3 Génie Logiciel · Groupe Supdeco Dakar · 2026</span>
</footer>

@stack('scripts')
</body>
</html>
