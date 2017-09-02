<?php

require_once __DIR__ . '/vendor/autoload.php'; 

$fb = new \Facebook\Facebook([
    'app_id' => '',
    'app_secret' => '',
    'default_graph_version' => 'v2.10',
    'default_access_token' => ''
  ]);

$cal = new \Eluceo\iCal\Component\Calendar('Melbourne Gaymers events');


  try {
    $response = $fb->get('/MelbourneGaymers/events');
    foreach ($response->getDecodedBody()['data'] as $event) {

        $vEvent = new \Eluceo\iCal\Component\Event();

        $vEvent
            ->setDtStart(new \DateTime($event['start_time']))
            ->setDtEnd(new \DateTime($event['end_time']))
            ->setSummary($event['name'])
            ->setUseUtc(false)
        ;

        $cal->addComponent($vEvent);

    }

  } catch(\Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  header('Content-Type: text/calendar; charset=utf-8');
  header('Content-Disposition: attachment; filename="MelbourneGaymers.ics"');
  echo $cal->render();  