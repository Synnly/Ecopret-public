<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class RegistrationControllerTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function testCreationCompteValide(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        // Remplissage du formulaire + CGU
        $crawler = $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST',
                                                                    'registration_form[PrenomCompte]' => 'Test',
                                                                    'registration_form[AdresseMailCOmpte]' => 'test@test.com',
                                                                    'registration_form[plainPassword]' => 'Testtest123',
                                                                    'registration_form[agreeTerms]' => '1']);

        // Confirmation
        $crawler->selectButton('Création du compte');

        $this->assertResponseRedirects('/main', null, "La création d'un compte valide a échoué");
    }

    public function testCreationCompteNom1a22Caracteres(): void
    {
        $nom = "testtesttesttesttesttest";
        $NOM = "TESTTESTTESTTESTTESTTEST";
        $suffixeEmail = "abcdefghijklmnopqrstuvwxyz";
        $client = static::createClient();

        for ($i = 1; $i <= 22; $i++) {

            // Lettres minuscules
            $client->request('GET', '/register');

            $crawler = $client->submitForm("Création du compte", ['registration_form[NomCompte]' => substr($nom, 0, $i),
                'registration_form[PrenomCompte]' => 'Test',
                'registration_form[AdresseMailCOmpte]' => "test@test".substr($suffixeEmail, $i, 1).".com",
                'registration_form[plainPassword]' => 'Testtest123',
                'registration_form[agreeTerms]' => '1']);

            $crawler->selectButton('Création du compte');

            $this->assertAnySelectorTextSame("li", "Votre nom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres majuscules ou - .", "La création d'un compte invalide (nom = '".substr($nom, 0, $i)."', len=$i) a réussi");

            // Lettres majuscules
            $client->request('GET', '/register');

            $crawler = $client->submitForm("Création du compte", ['registration_form[NomCompte]' => substr($NOM, 0, $i),
                'registration_form[PrenomCompte]' => 'Test',
                'registration_form[AdresseMailCOmpte]' => "test@test".substr($suffixeEmail, $i, 1).".com",
                'registration_form[plainPassword]' => 'Testtest123',
                'registration_form[agreeTerms]' => '1']);

            $crawler->selectButton('Création du compte');

            if($i <= 20 && $i >= 2) {
                $this->assertResponseRedirects('/main', null, "La création d'un compte valide a échoué (nom = '".substr($NOM, 0, $i)."', len=$i)");
            }
            else {
                $this->assertAnySelectorTextSame("li", "Votre nom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres majuscules ou - .", "La création d'un compte invalide (nom = '".substr($NOM, 0, $i)."', len=$i) a réussi");
            }
        }
    }

    public function testCreationCompteprenom1a22Caracteres(): void
    {
        $prenom = "testtesttesttesttesttest";
        $PRENOM = "TESTTESTTESTTESTTESTTEST";
        $suffixeEmail = "abcdefghijklmnopqrstuvwxyz";
        $client = static::createClient();

        for ($i = 1; $i <= 22; $i++) {

            // Lettres minuscules
            $client->request('GET', '/register');

            $crawler = $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST',
                'registration_form[PrenomCompte]' => substr($prenom, 0, $i),
                'registration_form[AdresseMailCOmpte]' => "test@test".substr($suffixeEmail, $i, 1).".com",
                'registration_form[plainPassword]' => 'Testtest123',
                'registration_form[agreeTerms]' => '1']);

            $crawler->selectButton('Création du compte');

            $this->assertAnySelectorTextSame("li", "Votre prénom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres ou - .", "La création d'un compte invalide (nom = '".substr($prenom, 0, $i)."', len=$i) a réussi");

            // Lettres majuscules
            $client->request('GET', '/register');

            $crawler = $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST',
                'registration_form[PrenomCompte]' => substr($PRENOM, 0, $i),
                'registration_form[AdresseMailCOmpte]' => "test@test".substr($suffixeEmail, $i, 1).".com",
                'registration_form[plainPassword]' => 'Testtest123',
                'registration_form[agreeTerms]' => '1']);

            $crawler->selectButton('Création du compte');

            if($i <= 20 && $i >= 2) {
                $this->assertResponseRedirects('/main', null, "La création d'un compte valide a échoué (nom = '".substr($PRENOM, 0, $i)."', len=$i)");
            }
            else {
                $this->assertAnySelectorTextSame("li", "Votre prénom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres ou - .", "La création d'un compte invalide (nom = '".substr($PRENOM, 0, $i)."', len=$i) a réussi");
            }
        }
    }

    public function testCreationCompteEmailPartieLocaleInvalide(): void{
        $client = static::createClient();

        $listeEmailsInvalides = ['@test.com', '.@test.com', '\'@test.com', '"@test.com', '`@test.com', '[@test.com', '<@test.com', ']@test.com', '>@test.com', '{@test.com', '{@test.com', '@@test.com', '?@test.com'];

        foreach ($listeEmailsInvalides as $email) {
            $client->request('GET', '/register');
            $crawler = $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST',
                'registration_form[PrenomCompte]' => 'Test',
                'registration_form[AdresseMailCOmpte]' => $email,
                'registration_form[plainPassword]' => 'Testtest123',
                'registration_form[agreeTerms]' => '1']);

            $crawler->selectButton('Création du compte');
            $this->assertAnySelectorTextSame("li", "Votre adresse mail n' est pas valide.", "La création d'un compte invalide (email=\"$email\") a réussi");
        }
    }

    public function testCreationCompteEmailDomaineInvalide(): void{
        $client = static::createClient();

        $listeEmailsInvalides = ['test@.com', 'test@1.fr', 'test@123.fr', 'test@127.0.0.1', 'test@[127.0.0.1', 'test@127.0.0.1]', 'test@{127.0.0.1]', 'test@[127.0.0.-1]', 'test@[256.0.0.1]', 'test@[127.0.0]', '[test@127.0]', 'test@[127]', 'test@[]', 'test@test', 'test@test.c'];

        foreach ($listeEmailsInvalides as $email) {
            $client->request('GET', '/register');
            $crawler = $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST',
                'registration_form[PrenomCompte]' => 'Test',
                'registration_form[AdresseMailCOmpte]' => $email,
                'registration_form[plainPassword]' => 'Testtest123',
                'registration_form[agreeTerms]' => '1']);

            $crawler->selectButton('Création du compte');
            $this->assertAnySelectorTextSame("li", "Votre adresse mail n' est pas valide.", "La création d'un compte invalide (email=\"$email\") a réussi");
        }
    }

}
