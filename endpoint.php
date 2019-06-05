<?php
    $secret = "your-secret-here";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Aller chercher les données JSON et les décoder.
        $data = json_decode(file_get_contents('php://input'), true);
        
        //Vérifier si les secrets match
        if($data["secret"] == $secret){
            // Sauvegarder l'image si elle existe.
            if(array_key_exists("picture", $data) && !empty($data["picture"])){
                $base64 = $data["picture"];
                
                // Validation de l'image et match avec le type d'image pour la sauvegarde.
                if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                    $base64 = substr($base64, strpos($base64, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, gif
                
                    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                        throw new \Exception('invalid image type');
                    }
                
                    $base64 = base64_decode($base64);
                
                    if ($base64 === false) {
                        throw new \Exception('base64_decode failed');
                    }
                } else {
                    throw new \Exception('did not match data URI with image data');
                }

                // Sauvegarde de l'image
                $file_name = uniqid(); // Nom aléatoire.
                file_put_contents("images/{$file_name}.{$type}", $base64);
            }

            // TODO : Enregistrer l'avis dans votre base de données.
            
            // Changer l'adresse de retour par une vraie adresse généré dans votre système.
            echo "https://www.example.com/obituary/john-doe";
        }
    }
?>