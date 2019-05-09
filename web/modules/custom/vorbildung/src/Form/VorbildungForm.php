<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 09.05.19
 * Time: 10:26
 */

namespace Drupal\vorbildung\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
class VorbildungForm extends FormBase {

  public function getFormId() {
    return 'vorbildung_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['vorbildung']['select'] = [
      '#type' => 'select',
      '#title' => $this->t('Bitte wählen Sie hier aus welche Vorbildung Sie haben'),
      '#options' => [
        'berufliche_vorbildung' => $this->t('Berufliche Vorbildung'),
        'schulische_vorbildung' => $this->t('Schulische Vorbildung'),
      ],
      '#default_value' => 'berufliche_vorbildung',
    ];

    $form['vorbildung']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    if($form_state->getValue('select') == 'berufliche_vorbildung') {
      //\Drupal::messenger()->addMessage('Beruf', 'status');
      //dsm('Berufliche Vorbildung');
      $form_state->setRedirect('berufliche_vorbildung.form');
      return;
    } elseif ($form_state->getValue('select') == 'schulische_vorbildung') {
      //\Drupal::messenger()->addMessage('Schule', 'status');
      $form_state->setRedirect('schulische_vorbildung.form');
      return;
    } else {
      \Drupal::messenger()->addMessage('Fehler. Bitte kontaktieren Sie den Administrator.', 'error');
      //dsm($form_state->getValue('select'));
    }
  }


}