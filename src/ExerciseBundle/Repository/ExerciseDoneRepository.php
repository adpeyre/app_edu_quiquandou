<?php

namespace ExerciseBundle\Repository;

/**
 * ExerciseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExerciseDoneRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNotDone($user){
        // Exception Doctrine\ORM\NoResultException
        /*$qb = $this->_em->createQueryBuilder()  
            ->select('e')
            ->from('ExerciseBundle:Exercise','e')          
            ->setMaxResults(1);
        */
        


         $qb = $this->_em->createQueryBuilder()  
            ->select('e')
            ->addSelect('(CASE WHEN (ed.user IS NULL) THEN 0 ELSE COUNT(e.id) END) AS nbExerciseDone')
            ->from('ExerciseBundle:Exercise','e')
            ->leftjoin('ExerciseBundle:ExerciseDone','eed','WITH','eed.exercise=e.id')
            ->leftjoin('AppBundle:ExerciseDone','ed','WITH','ed.user=?1')            
            ->groupBy('e.id')
            ->orderBy('nbExerciseDone')
            ->setParameter(1,$user)
            ;
         $result = $qb->getQuery()->getSingleResult();        
         return $result[0];

        
        

    }
}
