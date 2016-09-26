<?php

namespace KaemoTestBundle\Entity;

use JMS\Serializer\Annotation\Type;

class MoviesListResponse
{
    /**
     * @Type("array<KaemoTestBundle\Entity\Movie>")
     */
    public $videos;

    /**
     * @Type("integer")
     */
    public $count;

    public function setVideos($movies){
        $this->videos = $movies;
        $this->count = count($movies);
    }

    public function getVideos(){
        return $this->videos;
    }

    public function getCount(){
        return $this->count;
    }
}