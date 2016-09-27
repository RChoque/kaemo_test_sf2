<?php
namespace KaemoTestBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
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
        $this->setDefinition(
                new InputDefinition(array(
                    new InputOption('title', 't', InputOption::VALUE_REQUIRED),
                    new InputOption('date', 'd', InputOption::VALUE_REQUIRED),
                    new InputOption('real', 'r', InputOption::VALUE_REQUIRED),
                ))
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $message = "Création d'un nouveau film";
        $this->logger->info($message);
        $output->writeln($message);
        
        if(!$input->getOption('title')){
            $question_title = new Question("Titre du film : ", "Avatar");
            $title = $helper->ask($input, $output, $question_title);
        }else{
            $title = $input->getOption('title');
        }

        if(!$input->getOption('date')){
            $question_date = new Question("Date de sortie : ", "18/12/2009");
            $date = \DateTime::createFromFormat('d/m/Y', $helper->ask($input, $output, $question_date)); 
            $date->modify('midnight');  
        }else{
            $date = \DateTime::createFromFormat('d/m/Y', $input->getOption('date')); 
            $date->modify('midnight');
        }

        if(!$input->getOption('real')){
            $question_realisator = new Question("Nom du réalisateur : ", "James Cameron");
            $realisator = $helper->ask($input, $output, $question_realisator);    
        }else{
            $realisator = $input->getOption('real');
        }
        
        $movieRepository = $this->em->getRepository('KaemoTestBundle:Movie');
        $movie = $movieRepository->findOneBy(array('title' => $title));
        if($movie==null){
            $movie = new Movie();
            $movie->setTitle($title);
            $movie->setDate($date);
            $movie->setRealisator($realisator);

            $this->em->persist($movie);

            $this->em->flush();
            $message = $movie->getTitle().' a été ajouté à la base de données';
            $this->logger->info($message);
            $output->writeln($message);
        }else{
            $message = 'Le film '.$title.' existe déjà.';
            $this->logger->info($message);
            $output->writeln('<error>'.$message.'</error>');
        }
    }
}