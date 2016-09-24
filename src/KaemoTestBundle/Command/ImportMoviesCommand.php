<?php
namespace KaemoTestBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use KaemoTestBundle\Entity\Movie;

class ImportMoviesCommand extends Command
{

    private $em;

    private $xml_path;

    private $logger;

    public function __construct($doctrine, $xml_path, $logger)
    {
        $this->em = $doctrine->getManager();
        $this->xml_path = $xml_path;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('kaemo:movie:import');
        $this->setDescription('Importe les films');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $message = 'Lecture du fichier XML';
        $this->logger->info($message);
        $output->writeln($message);
        
        $xml = simplexml_load_file($this->xml_path);
        $movieRepository = $this->em->getRepository('KaemoTestBundle:Movie');
        $nb_new_movies = 0;
        foreach($xml->film as $movie_xml){
            $movie = $movieRepository->findOneBy(array('title' => $movie_xml->title));
            if($movie==null){
                $message = 'Création du film '.$movie_xml->title;
                $this->logger->info($message);
                $output->writeln($message);
                $movie = new Movie();
                $movie->setTitle($movie_xml->title);
                $date = \DateTime::createFromFormat('d/m/Y', $movie_xml->date); 
                $date->setTime(0,0,0);
                $movie->setDate($date);
                $movie->setRealisator($movie_xml->realisator);
                $this->em->persist($movie);
                $nb_new_movies++;
            }else{
                $message = 'Le film '.$movie_xml->title.' existe déjà.';
                $this->logger->info($message);
                $output->writeln('<error>'.$message.'</error>');
            }
        }

        $this->em->flush();
        $message = $nb_new_movies.' nouveaux films créés';
        $this->logger->info($message);
        $output->writeln($message);
    }
}