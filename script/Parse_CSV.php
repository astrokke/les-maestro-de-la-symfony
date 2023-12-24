<?php


$testPDO = new PDO('mysql:dbname=maestro;host=127.0.0.1', 'maestro', 'maestro');


$csv = '..\Ressources\cities.csv';


/**
 * Fonction permettant de parcourir le fichier .csv et mettre les valeurs dans un tableau pour traitement par la suite
 *
 * @param [type] $csv
 * @return array
 */
function read($csv)
{
    $file = fopen($csv, 'r');
    while (!feof($file)) {
        $line[] = fgetcsv($file, null, ';');
    }
    fclose($file);
    return $line;
}

//Assignation de la fonction
$csv = read($csv);

$regions = [];
$departments = [];
$villes = [];
$codePostaux = [];
$deptsValue = [];

for ($i = 1; $i <= count($csv); $i++) {
    $string = $csv[$i][0];
    $values = explode(',', $string);

    $ville = $values[3];
    $departmentName = $values[6];
    $departmentValue = $values[7];
    $regionName = $values[8];
    $codePostalValue = $values[2];

    // Stockez les noms dans les tableaux 
    if (!in_array($regionName, $regions)) {
        $regions[] = $regionName;
        $stmt = $testPDO->prepare('SELECT id FROM region WHERE nom = " ' . $regionName . '"');
        $stmt->execute();
        $existingRegion = $stmt->fetchColumn();

        if (!$existingRegion) {
            // Si la région n'existe pas, insérez-la
            $stmt = $testPDO->prepare('INSERT INTO region (nom) VALUES (" ' . $regionName . ' ")');
            $stmt->execute();

            // Récupérez l'ID de la nouvelle région
            $regionId = $testPDO->lastInsertId();
            $regions[$regionName] = $regionId;
        } else {
            // Si la région existe déjà, récupérez son ID
            $regions[$regionName] = $existingRegion;
        }
    }
    if (!in_array($departmentName, $departments)) {
        $regionId = $regions[$regionName];

        $stmt = $testPDO->prepare('SELECT id FROM departement WHERE nom = ? AND numero_departement = ?');
        $stmt->execute([$departmentName, $departmentValue]);
        $existingDept = $stmt->fetchColumn();

        if (!$existingDept) {

            $stmt = $testPDO->prepare('INSERT INTO departement (nom, numero_departement, region_id) VALUES ("' . $departmentName . '", "' . $departmentValue . '", "' . $regionId . '")');
            $stmt->execute();

            $deptId = $testPDO->lastInsertId();
            $departments[$departmentName] = $deptId;
        } else {

            $departments[$departmentName] = $existingDept;
            $departments[] = $departmentName;
        }
    }
    if (!in_array($ville, $villes)) {
        $deptId = $departments[$departmentName];
        $villes[] = $ville;

        // Supprimez les espaces inutiles autour de $ville
        $ville = trim($ville);

        $stmt = $testPDO->prepare('SELECT id FROM ville WHERE nom = ?');
        $stmt->execute([$ville]);
        $existingVille = $stmt->fetchColumn();

        if (!$existingVille) {
            $stmt = $testPDO->prepare('INSERT INTO ville (departement_id, nom) VALUES (?, ?)');
            $stmt->execute([$deptId, $ville]);

            $villeId = $testPDO->lastInsertId();
            $villes[$ville] = $villeId;
        } else {
            $villes[$ville] = $existingVille;
        }
    }

    if (!array_key_exists($codePostalValue, $codePostaux)) {
        // Insérer le nouveau code postal
        $stmt = $testPDO->prepare('INSERT INTO code_postal (libelle) VALUES (?)');
        $stmt->execute([$codePostalValue]);

        // Ajouter le code postal à la liste pour éviter les duplicatas
        $codePostaux[$codePostalValue] = $testPDO->lastInsertId();
    }
    //insertion de la relation
    $villeId = $villes[$ville];
    $codePostalId = $codePostaux[$codePostalValue];

    $stmt = $testPDO->prepare('SELECT * FROM ville_code_postal WHERE ville_id = ? AND code_postal_id =?');
    $stmt->execute([$villeId, $codePostalId]);
    $existingRelation = $stmt->fetchColumn();
    if (!$existingRelation) {
        // Insérer la relation entre la ville et le code postal dans la table intermédiaire
        $stmt = $testPDO->prepare('INSERT INTO ville_code_postal (ville_id, code_postal_id) VALUES (?, ?)');
        $stmt->execute([$villeId, $codePostalId]);
    }
}
