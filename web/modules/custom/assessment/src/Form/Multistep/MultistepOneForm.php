<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 12.01.19
 * Time: 20:27
 */

namespace Drupal\assessment\Form\Multistep;


use Drupal\Core\Form\FormStateInterface;

class MultistepOneForm extends MultiStepFormBase
{

    public function getFormId()
    {
        return 'multistep_form_one';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        //Get base form from parent
        $form =  parent::buildForm($form, $form_state);

        //add form elements
        $form['name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Your name'),
            '#default_value' => $this->store->get('name') ? $this->store->get('name') : ''
        );

        $form['email'] = array(
            '#type' => 'email',
            '#title' => $this->t('Your email address'),
            '#default_value' => $this->store->get('email') ? $this->store->get('email') : '',
        );

        //change submit value from base form
        $form['actions']['submit']['#value'] = $this->t('Next');

        return $form;

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->store->set('email', $form_state->getValue('email'));
        $this->store->set('name', $form_state->getValue('name'));

        $form_state->setRedirect('demo.multistep_two');
    }
}