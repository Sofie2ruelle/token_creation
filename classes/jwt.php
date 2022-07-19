<?php
// la classe prend le nom du fichier
class JWT
{
    public function generate(array $header, array $payload, string $secret, int $validity = 3600): string // tout ceci retourne une chaine de caractère
    {
        if ($validity > 0) {
            $now = new DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }
        // ici je vais mettre tout mon encodage dans ma methode
        // On encode en base64
        $base64Header = base64_encode(json_encode($header)); // notre header est un tableau donc on va le mettre en JSON
        $base64Payload = base64_encode(json_encode($payload)); // Json encode de mon Payload

        // On nettoie les valeurs encodées
        // On retire les +, /, = qui pourraient être dedans
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header); // On va les remplacer pour normaliser (le = par rien, le / par _ , et le + par un - )
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // On génère la signature : on va utiliser notre secret et l'encoder
        $secret = base64_encode(SECRET);

        $signature = hash_hmac(
            'sha256',
            $base64Header . '.' . $base64Payload,
            $secret,
            true
        ); // On utilise la fonction hash_hmac(), fonction de génération de hash dans laquelle on met l'algorithme sha256, on ajoute notre chaine de caractère que l'on veut signer : $base64Header + . + $base64Payload)
        $base64Signature = base64_encode($signature);

        $signature = str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            $base64Signature
        );

        // On crée le token
        $jwt = $base64Header . '.' . $base64Payload . '.' . $signature;

        return $jwt;
    }

    public function check(string $token, string $secret)
    {
        // On récupére le header du token
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);
        // A partir de là je vais réencoder avec ma clé secrète : je vais générer un token de vérification
        $verifToken = $this->generate($header, $payload, $secret, 0); // on met un temps à 0 car dans le payload on a déjà récupéré les issue date et expiration date
        return $token === $verifToken; // va retourner un boolean true ou false

    }

    public function getHeader(string $token) // bon à savoir les librairies peuvent gérer ça
    {
        // Démontage token
        $array = explode('.', $token); // le explode va regarder mon token et à chaque fois qu'il va voir un point, il va créer une ligne d'entrée dans mon tableau, ici un tableau à 3 entrée (header, upload, signature)
        // On décode le header en json car codé en json
        $header = json_decode(base64_decode($array[0]), true); // [0] header, 1 payload, 3 signature
        return $header;
    }
    public function getPayload(string $token)
    {
        $array = explode('.', $token); 
        // On décode le payload
        $payload = json_decode(base64_decode($array[1]), true);
        return $payload;
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        $now = new Datetime();

        return $payload['exp'] < $now->getTimestamp();
    }

    public function isValid(string $token): bool 
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/', // expressions régulières code REGEX 
            $token 	
        ) === 1;
    }
}
