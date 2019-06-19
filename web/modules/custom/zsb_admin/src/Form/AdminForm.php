<?php
/**
 * @file
 * Contains Drupal\welcome\Form\AdminForm.
 */
namespace Drupal\zsb_admin\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AdminForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'zsb_admin.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'zsb_admin_adminform';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('zsb_admin.adminsettings');

    $form['admin_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Admin text'),
      '#description' => $this->t('Admin text.'),
      '#default_value' => $config->get('admin_text'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);

    $this->config('zsb_admin.adminsettings')
      ->set('admin_text', $form_state->getValue('admin_text'))
      ->save();
  }
}
