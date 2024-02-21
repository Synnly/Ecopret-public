<?php

namespace App\Tests;

use Zenstruck\Foundry\Test\Factories;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class DegelTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function testPageAccessibleNonConnecte()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/degel');

        $this->assertResponseRedirects('/login');
    }

    public function testGelCompte(): void
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

        $client->request('GET', '/gel');

        $crawler = $client->submitForm("Enregistrer >", [
            'gel_compte_form[deb]' => '2024-02-21',
            'gel_compte_form[fin]' => '2024-02-28',
        ]);

        // Confirmation
        $crawler->selectButton('Enregistrer >');

        $this->assertResponseRedirects('/main', null, "Le gel d'un compte valide a échoué");

        $client->request('GET', '/degel');

        $crawler = $client->submitForm("Enregistrer >", [
            'degel_compte_form[retour]' => '1',
        ]);
        
        $this->assertResponseRedirects('/main', null, "Le degel d'un compte valide a échoué");
    }
}