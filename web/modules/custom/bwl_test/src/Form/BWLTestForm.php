<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 02.04.19
 * Time: 19:01
 */

namespace Drupal\bwl_test\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
class BWLTestForm extends FormBase {

  public function getFormId() {
    return 'bwl_test_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['task_1']['task']['text'] = [
      '#markup' => '<p>Ein Handwerker erhält für die Herstellung eines Stuhls 4 Euro. Im Rahmen einer 38-Stunden Woche fertigt er
        323 Stühle.</p>',
    ];

    $form['task_1']['question']['text_and_options'] = [
      '#type' => 'radios',
      '#title' => $this->t('Wie hoch war sein Stundenlohn?'),
      '#description' => $this->t('Bitte wählen Sie die richtige Antwort aus. Nur eine Antwort ist richtig.'),
      '#options' => ['8,50€/h','9,50€/h','34,00€/h','80,75€/h',],
    ];

    $form['task_1']['action'] = [
      '#type' => 'button',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::setExplanationMessage',
      ],
    ];

    $form['task_1']['explanation'] = [
      '#type' => 'markup',
      '#markup' => '<div class="explanation_message"></div>',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

  public function setExplanationMessage(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('.explanation_message', '<div>Explanation</div>'));

    return $response;
  }

}