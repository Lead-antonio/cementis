<?php

namespace App\Imports;

use App\Models\ImportInstallation;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Transporteur;
use App\Models\Chauffeur;
use App\Models\ChauffeurUpdate;
use App\Models\ImportInstallationError;
use App\Models\ImportNameInstallation;
use App\Models\Vehicule;
use App\Models\Installateur;
use App\Models\Installation;
use App\Models\VehiculeUpdate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class InstallationImport implements ToCollection, WithHeadingRow
{
    protected $name_file_excel;
    protected $import_name_id;
    protected $errors = [];
    protected $transporteurs = [];
    protected $successCount = 0;
    protected $errorCount = 0;


    public function __construct($name_file_excel, $import_name_id)
    {
        $this->name_file_excel = $name_file_excel;
        $this->import_name_id = $import_name_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $numero_ligne = $index + 2;

            // Vérifier et insérer le transporteur
            if (!isset($this->transporteurs[$row['transporteur']])) {
                $transporteur = Transporteur::firstOrCreate(
                    ['nom' => $row['transporteur']],
                    ['adresse' => $row['adresse'], 'tel' => (string) $row['transporteur_telephone']]
                );
                $this->transporteurs[$row['transporteur']] = $transporteur->id;
            } else {
                $transporteur = Transporteur::find($this->transporteurs[$row['transporteur']]);
            }

            // Vérification : au moins l'un des deux (RFID ou IMEI) doit être présent
            if (empty($row['rfid']) && empty($row['imei'])) {
                $this->addError("Ligne N° {$numero_ligne}: RFID et IMEI sont tous les deux vides, l'enregistrement est ignoré.");
                $this->errorCount++;
                continue;
            }

            // Vérifier l'existence du chauffeur et du véhicule
            $existingChauffeur = !empty($row['rfid']) ? Chauffeur::where('rfid', $row['rfid'])->first() : null;
            $existingVehicule = !empty($row['imei']) ? Vehicule::where('imei', $row['imei'])->orWhere('nom', $row['immatriculation'])->first() : null;

            // Si les deux existent, on saute la ligne
            

            $dateInstallation = null;
            if (!empty($row['date_installation'])) { // Vérifie si non vide
                $dateInstallation = $this->excelDateToCarbon($row['date_installation']);
            }
           

            if($existingChauffeur){

                ChauffeurUpdate::create([
                    'chauffeur_id' => $existingChauffeur->id,
                    'rfid' =>  $row['rfid'],
                    'rfid_physique' =>  $row['rfid_physique'],
                    'numero_badge' =>  $row['numero_badge'],
                    'nom' =>  $row['nom'],
                    'contact' =>  (string) $row['chauffeur_telephone'],
                    'transporteur_id' => $transporteur->id,
                    'date_installation' =>  $dateInstallation 
                ]);

                $this->addError("Ligne N° {$numero_ligne}: Rfid: [{$existingChauffeur->rfid}] du chauffeur [{$existingChauffeur->nom}]   existe déjà." ."Nouveau nom attribué à ce rfid:"  . $row['nom'] ."." );
                $this->errorCount++;
            }

           
            if($existingVehicule){
                VehiculeUpdate::create([
                    'vehicule_id' => $existingVehicule->id,
                    'imei' => (string) $row['imei'],
                    'nom' => $row['immatriculation'],
                    'id_transporteur' => $transporteur->id,
                    'date_installation' => $dateInstallation,
                ]);
                
                $this->addError("Ligne N° {$numero_ligne} :imei  [{$row['imei']}] est déjà attribué à {$existingVehicule->nom} .Nouveau vehicule attribué à cet imei :" . $row['immatriculation']  .".");
                $this->errorCount++;
            }

            // Insérer le chauffeur si inexistant et RFID non vide
            if (!$existingChauffeur && !empty($row['rfid'])) {
                $chauffeur = Chauffeur::create([
                    'rfid' => $row['rfid'],
                    'nom' => $row['nom'],
                    'rfid_physique' =>  $row['rfid_physique'],
                    'numero_badge' =>  $row['numero_badge'],
                    'contact' => (string) $row['chauffeur_telephone'],
                    'transporteur_id' => $transporteur->id,
                ]);
            } else {
                $chauffeur = $existingChauffeur;
            }

            // Insérer le véhicule si inexistant et IMEI non vide
            if (!$existingVehicule && !empty($row['imei'])) {
                $vehicule = Vehicule::create([
                    'imei' => (string) $row['imei'],
                    'nom' => $row['immatriculation'],
                    'description' => $row['description'],
                    'id_transporteur' => $transporteur->id,
                    'rfid_physique' =>  $row['rfid_physique'],
                    'numero_badge' =>  $row['numero_badge'],
                ]);
            } else {
                $vehicule = $existingVehicule;
            }

            // Insérer ou récupérer l'installateur
            $installateur = Installateur::firstOrCreate(
                ['matricule' => (string) $row['matricule_tech']],
                ['obs' => ""]
            );

            // Insérer l'installation si un véhicule a été créé ou existe déjà
            if ($vehicule) {
                $installation = Installation::create([
                    'date_installation' =>  $dateInstallation,
                    'vehicule_id' => $vehicule->id,
                    'installateur_id' => $installateur->id,
                ]);
            }

            

            // Insérer les données dans ImportInstallation
            ImportInstallation::create([
                'transporteur_nom' => $row['transporteur'],
                'transporteur_adresse' => $row['adresse'],
                'transporteur_tel' => (string) $row['transporteur_telephone'],
                'chauffeur_nom' => $row['nom'],
                'chauffeur_rfid' => $row['rfid'],
                'chauffeur_contact' => (string) $row['chauffeur_telephone'],
                'vehicule_nom' => $row['immatriculation'],
                'vehicule_imei' => (string) $row['imei'],
                'vehicule_description' => $row['description'],
                'installateur_matricule' => (string) $row['matricule_tech'],
                'dates' => $dateInstallation ,
                'import_name_id' => $this->import_name_id,
            ]);
            $this->successCount++;
        }

        $this->saveErrors();
    }



    // public function collection(Collection $rows)
    // {   
    //     foreach  ($rows as $index => $row)  {

    //         // dd($row);
            
    //          // Vérifier le RFID existant
    //         $existingChauffeur = Chauffeur::where('rfid', $row['rfid'])->first();
    //         if ($existingChauffeur) {
    //                 $numero_ligne = $index +1;
    //                 $this->addError("Ligne N° {$numero_ligne}: le RFID N° [{$row['rfid']}] est déjà attribué au chauffeur {$existingChauffeur->nom} ");
    //                 $this->errorCount++;
    //                 continue; // Passer à la ligne suivante
    //         }
 
    //         // // Vérifier le véhicule doublon
    //         $existingVehicule = Vehicule::where('imei', $row['imei'])->orWhere('nom', $row['immatriculation'])->first();
    //         if ($existingVehicule) {
    //             $numero_lignes = $index +1;
    //             $this->addError("Ligne N° {$numero_lignes}: le véhicule est déjà présent dans la base ");
    //             $this->errorCount++;
    //             continue; // Passer à la ligne suivante
    //         }

    //         // Vérifier et insérer les données dans la table Transporteur
    //         if (!isset($this->transporteurs[$row['transporteur']])) {
    //             $transporteur = Transporteur::firstOrCreate([
    //                 'nom' => $row['transporteur']
    //             ], [
    //                 'adresse' => $row['adresse'],
    //                 'tel' => (string) $row['transporteur_telephone'],
    //             ]);
    //             $this->transporteurs[$row['transporteur']] = $transporteur->id;
    //         } else {
    //             $transporteur = Transporteur::find($this->transporteurs[$row['transporteur']]);
    //         }

    //         // Insérer les données dans la table Chauffeur
    //         $chauffeur = Chauffeur::create([
    //             'rfid' => $row['rfid'],
    //             'nom' => $row['nom'],
    //             'contact' =>  (string)  $row['chauffeur_telephone'],
    //             'transporteur_id' => $transporteur->id,
    //         ]);

    //         // Insérer les données dans la table Véhicule
    //         $vehicule = Vehicule::create([
    //             'imei' => (string) $row['imei'],
    //             'nom' => $row['immatriculation'],
    //             'description' => $row['description'],
    //             'id_transporteur' => $transporteur->id,
    //         ]);

    //         // Insérer les données dans la table Installateur
    //         $installateur = Installateur::firstOrCreate([
    //             'matricule' => (string)  $row['matricule_tech'],
    //             'obs' => ""
    //         ]);

    //         // Insérer les données dans la table Installation
    //         $installation = Installation::create([
    //             'date_installation' => Carbon::parse($row['date_installation']),
    //             'vehicule_id' => $vehicule->id,
    //             'installateur_id' => $installateur->id,
    //         ]);

    //         $dateInstallation = $this->excelDateToCarbon($row['date_installation']);

    //         // Insérer les données dans la table ImportInstallation
    //         ImportInstallation::create([
    //             'transporteur_nom' => $row['transporteur'],
    //             'transporteur_adresse' => $row['adresse'],
    //             'transporteur_tel' => (string)  $row['transporteur_telephone'],
    //             'chauffeur_nom' => $row['nom'],
    //             'chauffeur_rfid' => $row['rfid'],
    //             'chauffeur_contact' => (string)  $row['chauffeur_telephone'],
    //             'vehicule_nom' => $row['immatriculation'],
    //             'vehicule_imei' => (string) $row['imei'],
    //             'vehicule_description' => $row['description'],
    //             'installateur_matricule' => (string)  $row['matricule_tech'],
    //             'dates' =>  $dateInstallation,
    //             'import_name_id' => $this->import_name_id,
    //         ]);

    //         $this->successCount++;

    //     }

    //     $this->saveErrors();
    // }

    protected function addError($error)
    {
        $this->errors[] = $error . "";
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function saveErrors()
    {
        if (!empty($this->errors)) {
            ImportInstallationError::create([
                'name' => 'Importation Erreur',
                'contenu' => implode("<br>", $this->errors),
                'import_name_id' => $this->import_name_id,
            ]);
            
            $importationinstall = ImportNameInstallation::find($this->import_name_id);
            // $formattedErrors = implode("<br>", array_map(function ($error) {
            //     return nl2br("Ligne N° " . $error); // Ajoute "Ligne N°" à chaque ligne avec un saut de ligne HTML
            // }, $this->errors));

            $importationinstall->update(['observation' => implode("", $this->errors)]);

        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    protected function excelDateToCarbon($excelDate)
    {
        if (is_numeric($excelDate)) {
            // Excel date starts on 1st January 1900
            return Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2); // Excel's day 1 is actually 0
        } else {
            // Si ce n'est pas un nombre, essayer de le convertir avec Carbon
            return Carbon::parse($excelDate);
        }
    }


    // public function generateErrorFile()
    // {
    //     if (empty($this->errors)) {
    //         return null; // Pas d'erreurs, pas de fichier à générer
    //     }

    //     $errorText = implode("\n", $this->errors);
    //     $fileName = "import_errors_" . time() . ".txt";

    //     // Créer un nom de fichier unique
    //     // $fileName = "export_" . date('Ymd_His') . ".txt";

    //     // Enregistrer le contenu dans un fichier texte temporaire
    //     $filePath = storage_path("app/{$fileName}");
    //     file_put_contents($filePath, $errorText);

    //     // Retourner une réponse de téléchargement
    //     return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        
    // }


}
