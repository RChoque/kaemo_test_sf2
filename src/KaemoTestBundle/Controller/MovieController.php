<?php

namespace KaemoTestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use KaemoTestBundle\Entity\SingleMovieResponse;
use KaemoTestBundle\Entity\MoviesListResponse;

class MovieController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  section="Movie",
     *  description="Get a list of movies' details",
     *  https=false,
     *  requirements={
     *      {"name"="realisator", "dataType"="string", "required"=false, "description"="Realisator"},
     *      {"name"="from", "dataType"="DateTime", "required"=false, "description"="From date"},
     *      {"name"="to", "dataType"="DateTime", "required"=false, "description"="To date"},
     *  },
     *  output="KaemoTestBundle\Entity\MoviesListResponse",
     *  statusCodes={
     *      200="Returned when successful",
     *  }
     * )
     */
    public function listMoviesAction(Request $request, $_format)
    {
        $em = $this->getDoctrine()->getManager();
        $params = array();

        if($request->get('realisator')){
            $params['realisator'] = $request->get('realisator');
        }
        if($request->get('from')){
            $date = \DateTime::createFromFormat('Ymd', $request->get('from')); 
            $params['from'] = $date->format('Y-m-d H:i:s');
        }
        if($request->get('to')){
            $date = \DateTime::createFromFormat('Ymd', $request->get('to')); 
            $params['to'] = $date->format('Y-m-d H:i:s');
        }

        $movies = $em->getRepository('KaemoTestBundle:Movie')->search($params);
        $data = new MoviesListResponse();
        $data->setVideos($movies);
        $serialized_data = $this->container->get('serializer')->serialize($data, $_format);
        $response = new Response();
        $response->headers->set('Content-type', 'application/'.$_format);
        $response->setContent($serialized_data);
        return $response;
    }

    /**
     * @ApiDoc(
     *  section="Movie",
     *  description="Show a movie's details",
     *  https=false,
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "required"=true, "description"="Movie ID"},
     *  },
     *  output="KaemoTestBundle\Entity\SingleMovieResponse",
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the job is not found"
     *  }
     * )
     */
    public function movieAction($id, $_format)
    {
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        $movie = $em->getRepository('KaemoTestBundle:Movie')->find($id);
        if($movie  != null){
            $data = new SingleMovieResponse();
            $data->setVideo($movie);
            $serialized_data = $this->container->get('serializer')->serialize($data, $_format);
            $response->headers->set('Content-type', 'application/'.$_format);
            $response->setContent($serialized_data);  
        }else{
            $message = "Movie not found";
            $response->setStatusCode(404, $message);
            $status = 0;
        }   
        return $response;
    }
}
