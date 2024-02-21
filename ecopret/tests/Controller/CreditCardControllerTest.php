<?php

namespace App\Tests\Controller;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class CreditCardControllerTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function testPaiementAbonnementCarteValide(): void
    {
        $client = static::createClient();
        // Deplacement vers la page de creation de compte
        $client->request('GET', '/register');

        // Remplissage du formulaire puis clic sur le bouton de creation
        $client->submitForm("Création du compte", ['registration_form[NomCompte]' => 'TEST', 'registration_form[PrenomCompte]' => 'Test', 'registration_form[AdresseMailCOmpte]' => 'test@test.com', 'registration_form[plainPassword]' => 'Testtest123', 'registration_form[agreeTerms]' => '1', 'magicInput' => 'KGsTNQxeeiVoakoZSGNKGVXkhZCxWu'])->selectButton('Création du compte');

        // Deplacement vers la page de connexion
        $client->request('GET', '/login');

        // Remplissage du formulaire
        $crawler = $client->submitForm("Connexion >", ['AdresseMailCOmpte' => 'test@test.com', 'password' => 'Testtest123']);

        // Confirmation
        $crawler->selectButton('Connexion >');

        $client->request('GET', '/infos/modif');
        $crawler = $client->submitForm("Modifier", [
            'modifier_informations_personnelles_form[NomCompte]' => 'TEST',
            'modifier_informations_personnelles_form[PrenomCompte]' => 'Test',
            'modifier_informations_personnelles_form[carte_credit][nom_carte]' => 'TEST Test',
            'modifier_informations_personnelles_form[AdresseMailCOmpte]' => "test@test.com",
            'modifier_informations_personnelles_form[motDePasseCompte]' => "Testtest123",
            'modifier_informations_personnelles_form[carte_credit][numero_carte]' => 2222400030000004,
            'modifier_informations_personnelles_form[carte_credit][date_expiration]' => '03/2030',
            'modifier_informations_personnelles_form[carte_credit][code_cvv]' => 999,
        ]);

        $crawler->selectButton('Modifier');

        $client->request('GET', '/payment_information');

        $crawler->selectButton('Payer');
        static::bootKernel();
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['noCompte' => $entityManager->getRepository(Compte::class)->findOneBy(['AdresseMailCOmpte' => 'test@test.com'])]);

        assertTrue($user->isPaiement(), 'Le test n\'a pas modifié la valeur de paiement dans Utilisateur');
        assertTrue($user->getDatePaiement()==new \DateTime('today'), 'Le test n\'a pas modifié la valeur de paiement dans Utilisateur');
    }

}
