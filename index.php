<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;
use Phalcon\Db\Column;

// Use Loader to autoload our model
$loader = new Loader();

$loader->registerDirs(
  array(
    __DIR__ . '/models/'
  )
)->register();

$di = new FactoryDefault();

// Setup the database service
$di->set('db', function () {
  return new PdoMysql(
    array(
      'host' => 'localhost',
      'username' => 'root',
      'password' => '',
      'dbname' => 'robot'
    )
  );
});

// Create and bind the DI to the application
$app = new Micro($di);

// Retrieves all robots
$app->get('/api/robots', function () use ($app) {

  // Query robots binding parameters with integer placeholders
  // $conditions = "name = :name: AND year = :year:";

  // Parameters whose keys are the same as placeholders
  $parameters = array("name" => "Robotina", "year" => 1972);

  $types = array(
    "name" => Column::BIND_PARAM_STR,
    "year" => Column::BIND_PARAM_INT
  );

  $robots = Robots::find(
    array(
      "name = :name: AND year = :year:",
      "bind" => $parameters,
      "bindTypes" => $types
    )
  );

  // $robots = Robots::find();
  foreach ($robots as $robot) {
    echo $robot->name . '<br>';
  }

  // $robots->rewind();
  // while ($robots->valid()) {
  //   $robot = $robots->current();
  //   echo $robot->name, '<br>';
  //   $robots->next();
  // }

  // echo $robots->count();
  // $robots->seek(2);
  // $robot = $robots->current();

  // $robot = $robots[2];
  // var_dump($robot);
  // if (isset($robots[3])) {
  //   $robot = $robots[3];
  // }
  // echo '<pre>';print_r($robot);
  // $name = 'Terminator';
  // $robot = Robots::findFirstByName($name);
  // if ($robot) {
  //   echo "The first robot with name " . $robot->name . " year: " . $robot->year;
  // } else {
  //   echo "Not found " . $name;
  // }
  // die;
  // $robots = Robots::query()
  //   ->where("type = :type:")
  //   ->andWhere("year < 2000")
  //   ->bind(array("type" => "mechanical"))
  //   ->order("name")
  //   ->execute();

  // echo '<pre>';print_r($robots);

  // $phql = "SELECT * FROM Robots ORDER BY name";
  // $robots = $app->modelsManager->executeQuery($phql);

  // $data = array();
  // foreach ($robots as $robot) {
  //   $data[] = array(
  //     'id' => $robot->id,
  //     'name' => $robot->name
  //   );
  // }

  // echo json_encode($data);
});

// Define the route here
$app->get('/api/robots/search/{name}', function ($name) use ($app) {
  
  $phql = "SELECT * FROM Robots WHERE name LIKE :name: ORDER BY name";
  $robots = $app->modelsManager->executeQuery(
    $phql,
    array(
      'name' => '%' . $name . '%'
    )
  );

  $data = array();
  foreach ($robots as $robot) {
    $data[] = array(
      'id' => $robot->id,
      'name' => $robot->name
    );
  }

  echo json_encode($data);
});

// Retrieves robots based on primary key
$app->get('/api/robots/{id:[0-9]+}', function ($id) use ($app) {

  $robotSimilar = RobotsSimilar::findFirst();
  $robot = $robotSimilar->similarRobot;
  var_dump($robot);die;

  $robot = Robots::findFirst(1);
  echo $robot->countRobotsParts();
  // Robots model has 1-n relationship to RobotsParts, then
  $robotsParts = RobotsParts::find("robots_id = '". $robot->id ."' AND created_at = '2016-03-12'");

  foreach ($robotsParts as $robotPart) {
    echo $robotPart->parts->name , '<br>';
  }


  die;
  $phql = "SELECT * FROM Robots WHERE id = :id:";
  $robot = $app->modelsManager->executeQuery(
    $phql,
    array(
      'id' => $id
    )
  )->getFirst();

  // Create a response
  $response = new Response();
  if ($robot == FALSE) {
    $response->setJsonContent(
      array(
        'status' => 'NOT-FOUND'
      )
    );
  } else {
    $response->setJsonContent(
      array(
        'status' => 'FOUND',
        'data' => array(
          'id' => $robot->id,
          'name' => $robot->name
        )
      )
    );
  }

  return $response;
});

// Add a new robot
$app->post('/api/robots', function () use ($app) {

  $robot = $app->request->getJsonRawBody();
  $phql = "INSERT INTO Robots (name, type, year) VALUES (:name:, :type:, :year:)";
  $status = $app->modelsManager->executeQuery($phql, array(
    'name' => $robot->name,
    'type' => $robot->type,
    'year' => $robot->year
  ));

  $response = new Response();
  // Check if the insertion was successful
  if ($status->success() == true) {
    // Change the HTTP status
    $response->setStatusCode(201, 'Created');

    $robot->id = $status->getModel()->id;
    $response->setJsonContent(
      array(
        'status' => 'OK',
        'data' =>  $robot
      )
    );

  } else {

    $response->setStatusCode(409, 'Conflict');
    // Send the errors to client
    $errors = array();
    foreach ($status->getMessages() as $message) {
      $errors[] = $message->getMessage();
    }

    $response->setJsonContent(
      array(
        'status' => 'ERROR',
        'messages' => $errors
      )
    );

  }

  return $response;
});

// Update robot based on primary key
$app->put('/api/robots/{id:[0-9]+}', function ($id) use ($app) {

  $robot = $app->request->getJsonRawBody();

  $phql = "UPDATE Robots SET name = :name:, type = :type:, year = :year: WHERE id = :id:";
  $status = $app->modelsManager->executeQuery($phql, array(
    'id' => $id,
    'name' => $robot->name,
    'type' => $robot->type,
    'year' => $robot->year
  ));

  $response = new Response();
  if ($status->success() == true) {
    $response->setJsonContent(
      array(
        'status' => 'OK'
      )
    );
  } else {
    $response->setStatusCode(409, 'Conflict');
    
    $errors = array();
    foreach ($status->getMessages() as $message) {
      $errors[] = $message->getMessage();
    }

    $response->setJsonContent(
      array(
        'status' => 'ERROR',
        'messages' => $errors
      )
    );

  }

  return $response;
});

// Delete robot based on primary key
$app->delete('/api/robots/{id:[0-9]+}', function ($id) use ($app) {

  $phql = "DELETE FROM Robots WHERE id = :id:";
  $status = $app->modelsManager->executeQuery($phql, array(
    'id' => $id
  ));

  $response = new Response();
  if ($status->success() == true) {
    $response->setJsonContent(array(
      'status' => 'OK'
    ));
  } else {
    $response->setStatusCode(409, 'Conflict');

    $errors = array();
    foreach ($status->getMessages() as $message) {
      $errors[] = $message->getMessage();
    }

    $response->setJsonContent(array(
      'status' => 'ERROR',
      'messages' => $errors
    ));

  }

  return $response;
});

$app->handle();