<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 10.01.19
 * Time: 12:47
 */

namespace Drupal\assessment\Form\ExpectationAssessment;


use Drupal\assessment\Data\AssessmentQuestion;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;

class ExpectationAssessmentForm extends FormBase
{
    private $assessmentQuestion;

    public static function create(ContainerInterface $container)
    {
        $assessmentQuestion = $container->get('assessment_data');
        return new static($assessmentQuestion);
    }

    public function __construct(AssessmentQuestion $assessmentQuestion) {
        $this->assessmentQuestion = $assessmentQuestion;
    }



    public function getFormId()
    {
        // TODO: Implement getFormId() method.
        return 'expectation_assessment';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        //$this->assessmentQuestion->setQuestion('Warum?');
        $test = ['gar nicht zutreffend', 'weniger zutreffend', 'neutral', 'eher zutreffend', 'sehr zutreffend'];
        $questions = $this->assessmentQuestion->getQuestions();
        $answers_op = $this->assessmentQuestion->getAnswerOpportunities($questions[0]["id"]);


        $i = 0;
        foreach ($questions as $question) {
            $form['expaction_assessment_' . $i]["question"] = [
                '#type' => 'item',
                '#title' => $question["title"],
                //'#options' => $this->assessmentQuestion->getAnswerOpportunities($question["id"]),
            ];
            $answer_oportunities = $this->assessmentQuestion->getAnswerOpportunities($question["id"]);
            if(sizeof($answer_oportunities) > 2) {
                $j=0;
                foreach ($answer_oportunities as $answer_oportunity) {
                    $form['expaction_assessment_' . $i]["answer_oportunity_" . $j] = [
                        '#type' => 'radios',
                        '#title' => $this->t($answer_oportunity),
                        '#options' => $test,
                    ];
                    $j++;
                }
                $i++;

            } else {
                $form['expaction_assessment_' . $i]["answer_oportunity"] =[
                    '#type' => 'radios',
                    //'#title' => 'ddjdjd',
                    '#options' => $answer_oportunities,
                ];
                $i++;

            }
        }

        $form['expaction_assessment']['#attached']['library'][] = 'assessment/assessment';

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;


    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        // TODO: Implement submitForm() method.
    }
}