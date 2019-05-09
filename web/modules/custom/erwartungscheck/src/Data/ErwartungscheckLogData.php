<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 09.05.19
 * Time: 15:47
 */

namespace Drupal\erwartungscheck\Data;


class ErwartungscheckLogData {
  public $correct_answers;

  /**
   * @return mixed
   */
  public function getCorrectAnswers() {
    return $this->correct_answers;
  }

  /**
   * @param mixed $correct_answers
   */
  public function setCorrectAnswers($correct_answers) {
    $this->correct_answers = $correct_answers;
  }
}