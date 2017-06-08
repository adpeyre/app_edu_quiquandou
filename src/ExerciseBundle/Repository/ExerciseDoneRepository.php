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
        
           

        // Les exercices encore non faits

        $subQ = $this->createQueryBuilder('eed')
        ->select('IDENTITY(eed.exercise)')
        ->innerJoin('AppBundle:ExerciseDone', 'ed', 'WITH', 'eed.exerciseDone = ed.id')
        ->where('ed.user=:user')          
        ->groupBy('eed.exercise');
     
         $qb_notDone = $this->_em->createQueryBuilder();
            $qb_notDone
            ->select('e')
            ->from('ExerciseBundle:Exercise','e')
            ->where($qb_notDone->expr()->notIn('e.id', $subQ->getDQL()))
            ->andwhere('e.active=1')   
            ->andWhere('e.level=:level')   
            ->setParameter('user',$user)
            ->setParameter('level',$difficulty)  
            ->setMaxResults(1)     
            
            ;

        $results = ($qb_notDone->getQuery()->getResult());


        if(count($results) >= 1){
            return $results[0];
        }


        // Les moins faits sinon

        $qb_lessDone = $this->_em->createQueryBuilder()  
            ->select('e')
            ->addSelect('(CASE WHEN (ed.user IS NULL) THEN 0 ELSE COUNT(e.id) END) AS HIDDEN nbExerciseDone')           
            ->from('ExerciseBundle:Exercise','e')                          
            ->leftjoin('ExerciseBundle:ExerciseDone','eed','WITH','eed.exercise=e.id')
            ->innerJoin('AppBundle:ExerciseDone','ed','WITH','eed.exerciseDone = ed.id')                  
            ->where('e.active=1')   
            ->andWhere('e.level=:level')     
            ->andWhere('ed.user=:user')       
            ->groupBy('e.id')
            ->addOrderBy('nbExerciseDone')
            ->setParameter('user',$user)            
            ->setParameter('level',$difficulty)
            ->setMaxResults(1);
        
         $query = $qb_lessDone->getQuery();
         $result = $query->getSingleResult();        
         return $result; 
        

         /*$qb = $this->_em->createQueryBuilder()  
            ->select('e.id')
            //->addSelect('(CASE WHEN (ed.user IS NULL) THEN 0 ELSE COUNT(e.id) END) AS nbExerciseDone')            
            //->addSelect('(CASE WHEN e.level = ?2 THEN 1 ELSE 0 END) AS HIDDEN ordCol')
            ->from('ExerciseBundle:Exercise','e')                          
            ->leftjoin('ExerciseBundle:ExerciseDone','eed','WITH','eed.exercise=e.id')
            ->leftJoin('AppBundle:ExerciseDone','ed','WITH','eed.exerciseDone = ed.id AND ed.user=?1')            
                   
            ->where('e.active=1')   
            //->andWhere('e.level=?2')            
            //->groupBy('e.id')            
            //->orderBy('ordCol','DESC')
            //->addOrderBy('nbExerciseDone')
            ->setParameter(1,$user)            
            //->setParameter(2,$difficulty)
            //->setMaxResults(1)
            // Temporaire pour en choisir un au hasard (Rand())
            //->setFirstResult(rand(0,$nb_exercices-1))
            
            ;
            */
        

         
        

    }

    public function getLastDone($limit,$user){

        $qb = $this->createQueryBuilder('eed')
            ->select('e.title, u.username, u.firstname, u.lastname,ed.date, eed.err_qui , eed.err_quand , eed.err_ou ')
            ->addSelect('e.id AS exercise_id')
            ->addSelect('u.id as user_id')
            ->innerJoin('ExerciseBundle:Exercise','e','WITH','eed.exercise = e.id')
            ->innerJoin('AppBundle:ExerciseDone','ed','WITH','eed.exerciseDone = ed.id')
            ->innerJoin('SchoolBundle:User','u','WITH','ed.user=u.id')
            ->orderBy('ed.date','DESC')
            ->setMaxResults($limit)
            //->where('role=""');
            ;

            if(!is_null($user)){
                $qb->where('u=?1')
                ->setParameter(1,$user);
            }
        $result = $qb
            ->getQuery()
            ->getArrayResult();
        return $result;

    }

    public function getStatsForUser($user=1, $date=null){
        
        $qb = $this->createQueryBuilder('eed')
            //->select('ed.id')            
            ->select('e.level')
            ->addselect('COUNT(ed) as nb_exercises_done')    
            //->addSelect('(CASE WHEN eed.qui > 1 THEN 1 ELSE 0 END) AS err_qui')   
            //->addSelect('(CASE WHEN eed.quand > 1 THEN 1 ELSE 0 END) AS err_quand')  
            
            ->addSelect('SUM(  (CASE WHEN eed.err_qui = 0 AND eed.err_quand = 0 AND eed.err_ou = 0 THEN 1 ELSE 0 END)  ) AS nb_successful')
            ->addSelect('SUM(eed.err_qui) AS nb_err_qui') 
            ->addSelect('SUM(eed.err_quand) AS nb_err_quand')
            ->addSelect('SUM(eed.err_ou) AS nb_err_ou')   
            ->innerjoin('AppBundle:ExerciseDone','ed','WITH','eed.exerciseDone=ed.id')   
            ->innerJoin('ExerciseBundle:Exercise','e','WITH','e.id = eed.exercise')           
            ->where('ed.user=:user')
            ->setParameter('user',$user)
            ->groupBy('e.level')
            ->orderBy('e.level')
            // analyse sur les 20 derniers exercices effectués
           
            ;

            if(!is_null($date)){
                $qb->andWhere('ed.date > :date')
                    ->setParameter('date',$date);
            }
            

        $results = $qb
            ->getQuery()
            ->getArrayResult();
        
        return $results;
    }
}
