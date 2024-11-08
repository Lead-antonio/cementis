<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class GeolocalisationService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = "www.m-tectracking.mg/api/api.php";
        $this->apiKey = "5AA542DBCE91297C4C3FB775895C7500";
    }

    /**
     * Antonio
     * Effectue une requête HTTP GET vers l'API donnée.
     *
     * @param string $url
     * @return string|null
     */
    protected function makeRequest($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000); // Timeout réduit pour des raisons de performance
        $response = curl_exec($ch);

        // Vérifier si cURL a rencontré une erreur
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    /**
     * Antonio
     * Effectue une requête HTTP GET vers l'API pour avoir les vehicule.
     *
     * @param null
     * @return json|null
     */
    public  function getAlphaCimentVehicule()
    {
        try {
            // Créer l'URL complète avec les paramètres
            $url = "{$this->apiUrl}?api=user&ver=1.0&key={$this->apiKey}&cmd=USER_GET_OBJECTS";
            
            // Faire l'appel API
            $response = $this->makeRequest($url);
            
            // Décoder la réponse
            return json_decode($response, true);
        } catch (Exception $e) {
            // Logger l'erreur pour la traçabilité
            Log::error('Erreur lors de la récupération des données de géolocalisation : ' . $e->getMessage());
            
            return null;
        }
    }

    /**
     * Antonio
     * Récupère le RFID et la distance pour un IMEI et une période donnés.
     *
     * @param string $imei_vehicule
     * @param \Carbon\Carbon $start_date
     * @param \Carbon\Carbon $end_date
     * @return array
     */
    public function getRfidAndDistanceWithImeiAndPeriod($imei_vehicule, $start_date, $end_date)
    {
        $rfid = "";
        $distance = 0;
    
        // Formatage des dates au format YYYYMMDDHHMMSS
        $formattedStartDate = $start_date->format('YmdHis');
        $formattedEndDate = $end_date->format('YmdHis');

        $url = "{$this->apiUrl}?api=user&ver=1.0&key={$this->apiKey}&cmd=OBJECT_GET_ROUTE,{$imei_vehicule},{$formattedStartDate},{$formattedEndDate},20";
        
        try {
            $response = $this->makeRequest($url);
            $data = json_decode($response, true);

            // Vérifier si $data est null ou ne contient pas la clé 'route'
            if (!isset($data['route']) || !is_array($data['route'])) {
                return [
                    'rfid' => null,
                    'distance' => null
                ];
            }
    
            // foreach ($data['route'] as $item) {
            //     if (isset($item[6]['rfid']) && $item[6]['rfid'] !== null) {
            //         $rfid = $item[6]['rfid'];
            //         $distance = isset($data['route_length']) ? $data['route_length'] : 0;
            //         break;
            //     }
            // }
            $rfid = null;
            $firstOdo = null;
            $lastOdo = null;
            $firstRfid = null;
            $lastRfid = null;
            foreach ($data['route'] as $item) {
                // Vérifier si l'RFID et l'odomètre sont présents dans les données
                if (isset($item[6]['rfid'], $item[6]['odo']) && $item[6]['rfid'] !== null) {
                    // Assigner l'RFID du premier enregistrement
                    if ($firstRfid === null) {
                        $firstRfid = $item[6]['rfid'];
                        $firstOdo = $item[6]['odo'];
                    }
                    
                    // Mettre à jour l'RFID et l'odomètre du dernier enregistrement
                    $lastRfid = $item[6]['rfid'];
                    $lastOdo = $item[6]['odo'];
                    $rfid = $item[6]['rfid'];
                }
            }
            
            // Calculer la distance seulement si l'RFID du premier et du dernier sont identiques
            $distance = ($firstRfid === $lastRfid && $firstOdo !== null && $lastOdo !== null) 
                ? ($lastOdo - $firstOdo) 
                : null;
        } catch (\Exception $e) {
            // Gérer les erreurs de requête HTTP
            // Vous pouvez enregistrer le message d'erreur ou retourner des valeurs par défaut
            return [
                'rfid' => null,
                'distance' => null
            ];
        }
        
        return [
            'rfid' => $rfid,
            'distance' => $distance
        ];
    
    }


    /**
     * Antonio
     * Effectue une requête vers l'API pour avoir les évenements de 7 derniers jours.
     *
     * @param null
     * @return json|null
     */
    public  function getEventApi()
    {
        try {
            // Créer l'URL complète avec les paramètres
            $url = "{$this->apiUrl}?api=user&ver=1.0&key={$this->apiKey}&cmd=OBJECT_GET_LAST_EVENTS_7D";

            // Faire l'appel API
            $response = $this->makeRequest($url);
            
            // Décoder la réponse
            return json_decode($response, true);
        } catch (Exception $e) {
            // Logger l'erreur pour la traçabilité
            Log::error('Erreur lors de la récupération des données de géolocalisation : ' . $e->getMessage());
            
            return null;
        }
    }

    /**
     * Antonio
     * Effectue une requête vers l'API pour avoir les évenements de 7 derniers jours.
     *
     * @param string imei
     * @param datetime start_date
     * @param datetime end_date
     * @return json|null
     */
    public  function getEventForPeriodeApi($imei, $start_date, $end_date)
    {
        try {
            // Créer l'URL complète avec les paramètres
            $url = "{$this->apiUrl}?api=user&ver=1.0&key={$this->apiKey}&cmd=OBJECT_GET_EVENTS,{$imei},{$start_date->format('YmdHis')},{$end_date->format('YmdHis')},20";
            
            // Faire l'appel API
            $response = $this->makeRequest($url);
            
            // Décoder la réponse
            return json_decode($response, true);
        } catch (Exception $e) {
            // Logger l'erreur pour la traçabilité
            Log::error('Erreur lors de la récupération des données de géolocalisation : ' . $e->getMessage());
            
            return null;
        }
    }

    /**
     * Antonio
     * Effectue une requête vers l'API pour avoir tous les mouvements dans une intervalle de date.
     *
     * @param string imei
     * @param datetime start_date
     * @param datetime end_date
     * @return json|null
     */
    public  function getMovementDriveAndStop($imei_vehicule, $start_date, $end_date){
        $url = "{$this->apiUrl}?api=user&ver=1.0&key={$this->apiKey}&cmd=OBJECT_GET_ROUTE," . $imei_vehicule . "," . $start_date->format('YmdHis') . "," . $end_date->format('YmdHis') . ",1";
        
        try {

            $response = $this->makeRequest($url);
            $data = json_decode($response, true);
            
            $drives = isset($data['drives']) && is_array($data['drives']) ? $data['drives'] : null;
            $stops = isset($data['stops']) && is_array($data['stops']) ? $data['stops'] : null;
            $rfid = isset($data['route']) && is_array($data['route']) ? $data['route'][0][6]['rfid'] : null;
    
            // Retourner les drives et stops (peuvent être null ou des tableaux vides)
            return [
                'rfid' => $rfid,
                'drives' => $drives,
                'stops' => $stops
            ];
        } catch (\Exception $e) {
            // Gérer les erreurs de requête HTTP
            // Vous pouvez enregistrer le message d'erreur ou retourner des valeurs par défaut
            return [
                'rfid' => null,
                'drives' => null,
                'stops' => null
            ];
        }

    }
}