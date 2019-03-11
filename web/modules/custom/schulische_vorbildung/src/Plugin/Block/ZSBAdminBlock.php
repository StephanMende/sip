<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 13.01.19
 * Time: 19:31
 */

namespace Drupal\schulische_vorbildung\Plugin\Block;


use Drupal\Core\Block\BlockBase;

/**
 * Class ZSBAdminBlock
 * @package Drupal\schulische_vorbildung\Plugin\Block
 *
 * @Block(
 *     id = "ZSB",
 *     admin_label = @Translation("ZSB"),
 *
 * )
 */

class ZSBAdminBlock extends BlockBase
{

   public function build()
   {

       $items[] = [
           '#markup' => '<a href="/node/add/studiengang">Studiengang anlegen</a>',
       ];
       $items[] = [
           '#markup' => '<a href="/node/add/berufsgruppe">Berufsgruppe anlegen</a>',
       ];
       $items[] = [
           '<a href="/node/add/beruf/">Beruf anlegen</a>',
       ];
       $items[] = [
           '#markup' => '<a href="/node/add/schulabschluss">Schulabschluss anlegen</a>',
       ];

       $items[] = [
           '#markup' => '<a href="/berufliche-vorbildung">Berufliche Vorbildung</a>',
       ];

       $items[] = [
           '#markup' => '<a href="/schulische-vorbildung">Schulische Vorbildung</a>',
       ];

       $items[] = [
           '#markup' => '<a href="/erwartungscheck">Erwartungscheck</a>',
       ];

       $list = [
           '#theme' => 'item_list',
           '#list_type' => 'ul',
           '#title' => 'ZSB',
           '#items' => $items,
       ];
       return $list;
   }
}