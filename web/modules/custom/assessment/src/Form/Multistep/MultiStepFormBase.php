<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 12.01.19
 * Time: 20:03
 */

namespace Drupal\assessment\Form\Multistep;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class MultiStepFormBase extends FormBase
{
    protected $tempStoreFactory;
    private $sessionManager;
    private $currentUser;
    protected $store;


    public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager,
                                AccountInterface $current_user)
    {
        $this->tempStoreFactory = $temp_store_factory;
        $this->sessionManager = $session_manager;
        $this->currentUser = $current_user;

        $this->store = $this->tempStoreFactory->get('multistep_data');
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('user.private_tempstore'),
            $container->get('session_manager'),
            $container->get('current_user')
        );
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        //start a manual session for anonymous user
        if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
            $_SESSION['multistep_form_holds_session'] = TRUE;
            $this->sessionManager->start();
        }

        $form = array();
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#button_type' => 'primary',
            '#weight' => 10,
        );

        return $form;
    }

    /**
     * Save the date from the multistep form
     */
    protected function saveData() {
        //Logic for saving data...
        //e.g. save data to database

        //reset store
        $this->deleteStore();
        drupal_set_message($this->t('Form has been saved'));
    }

    protected function deleteStore() {
        $keys = ['name', 'email', 'age', 'location'];
        foreach ($keys as $key) {
            $this->store->delete($key);
        }
    }
}