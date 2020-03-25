<?php


function AddFrais()
  {
    global $conn;
    $TarifJournalier = $_POST["TarifJournalier"];
    $JoursTravailles = $_POST["JoursTravailles"];
    $FraisMensuel = $_POST["FraisMensuel"];
    
  
    $query="INSERT INTO demo(TarifJournalier,JoursTravailles,FraisMensuel) VALUES('".$TarifJournalier."', '".$JoursTravailles."', '".$FraisMensuel."')";
    if(mysqli_query($conn, $query))
    {
      $response=array(
        //'status' => 1,
       // 'status_message' =>'Frais ajoute avec succes.'
        
      );
    }
    else
    {
      $response=array(
        'status' => 0,
        'status_message' =>'ERREUR!.'. mysqli_error($conn)
      );
    }
    header('Content-Type: application/json');
    //echo json_encode($response);

    $temp = array(
      "TartifJournalier"   => $TarifJournalier,
      "JoursTravailles" => $JoursTravailles,
      "FraisMensuel" => $FraisMensuel,
  );
  $JsonArray[] = $temp;

  echo json_encode(array($JsonArray));


  //calcul outputt

  //Chiffre d'affaires mensuel = Tarif Journalier en HT € * Jours travaillés par mois
  $CA =  $TarifJournalier * $JoursTravailles;
  //Frais de gestion = 10% ou 9% ou 8% ou 5% du chiffre d'affaire ( selon sa valeur ..)
  $FG=Frais($CA);
  //Disponible consultant = Chiffre d'affaires mensuel - Frais de gestion
  $DipC = $CA -$FG;
  //Montant chargé = Disponible consultant - Frais mensuel en €
  $MontantC = $DipC -$FraisMensuel;
  //Montant Brut = Montant chargé * 65,420570%
  $MontantB =  ($MontantC * 65.420570)/100;
  //Salaire net mensuel = Montant Brut * 79,420474%
  $SalaireNet = ($MontantB * 79.420474)/100;

  //Salaire net mensuel + frais = Salaire net mensuel + Frais mensuel en €
  $SalaireNetMensuel = $SalaireNet + $FraisMensuel;

  //Salaire net annuel = Salaire net mensuel * 12
  $SalaireNetAnnuel = $SalaireNetMensuel * 12;
  //Salaire net annuel + frais + avantages exclusifs Skalis = ( Salaire net + Frais mensuel en € + 460 ) * 12
  $SalaireNetAnnuelFraisSkalis = ($SalaireNet + $FraisMensuel + 460) * 12;

 
    $temp1 = array(
      "SalaireNetMensuel"   => $SalaireNet,
      "SalaireNetMensuelFrais" => $SalaireNetMensuel,
      "SalaireNetAnnuel" => $SalaireNetAnnuel,
      "SalaireNetAnuuelFraisSkalis" => $SalaireNetAnnuelFraisSkalis,
     
  );
  $JsonArray1[] = $temp1;

  echo json_encode(array( $JsonArray1));


}


function Frais($CA)
{
  if ($CA>=0 AND $CA <= 6500) {
    return ($CA * 10)/100;}
     
  elseif ($CA>=6501 AND $CA <= 7500) {
    return ($CA * 9)/100;}

    elseif ($CA>= 7500 AND $CA <= 8500) {
      return ($CA * 8)/100;}

      elseif ($CA>= 8500 AND $CA <= 10000) {
        return ($CA * 6)/100;}

        elseif ($CA>= 10000) {
          return ($CA * 5)/100;}

      
  

  
}




  $server = "localhost";
  $username = "root";
  $password = "rihabbahri";
  $db = "api";
  $conn = mysqli_connect($server, $username, $password, $db);
  $_SERVER["REQUEST_METHOD"] = "POST";
  $request_method = $_SERVER["REQUEST_METHOD"];
  switch($request_method)
  {
    case 'POST':
      AddFrais();
      break;
    default:
      // Requête invalide
      header("HTTP/1.0 405 Method Not Allowed");
      break;
  }

  

  

?>