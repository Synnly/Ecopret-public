<?php

namespace App\Tests\Controller;


use App\Entity\Annonce;
use App\Entity\Compte;
use App\Entity\Prestataire;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ConnexionToEntityManager extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function getCompte($name): Compte
    {
        $co = $this->entityManager
            ->getRepository(Compte::class)
            ->findOneBy(['NomCompte' => $name]);

        return $co;
    }

    public function getUtilisateur($name): Utilisateur
    {
        $co = $this->entityManager
            ->getRepository(Utilisateur::class)
            ->findOneBy(['noCompte' => $this->getCompte($name)]);
        return $co;
    }

    public function sendDB($obj)
    {
        $this->entityManager->persist($obj);
        $this->entityManager->flush();
    }
}

class RetourEmpruntTest

extends WebTestCase
{
    use ResetDatabase, Factories;
    public function testAccesPagePasConnecter(): void
    {
        $client = static::createClient();

        // Deplacement vers la page de connexion
        $client->request('GET', '/retour/1');


        $this->assertResponseRedirects('/', null, "Acces sans ce connecter");
    }
    /*On peut y acceder sans se connecter */

    public function testAccesPageConnecter(): void
    {
        $client = static::createClient();
        // Deplacement vers la page de creation de compte
        $client->request('GET', '/register');

        // Remplissage du formulaire puis clic sur le bouton de creation
        $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST', 'registration_form[PrenomCompte]' => 'Test', 'registration_form[AdresseMailCOmpte]' => 'test@test.com', 'registration_form[plainPassword]' => 'Testtest123', 'registration_form[agreeTerms]' => '1', 'magicInput' => 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'])->selectButton('Création du compte');


        // Deplacement vers la page de connexion
        $client->request('GET', '/login');


        // Remplissage du formulaire
        $crawler = $client->submitForm("Connexion >", [
            'AdresseMailCOmpte' => 'test@test.com',
            'password' => 'Testtest123'
        ]);

        // Confirmation
        $crawler->selectButton('Connexion >');

        $this->assertResponseRedirects('/main', null, "La connexion d'un compte valide a échoué");

        $client->request('GET', '/retour/1');

        $this->assertResponseRedirects('/main', null, "L'acces à un retour d'annonce est possible sans en avoir");
    }
    
    /*public function testRetourEmprunt(): void
    {
        $client = static::createClient();
        // Deplacement vers la page de creation de compte
        $client->request('GET', '/register');

        // Remplissage du formulaire puis clic sur le bouton de creation
        $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST', 'registration_form[PrenomCompte]' => 'Test', 'registration_form[AdresseMailCOmpte]' => 'test@test.com', 'registration_form[plainPassword]' => 'Testtest123', 'registration_form[agreeTerms]' => '1', 'magicInput' => 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'])->selectButton('Création du compte');

        $client->request('GET', '/register');

        // Remplissage du formulaire puis clic sur le bouton de creation
        $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TESTT', 'registration_form[PrenomCompte]' => 'Testt', 'registration_form[AdresseMailCOmpte]' => 'test2@test.com', 'registration_form[plainPassword]' => 'Testtest123', 'registration_form[agreeTerms]' => '1', 'magicInput' => 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'])->selectButton('Création du compte');

        // Deplacement vers la page de connexion
        $client->request('GET', '/login');

        //Création du prestataire
        $prestataire = new Prestataire();

        //Création pour avoir entityManager
        $entity = new ConnexionToEntityManager();
        $entity->setUp();

        $prestataire->setNoUtisateur($entity->getUtilisateur("TEST"));
        $entity->sendDB($prestataire);

        //Création de l'annonce
        $annonce = new Annonce();
        $annonce->setNomAnnonce("test");
        $annonce->setPrestataire($prestataire);
        $annonce->setDisponibilite("");
        $annonce->setImageAnnonce("");
        $entity->sendDB($annonce);

        //Création de la transaction
        $transaction = new Transaction();
        $transaction->setClient($entity->getUtilisateur("TESTT"));
        $transaction->setPrestataire($prestataire);
        $transaction->setAnnonce($annonce);
        $transaction->setEstCloture(false);
        $entity->sendDB($transaction);

        // Remplissage du formulaire
        $crawler = $client->submitForm("Connexion >", [
            'AdresseMailCOmpte' => 'test@test.com',
            'password' => 'Testtest123'
        ]);

        // Confirmation
        $crawler->selectButton('Connexion >');

        $this->assertResponseRedirects('/main', null, "La connexion d'un compte valide a échoué");

        $client->request('GET', '/litige/declarer');
        $crawler = $client->submitForm("Valider", [
            'declarer_litige[prenom]' => "Test",
            'declarer_litige[mail]' => "test@test.com",
            'declarer_litige[transaction]' => 1,
            'declarer_litige[description]' => "test",
            'declarer_litige[typeUtil]' => "prest",
        ]);
        $crawler->selectButton('Valider');
        $this->assertResponseRedirects('/litige', null, "La création d'un litige valide a échoué");
    }*/
}