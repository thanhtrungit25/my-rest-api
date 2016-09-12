<?php

use Phalcon\Mvc\Model;

class RobotsSimilar extends Model
{

  public $id;

  public $robots_id;

  public $similar_robots_id;

  public function initialize()
  {
    $this->belongsTo(
      "robots_id",
      "Robots",
      "id",
      array(
        'alias' => 'Robot'
      )
    );
    $this->belongsTo(
      "similar_robots_id",
      "Robots",
      "id",
      array(
        'alias' => 'SimilarRobot'
      )
    );
  }

}