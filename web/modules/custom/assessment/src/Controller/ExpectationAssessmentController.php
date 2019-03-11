<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 11.01.19
 * Time: 17:55
 */

namespace Drupal\assessment\Controller;


use Drupal\assessment\Data\AssessmentQuestion;
use Drupal\Core\Controller\ControllerBase;

class ExpectationAssessmentController extends ControllerBase
{

    public function content() {
        $assessmentQuestion = new AssessmentQuestion();

        $assessmentQuestion->setQuestion('Warum möchten Sie das OSA durchführen');
        $assessmentQuestion->setAnswerOpportunities(array('Antwort A', 'Antwort B', 'Antwort C'));


        return [
            '#type' => 'radios',
            '#title' => $this->t('<p>' . $assessmentQuestion->getQuestion() . '</p>'),
            '#options' => $assessmentQuestion->getAnswerOpportunities(),
        ];
    }
}