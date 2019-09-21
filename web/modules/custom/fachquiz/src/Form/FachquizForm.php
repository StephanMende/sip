<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 26.03.19
 * Time: 19:27
 */

namespace Drupal\fachquiz\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\fachquiz\Data\FachquizData;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\fachquiz\Helper\FachquizHelper;

class FachquizForm extends FormBase {

  protected $step = 0;
  protected $aufgaben_count;
  protected $correct_answer_flag;
  protected $correct_answer = 0;
  protected $targetId;
  protected $codeFlag;    // soll Token generiert werden oder nicht?

  public function getFormId() {
    return 'fachquiz';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $fachquiz_nid = null, $codeFlag = null) {

    //$targetId = $form_state->getBuildInfo()['args'][0];
    $this->targetId = $fachquiz_nid;
    $this->codeFlag = $codeFlag;
    //Hole die Aufgaben
    $aufgaben = $this->getAufgaben($this->targetId);
    //Setze den Aufgaben Count, dies ist wichtig um eine Multi-Step-Form zu haben
    $this->aufgaben_count = count($aufgaben);

    //Erstelle die Form
    $form['fachquiz']['aufgabe'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $aufgaben[$this->step]->aufgabe . '</p>',
    ];

    $form['fachquiz']['bild'] = [
      '#type' => 'markup',
      '#markup' => '<img src="">',
    ];

    $form['fachquiz']['frage'] = [
      '#type' => 'markup',
      '#markup' => $aufgaben[$this->step]->frage,
    ];

    $form['fachquiz']['antwort'] = [
      '#type' => 'radios',
      '#options' => $this->randomizeAntworten($aufgaben[$this->step]->antwortoptionen),
      '#default_value' => 1,
    ];

    $form['fachquiz']['button'] = [
      '#type' => 'button',
      '#value' => 'Beantworten',
      '#id' => 'button_fachquiz_beantworten',
      '#ajax' => [
        'callback' => '::setRueckmeldungMessage',
      ],
    ];

    $form['fachquiz']['rueckmeldung'] = [
      '#type' => 'markup',
      '#markup' => '<div class="rueckmeldung"></div>',
    ];

    $form['fachquiz']['submit'] = [
      '#id' => 'button_submit',
      '#type' => 'submit',
      '#value' => $this->t('Next'),
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    //Erhoehe den correct_answer counter um 1, wenn eine richtige Antwort gegeben wurde
    if ($form_state->getValue('antwort') == 0) {
      $this->correct_answer++;
    }

    //Pruefe ob alle Fragen beantwortet wurden
    if($this->step == ($this->aufgaben_count-1)) {
      //Berechne den Prozentwert
      $percent = round($this->correct_answer/$this->aufgaben_count,2) * 100;
      //TODO: Gehe zur URL die den Auswertungstext anzeigt und gebe die nid des Fachquizzes mit
      $url = \Drupal\Core\Url::fromRoute('fachquiz.auswertung')->setRouteParameters(['percent' => $percent, 'codeFlag' => $this->codeFlag]);
      $form_state->setRedirectUrl($url);
    } else {
      $form_state->setRebuild();

      $this->step++;
    }
  }

  public function setRueckmeldungMessage(array $form, FormStateInterface $form_state) {
    $aufgaben = $this->getAufgaben($this->targetId);
    $step = $this->step;

    if($form_state->getValue('antwort') == 0) {
      $this->correct_answer_flag = true;
      $rueckmeldung_header = '<div style="background-color: #3c763d;" class="explanation-header"><h3>Ihre Antwort ist richtig.</h3></div>';
    } else {
      $rueckmeldung_header = '<div style="background-color: #a94442;" class="explanation-header"><h3>Ihre Antwort ist falsch.</h3></div>';
    }

    $rueckmeldung = $aufgaben[$this->step]->erklaerung;
    $rueckmeldung_button = "<input data-drupal-selector='edit-submit' type='submit' id='edit-submit' name= 'op' value='Weiter' class='button js-form-submit form-submit'>";
   ;

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('.rueckmeldung', '<div class="explanation-msg" style="margin-top: -50px; z-index: 100; transition: margin-top 1s; background-color: #ebebed; padding: 0 10px 10px;">' . $rueckmeldung_header . $rueckmeldung . $rueckmeldung_button  . '</div>'));
    $response->addCommand(new CssCommand('#button_fachquiz_beantworten', ['visibility' => 'hidden']));

    return $response;
  }

  public function getAufgaben($targetId) {

    $fachquizHelper = new FachquizHelper();
    $fachquizAufgaben = $fachquizHelper->getFachquiz($targetId); //TODO: Parameter einbauen

    foreach ($fachquizAufgaben as $data) {
      $fachquizData = new FachquizData();
      $fachquizData->setAufgabe($data['aufgabe']);
      $fachquizData->setFrage($data['frage']);
      $fachquizData->setAntwortoptionen($data['antwortoptionen']);
      $fachquizData->setErklaerung($data['erklaerung']);

      $aufgaben[] = $fachquizData;
    }
    return $aufgaben;
  }

  public function randomizeAntworten(array $antworten) {
    $shuffled_array = array();
    $keys = array_keys($antworten);
    shuffle($keys);
    foreach ($keys as $key) {
      $shuffled_array[$key] = $antworten[$key];
    }
    return $shuffled_array;
  }
}
