<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 26.03.19
 * Time: 20:29
 */

namespace Drupal\fachquiz\Data;


class FachquizData {

  public $aufgabe;
  public $bild;
  public $frage;
  public $antwortoptionen;
  public $erklaerung;

  /**
   * @return mixed
   */
  public function getAufgabe() {
    return $this->aufgabe;
  }

  /**
   * @param mixed $aufgabe
   */
  public function setAufgabe($aufgabe) {
    $this->aufgabe = $aufgabe;
  }

  /**
   * @return mixed
   */
  public function getBild() {
    return $this->bild;
  }

  /**
   * @param mixed $bild
   */
  public function setBild($bild) {
    $this->bild = $bild;
  }

  /**
   * @return mixed
   */
  public function getFrage() {
    return $this->frage;
  }

  /**
   * @param mixed $frage
   */
  public function setFrage($frage) {
    $this->frage = $frage;
  }

  /**
   * @return mixed
   */
  public function getAntwortoptionen() {
    return $this->antwortoptionen;
  }

  /**
   * @param mixed $antwortoptionen
   */
  public function setAntwortoptionen($antwortoptionen) {
    $this->antwortoptionen = $antwortoptionen;
  }

  /**
   * @return mixed
   */
  public function getErklaerung() {
    return $this->erklaerung;
  }

  /**
   * @param mixed $erklaerung
   */
  public function setErklaerung($erklaerung) {
    $this->erklaerung = $erklaerung;
  }




}