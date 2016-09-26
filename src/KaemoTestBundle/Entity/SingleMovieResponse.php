<?php

namespace KaemoTestBundle\Entity;

use JMS\Serializer\Annotation\Type;

class SingleMovieResponse
{
    /**
     * @Type("KaemoTestBundle\Entity\Movie")
     */
    public $video;

    public function setVideo($movie){
        $this->video = $movie;
    }

    public function getVideo(){
        return $this->video;
    }
}