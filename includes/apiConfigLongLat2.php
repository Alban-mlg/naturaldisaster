<?php
    class longLatAPI
    {

        public function getCountry($city)
        {
            $data = $this->callAPI($city);
            // $d = "0";
            // echo '<pre>';
            // print_r($data->results[0]->address_components[0]);
            // echo '</pre>';
            // exit;
            $result;
            
            foreach($data->results[0]->address_components as $latLong)
            {
                $result = $latLong->short_name;
            }

            return $result;
        }
        // Appel l'API avec en paramettre ce que l'on veut recuperer
        private function callAPI($city)
        {
            $curl = curl_init();
            curl_setopt_array($curl, 
            [
                CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=$city&key=AIzaSyAhZrab8kigjFyUtZydFHCl0GvJa4rlt4s",
                CURLOPT_RETURNTRANSFER => true,
            ]);

            $data = curl_exec($curl);

            if($data === null || curl_getinfo($curl, CURLINFO_HTTP_CODE !== 200))
            {
                return 'Aucun résultat';
            }

            curl_close($curl);
            return json_decode($data);
        }

        // Crée un systeme de cache en envoyant les fichier dans un dossier nommé "cache" et retourne la data déjà passé par json_decode, utlise aussi la function callAPI pour ne pas avoir à appeler les deux fonctions à chaque fois
        private function useCache($db)
        {
            $cacheKey = md5("http://localhost:8888/index.php?db=$db");
            $pathOrigin = './cache';
            $path = $pathOrigin.'/'.$cacheKey;
            // $data;
            $addAllObject = true;

            if(!is_dir($pathOrigin))
            {
                mkdir($pathOrigin);
            }

            if(file_exists($path))
            {
                $data = file_get_contents($path);
            }
            else
            {
                file_put_contents($path, $data);
            }

            return json_decode($data);
        }
    }