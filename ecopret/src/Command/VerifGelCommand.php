<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:verif-gel',
    description: 'Vérifie tous les utilisateurs qui ont une date de gel pour vérifier s il faut les geler ou dégeler',
)]
class VerifGelCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->entityManager = $em;
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->entityManager;
        $utilisateurs = $em->getRepository(Utilisateur::class)->createQueryBuilder('u')
            ->where('u.date_deb_gel IS NOT NULL')
            ->getQuery()
            ->getResult();
        $today = new \DateTime();
        foreach($utilisateurs as $user){
            $dateDeb = clone $user->getDateDebGel();
            $dateFin = clone $user->getDateFinGel();
            if($dateDeb->format('Y-m-d') === $today->format('Y-m-d') && !$user->isEstGele()){
                $user->setEstGele(true);
                $em->persist($user);
            }
            if($dateFin->format('Y-m-d') === $today->format('Y-m-d') && $user->isEstGele()){
                $user->setEstGele(false);
                $user->setDateDebGel(null);
                $user->setDateFinGel(null);
                $em->persist($user);
            }
        }
        $em->flush();
        $output->writeln('Verification des gel/degel effectuée.');
        return Command::SUCCESS;
    }
}
