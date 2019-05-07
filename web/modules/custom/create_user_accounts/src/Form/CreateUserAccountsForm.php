<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 09.04.19
 * Time: 07:32
 */

namespace Drupal\create_user_accounts\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
class CreateUserAccountsForm extends FormBase {

  public function getFormId() {
    return 'create_user_accounts_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['create_user_accounts']['count'] = [
      '#type' => 'number',
     '#title' => $this->t('Quantity'),
    ];

    $form['create_user_accounts']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}