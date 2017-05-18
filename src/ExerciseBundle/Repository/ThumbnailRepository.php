<?php

namespace ExerciseBundle\Repository;

use ExerciseBundle\Entity\Thumbnail;

/**
 * ThumbnailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ThumbnailRepository extends \Doctrine\ORM\EntityRepository
{
    public function getQui(){
        return $this->createQueryBuilder('Thumbnail')->where('Thumbnail.type=1');
    }
    public function getQuand(){
        return $this->createQueryBuilder('Thumbnail')->where('Thumbnail.type=2');
    }
    public function getOu(){
        return $this->createQueryBuilder('Thumbnail')->where('Thumbnail.type=3');
    }

    public function getRandom($type,$nb,$exclude=array()){
        // RAND n'existe pas sur Doctrine. Obligé de le simuler
        $qb= $this->createQueryBuilder('t')
            ->where('t.type=:type AND t.id != :id')
            ->setParameter('type',$type)                 
            ->setParameter('id',$exclude[0]->getId());         
            ;
        $results = $qb->getQuery()->getResult();

        if(count($results) <= 0 )
            return array();
       
          
        $keys = array_rand($results,count($results));
        if(!is_array($keys))
            $keys = array($keys);

        $return = array();

        //for($i=count($keys);$i<$nb; $i++){  
        foreach($keys as $key){                        
            $return[ $results[$key]->getId()  ] = $results[$key];            
        }
        // }

        return $return;

        
    
    }
}
