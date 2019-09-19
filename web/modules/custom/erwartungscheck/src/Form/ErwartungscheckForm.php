<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 12.04.19
 * Time: 18:47
 */

namespace Drupal\erwartungscheck\Form;

use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\erwartungscheck\Data;
use Drupal\erwartungscheck\Helper\ErwartungscheckHelper;

class ErwartungscheckForm extends FormBase {

    protected $step = 0;
    protected $question_count;
    protected $correct_answer_flag;
    protected $correct_answer = 0;
    protected $targetId;
    protected $check;

    public function getFormId() {
      return 'erwartungscheck';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $targetId = $form_state->getBuildInfo()['args'][0];
        $this->targetId = $form_state->getBuildInfo()['args'][0];

        $check = $form_state->getBuildInfo()['args'][1];
        $this->check = $form_state->getBuildInfo()['args'][1];

        //kint($check);

        //Hole die Fragen
        $questions = $this->getQuestions($targetId);
        //Speichere die Anzahl der Fragen
        $this->question_count = count($questions);

        $form['erwartungscheck']['gruppe'] = [
          '#type' => 'markup',
          '#markup' => '<h2>' . $questions[$this->step]->gruppe. '</h2>',
        ];

        $form['erwartungscheck']['frage'] = [
          '#type' => 'radios',
          '#title' => $questions[$this->step]->aussage,
          '#options' => ['wahr' => 'wahr', 'falsch' => 'falsch'],
          //'#required' => TRUE,
          '#default_value' => 'wahr',
        ];

        $form['erwartungscheck']['button'] = [
          '#type' => 'button',
          '#value' => 'Beantworten',
          '#id' => 'button_beantworten',
          '#ajax' => [
            'callback' => '::setExplanationMessage',
          ],
        ];

        $form['erwartungscheck']['explanation'] = [
          '#type' => 'markup',
          '#markup' => '<div class="explanation_message"></div>',
        ];

        $form['erwartungscheck']['submit'] = [
          '#id' => 'button_submit',
          '#type' => 'submit',
          '#value' => $this->t('Next'),
        ];
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $questions = $this->getQuestions($this->targetId);

        if ($questions[$this->step]->richtige_antwort === $form_state->getValue('frage')) {
            $this->correct_answer++;
        }

        if ($this->step === ($this->question_count-1)) {

            $percent = round($this->correct_answer / $this->question_count, 2)*100;

            $url = \Drupal\Core\Url::fromRoute('erwartungscheck.info')->setRouteParameters(['percent'=> $percent, 'check'=> 'Wirtschaftsinformatik']);
            $form_state->setRedirectUrl($url);

        } else {
            $form_state->setRebuild();
            $this->step++;
        }
    }

    public function setExplanationMessage(array $form, FormStateInterface $form_state) {

        $targetId = $form_state->getBuildInfo()['args'][0];

        $questions = $this->getQuestions($targetId);

        if ($questions[$this->step]->richtige_antwort == $form_state->getValue('frage')) {
            $explanation_header = '<div style="background-color: #3c763d;" class="explanation-header"><h3>Ihre Antwort ist richtig.</h3></div>';
            $this->correct_answer_flag = true;

            $correct_answers = $this->correct_answer;
        } else {
            $explanation_header = '<div style="background-color: #a94442;" class="explanation-header"><h3>Ihre Antwort ist falsch.</h3></div>';
        }

        $explanation = $questions[$this->step]->rueckmeldung;
        $explanation_button = "<input data-drupal-selector='edit-submit' type='submit' id='edit-submit' name= 'op' value='Weiter' class='button js-form-submit form-submit'>";

        $response = new AjaxResponse();
        $response->addCommand(
            new HtmlCommand('.explanation_message',
                '<div class="explanation-msg" style="margin-top: -50px; z-index: 100; transition: margin-top 1s; background-color: #ebebed; padding: 0 10px 10px; ">'
                . $explanation_header .$explanation . $explanation_button . '</div>'
            )
        );
        $response->addCommand(new CssCommand('#button_beantworten', ['visibility' => 'hidden']));

        return $response;
    }

    public function getQuestions($targetId) {

        $erwartungscheckHelper = new ErwartungscheckHelper();
        $erwartungscheckAussagen = $erwartungscheckHelper->getErwartungcheck($targetId);

        foreach ($erwartungscheckAussagen as $data) {
            $erwartungscheckData = new Data\ErwartungscheckData();
            $erwartungscheckData->setAussage($data['aussage']);
            $erwartungscheckData->setGruppe($data['gruppe']); //TODO Change to term reference
            $erwartungscheckData->setRichtigeAntwort($data['richtige_antwort']);
            $erwartungscheckData->setRueckmeldung($data['rueckmeldung']);

            $questions[] = $erwartungscheckData;
        }
        return $questions;
    }

    public function setCorrectFlag(): void {
        $this->correct_answer_flag = true;
    }
}
