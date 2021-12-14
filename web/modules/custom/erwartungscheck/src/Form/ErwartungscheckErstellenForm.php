<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 06.05.19
 * Time: 17:34
 */

namespace Drupal\erwartungscheck\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ErwartungscheckErstellenForm extends FormBase {

  public function getFormId() {
    return 'erwartungscheck_erstellen_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    // TODO: Implement buildForm() method.


    $form['erwartungscheck_erstellen']['studiengang'] = [
      '#type' => 'select',
      '#options' => [
        'wirtschaftsinformatik' => 'Wirtschaftsinformatik', //TODO studiengaenge aus de db holen
        'angewandte_informatik' => 'Angewandte Informatik',
        'imit' => 'Informationsmanagement und IT (IMIT)',

      ],
    ];
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}