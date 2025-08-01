<?php
 
namespace App\Controller;

use App\Entity\User;

use App\Service\Level0;
use Yoop\AbstractController;
use App\Service\ControlLevel;

class HomeController extends AbstractController
{
    public function print() 
    {
        $message = null;
        if(sizeof($_POST) > 0) { 
            if(!empty($_POST["description"]) && !empty($_POST["nom_dossier"]) && !empty($_POST["date_creation"])) {
                $date = $_POST["date_creation"];
                $verify = \DateTime::createFromFormat("Y-m-d", $date);

                if($verify === false || $date < date("Y-m-d")) {
                    $message = "Non mais sérieux, on est le FBI !!! Tu croyais vraiment passer comme ça ???";
                } else {
                    $uint32 = unpack('V', pack('V', strtotime($date)))[1]; // reste non signé
                    $int32 = ($uint32 > 2147483647) ? $uint32 - 4294967296 : $uint32;

                    if ($uint32 === $int32) {
                        $timestamp = $int32;
                        $message = nl2br("<strong>ENREGISTREMENT SYSTEM</strong>
                        Tiemstamp : ".htmlspecialchars($timestamp)."
                        Message : L'enregistrement du dossier <strong>".htmlspecialchars($_POST["nom_dossier"])."</strong> a bien été pris en compte.
                        ");
                    } else {
                        $message = nl2br("<strong>ERREUR SYSTEM</strong>
                        System : IBM i version 7.4
                        Processor : 32bits
                        Memory : 4 Go
                        Satus Code : 00000000000000A1 
                        Error : Echec enregistrement   
                        Type de message : Programme de gestion d'enregistrements
                        Tiemstamp : ".htmlspecialchars($timestamp)."
                        Message : L'enregistrement du dossier <strong>".htmlspecialchars($_POST["nom_dossier"])."</strong>. 
                        <i>Veuillez contacter l'administrateur système pour résoudre ce problème.</i>

                        <hr>

                        <h3>Affaire classée !</h3>
                        <h3>Flag : <strong>".$this->getFlag('DEFAULT_CTF_FLAG')."</strong></h3>
                        ");
                    }
                }
            }
            else {
                $message = 'Le formulaire est incomplet';
            }
        }

        return $this->render('home', ['status' => $status, 'message' =>  $message]);
    }
}
