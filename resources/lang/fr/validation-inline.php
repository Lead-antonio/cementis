<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Ce champ doit être accepté.',
    'active_url'           => 'Ce n\'est pas une URL valide.',
    'after'                => 'Cela doit être une date après le :date.',
    'after_or_equal'       => 'Cela doit être une date après ou égale au :date.',
    'alpha'                => 'Ce champ ne peut contenir que des lettres.',
    'alpha_dash'           => 'Ce champ ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
    'alpha_num'            => 'Ce champ ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Ce champ doit être un tableau.',
    'attached'             => 'Ce champ est déjà attaché.',
    'before'               => 'Cela doit être une date avant le :date.',
    'before_or_equal'      => 'Cela doit être une date avant ou égale au :date.',
    'between'              => [
        'numeric' => 'Cette valeur doit être comprise entre :min et :max.',
        'file'    => 'Ce fichier doit être compris entre :min et :max kilo-octets.',
        'string'  => 'Cette chaîne doit être comprise entre :min et :max caractères.',
        'array'   => 'Ce contenu doit avoir entre :min et :max éléments.',
    ],
    'boolean'              => 'Ce champ doit être vrai ou faux.',
    'confirmed'            => 'La confirmation ne correspond pas.',
    'current_password'     => 'Le mot de passe est incorrect.',
    'date'                 => 'Ce n\'est pas une date valide.',
    'date_equals'          => 'Cela doit être une date égale au :date.',
    'date_format'          => 'Cela ne correspond pas au format :format.',
    'different'            => 'Cette valeur doit être différente de :other.',
    'digits'               => 'Cela doit avoir :digits chiffres.',
    'digits_between'       => 'Cela doit avoir entre :min et :max chiffres.',
    'dimensions'           => 'Cette image a des dimensions invalides.',
    'distinct'             => 'Ce champ a une valeur en double.',
    'email'                => 'Cela doit être une adresse e-mail valide.',
    'ends_with'            => 'Cela doit se terminer par l\'un des éléments suivants : :values.',
    'exists'               => 'La valeur sélectionnée est invalide.',
    'file'                 => 'Le contenu doit être un fichier.',
    'filled'               => 'Ce champ doit avoir une valeur.',
    'gt'                   => [
        'numeric' => 'La valeur doit être supérieure à :value.',
        'file'    => 'La taille du fichier doit être supérieure à :value kilo-octets.',
        'string'  => 'La chaîne doit être supérieure à :value caractères.',
        'array'   => 'Le contenu doit avoir plus de :value éléments.',
    ],
    'gte'                  => [
        'numeric' => 'La valeur doit être supérieure ou égale à :value.',
        'file'    => 'La taille du fichier doit être supérieure ou égale à :value kilo-octets.',
        'string'  => 'La chaîne doit être supérieure ou égale à :value caractères.',
        'array'   => 'Le contenu doit avoir :value éléments ou plus.',
    ],
    'image'                => 'Cela doit être une image.',
    'in'                   => 'La valeur sélectionnée est invalide.',
    'in_array'             => 'Cette valeur n\'existe pas dans :other.',
    'integer'              => 'Cela doit être un entier.',
    'ip'                   => 'Cela doit être une adresse IP valide.',
    'ipv4'                 => 'Cela doit être une adresse IPv4 valide.',
    'ipv6'                 => 'Cela doit être une adresse IPv6 valide.',
    'json'                 => 'Cela doit être une chaîne JSON valide.',
    'lt'                   => [
        'numeric' => 'La valeur doit être inférieure à :value.',
        'file'    => 'La taille du fichier doit être inférieure à :value kilo-octets.',
        'string'  => 'La chaîne doit être inférieure à :value caractères',
    ],
    'max' => [
        'numeric' => 'La valeur ne doit pas être supérieure à :max.',
        'file' => 'La taille du fichier ne doit pas dépasser :max kilo-octets.',
        'string' => 'La chaîne ne doit pas dépasser :max caractères.',
        'array' => 'Le contenu ne doit pas avoir plus de :max éléments.',
    ],
    'mimes' => 'Cela doit être un fichier de type :values.',
    'mimetypes' => 'Cela doit être un fichier de type :values.',
    'min' => [
        'numeric' => 'La valeur doit être d\'au moins :min.',
        'file' => 'La taille du fichier doit être d\'au moins :min kilo-octets.',
        'string' => 'La chaîne doit être d\'au moins :min caractères.',
        'array' => 'La valeur doit avoir au moins :min éléments.',
    ],
    'multiple_of' => 'La valeur doit être un multiple de :value',
    'not_in' => 'La valeur sélectionnée est invalide.',
    'not_regex' => 'Ce format est invalide.',
    'numeric' => 'Cela doit être un nombre.',
    'password' => 'Le mot de passe est incorrect.',
    'present' => 'Ce champ doit être présent.',
    'regex' => 'Ce format est invalide.',
    'relatable' => 'Ce champ ne peut pas être associé à cette ressource.',
    'required' => 'Ce champ est requis.',
    'required_if' => 'Ce champ est requis lorsque :other est :value.',
    'required_unless' => 'Ce champ est requis sauf si :other est dans :values.',
    'required_with' => 'Ce champ est requis lorsque :values est présent.',
    'required_with_all' => 'Ce champ est requis lorsque :values sont présents.',
    'required_without' => 'Ce champ est requis lorsque :values n\'est pas présent.',
    'required_without_all' => 'Ce champ est requis lorsque aucun des :values n\'est présent.',
    'prohibited' => 'Ce champ est interdit.',
    'prohibited_if' => 'Ce champ est interdit lorsque :other est :value.',
    'prohibited_unless' => 'Ce champ est interdit sauf si :other est dans :values.',
    'same' => 'La valeur de ce champ doit correspondre à celle de :other.',
    'size' => [
        'numeric' => 'La valeur doit être :size.',
        'file' => 'La taille du fichier doit être de :size kilo-octets.',
        'string' => 'La chaîne doit être de :size caractères.',
        'array' => 'Le contenu doit contenir :size éléments.',
    ],
    'starts_with' => 'Cela doit commencer par l\'un des éléments suivants : :values.',
    'string' => 'Cela doit être une chaîne.',
    'timezone' => 'Cela doit être un fuseau horaire valide.',
    'unique' => 'Cela a déjà été pris.',
    'uploaded' => 'Cela n\'a pas pu être téléchargé.',
    'url' => 'Cela doit être une URL valide.',
    'uuid' => 'Cela doit être un UUID valide.',
    
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez spécifier des messages de validation personnalisés pour les attributs en utilisant
    | la convention "attribute.rule" pour nommer les lignes. Cela permet de spécifier rapidement
    | une ligne de langue personnalisée spécifique pour une règle d'attribut donnée.
    |
    */
    
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'message-personnalisé',
        ],
    ],
    

];
