<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'situation'          => 'required|string|min:20|max:2000',
            'type_plateforme'    => 'required|in:ecommerce,reseau_social,fintech,marketplace,services,media,sante,education',
            'categorie_probleme' => 'required|in:donnees_personnelles,contrat_electronique,paiement,cybersecurite,contenu_illicite,consommateur,propriete_intellectuelle,fiscalite,mineurs',
            'contexte_geo'       => 'required|in:senegal,cedeao,international',
            'aspects'            => 'nullable|array',
            'aspects.*'          => 'in:traitement_donnees,paiement_electronique,publication_contenu,acces_mineurs,operations_transfrontalieres',
        ];
    }

    public function messages(): array
    {
        return [
            'situation.required'          => 'Veuillez décrire la situation juridique.',
            'situation.min'               => 'La description doit contenir au moins 20 caractères.',
            'situation.max'               => 'La description ne peut pas dépasser 2000 caractères.',
            'type_plateforme.required'    => 'Veuillez sélectionner le type de plateforme.',
            'type_plateforme.in'          => 'Type de plateforme invalide.',
            'categorie_probleme.required' => 'Veuillez sélectionner la catégorie juridique.',
            'categorie_probleme.in'       => 'Catégorie juridique invalide.',
            'contexte_geo.required'       => 'Veuillez sélectionner le contexte géographique.',
            'contexte_geo.in'             => 'Contexte géographique invalide.',
        ];
    }
}
