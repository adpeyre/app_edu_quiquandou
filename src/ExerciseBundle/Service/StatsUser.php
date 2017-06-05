<?php

namespace ExerciseBundle\Service;

use Doctrine\ORM\EntityManager;
use ExerciseBundle\Entity\Exercise;

class StatsUser
{

    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }


    public function getSummary($user, $lines=100){
        $results = $this->em->getRepository('ExerciseBundle:ExerciseDone')->getStatsForUser($user, $lines);
        
        $levelAvailable = Exercise::getLevelsAvailable();

        $global_nbDone=0;
        $global_qui=0;
        $global_quand=0;
        $global_ou=0;
        $global_score=0;
        $global_successful=0;

        //print_r($results);


        //foreach($results as $r){
        foreach($levelAvailable as $l){

            $r_key = array_search($l, array_column($results, 'level'));
           
            
            $data = array();

            // Des résultats sur ce niveau d'exercice
            if($r_key !== false){
                
                $r = $results[$r_key];
               
                $nb_exercises_done = $r['nb_exercises_done'];
                $score_qui = $this->getPercentageSuccess($r['nb_err_qui'],$r['nb_exercises_done']);
                $score_quand = $this->getPercentageSuccess($r['nb_err_quand'],$r['nb_exercises_done']);
                $score_ou = $this->getPercentageSuccess($r['nb_err_ou'],$r['nb_exercises_done']);
                $score_global = intval(($score_qui + $score_quand + $score_ou) * 100 / 300);
                $nb_successful = $r['nb_successful'];
            }

            // Pas de résultats, on met tout à 0
            else{
                $nb_exercises_done = 0;
                $score_qui = 0;
                $score_quand = 0;
                $score_ou = 0;
                $score_global = 0;
                $nb_successful = 0;
            }

            $data = array(
                'nb_done' => $nb_exercises_done,
                'score_qui' => $score_qui,
                'score_quand' => $score_quand,
                'score_ou' => $score_ou,
                'score_global' =>   $score_global,
                'nb_successful' => $nb_successful
            );
           

            $resume[ $l ] = $data;
          
            $global_nbDone+=$nb_exercises_done;     

            
            $global_qui += $score_qui;
            $global_quand += $score_quand;
            $global_ou += $score_ou;

            $global_score+= $score_global/count($levelAvailable);
            $global_successful+=$nb_successful;

            

        }
        
       
        // Score global (en pourcentage) qui prend en compte la difficulté de l'exercice
        $resume['global'] = array(
            'nb_done' => $global_nbDone,
            'score_qui' =>ceil($global_qui / count($levelAvailable)),
            'score_quand' =>ceil($global_quand / count($levelAvailable)),
            'score_ou' =>ceil($global_ou / count($levelAvailable)),
            'score_global' => ceil($global_score),
            'nb_successful'=>$global_successful
            
        );
       

        return $resume;
    }

    private function getPercentageSuccess($errs,$done){
        return intval(100 - ($errs / $done * 100));
    }
    public function getLevelAppropriate($user, $date_begining=null){
        $data = $this->getSummary($user, $date_begining);

        $diff = array(
            1 => 'easy',
            2 => 'medium',
            3 => 'hard'
        );

        // Si pas fait au moins un nombre d'exercices précis, on prend le niveau le plus facile
        
        if($data['global']['nb_done'] < 3)
            return 1; 

        foreach(Exercise::getLevelsAvailable() as $l){
            // Moins de 3 faits pour ce niveau, on renvoie ce niveau
            //echo $l."-".$data[$l]['score_global']."<br />";
            if($data[$l]['nb_done'] <= 3)
                return $l;
            elseif($data[$l]['score_global'] < 85)
                return $l;
        }

        // Sinon, on revoie le plus dur
        return 3;


        //return rand(1,3);
    }
}