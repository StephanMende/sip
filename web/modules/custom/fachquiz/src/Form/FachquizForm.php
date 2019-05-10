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

  public function getFormId() {
    return 'fachquiz';
  }


  public function buildForm(array $form, FormStateInterface $form_state) {
    $aufgaben = $this->getAufgaben();
    $this->aufgaben_count = count($aufgaben);


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
    // TODO: Implement submitForm() method.
    if($this->step == ($this->aufgaben_count-1)) {
      $url = \Drupal\Core\Url::fromRoute('fachquiz.auswertung');
      $form_state->setRedirectUrl($url);
    } else {
      $form_state->setRebuild();

      $this->step++;
    }

  }

  public function setRueckmeldungMessage(array $form, FormStateInterface $form_state) {
    $aufgaben = $this->getAufgaben();
    $step = $this->step;


    if($form_state->getValue('antwort') == 0) {
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

  public function getAufgaben() {

    $fachquizHelper = new FachquizHelper();
    $fachquizAufgaben = $fachquizHelper->getFachquiz();

    foreach ($fachquizAufgaben as $data) {
      $fachquizData = new FachquizData();
      $fachquizData->setAufgabe($data['aufgabe']);
      $fachquizData->setFrage($data['frage']);
      $fachquizData->setAntwortoptionen($data['antwortoptionen']);
      $fachquizData->setErklaerung($data['erklaerung']);

      $aufgaben[] = $fachquizData;
    }
    /**
    $fachquizData = new FachquizData();

    $fachquizData->setAufgabe('Ein Handwerker erhält für die Herstellung eines Stuhls 4 Euro. Im Rahmen einer 38-Stunden Woche fertigt er 323 Stühle.');
    $fachquizData->setFrage('Wie hoch ist sein Stundenlohn?');
    $fachquizData->setAntwortoptionen([0 => '34,00€/Stunde', 1 => '8,50€/Stunde', 2 => '9,50€/Stunde', 3 => '80,75€/Stunde']);
    $fachquizData->setErklaerung('Zur Berechnung ...');

    $aufgaben[] = $fachquizData;

    $fachquizData = new FachquizData();

    $fachquizData->setAufgabe('Ein Unternehmen befragt...');
    $fachquizData->setFrage('Welche der drei Handlungsalternativen sollte das Unternehmen wählen?');
    $fachquizData->setAntwortoptionen([0=>'Werbebudgeterhöhung', 1 => 'Preissenkung(P)', 2 => 'Sortimentserweiterung (S)']);
    $fachquizData->setErklaerung('Zur Berechnung ...');

    $aufgaben[] = $fachquizData;
    **/
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
