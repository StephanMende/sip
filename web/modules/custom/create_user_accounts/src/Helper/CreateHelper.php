<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 09.04.19
 * Time: 08:04
 */


namespace Drupal\create_user_accounts\Helper;

use Drupal\user\Entity\User;

class CreateHelper {

  /**
   * Helper function to create a new user
   */
  public function create_new_user($userName, $userPassword, $language) {
    $user = User::create();

    //Mandatory settings
    $user->setPassword($userPassword);
    $user->enforceIsNew();
    $user->setEmail('$userName' . '@test.de');

    //This username must be unique and accept only a-Z,0-9, - _ @ .
    $user->setUsername($userName);

    //Optional settings
    $language = 'en';
    $user->set("init", 'email');
    $user->set("langcode", $language);
    $user->set("preferred_langcode", $language);
    $user->set("preferred_admin_langcode", $language);
    $user->activate();

    //Save user
    $user->save();
    drupal_set_message("User with uid " . $user->id() . " saved!\n");
  }
}