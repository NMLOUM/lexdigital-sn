@extends('layouts.app')

@section('title', 'Nouvelle analyse')

@section('content')
<div style="max-width:760px;margin:0 auto;padding:3rem 1.5rem 2.5rem;">

    {{-- EN-TÊTE DE PAGE --}}
    <div style="margin-bottom:2.5rem;">
        <div style="font-family:'IBM Plex Mono',monospace;font-size:.67rem;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:.75rem;">
            Analyse juridique · Plateformes numériques · Sénégal
        </div>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;line-height:1.2;color:var(--ink);margin-bottom:.6rem;">
            Évaluation des risques juridiques
        </h1>
        <p style="font-size:.93rem;color:var(--ink-muted);line-height:1.6;max-width:560px;">
            Renseignez les paramètres de votre plateforme pour obtenir une analyse au regard du cadre juridique sénégalais en vigueur.
        </p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Veuillez corriger les erreurs suivantes :</strong>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- FORMULAIRE --}}
    <div class="card" style="padding:2rem 2.25rem;">
        <form id="analyseForm" action="{{ route('analyse') }}" method="POST" novalidate>
            @csrf

            <p class="form-section" style="margin-top:0;">Identification de la plateforme</p>

            <div class="grid-2">
                <div class="field" style="margin-bottom:0;">
                    <label for="type_plateforme">Type de plateforme</label>
                    <select name="type_plateforme" id="type_plateforme"
                            class="{{ $errors->has('type_plateforme') ? 'is-invalid' : '' }}">
                        <option value="">Sélectionner</option>
                        <option value="ecommerce"     {{ old('type_plateforme')=='ecommerce'    ?'selected':'' }}>E-commerce / Vente en ligne</option>
                        <option value="reseau_social" {{ old('type_plateforme')=='reseau_social'?'selected':'' }}>Réseau social / Communauté</option>
                        <option value="fintech"       {{ old('type_plateforme')=='fintech'      ?'selected':'' }}>Fintech / Paiement mobile</option>
                        <option value="marketplace"   {{ old('type_plateforme')=='marketplace'  ?'selected':'' }}>Marketplace / Place de marché</option>
                        <option value="services"      {{ old('type_plateforme')=='services'     ?'selected':'' }}>Services en ligne (SaaS)</option>
                        <option value="media"         {{ old('type_plateforme')=='media'        ?'selected':'' }}>Média / Publication de contenu</option>
                        <option value="sante"         {{ old('type_plateforme')=='sante'        ?'selected':'' }}>Santé / Télémédecine</option>
                        <option value="education"     {{ old('type_plateforme')=='education'    ?'selected':'' }}>Éducation / E-learning</option>
                    </select>
                    @error('type_plateforme')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="field" style="margin-bottom:0;">
                    <label for="contexte_geo">Contexte géographique</label>
                    <select name="contexte_geo" id="contexte_geo"
                            class="{{ $errors->has('contexte_geo') ? 'is-invalid' : '' }}">
                        <option value="">Sélectionner</option>
                        <option value="senegal"       {{ old('contexte_geo')=='senegal'      ?'selected':'' }}>Sénégal (national)</option>
                        <option value="cedeao"        {{ old('contexte_geo')=='cedeao'       ?'selected':'' }}>Zone CEDEAO (régional)</option>
                        <option value="international" {{ old('contexte_geo')=='international'?'selected':'' }}>International</option>
                    </select>
                    @error('contexte_geo')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="field" style="margin-top:1.25rem;">
                <label for="categorie_probleme">Domaine juridique à analyser</label>
                <select name="categorie_probleme" id="categorie_probleme"
                        class="{{ $errors->has('categorie_probleme') ? 'is-invalid' : '' }}">
                    <option value="">Sélectionner le domaine</option>
                    <option value="donnees_personnelles"     {{ old('categorie_probleme')=='donnees_personnelles'    ?'selected':'' }}>Protection des données personnelles</option>
                    <option value="contrat_electronique"     {{ old('categorie_probleme')=='contrat_electronique'    ?'selected':'' }}>Contrats électroniques et conditions générales</option>
                    <option value="paiement"                 {{ old('categorie_probleme')=='paiement'                ?'selected':'' }}>Paiement électronique et monnaie mobile</option>
                    <option value="cybersecurite"            {{ old('categorie_probleme')=='cybersecurite'           ?'selected':'' }}>Cybersécurité et incidents informatiques</option>
                    <option value="contenu_illicite"         {{ old('categorie_probleme')=='contenu_illicite'        ?'selected':'' }}>Contenu illicite et modération</option>
                    <option value="consommateur"             {{ old('categorie_probleme')=='consommateur'            ?'selected':'' }}>Protection des consommateurs</option>
                    <option value="propriete_intellectuelle" {{ old('categorie_probleme')=='propriete_intellectuelle'?'selected':'' }}>Propriété intellectuelle</option>
                    <option value="fiscalite"                {{ old('categorie_probleme')=='fiscalite'               ?'selected':'' }}>Fiscalité du numérique</option>
                    <option value="mineurs"                  {{ old('categorie_probleme')=='mineurs'                 ?'selected':'' }}>Protection des mineurs en ligne</option>
                </select>
                @error('categorie_probleme')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <p class="form-section">Aspects techniques concernés
                <span style="font-weight:400;font-family:'IBM Plex Sans',sans-serif;font-size:.72rem;letter-spacing:0;text-transform:none;"> — optionnel</span>
            </p>

            <div class="check-group">
                @php
                $aspectOptions = [
                    'traitement_donnees'           => 'Traitement de données personnelles',
                    'paiement_electronique'        => 'Paiement électronique',
                    'publication_contenu'          => 'Publication de contenu',
                    'acces_mineurs'                => 'Accès par des mineurs',
                    'operations_transfrontalieres' => 'Opérations transfrontalières',
                ];
                $oldAspects = old('aspects', []);
                @endphp
                @foreach($aspectOptions as $val => $label)
                <label class="check-item {{ in_array($val,$oldAspects)?'checked':'' }}" for="asp_{{ $val }}">
                    <input type="checkbox" name="aspects[]" value="{{ $val }}" id="asp_{{ $val }}"
                           {{ in_array($val,$oldAspects)?'checked':'' }}
                           onchange="this.parentElement.classList.toggle('checked',this.checked)">
                    {{ $label }}
                </label>
                @endforeach
            </div>

            <p class="form-section">Description de la situation</p>

            <div class="field">
                <label for="situation">Décrivez votre situation juridique</label>
                <textarea name="situation" id="situation" rows="7"
                          class="{{ $errors->has('situation') ? 'is-invalid' : '' }}"
                          maxlength="2000"
                          placeholder="Exemple : Notre plateforme de commerce en ligne collecte les coordonnées et données de paiement de clients établis au Sénégal. Nous souhaitons évaluer nos obligations légales en matière de protection des données et de conformité aux textes en vigueur…"
                          oninput="document.getElementById('charCount').textContent=this.value.length">{{ old('situation') }}</textarea>
                @error('situation')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <div class="field-hint" style="display:flex;justify-content:space-between;">
                    <span>Minimum 20 caractères</span>
                    <span style="font-family:'IBM Plex Mono',monospace;"><span id="charCount">{{ strlen(old('situation','')) }}</span>/2000</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn" style="margin-top:.25rem;">
                <span id="btnText">Lancer l'analyse</span>
                <span id="btnSpinner" style="display:none;">Analyse en cours…</span>
            </button>

        </form>
    </div>

    {{-- RÉFÉRENCE DES DOMAINES --}}
    <div style="margin-top:2.5rem;">
        <div style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-muted);margin-bottom:1rem;">
            Domaines couverts par l'outil
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;">
            @php $domaines = [
                ['Protection des données','Loi n°2008-12 · CDP'],
                ['Contrats électroniques','Loi n°2008-08'],
                ['Paiement mobile','BCEAO 008/2002/SP'],
                ['Cybersécurité','Loi n°2008-11'],
                ['Contenus illicites','Loi n°2008-11 + Code presse'],
                ['Consommateurs','Loi n°2001-15'],
                ['Propriété intellectuelle','OAPI · BSDA'],
                ['Fiscalité numérique','CGI · TVA 18%'],
                ['Protection des mineurs','Code Pénal · CDP'],
            ]; @endphp
            @foreach($domaines as [$name, $ref])
            <div style="padding:.6rem .8rem;border:1px solid var(--rule);border-radius:var(--radius);background:var(--surface);">
                <div style="font-size:.82rem;font-weight:500;color:var(--ink-2);margin-bottom:.15rem;">{{ $name }}</div>
                <div style="font-size:.68rem;font-family:'IBM Plex Mono',monospace;color:var(--ink-muted);">{{ $ref }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <p style="margin-top:1.5rem;font-size:.72rem;color:var(--ink-muted);line-height:1.5;border-top:1px solid var(--rule);padding-top:1rem;">
        Les résultats produits par cet outil sont fournis à titre informatif et ne constituent pas un avis juridique professionnel. Consultez un avocat qualifié pour toute décision engageant votre responsabilité.
    </p>

    @auth
    <div style="margin-top:1rem;text-align:right;">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Voir mes analyses précédentes</a>
    </div>
    @endauth

</div>
@endsection

@push('scripts')
<script>
document.getElementById('analyseForm').addEventListener('submit', function() {
    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    document.getElementById('btnText').style.display = 'none';
    document.getElementById('btnSpinner').style.display = 'inline';
});
</script>
@endpush
