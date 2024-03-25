<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed'   => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'password' => 'Le mot de passe fourni est incorrect.',
    'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',
    'login' => [
        'title' => 'Se connecter',
        'field' => [
            'email' => 'E-mail',
            'password' => 'Mot de passe',
            'remember' => 'Se souvenir de moi'
        ],
        'button' => [
            'submit' => 'Se connecter',
            'reset-password' => 'J\'ai oublié mon mot de passe',
            'register' => 'Créer un nouveau compte'
        ]
    ],
    'register' => [
        'title' => 'Créer un nouveau compte',
        'field' => [
            'fullname' => 'Nom complet',
            'email' => 'E-mail',
            'password' => 'Mot de passe',
            'password2' => 'Retapez le mot de passe',
            'agreeTerms' => 'J\'accepte les <a href=":link">conditions</a>'
        ],
        'button' => [
            'submit' => 'Créer un compte',
            'login' => 'J\'ai déjà un compte',
            'reset-password' => 'J\'ai oublié mon mot de passe'
        ]
    ],
    'verify' => [
        'title' => 'Vérifiez votre adresse e-mail',
        'message' => [
            'resent' => 'Un nouveau lien de vérification a été envoyé à votre adresse e-mail',
            'info' => 'Avant de continuer, veuillez vérifier votre e-mail pour un lien de vérification. Si vous n\'avez pas reçu
            l\'e-mail,'
        ],
        'button' => [
            'request-new' => 'cliquez ici pour en demander un autre'
        ]
    ],
    'confirm' => [
        'title' => 'Veuillez confirmer votre mot de passe avant de continuer.',
        'field' => [
            'password' => 'Mot de passe',
        ],
        'button' => [
            'submit' => 'Confirmer le mot de passe',
            'reset-password' => 'Mot de passe oublié?'
        ]
    ],
    'email' => [
        'title' => 'Vous avez oublié votre mot de passe ? Vous pouvez facilement en obtenir un nouveau ici.',
        'field' => [
            'email' => 'E-mail',
        ],
        'button' => [
            'submit' => 'Envoyer le lien de réinitialisation du mot de passe',
            'login' => 'Se connecter',
            'register' => 'Créer un nouveau compte'
        ]
    ],
    'app' => [
        'create' => 'Créer',
        'export' => 'Exporter',
        'print' => 'Printer',
        'reset' => 'Réinitialiser',
        'reload' => 'Actualiser',
    ]
];
