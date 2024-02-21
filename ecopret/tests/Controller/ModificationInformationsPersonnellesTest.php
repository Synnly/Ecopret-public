<?php

namespace App\Tests\Controller;

use App\Entity\Lieu;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ModificationInformationsPersonnellesTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function testInformationsPersoValide(): void
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

        $client->request('GET', '/infos/modif');

        //self::bootKernel();
        //$entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        //$lieu = $entityManager->getRepository(Lieu::class)->find('2020');

        // Remplir le formulaire + CGU
        $crawler = $client->submitForm("Modifier", [
            'modifier_informations_personnelles_form[NomCompte]' => 'TES',
            'modifier_informations_personnelles_form[PrenomCompte]' => "Test",
            'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "test@test.com",
            'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
            'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
            'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
            'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,

        ]);

        // Confirmation
        $crawler->selectButton("Modifier");

        $this->assertResponseRedirects('/infos', null, "La modification d'un compte valide à échoué  compte total");
    }
    public function testModificationsInfosPersoNom1a22Caracteres(): void
    {
        $nom = "testtesttesttesttesttest";
        $NOM = "TESTTESTTESTTESTTESTTEST";
        $suffixeEmail = "abcdefghijklmnopqrstuvwxyz";
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



        for ($i = 1; $i <= 22; $i++) {

            // Lettres minuscules
            $client->request('GET', '/infos/modif');

            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => substr($nom, 0, $i),
                'modifier_informations_personnelles_form[PrenomCompte]' => "CCC",
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "test@test" . substr($suffixeEmail, $i, 1) . ".com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,

            ]);

            $crawler->selectButton('Modifier');

            $this->assertAnySelectorTextSame("li", "Votre nom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres majuscules ou - .", "La création d'un compte invalide (nom = '" . substr($nom, 0, $i) . "', len=$i) a réussi");

            // Lettres majuscules
            $client->request('GET', '/infos/modif');

            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => substr($NOM, 0, $i),
                'modifier_informations_personnelles_form[PrenomCompte]' => "CCC",
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "test@test" . substr($suffixeEmail, $i, 1) . ".com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]);

            $crawler->selectButton('Modifier');

            if ($i <= 20 && $i >= 2) {
                $this->assertResponseRedirects('/infos', null, "La modification d'un compte valide à échoué (nom = '" . substr($NOM, 0, $i) . "', len=$i)");
            } else {
                $this->assertAnySelectorTextSame("li", "Votre nom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres majuscules ou - .", "La création d'un compte invalide (nom = '" . substr($NOM, 0, $i) . "', len=$i) a réussi");
            }
        }
    }

    public function testModificationsInfosPersoprenom1a22Caracteres(): void
    {
        $prenom = "testtesttesttesttesttest";
        $PRENOM = "TESTTESTTESTTESTTESTTEST";
        $suffixeEmail = "abcdefghijklmnopqrstuvwxyz";
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

        for ($i = 1; $i <= 22; $i++) {

            // Lettres minuscules
            $client->request('GET', '/infos/modif');

            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => substr($prenom, 0, $i),
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "test@test" . substr($suffixeEmail, $i, 1) . ".com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]);

            $crawler->selectButton('Modifier');

            $this->assertAnySelectorTextSame("li", "Votre prénom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres ou - .", "La création d'un compte invalide (nom = '" . substr($prenom, 0, $i) . "', len=$i) a réussi");

            // Lettres majuscules
            $client->request('GET', '/infos/modif');

            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => substr($PRENOM, 0, $i),
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "test@test" . substr($suffixeEmail, $i, 1) . ".com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]);

            $crawler->selectButton('Modifier');

            if ($i <= 20 && $i >= 2) {
                $this->assertResponseRedirects('/infos', null, "La modification d'un compte valide à échoué (nom = '" . substr($PRENOM, 0, $i) . "', len=$i)");
            } else {
                $this->assertAnySelectorTextSame("li", "Votre prénom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres ou - .", "La création d'un compte invalide (nom = '" . substr($PRENOM, 0, $i) . "', len=$i) a réussi");
            }
        }
    }

    public function testModificationsInfosPersoEmailPartieLocaleInvalide(): void
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

        $listeEmailsInvalides = ['@test.com', '.@test.com', '\'@test.com', '"@test.com', '`@test.com', '[@test.com', '<@test.com', ']@test.com', '>@test.com', '{@test.com', '{@test.com', '@@test.com', '?@test.com'];

        foreach ($listeEmailsInvalides as $email) {
            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => $email,
                'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]);

            $crawler->selectButton('Modifier');
            $this->assertAnySelectorTextSame("li", "Votre adresse mail n' est pas valide.", "La création d'un compte invalide (email=\"$email\") a réussi");
        }
    }

    public function testModificationsInfosPersoEmailDomaineInvalide(): void
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

        $listeEmailsInvalides = ['test@.com', 'test@1.fr', 'test@123.fr', 'test@127.0.0.1', 'test@[127.0.0.1', 'test@127.0.0.1]', 'test@{127.0.0.1]', 'test@[127.0.0.-1]', 'test@[256.0.0.1]', 'test@[127.0.0]', '[test@127.0]', 'test@[127]', 'test@[]', 'test@test', 'test@test.c'];

        foreach ($listeEmailsInvalides as $email) {
            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => $email,
                'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]);

            $crawler->selectButton('Modifier');
            $this->assertAnySelectorTextSame("li", "Votre adresse mail n' est pas valide.", "La modification d\'un (email=\"$email\") invalide a réussi");
        }
    }

    public function testModificationsInfosPersoMDPInvalide(): void
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

        $listeMDPSInvalides = [
            'Mot1',
            'mot2',
            'Mot123',
            'motmot',
            'MOOOT',
            'MOOOOOOOOOOOT',
            '        ',
            '               ',
            '1111',
            '12345678',
            'mot11111111',
            '& & & & & & & & ',
            'c0°°ipou  555',
            '100$ccc##',
            'AAA11',
            'AAAM101012',
            'cccccccccccccc',
            'mo1',
            'MINmaj',
            'MINMajlongeur',
        ];

        foreach ($listeMDPSInvalides as $mdp) {
            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "toto@gmail.com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => $mdp,
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]);

            $crawler->selectButton('Modifier');
            $this->assertAnySelectorTextSame("li", "Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule et un chiffre.", "La modification du (mot de passe =\"$mdp\") invalide a réussi");
        }
    }
    public function testModificationsInfosPersoMDPvalide(): void
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

        $listeMDPSInvalides = [
            'mot2ccPPP',
            'c0°°IPou  555',
        ];

        foreach ($listeMDPSInvalides as $mdp) {
            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "toto@gmail.com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => $mdp,
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]);

            $crawler->selectButton('Modifier');
            $this->assertResponseRedirects('/infos', null, "La modification d'un compte valide à échoué (mdp = \"$mdp\")");
        }
    }
    public function testInformationsDateValide(): void
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

        $client->request('GET', '/infos/modif');

        $date = "10/2027";
        // Remplir le formulaire + CGU
        $crawler = $client->submitForm("Modifier", [
            'modifier_informations_personnelles_form[NomCompte]' => 'TES',
            'modifier_informations_personnelles_form[PrenomCompte]' => "Test",
            'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "test@test.com",
            'modifier_informations_personnelles_form[motDePasseCompte]' => 'Azerty88',
            'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
            'modifier_informations_personnelles_form[carte_credit][date_expiration]' => $date,
            'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,

        ]);
        $crawler->selectButton('Modifier');
        $this->assertResponseRedirects('/infos', null, "La modification d'un compte valide à échoué (date = \"$date\")");
    }

    public function testModificationsInfosPersoCVVvalide(): void
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


        for ($i = 101; $i < 999; $i++) {

            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "toto@gmail.com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => "Azerty88",
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => $i,
            ]);

            $crawler->selectButton('Modifier');
            $this->assertResponseRedirects('/infos', null, "La modification d'un compte valide à échoué (CVV = \"$i\")");
        }
    }

    public function testModificationsInfosPersoCVVInvalide(): void
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


        for ($i = -100; $i < -1200; $i--) {

            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "toto@gmail.com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => "Azerty88",
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => $i,
            ]);

            $crawler->selectButton('Modifier');
            $this->assertAnySelectorTextSame("li", "Le CVV doit contenir 3 chiffres.", "La modification du (CVV =\"$i\") invalide a réussi");
        }
        for ($i = 1000; $i < 1200; $i++) {

            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "toto@gmail.com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => "Azerty88",
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => $i,
            ]);

            $crawler->selectButton('Modifier');
            $this->assertAnySelectorTextSame("li", "Le CVV doit contenir 3 chiffres.", "La modification du (CVV =\"$i\") invalide a réussi");
        }
    }

    public function testModificationsInfosPersoNumCarteInvalide(): void
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

        $listCardValide = [
            2222400070000005,
            5555341244441115,
            5577000055770004,
            5555444433331111,
            2222410740360010,
            5555555555554444,
            2222410700000002,
            2222400010000008,
            2223000048410010,
            2222400060000007,
            2223520443560010,
            2222400030000004,
            2222400050000009,
            5103221911199245,
        ];
        foreach ($listCardValide as $cv) {

            $client->request('GET', '/infos/modif');
            $crawler = $client->submitForm("Modifier", [
                'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
                'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
                'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "toto@gmail.com",
                'modifier_informations_personnelles_form[motDePasseCompte]' => "Azerty88",
                'modifier_informations_personnelles_form[carte_credit][numero_carte]' => $cv,
                'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
                'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 737,
            ]); 	

            $crawler->selectButton('Modifier');
            $this->assertResponseRedirects('/infos', null, "La modification d'un compte valide a échoué");
        }
    }
    public function testResiliationAboConnecte(): void
    {
        /* Création du compte + connexion */
        $client = static::createClient();
        // Creation d'un compte
        $client->request('GET', '/register');
        $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST', 'registration_form[PrenomCompte]' => 'Test', 'registration_form[AdresseMailCOmpte]' => 'test@test.com', 'registration_form[plainPassword]' => 'Testtest123', 'registration_form[agreeTerms]' => '1', 'magicInput' => 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'])->selectButton('Création du compte');
        $client->request('GET', '/login');
        // Remplissage du formulaire
        $crawler = $client->submitForm("Connexion >" , [
            'AdresseMailCOmpte' => 'test@test.com',
            'password' => 'Testtest123'
        ]);
        // Confirmation
        $crawler->selectButton('Connexion >');

        $client->request('GET', '/infos/modif/cancel');
        $client->submitForm("Oui, résilier");

        $this->assertResponseRedirects('/main', null, "La résiliation d'un compte connecté à échouée");
    }

    public function testResiliationAboPasConnecte(): void
    {
        /* Création du compte + connexion */
        $client = static::createClient();

        $client->request('GET', '/infos/modif/cancel');

        $this->assertResponseRedirects('/login', null, "Possibilité d'accéder à la résiliation d'un compte pas connecté");
    }
}
