<?php
// on importe ntotre secret dans l'index pour vérifier ma configuration 
require_once 'includes/config.php';
require_once 'classes/jwt.php'; // on appelle notre methode pour le token


// On crée le header dans un tableau
$header = [
    'typ' => 'JWT', // type de token
    'alg' => 'HS256', // algorithme par défaut (voir jwt les differents algorithmes qui existent dans la librairie JWT)
];

// On crée le contenu (payload) qui contient les donnees dans un tableau
$payload = [ // On y met ce qu'on veut
    'user_id' => 123,
    'role' => [ // ça va être du javascript car le json c'est du js
        'ROLE_ADMIN',
        'ROLE_USER',
    ],
    'email' => 'user@gmail.com',
    ];

$jwt = new JWT();

$token = $jwt->generate($header, $payload, SECRET, 60);

echo $token;

