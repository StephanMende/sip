<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 11.01.19
 * Time: 17:51
 */

namespace Drupal\assessment\Data;


class AssessmentQuestion
{
    /**
     * @return mixed
     */
    public function getQuestions()
    {
        //get nids of the content type Frage
        $nids = \Drupal::entityQuery('node')->condition('type', 'frage')->execute();
        $nodes = node_load_multiple($nids);
        foreach ($nodes as $node) {
            $questions[] = ["title" => $node->getTitle(), "id" => $node->id()];
        }
        return $questions;
    }


    /**
     * @return array
     */
    public function getAnswerOpportunities($entity_id)
    {
        //$entity_id = 16;
        $connection = \Drupal::database();
        $query = $connection->query("SELECT field_antwortmoeglichkeiten_target_id FROM {node__field_antwortmoeglichkeiten} WHERE entity_id = :entity_id", [
            ':entity_id' => $entity_id,
        ]);

        $result = $query->fetchAll();

        foreach ($result as $row) {
            $nids[] = $row->field_antwortmoeglichkeiten_target_id;
        }

        $nodes = node_load_multiple($nids);
        foreach ($nodes as $node) {
            $answerOportunities[] = $node->getTitle();
        }

        return $answerOportunities;
    }




}