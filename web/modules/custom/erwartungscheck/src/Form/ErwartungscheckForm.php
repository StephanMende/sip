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

  public function getFormId() {
    return 'erwartungscheck';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    //Hole die Fragen
    $questions = $this->getQuestions();
    //Speichere die Anzahl der Fragen
    $this->question_count = count($questions);



    /**
    for($i=0; $i<count($questions); $i++) {
      if($this->step == $i) {
        $form['erwartungscheck']['gruppe'] = [
          '#type' => 'markup',
          '#markup' => '<h2>' . $questions[$i]->gruppe. '</h2>',
        ];

        $form['erwartungscheck']['frage'][$i] = [
          '#type' => 'radios',
          '#title' => $questions[$i]->aussage,
          '#options' => [1 => 'wahr', 0 => 'falsch'],
          //'#required' => TRUE,
          '#default_value' => 1,
        ];
      }
    }
    **/

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
    if($this->step == ($this->question_count-1)) {
      $url = \Drupal\Core\Url::fromRoute('erwartungscheck.info');
      $form_state->setRedirectUrl($url);

    } else {
      $form_state->setRebuild();
      $this->step++;

    }
  }

  public function setExplanationMessage(array $form, FormStateInterface $form_state) {

    $questions = $this->getQuestions();
    $step = $this->step;
    //dsm($questions[$step]);

    if($questions[$this->step]->richtige_antwort == $form_state->getValue('frage')) {
      $explanation_header = '<div style="background-color: green;"><h3>Ihre Antwort ist richtig.</h3></div>';
    } else {
      $explanation_header = '<div style="background-color: red;"><h3>Ihre Antwort ist falsch.</h3></div>';
    }

    /**
    $explanation = "<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. 
At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>" .
      "<input data-drupal-selector='edit-submit' type='submit' id='edit-submit' name= 'op' value='Weiter' class='button js-form-submit form-submit'>"
      ."Step: " . $this->step . " Count: " . $this->question_count . " Richtige Antwort: " . $questions[$this->step]->richtige_antwort .
      " Formularantwort: " . $form_state->getValue('frage');
     **/


    $explanation = $questions[$this->step]->rueckmeldung;
    $explanation_button = "<input data-drupal-selector='edit-submit' type='submit' id='edit-submit' name= 'op' value='Weiter' class='button js-form-submit form-submit'>";

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('.explanation_message', '<div style="border: dashed red; margin-top: -50px; z-index: 100; transition: margin-top 1s;">' . $explanation_header .$explanation . $explanation_button  . '</div>'));
    $response->addCommand(new CssCommand('#button_beantworten', ['visibility' => 'hidden']));


    return $response;
  }

  public function getQuestions() {

    $erwartungscheckHelper = new ErwartungscheckHelper();
    $erwartungscheckAussagen = $erwartungscheckHelper->getErwartungcheck();



    foreach ($erwartungscheckAussagen as $data) {
      $erwartungscheckData = new Data\ErwartungscheckData();
      $erwartungscheckData->setAussage($data['aussage']);
      $erwartungscheckData->setGruppe($data['gruppe']); //TODO Change to term reference
      $erwartungscheckData->setRichtigeAntwort($data['richtige_antwort']);
      $erwartungscheckData->setRueckmeldung($data['rueckmeldung']);

      $questions[] = $erwartungscheckData;
    }


    /**
    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Es ist einem Studium wichtig, selbst die Initiative zu ergreifen.');
    $erwartungscheckData->setGruppe('Vor dem Studium / allgemeines');
    $erwartungscheckData->setRichtigeAntwort('wahr');
    $erwartungscheckData->setRueckmeldung('Im Studium werden Sie als Student_in nicht für jeden Schritt aufgefordert oder
    angeleitet. Ein Studium bedeutet, dass man selbst die Initiative ergriffen werden muss, um die eigenen Ziele zu erreichen.
     Selbstständigkeit und Zuverlässigkeit sind dabei zwei wichtige Eigenschaften, die helfen, das Studium erflogreich abzuschließen.');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Das Studium "Wirtschaftsinformatik" erfordert ein hohes Maß an Selbstorganisation.');
    $erwartungscheckData->setGruppe('Vor dem Studium / allgemeines');
    $erwartungscheckData->setRichtigeAntwort('wahr');
    $erwartungscheckData->setRueckmeldung('Die Hausarbeiten stapeln sich, für die Klausur wurde noch nicht gelernt und eigentlich hat
     man den Überblick über die eigenen Aufgaben und die Zeit verloren... Damit das nicht passiert, ist es wichtig, sich selbst organisieren
      zu können und seine Zeit richtig zu managen.');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Vor dem.');
    $erwartungscheckData->setGruppe('Vor dem Studium / allgemeines');
    $erwartungscheckData->setRichtigeAntwort('falsch');
    $erwartungscheckData->setRueckmeldung('Der Studien- und Lernaufwand in einem Studium ist im allgemeinen höher als 
    beispielsweise in der Schulzeit und vergleichbar mit einem Vollzeit Job. Neben dem Studienalltag mit Vorlesungen und weiteren 
    Veranstaltungen, müssen anschließend in der Freizeit Nerven und Zeit in Lernen sowie in Vor- und Nachbereitung investiert werden.
     Während Vorlesung und Tutorien meistens in der Vorlesungszeit stattfinden, werden beispielsweise Klausuren in der 
     vorlesungsfreien Zeit geschrieben. Die umgangssprachliche Bezeichnung "Semesterferien" ist daher nicht richtig.');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Das Wissen bekomme ich in Vorlesungen vermittelt, deswegen muss ich nur für Vorlesungen anwesend sein.');
    $erwartungscheckData->setGruppe('Während des Studiums');
    $erwartungscheckData->setRichtigeAntwort('falsch');
    $erwartungscheckData->setRueckmeldung('Neben Vorlesungen gibt es weitere Veranstaltungsformen, die im Studium sehr wichtig sind.
    Während Vorlesungen das Wissen eher theoretisch vermitteln, werden Tutorien und in Übungen das Gelernte praktisch umgesetzt. Ob es eine
    Anwesenheitspflicht gibt oder nicht die – die Teilnahme lohnt sich.');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Neben logischem Denken ist es im Wirtschaftsinformatik-Studium auch wichtig, kreativ zu sein.');
    $erwartungscheckData->setGruppe('Während des Studiums');
    $erwartungscheckData->setRichtigeAntwort('wahr');
    $erwartungscheckData->setRueckmeldung('Verschiedene Prozesse logisch verstehen und nachvollziehen zu können ist als 
    Wirtschaftsinformatiker_in genauso wichtig, wie selbst kreative Lösungswege zu gestalten. Dabei ist es hilfreich, nicht "in Schubladen" 
    zu denken und gelernte Inhalte fächerübergreifend kombinieren und anwenden zu können.');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('In der Wirtschaftsinformatik arbeite ich nie im Team und muss keine Präsentation halten.');
    $erwartungscheckData->setGruppe('Während des Studiums');
    $erwartungscheckData->setRichtigeAntwort('falsch');
    $erwartungscheckData->setRueckmeldung('Teamfähigkeit und Präsentationsstärke sind als Wirtschaftsinformatiker_in sehr 
    wichtig. Häufig werden Aufgaben im Team bearbeitet. Gemeinsam werden neue Lösungswege entwickelt und vor Publikum präsentiert.
    
    Wer sich dabei nicht so sicher fühlt kann dabei verschiedene Angebote und Möglichkeiten an der Universität nutzen, um diese Kompetenzen 
    zu vertiefen und aufzufrischen.');

    $questions[] = $erwartungscheckData;


    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('It is also important to understand and speak English when studying "Wirtschaftsinformatik" at the 
    Universität Hildesheim.');
    $erwartungscheckData->setGruppe('Während des Studiums');
    $erwartungscheckData->setRichtigeAntwort('wahr');
    $erwartungscheckData->setRueckmeldung('Im Bachelor-Studium "Wirtschaftsinformatik" sind Veranstaltungen (fast) alle auf deutscher
    Sprache Ein Großteil der Fachliteratur ist jedoch in englischer Sprache verfasst. Somit ist es bereits im Bachelor notwendig, Englischkenntnisse 
    zu haben.
    
    Im Master-Studium sind gute Englischkenntnisse noch wichtiger, da Seminare und eigene Präsentationen auf Englisch sein werden.');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Wenn ich an einer Universität studiere Wirtschaftsinformatik studieren werde ich später im Bereich 
    "Forschung" arbeiten.  
    Universität Hildesheim.');
    $erwartungscheckData->setGruppe('Nach dem Studium');
    $erwartungscheckData->setRichtigeAntwort('wahr');
    $erwartungscheckData->setRueckmeldung('Da stimmt so nicht ganz. Während die Arbeitsweisen in Universitäten und Fachhochschulen (FH)
     früher klar getrennt waren, ist es heutzutage eher gemischt. Universitäten bieten auch praxisorientierte Anwendungen und FHs forschen.
     
     Als Wirtschaftsinformatik-Student_in an der Universität Hildesheim sammeln Sie schon früh praktische Erfahrung und werden anwendungsorientiert
      ausgebildet. Im Rahmen eines Praktikums erhalten Sie Einblicke in betriebliche Abläufe und Sie können von den vielen starken Kooperationspartnern 
      der Universität/des Fachbereichs profitieren. ');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Die meisten Absolventen steigen nach dem Bachelor-Studium Wirtschaftsinformatik sofort ins Berufsleben
    ein.');
    $erwartungscheckData->setGruppe('Nach dem Studium');
    $erwartungscheckData->setRichtigeAntwort('falsch');
    $erwartungscheckData->setRueckmeldung('Hier kommt noch ein Erklärungstext hin.');

    $questions[] = $erwartungscheckData;

    $erwartungscheckData = new Data\ErwartungscheckData();

    $erwartungscheckData->setAussage('Ich muss schon vor dem Wirtschaftsinformatik-Studium wissen, welchen Beruf ich nach dem Studium
     ausüben möchte.');
    $erwartungscheckData->setGruppe('Nach dem Studium');
    $erwartungscheckData->setRichtigeAntwort('falsch');
    $erwartungscheckData->setRueckmeldung('Hier kommt noch ein Erklärungstext hin.');

    $questions[] = $erwartungscheckData;

    **/

    return $questions;
  }

}