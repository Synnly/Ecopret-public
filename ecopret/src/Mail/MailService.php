<?php 

namespace App\Mail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use App\Entity\Compte;

/**
 * Classe pour envoie de mail
 */
class MailService{
    private $mailer;

    /**
     * Constructeur
     */
    public function __construct(){
        //Je dis qui transporte adresseMail : motDePasse (pour Application != mdp de l'adr mail) @ type : port
        //Pas besoin d'y touucher
        $transport = Transport::fromDsn('smtp://noreplyecopret@gmail.com:wasvpboyxkqcythh@smtp.gmail.com:587');
        $this->mailer = new Mailer($transport);
    }

    /**
     * Envoie un mail, avec le mail, l'obj du mail, la descirption. cc et bcc sont optionels pas besoin de les remplir (null si vous mettrez rien)
     */
    public function sendMail(Compte $user, String $obj, String $description, String $cc = '', String $bcc = ''){
        $email = (new Email());
        $email->from('sender@sender.sender'); //Peut inporte le mail affiché, ça sera celui du compte mail lié  
        $email->to($user->getAdresseMailCOmpte());
        $email->subject($obj);
        $email->html($description);
        if($cc !== ''){
            $email->cc($cc);
        }
        if($bcc !== ''){
            $email->bcc($bcc);
        }
        $this->mailer->send($email);
    }
}



