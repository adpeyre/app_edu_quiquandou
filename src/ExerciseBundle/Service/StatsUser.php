<?php

namespace ExerciseBundle\Service;

use Doctrine\ORM\EntityManager;

class StatsUser
{

    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }


    public function getSummary($user){
        $results = $this->em->getRepository('ExerciseBundle:ExerciseDone')->getStatsForUser($user);

        $diff_name = array(
            1 => 'easy',
            2 => 'medium',
            3 => 'hard'
        );

        $add_with_coef = 0;
        $div_with_coef=1;
        $nbDoneGlobal = 0;

        foreach($results as $r){
            
            
            $data = array();
           

            $data = array(
                'nb_done' => $r['nb_exercises_done'],
                'score_qui' => $this->getPercentageSuccess($r['nb_err_qui'],$r['nb_exercises_done']),
                'score_quand' => $this->getPercentageSuccess($r['nb_err_quand'],$r['nb_exercises_done']),
                'score_ou' => $this->getPercentageSuccess($r['nb_err_ou'],$r['nb_exercises_done'])               


            );

            $data['score_global'] = intval(($data['score_qui'] + $data['score_quand'] + $data['score_ou']) * 100 / 300);

            $resume[  $diff_name[$r['level']]  ] = $data;

            $add_with_coef = $data['score_global'] * (4 - $r['level']);
            $div_with_coef = (4 - $r['level']);
            $nbDoneGlobal += $r['nb_exercises_done'];
            
        }

        // Score global (en pourcentage) qui prend en compte la difficulté de l'exercice
        $resume['global']['score_global'] = $add_with_coef / $div_with_coef ;
        $resume['global']['nb_done'] = $nbDoneGlobal;

        return $resume;
    }

    private function getPercentageSuccess($errs,$done){
        return intval(100 - ($errs / $done * 100));
    }
    public function getLevelAppropriate($user){
        $data = $this->getSummary($user);

        
        // Si pas fait au moins un nombre d'exercices précis, on prend le niveau le plus facile
        if($data['global']['nb_done'] < 5)
            return 1;

        return rand(1,3);
    }
}