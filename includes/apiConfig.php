<?php
    class naturalDesasterAPI
    {

        public function getCountry($db)
        {
            $data = $this->callAPI($db);
            $d = "0";
            
            // exit;
            $result = [];

            foreach($data as $country)
            {
                if(strstr(str_replace(' ', '+', $country->country), '(', true) == '')
                {
                    $name = str_replace(' ', '+', $country->country);
                }
                else if(strlen(strstr(str_replace(' ', '+', $country->country), ',', true)) > 2)
                {
                    $name = strstr(str_replace(' ', '+', $country->country), ',', true);
                }
                else
                {
                    $name = strstr(str_replace(' ', '+', $country->country), '(', true);
                }
                $result[] = $name;
            }

            return $result;
        }

        public function getCountryOfAllDBWithYear($year)
        {
            $data = $this->callAPI('all');

            $result = [];

            foreach($data as $content)
            {
                foreach($content as $country)
                {
                    if($year == $country->year)
                    {
                        if(strstr(str_replace(' ', '+', $country->country), '(', true) == '')
                        {
                            $name = str_replace(' ', '+', $country->country);
                        }
                        else if(strlen(strstr(str_replace(' ', '+', $country->country), ',', true)) > 2)
                        {
                            $name = strstr(str_replace(' ', '+', $country->country), ',', true);
                        }
                        else
                        {
                            $name = strstr(str_replace(' ', '+', $country->country), '(', true);
                        }
                        $result[] = $name;
                    }
                }
            }
            return $result;
        }
        
        public function getDesasterDetail($db, $id)
        {
            $data = $this->callAPI("$db&id=$id");
            return $data;

        }
        // Appel l'API avec en paramettre ce que l'on veut recuperer
        private function callAPI($db)
        {
            $curl = curl_init();
            curl_setopt_array($curl, 
            [
                CURLOPT_URL => "http://localhost:8888/index.php?db=$db",
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