<?php
namespace KaemoTestBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use KaemoTestBundle\Entity\Movie;

class CreateMovieCommand extends Command
{

    private $em;

    private $logger;

    public function __construct($doctrine, $logger)
    {
        $this->em = $doctrine->getManager();
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('kaemo:movie:create');
        $this->setDescription('Ajoute un nouveau film');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $message = "Création d'un nouveau film";
        $this->logger->info($message);
        $output->writeln($message);
        
        $question_title = new Question("Titre du film : ", "Avatar");
        $question_date = new Question("Date de sortie : ", "18/12/2009");
        $question_realisator = new Question("Nom du réalisateur : ", "James Cameron");

        $title = $helper->ask($input, $output, $question_title);        
        $date = \DateTime::createFromFormat('d/m/Y', $helper->ask($input, $output, $question_date)); 
        $date->setTime(0,0,0);   
        $realisator = $helper->ask($input, $output, $question_realisator);        

        $movieRepository = $this->em->getRepository('KaemoTestBundle:Movie');

        $movie = new Movie();
        $movie->setTitle($title);
        $movie->setDate($date);
        $movie->setRealisator($realisator);

        $this->em->persist($movie);

        $this->em->flush();
        $message = $movie->getTitle().' a été ajouté à la base de données';
        $this->logger->info($message);
        $output->writeln($message);
    }
}