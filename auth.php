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
echo $token;