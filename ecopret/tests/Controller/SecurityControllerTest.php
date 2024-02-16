<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class SecurityControllerTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function testConnexionValide(): void{
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

        $this->assertResponseRedirects('/main', null, "La connexion d'un compte valide a échoué");

    }

    public function testConnexionInvalideCompteInexistant(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        // Remplissage du formulaire
        $crawler = $client->submitForm("Connexion >" , [
            'AdresseMailCOmpte' => 'marchepas@nope.com',
            'password' => 'toujours pas'
        ]);

        // Confirmation
        $crawler->selectButton('Connexion >');

        $this->assertResponseRedirects('/login', null, "La connexion d'un compte invalide a réussi");
    }

    public function testConnexionInvalideMailInvalide(): void
    {
        $client = static::createClient();

        // Creation d'un compte
        $client->request('GET', '/register');
        $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST', 'registration_form[PrenomCompte]' => 'Test', 'registration_form[AdresseMailCOmpte]' => 'test@test.com', 'registration_form[plainPassword]' => 'Testtest123', 'registration_form[agreeTerms]' => '1', 'magicInput' => 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'])->selectButton('Création du compte');

        $client->request('GET', '/login');

        // Remplissage du formulaire
        $crawler = $client->submitForm("Connexion >" , [
            'AdresseMailCOmpte' => 'test@notamail.com',
            'password' => 'Testtest123'
        ]);

        // Confirmation
        $crawler->selectButton('Connexion >');

        $this->assertResponseRedirects('/login', null, "La connexion d'un compte invalide a réussi");
    }

    public function testConnexionInvalideMdPInvalide(): void
    {
        $client = static::createClient();

        // Creation d'un compte
        $client->request('GET', '/register');
        $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST', 'registration_form[PrenomCompte]' => 'Test', 'registration_form[AdresseMailCOmpte]' => 'test@test.com', 'registration_form[plainPassword]' => 'Testtest123', 'registration_form[agreeTerms]' => '1', 'magicInput' => 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'])->selectButton('Création du compte');

        $client->request('GET', '/login');

        // Remplissage du formulaire
        $crawler = $client->submitForm("Connexion >" , [
            'AdresseMailCOmpte' => 'test@test.com',
            'password' => 'Notthemdp'
        ]);

        // Confirmation
        $crawler->selectButton('Connexion >');

        $this->assertResponseRedirects('/login', null, "La connexion d'un compte invalide a réussi");
    }
}
