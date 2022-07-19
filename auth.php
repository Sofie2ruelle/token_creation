<?php
// On met un header qui va nous permettre d'utiliser AJAX *=URL
header('Access-Control-Allow-Origin: *');

// Lui dire qu'on lui renvoie du JSON

header('content-type: application/json');
// On met la methode, on interdit toute méthode qui n'est pas POST
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['message' => 'Méthode non authorisée']);
    exit;
}

// On vérifie si on recoit un token côté serveur
if(isset($_SERVER['Authorization'])){ // si on a celle-là on recupère le token
    $token = trim($_SERVER['Authorization']); // trim pour supprimer les espaces
}elseif(isset($_SERVER['HTTP_AUTHORIZATION'])){ // pour vérifier si j'ai un header qui s'appelle http_authorization
    $token = trim($_SERVER['HTTP_AUTHORIZATION']); // je récupère un trim de cette information
}elseif(function_exists('apache_request_headers')){ // si cette fonction exite on récupère les headers
    $_requestHeaders = apache_request_headers();
    if(isset($_requestHeaders['Authorization'])){ // on vérifie si on a un header 'authorisation' 
        $token = trim($_requestHeaders['Authorization']);
    }
}
// vérifier qu'on a bien un token d'authentification
if (!isset($token) || !preg_match('/Bearer\s(\S+)/', $token, $matches)) { // si on a une correspondance qui commence par Bearer, suivi d'un espace, puis une chaîne de caractère dans Token, je récupère les matches
    http_response_code(400); // si aucun matches : page erreur 400
    echo json_encode(['message' => 'Token not found']); // j'encode un message d'erreur
    exit;
};
// echo $token;

// On extrait le token (nettoyage)
$token = str_replace('Bearer ', '', $token); // On récupère le Bearer et le Token

require_once 'includes/config.php';
require_once 'classes/jwt.php';

$jwt = new JWT();
// On vérifie la validité
if(!$jwt->isValid($token)){
    http_response_code(400);
    echo json_encode(['message' => 'Token invalide']); 
    exit;
}

// On vérifie la signature
if(!$jwt->check($token, SECRET)){ // passer le token et le SECRET
    http_response_code(403); // on met une 403 pour interdire l'accès
    echo json_encode(['message' => 'Token expiré']); 
    exit;
}

// On vérifie l'expiration
if($jwt->isExpired($token)){ 
    http_response_code(403); // on met une 403 pour interdire l'accès
    echo json_encode(['message' => 'Token invalide']); 
    exit;
}

echo json_encode($jwt->getPayload($token));



