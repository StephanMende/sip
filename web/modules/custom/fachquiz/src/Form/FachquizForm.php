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



class FachquizForm extends FormBase {
  public function getFormId() {
    return 'fachquiz_form';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array|void
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $fachquizData = new FachquizData();


    $form['aufgabe'] = [
      '#markup' => $fachquizData->getData()[0]['aufgabe'],
    ];

    $form['frage'] = [
      '#markup' => $fachquizData->getData()[0]['frage'],
    ];
    if($fachquizData->getData()[0]['fragetyp'] == "single choice") {
      $form['antwortoptionen'] = [
        '#type' => 'radios',
        '#title' => $this->t('Antwortoptionen'),
        '#options' => $fachquizData->getData()[0]['antwortoptionen'],
        '#default_value' => 1,
      ];
    }



    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}