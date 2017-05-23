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
    public function getNotDone($user,$difficulty=null){
        // Exception Doctrine\ORM\NoResultException
        /*$qb = $this->_em->createQueryBuilder()  
            ->select('e')
            ->from('ExerciseBundle:Exercise','e')          
            ->setMaxResults(1);
        */
        


         $qb = $this->_em->createQueryBuilder()  
            ->select('e')
            ->addSelect('(CASE WHEN (ed.user IS NULL) THEN 0 ELSE COUNT(e.id) END) AS nbExerciseDone')            
            ->addSelect('(CASE WHEN e.level = ?2 THEN 1 ELSE 0 END) AS HIDDEN ordCol')
            ->from('ExerciseBundle:Exercise','e')
            ->leftjoin('ExerciseBundle:ExerciseDone','eed','WITH','eed.exercise=e.id')
            ->leftjoin('AppBundle:ExerciseDone','ed','WITH','ed.user=?1')            
            ->groupBy('e.id')
            ->orderBy('nbExerciseDone')
            ->setParameter(1,$user)
            ->addOrderBy('ordCol','DESC')
            ->setParameter(2,$difficulty)
            
            ;
         $result = $qb->getQuery()->getSingleResult();        
         return $result[0];       
        

    }

    public function getLastDone($limit=10){

        $qb = $this->createQueryBuilder('eed')
            ->select('e.id, e.title, u.id, u.username,ed.date')
            ->innerJoin('ExerciseBundle:Exercise','e','WITH','eed.exercise = e.id')
            ->innerJoin('AppBundle:ExerciseDone','ed','WITH','eed.exerciseDone = ed.id')
            ->innerJoin('SchoolBundle:User','u','WITH','ed.user=u.id')
            ->orderBy('e.id')
            ->setMaxResults($limit)
            //->where('role=""');
            ;
        $result = $qb
            ->getQuery()
            ->getArrayResult();
        return $result;

    }

    public function getStatsForUser($user=1){
        $qb = $this->createQueryBuilder('eed')
            ->select('ed.id')            
            ->addSelect('e.level')
            ->addselect('COUNT(ed.id) as nb_exercises_done')    
            ->addSelect('(CASE WHEN eed.qui > 1 THEN 1 ELSE 0 END) AS err_qui')   
            //->addSelect('(CASE WHEN eed.quand > 1 THEN 1 ELSE 0 END) AS err_quand')  
            //->addSelect('(CASE WHEN eed.ou > 1 THEN 1 ELSE 0 END) AS err_ou')  
            ->addSelect('SUM(eed.qui) AS nb_err_qui') 
            ->addSelect('SUM(eed.quand) AS nb_err_quand')
            ->addSelect('SUM(eed.ou) AS nb_err_ou')   
            ->innerjoin('AppBundle:ExerciseDone','ed','WITH','eed.exerciseDone=ed.id')   
            ->innerJoin('ExerciseBundle:Exercise','e','WITH','e.id = eed.exercise')           
            ->where('ed.user=:user')
            ->setParameter('user',$user)         
            ->groupBy('e.level')
            ->orderBy('e.level')
            // analyse sur les 20 derniers exercices effectués
            ->setMaxResults(20); 

        $results = $qb
            ->getQuery()
            ->getArrayResult();
        
        return $results;
    }
}
