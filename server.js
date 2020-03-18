var express = require('express');
var bodyParser = require('body-parser');

var app = express();
app.use(bodyParser.json());

var FG;
// root (localhost:3000/)
app.post('/service', function (req, res) {
    //console.log(req.body);
    //DEFINE INPUT
    const TarifJournalier = req.body.TarifJournalier;
    const JoursTravailles = req.body.JoursTravailles;
    const FraisMensuel = req.body.FraisMensuel;
  
    //CALCUL OUTPUT
    const CA =TarifJournalier * JoursTravailles;
    var FG;
    // Chiffre d'affaires mensuel = Tarif Journalier en HT € * Jours travaillés par mois
    //res.send(`Chiffre d'affaire ${CA}`);
   // Frais de gestion = 10% ou 9% ou 8% ou 5% du chiffre d'affaire ( selon sa valeur )
  
   if (CA>=0 && CA <= 6500) {
      FG = (CA * 10)/100;
   
 } else if(CA>=6501 && CA <= 7500) {
     FG = (CA * 9)/100;
}  else if(CA>= 7500 && CA <= 8500) {
    FG = (CA * 8)/100;
}   else if(CA>= 8500 && CA <= 10000) {
    FG = (CA * 6)/100;
}  else if(CA >=10000) {
    FG = (CA * 5)/100;
}  



//console.log(FG);
//Disponible consultant
const Dip = CA - FG;
//res.send(`Dispo consultant ${Dip}`);

//Montant chargé = Disponible consultant - Frais mensuel en €
const MontantC = Dip - FraisMensuel;

//Montant Brut = Montant chargé * 65,420570%
const MontantB =( MontantC * 65.420570)/100;

//Salaire net mensuel = Montant Brut * 79,420474%
const SalaireNet = (MontantB * 79,420474)/100;

//Salaire net mensuel + frais = Salaire net mensuel + Frais mensuel en €
const SalaireNetMensuel = SalaireNet +FraisMensuel;

//Salaire net annuel = Salaire net mensuel * 12
const SalaireNetAnnuel = SalaireNetMensuel * 12;

//Salaire net annuel + frais + avantages exclusifs Skalis = ( Salaire net mensuel + Frais mensuel en € + 460 ) * 12
const SalaireNetAnuuelFraisSkalis =  (SalaireNetMensuel + FraisMensuel + 460 ) * 12;

//res.send({ChiffreAffaire: CA, Dispocons: Dip, Montant:MontantC, MontantBrut : MontantB });
//SEND RESULTS
res.send({SalaireNet: SalaireNet, SalaireNetMensuelFrais : SalaireNetMensuel, SalaireNetAnnuel: SalaireNetAnnuel, SalaireNetAnuuelFraisSkalis: SalaireNetAnuuelFraisSkalis});




});

// On localhost:3000/welcome
app.get('/welcome', function (req, res) {
    res.send('<b>Hello</b> welcome to my http server made with express');
});

// Change the 404 message modifing the middleware
app.use(function(req, res, next) {
    res.status(404).send("Sorry, that route doesn't exist. Have a nice day :)");
});

// start the server in the port 3000 !
app.listen(3000, function () {
    console.log('Example app listening on port 3000.');
});