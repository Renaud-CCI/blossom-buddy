<?php

namespace App\Services;

class VarDumpService
{
    function dumpToFile($variable) {
        // Ouvrir le fichier en mode append pour ajouter du contenu à la fin du fichier
        $directoryPath = storage_path('app/public/draft');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
        $filePath = $directoryPath . '/vardump.txt';
        $file = fopen($filePath, 'a');
    
        // Si le fichier ne peut pas être ouvert, on arrête l'exécution
        if (!$file) {
            throw new Exception("Unable to open file: $filename");
        }
    
        // Capturer la sortie de var_dump
        ob_start();
        var_dump($variable);
        $dump = ob_get_clean();
    
        // Ecrire la date et l'heure actuelles, suivies du contenu de var_dump
        fwrite($file, "[" . date('Y-m-d H:i:s') . "]\n" . $dump . "\n");
    
        // Fermer le fichier
        fclose($file);
    }
    
    
}