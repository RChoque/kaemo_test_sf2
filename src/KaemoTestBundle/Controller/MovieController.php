<?php

namespace KaemoTestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class MovieController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  description="Get list of movies",
     *  input="Your\Namespace\Form\Type\YourType",
     *  output="Your\Namespace\Class"
     * )
     */
    public function listMoviesAction($_format)
    {
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository('KaemoTestBundle:Movie')->findAll();

        $data = array('videos' => $movies, "count" => count($movies));
        $serialized_data = $this->container->get('serializer')->serialize($data, $_format);
        $response = new Response();
        $response->headers->set('Content-type', 'application/'.$_format);
        $response->setContent($serialized_data);
        return $response;
    }

    public function movieAction($id, $_format)
    {
        $em = $this->getDoctrine()->getManager();
        $movie = $em->getRepository('KaemoTestBundle:Movie')->find($id);
        $data = array('video' => $movie);
        $serialized_data = $this->container->get('serializer')->serialize($data, $_format);
        $response = new Response();
        $response->headers->set('Content-type', 'application/'.$_format);
        $response->setContent($serialized_data);
        return $response;
    }
}
