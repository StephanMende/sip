<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 17.04.19
 * Time: 21:20
 */

namespace Drupal\erwartungscheck\Data;


class ErwartungscheckData {
public $aussage;
public $gruppe;
public $richtige_antwort;
public $rueckmeldung;
public $studiengang;

  /**
   * @return mixed
   */
  public function getAussage() {
    return $this->aussage;
  }

  /**
   * @param mixed $aussage
   */
  public function setAussage($aussage) {
    $this->aussage = $aussage;
  }

  /**
   * @return mixed
   */
  public function getGruppe() {
    return $this->gruppe;
  }

  /**
   * @param mixed $gruppe
   */
  public function setGruppe($gruppe) {
    $this->gruppe = $gruppe;
  }

  /**
   * @return mixed
   */
  public function getRichtigeAntwort() {
    return $this->richtige_antwort;
  }

  /**
   * @param mixed $richtige_antwort
   */
  public function setRichtigeAntwort($richtige_antwort) {
    $this->richtige_antwort = $richtige_antwort;
  }

  /**
   * @return mixed
   */
  public function getRueckmeldung() {
    return $this->rueckmeldung;
  }

  /**
   * @param mixed $rueckmeldung
   */
  public function setRueckmeldung($rueckmeldung) {
    $this->rueckmeldung = $rueckmeldung;
  }

  /**
   * @return mixed
   */
  public function getStudiengang() {
    return $this->studiengang;
  }

  /**
   * @param mixed $studiengang
   */
  public function setStudiengang($studiengang) {
    $this->studiengang = $studiengang;
  }


}