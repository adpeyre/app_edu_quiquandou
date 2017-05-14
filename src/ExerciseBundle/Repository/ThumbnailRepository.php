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
            ->where('t.type=:type')
            ->setParameter('type',$type)
            //->andWhere('t.id!=:id')            
            //->setParameter('id',$exclude[0]->getId());         
            ;
        $results = $qb->getQuery()->getResult();

        
       
        $keys=array();
        if(count($results) >= 1 )
             $keys = array_rand($results,count($results));
        if(!is_array($keys))
            $keys = array($keys);

        $return = array();

        foreach($keys as $key){
            for($i=count($keys);$i<$nb; $i++){                
                $return[] = $results[$key];
            }
        }

        return $return;

        
    
    }
}
