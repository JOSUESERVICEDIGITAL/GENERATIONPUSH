<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Illuminate\Support\Facades\url;


class EngagementPage extends Model
{
    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'description',
        'benefits',
        'image_path',
        'cta_label',
        'form_content',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'form_content' => 'array',
        ];
    }

    public static function forType(string $type): self
    {
        return static::firstOrCreate(
            ['type' => $type],
            [
                'title' => $type === 'partner'
                    ? 'Devenir partenaire'
                    : 'Devenir bénévole',

                'cta_label' => $type === 'partner'
                    ? 'Devenir partenaire'
                    : 'Devenir bénévole',

                'form_content' => static::defaultFormContent($type),
            ]
        );
    }

    public function imageUrl(): ?string
    {
        return $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : null;
    }

    public function benefitsList(): array
    {
        return $this->benefits
            ? array_filter(
                array_map('trim', explode("\n", $this->benefits))
            )
            : [];
    }

    public static function defaultFormContent(string $type): array
    {
        if ($type === 'partner') {
            return [
                'organization_section' => 'Informations sur l’organisation',
                'organization' => [
                    'label' => 'Nom de l’organisation / entreprise',
                    'placeholder' => '',
                ],
                'sector' => [
                    'label' => 'Secteur d’activité',
                    'placeholder' => 'Ex. Technologie, Finance, Éducation...',
                ],
                'name' => [
                    'label' => 'Nom et prénom du contact',
                    'placeholder' => '',
                ],
                'position' => [
                    'label' => 'Fonction / poste',
                    'placeholder' => 'Ex. Directeur Général',
                ],
                'email' => [
                    'label' => 'Email professionnel',
                    'placeholder' => 'contact@entreprise.com',
                ],
                'phone' => [
                    'label' => 'Numéro WhatsApp',
                    'placeholder' => '+212...',
                ],
                'country' => [
                    'label' => 'Pays',
                    'placeholder' => 'Ex. Burkina Faso',
                ],

                'partnership_section' => 'Type de partenariat',

                'partnership_question' => [
                    'label' => 'Type de partenariat souhaité',
                    'help' => 'plusieurs choix possibles',
                ],

                'partnerships' => [
                    'event' => 'Partenariat événementiel (PushConnect, Push Reading Session...)',
                    'content' => 'Partenariat de contenu & visibilité',
                    'institutional' => 'Partenariat institutionnel (programmes jeunesse, formations...)',
                    'sponsorship' => 'Sponsoring financier',
                ],

                'other_label' => 'Autre :',
                'other_placeholder' => 'Précisez...',

                'project_section' => 'Projet de collaboration',

                'collaboration_project' => [
                    'label' => 'Décrivez votre projet de collaboration',
                    'placeholder' => 'Présentez-nous votre projet, vos objectifs et ce que vous souhaitez construire avec Generation PUSH...',
                ],

                'budget' => [
                    'label' => 'Quel est votre budget indicatif ?',
                    'help' => 'optionnel',
                    'options' => [
                        'less_5000' => 'Moins de 5 000 DH',
                        '5000_20000' => '5 000 — 20 000 DH',
                        'more_20000' => 'Plus de 20 000 DH',
                        'discuss' => 'À discuter',
                    ],
                ],

                'website' => [
                    'label' => 'Site web de votre organisation',
                    'help' => 'optionnel',
                    'placeholder' => 'https://...',
                ],

                'discovery' => [
                    'label' => 'Comment avez-vous connu Generation PUSH ?',
                    'options' => [
                        'social_media' => 'Réseaux sociaux',
                        'word_of_mouth' => 'Bouche à oreille',
                        'gp_event' => 'Événement GP',
                        'other' => 'Autre',
                    ],
                    'other_placeholder' => 'Si autre, précisez...',
                ],
            ];
        }

        return [
            'personal_section' => 'Informations personnelles',

            'name' => [
                'label' => 'Prénom et nom',
                'placeholder' => '',
            ],

            'email' => [
                'label' => 'Email',
                'placeholder' => '',
            ],

            'phone' => [
                'label' => 'Numéro WhatsApp',
                'placeholder' => '+212...',
            ],

            'age' => [
                'label' => 'Âge',
                'placeholder' => '',
            ],

            'city_country' => [
                'label' => 'Ville et pays de résidence',
                'placeholder' => 'Ex. Rabat, Maroc',
            ],

            'profession' => [
                'label' => 'Domaine d’études ou profession actuelle',
                'placeholder' => '',
            ],

            'contribution_section' => 'Domaine de contribution',

            'contribution_question' => [
                'label' => 'Dans quel domaine veux-tu contribuer ?',
                'help' => 'plusieurs choix possibles',
            ],

            'contributions' => [
                'Communication & réseaux sociaux',
                'Organisation d’événements',
                'Création de contenu (vidéo, photo, design)',
                'Coaching & formation',
                'Développement web & digital',
                'Traduction français / anglais',
            ],

            'other_label' => 'Autre :',
            'other_placeholder' => 'Précisez...',

            'motivation_section' => 'Motivation',

            'motivation' => [
                'label' => 'Pourquoi veux-tu rejoindre Generation PUSH ?',
                'placeholder' => '',
            ],

            'skills' => [
                'label' => 'Qu’est-ce que tu apportes à GP ? Parle-nous de toi.',
                'placeholder' => '',
            ],

            'availability_section' => 'Disponibilité & expérience',

            'availability' => [
                'label' => 'Disponibilité',
                'options' => [
                    'part_time' => 'Temps partiel (quelques heures par semaine)',
                    'events_only' => 'Disponible pour les événements uniquement',
                    'full_time' => 'Temps plein (engagement fort)',
                ],
            ],

            'participated_before' => [
                'label' => 'As-tu déjà participé à un événement Generation PUSH ?',
                'yes' => 'Oui',
                'no' => 'Non',
            ],

            'social_link' => [
                'label' => 'Lien LinkedIn ou réseau social',
                'help' => 'optionnel',
                'placeholder' => 'https://linkedin.com/in/...',
            ],
        ];
    }
}
