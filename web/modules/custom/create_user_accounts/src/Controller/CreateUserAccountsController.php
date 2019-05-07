<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 09.04.19
 * Time: 07:24
 */

namespace Drupal\create_user_accounts\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\create_user_accounts\Helper;


class CreateUserAccountsController extends ControllerBase {


  public function showAccounts() {

    //$password = $this->generateRandomPasswordString(8);

    for($i=1; $i<13; $i++) {
      $rows[] = ['Gruppe' . $i, $this->generateRandomPasswordString(8)];
    }

    $table = [
      '#type' => 'table',
      '#header' => [$this->t('User Name'),$this->t('Password') ],
      '#rows' => $rows,
      '#responsive' => TRUE,
    ];

    //dsm($rows);

    $createNewUser = new Helper\CreateHelper();
    $createNewUser->create_new_user($rows[0][0], $rows[0][1], 'de');

    return $table;

  }

  public function generateRandomPasswordString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
    }

    return $randomString;
  }

}