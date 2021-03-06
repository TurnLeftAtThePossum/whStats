<?php
require_once 'rethinkQueries.php';
require_once 'cacheManager.php';
date_default_timezone_set('Etc/GMT');

class GenerateStats {

  public function __construct(){
    $this->rethinkQueries = new RethinkQueries();
    $this->cacheManager = new CacheManager();
    $this->subValues = array(
      "hour" => 3600,
      "day" => 86400,
      "week" => 604800
    );
    $this->classArray = array(
      0 => 0,
      30 => 7,
      31 => 8,
      32 => 8,
      33 => 8,
      34 => 8,
      35 => 8,
      36 => 8,
      41 => 9,
      42 => 9,
      43 => 9
    );
    $this->dummyArray = array(
      'biggestKill' => array(
        'killID' => null,
        'shipName' => null,
        'shipType' => null,
        'value' => null
      ),
      'biggestSoloKill' => array(
        'killID' => null,
        'shipName' => null,
        'shipType' => null,
        'value' => null
      ),
      'biggestNPCKill' => array(
        'killID' => null,
        'shipName' => null,
        'shipType' => null,
        'value' => null
      ),
      'class' => 0,
      'kills' => array(
        'shipNames' => array(),
        'shipRaces' => array(),
        'shipTechs' => array(),
        'typeIDs' => array(),
        'typeNames' => array()
      ),
      'totalISK' => 0,
      'totalKills' => 0
    );
  }

  public function formatValue($num) {
    if (!is_numeric($num)) {
      return 0;
    }
    return ceil(($num/1000000));
  }

  public function getClass($class) {
    if($class > 0 && $class < 7) {
      return $class;
    }
    return $this->classArray[$class];
  }

  public function listenForChanges() {
    $conn = r\connect('localhost', 28015, 'stats');
    $feed = r\table('whKills')->changes()->run($conn);
    $currentKillID = 0;
    $lastCheck = strtotime('-60 minutes');
    $lastDayCheck = strtotime('-60 minutes');
    $lastWeekCheck = strtotime('-60 minutes');
    $lastMonthCheck = strtotime('-60 minutes');
    $month = intval(date('m'), 10);
    $year = intval(date('Y'), 10);
    $key = md5(strtoupper('entityStats_month_'.$year.'_'.$month));
    $data = array('period' => 'month', 'year' => $year, 'month' => $month);
    $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    foreach($feed as $change) {
      $thisKillID = $change['new_val']['killID'];
      if($thisKillID != $currentKillID) {
        $currentKillID = $thisKillID;
        $killTime = $change['new_val']['killTime'];
        $killTime = $killTime->getTimestamp();
        $killMonth = intval(date('m', $killTime), 10);
        $killYear = intval(date('Y', $killTime), 10);
        $data = array('kill' => $change['new_val'], 'period' => 'month', 'year' => $killYear, 'month' => $killMonth);
        $key =  md5(strtoupper('periodStats_month_'.$killYear.'_'.$killMonth));
        $this->cacheManager->queueTask('addStats', '', '', $data);
        $this->cacheManager->queueTask('getStats', 'periodStats', $key, $data);
        $key = md5(strtoupper('entityStats_month_'.$killYear.'_'.$killMonth));
        $this->cacheManager->queueTask('addEntityStatsMonth', '', '', $data);
        $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);

        if($killTime >= strtotime('-1 hour') || strtotime("-2 minutes") >= $lastCheck) {
          $lastCheck = strtotime("now");
          $killTimeFormatted = date('Y.m.d H:i:s', $killTime);
          printf("[H] Called - ID: {$change['new_val']['killID']} | Time: {$killTimeFormatted}\n");
          $key = md5(strtoupper('periodStats_hour_0_0'));
          $data = array('period' => 'hour', 'year' => 0, 'month' => 0);
          $this->cacheManager->queueTask('genStats', '', '', $data);
          $this->cacheManager->queueTask('getStats', 'periodStats', $key, $data);
          $key = md5(strtoupper('entityStats_hour_0_0'));
          $this->cacheManager->queueTask('genEntityStats', '', '', $data);
          $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
        }
        if($killTime >= strtotime('-1 day') && $killTime < strtotime('-1 hour') || strtotime("-5 minutes") >= $lastDayCheck) {
          $lastDayCheck = strtotime("now");
          $killTimeFormatted = date('Y.m.d H:i:s', $killTime);
          printf("[D] Called - ID: {$change['new_val']['killID']} | Time: {$killTimeFormatted}\n");
          $key = md5(strtoupper('periodStats_day_0_0'));
          $data = array('period' => 'day', 'year' => 0, 'month' => 0);
          $this->cacheManager->queueTask('genStats', '', '', $data);
          $this->cacheManager->queueTask('getStats', 'periodStats', $key, $data);
          $key = md5(strtoupper('entityStats_day_0_0'));
          $this->cacheManager->queueTask('genEntityStats', '', '', $data);
          $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
        }
        if($killTime >= strtotime('-1 week') && $killTime < strtotime('-1 day') || strtotime("-30 minutes") >= $lastWeekCheck) {
          $lastWeekCheck = strtotime("now");
          $killTimeFormatted = date('Y.m.d H:i:s', $killTime);
          printf("[W] Called - ID: {$change['new_val']['killID']} | Time: {$killTimeFormatted}\n");
          $key = md5(strtoupper('periodStats_week_0_0'));
          $data = array('period' => 'week', 'year' => 0, 'month' => 0);
          $this->cacheManager->queueTask('genStats', '', '', $data);
          $this->cacheManager->queueTask('getStats', 'periodStats', $key, $data);
          $key = md5(strtoupper('entityStats_week_0_0'));
          $this->cacheManager->queueTask('genEntityStats', '', '', $data);
          $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
        }
        // if($killTime >= strtotime('-1 month') && $killTime < strtotime('-1 week') || strtotime("-2 minutes") >= $lastMonthCheck) {
        //   $lastMonthCheck = strtotime("now");
        //   $killTimeFormatted = date('Y.m.d H:i:s', $killTime);
        //   printf("[M] Called - ID: {$change['new_val']['killID']} | Time: {$killTimeFormatted}\n");
        //   $month = intval(date('m'), 10);
        //   $year = intval(date('Y'), 10);
        //   $key = md5(strtoupper('entityStats_month_'.$year.'_'.$month));
        //   $data = array('kill' => $change['new_val'], 'period' => 'month', 'year' => $year, 'month' => $month);
        //   $this->cacheManager->queueTask('genEntityStats', '', '', $data);
        //   $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
        // }
      }
    }
    $conn->close();
  }

  public function populateTables() {
    // $data = array('period' => 'hour', 'year' => 0, 'month' => 0);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'day', 'year' => 0, 'month' => 0);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'week', 'year' => 0, 'month' => 0);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    //
    // $key = md5(strtoupper('entityStats_hour_0_0'));
    // $data = array('period' => 'hour', 'year' => 0, 'month' => 0);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $key = md5(strtoupper('entityStats_day_0_0'));
    // $data = array('period' => 'day', 'year' => 0, 'month' => 0);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $key = md5(strtoupper('entityStats_week_0_0'));
    // $data = array('period' => 'week', 'year' => 0, 'month' => 0);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    //
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 10);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_10'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 10);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    //
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 9);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 9);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 8);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 8);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 7);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 7);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 6);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 6);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 5);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 5);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 4);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 4);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 3);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 3);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 2);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 2);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 1);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 1);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);
    // $data = array('period' => 'month', 'year' => 2015, 'month' => 12);
    // $this->cacheManager->queueTask('genEntityStats', '', '', $data);
    // $key = md5(strtoupper('entityStats_month_2016_9'));
    // $data = array('period' => 'month', 'year' => 2015, 'month' => 12);
    // $this->cacheManager->queueTask('getEntityStats', 'entityStats', $key, $data);

    // $data = array('period' => 'month', 'year' => 2016, 'month' => 8);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 7);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 6);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 5);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 4);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 3);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 2);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2016, 'month' => 1);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
    // $data = array('period' => 'month', 'year' => 2015, 'month' => 12);
    // $this->cacheManager->queueTask('genStats', '', '', $data);
  }

  public function genStats($period, $year, $month) {
    $key = md5(strtoupper('periodStats_'.$period.'_'.$year.'_'.$month));
    $limit = 1500;
    $time = date('Y-m-d H:i');
    $conn = r\connect('localhost', 28015, 'stats');
    $killsArray = array();
    $statsArray = array();
    for($i=1; $i<10; $i++) {
      $statsArray[$i] = $this->dummyArray;
      $statsArray[$i]['class'] = $i;
    }
    $statsQuery = $this->rethinkQueries->getKills($limit, $period, $year, $month, 1);

    for($page = 1; $page <= $statsQuery['numPages']; $page++) {
      $pageQuery = $this->rethinkQueries->getKills($limit, $period, $year, $month, $page);
      $killsArray = $pageQuery['kills'];

      foreach ($killsArray as $kill) {
        $system = r\table('whSystems')->get($kill['solarSystemID'])->run($conn);
        $time = date("H00", $kill['killTime']->getTimestamp());
        $class = $this->getClass($system['class']);
        if ($class == 0 || $class == null) {
            continue;
        }
        $ship = r\table('shipTypes')->get(intval($kill["victim"]["shipTypeID"], 10))->run($conn);
        if(!isset($statsArray[$class]['class'])) { $statsArray[$class]['class'] = $class; };
        !isset($statsArray[$class]['totalKills']) ? $statsArray[$class]['totalKills'] = 1 : $statsArray[$class]['totalKills'] += 1;
        if($ship['shipType'] == "Dreadnoughts" || $ship['shipType'] == "Carriers" || $ship['shipType'] == "Force Auxiliary" || $ship['shipType'] == "Capital Industrial Ships") {
          !isset($statsArray[$class]['capKills']) ? $statsArray[$class]['capKills'] = 1 : $statsArray[$class]['capKills'] += 1;
        }
        !isset($statsArray[$class]['totalISK']) ? $statsArray[$class]['totalISK'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10)) : $statsArray[$class]['totalISK'] += $this->formatValue(intval($kill['zkb']['totalValue'], 10));

        if($ship['shipType'] != 'Structure' && $ship['shipType'] != 'Citadel') {
          if(count($kill['attackers']) == 1 && $kill['attackers'][0]['factionName'] != "Unknown" && $kill['attackers'][0]['factionName'] != "Drifters" && $kill['attackers'][0]['factionName'] != "Serpentis") {
            if(!isset($statsArray[$class]['biggestSoloKill']) || $this->formatValue(intval($kill['zkb']['totalValue'], 10)) > $statsArray[$class]['biggestSoloKill']['value']) {
              $statsArray[$class]['biggestSoloKill']['value'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10));
              $statsArray[$class]['biggestSoloKill']['killID'] = $kill['killID'];
              $statsArray[$class]['biggestSoloKill']['shipName'] = $ship['shipName'];
              $statsArray[$class]['biggestSoloKill']['shipType'] = $ship['shipType'];
              $statsArray[$class]['biggestSoloKill']['typeID'] = $ship['shipTypeID'];
            }
          }
          $npcOnly = true;
          foreach($kill['attackers'] as $attacker) {
            if(!$attacker['factionName'] == "Unknown" || !$attacker['factionName'] == "Drifters" || !$attacker['factionName'] == "Serpentis") {
              $npcOnly = false;
              break;
            }
          }
          if($npcOnly) {
            if(!isset($statsArray[$class]['biggestNPCKill']) || $this->formatValue(intval($kill['zkb']['totalValue'], 10)) > $statsArray[$class]['biggestNPCKill']['value']) {
              $statsArray[$class]['biggestNPCKill']['value'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10));
              $statsArray[$class]['biggestNPCKill']['killID'] = $kill['killID'];
              $statsArray[$class]['biggestNPCKill']['shipName'] = $ship['shipName'];
              $statsArray[$class]['biggestNPCKill']['shipType'] = $ship['shipType'];
              $statsArray[$class]['biggestNPCKill']['typeID'] = $ship['shipTypeID'];
            }
          }
          if(!isset($statsArray[$class]['biggestKill']) || $this->formatValue(intval($kill['zkb']['totalValue'], 10)) > $statsArray[$class]['biggestKill']['value']) {
            $statsArray[$class]['biggestKill']['value'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10));
            $statsArray[$class]['biggestKill']['killID'] = $kill['killID'];
            $statsArray[$class]['biggestKill']['shipName'] = $ship['shipName'];
            $statsArray[$class]['biggestKill']['shipType'] = $ship['shipType'];
            $statsArray[$class]['biggestKill']['typeID'] = $ship['shipTypeID'];
          }
        }
        !isset($statsArray[$class]['kills']['typeIDs'][$ship['shipTypeID']]) ? $statsArray[$class]['kills']['typeIDs'][$ship['shipTypeID']] = 1 : $statsArray[$class]['kills']['typeIDs'][$ship['shipTypeID']] += 1;
        !isset($statsArray[$class]['kills']['typeNames'][$ship['shipType']]) ? $statsArray[$class]['kills']['typeNames'][$ship['shipType']] = 1 : $statsArray[$class]['kills']['typeNames'][$ship['shipType']] += 1;
        !isset($statsArray[$class]['kills']['shipNames'][$ship['shipName']]) ? $statsArray[$class]['kills']['shipNames'][$ship['shipName']] = 1 : $statsArray[$class]['kills']['shipNames'][$ship['shipName']] += 1;
        !isset($statsArray[$class]['kills']['shipRaces'][$ship['shipRace']]) ? $statsArray[$class]['kills']['shipRaces'][$ship['shipRace']] = 1 : $statsArray[$class]['kills']['shipRaces'][$ship['shipRace']] += 1;
        !isset($statsArray[$class]['kills']['shipTechs'][$ship['shipTech']]) ? $statsArray[$class]['kills']['shipTechs'][$ship['shipTech']] = 1 : $statsArray[$class]['kills']['shipTechs'][$ship['shipTech']] += 1;
        !isset($statsArray[$class]['period'][$time]) ? $statsArray[$class]['period'][$time] = 1 : $statsArray[$class]['period'][$time] += 1;
      }
    }
    $record = array('key' => $key, 'stats' => $statsArray);
    $documentExists = r\table('generatedStats')->get($key)->run($conn);
    $documentExists != null ? $result = r\table('generatedStats')->get($key)->replace($record)->run($conn) :
                              $result = r\table('generatedStats')->insert($record)->run($conn);
    $conn->close();
  }

  public function addStats($kill, $period, $year, $month) {
    $key = md5(strtoupper('periodStats_'.$period.'_'.$year.'_'.$month));
    $time = date('Y-m-d H:i');
    $conn = r\connect('localhost', 28015, 'stats');
    $existingArray = r\table('generatedStats')->get($key)->run($conn);
    if($existingArray == null) {
      $statsArray = array();
      for($i=1; $i<10; $i++) {
        $statsArray[$i] = $this->dummyArray;
        $statsArray[$i]['class'] = $i;
      }
    } else {
      $statsArray = $existingArray['stats'];
    }

    $system = r\table('whSystems')->get($kill['solarSystemID'])->run($conn);
    $time = date("H00", strtotime($kill['killTime']['date']));
    $class = $this->getClass($system['class']);
    if ($class == 0 || $class == null) {
      return null;
    }
    $ship = r\table('shipTypes')->get(intval($kill["victim"]["shipTypeID"], 10))->run($conn);
    if(!isset($statsArray[$class]['class'])) { $statsArray[$class]['class'] = $class; };
    !isset($statsArray[$class]['totalKills']) ? $statsArray[$class]['totalKills'] = 1 : $statsArray[$class]['totalKills'] += 1;
    if($ship['shipType'] == "Dreadnoughts" || $ship['shipType'] == "Carriers" || $ship['shipType'] == "Force Auxiliary" || $ship['shipType'] == "Capital Industrial Ships") {
      !isset($statsArray[$class]['capKills']) ? $statsArray[$class]['capKills'] = 1 : $statsArray[$class]['capKills'] += 1;
    }
    !isset($statsArray[$class]['totalISK']) ? $statsArray[$class]['totalISK'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10)) : $statsArray[$class]['totalISK'] += $this->formatValue(intval($kill['zkb']['totalValue'], 10));

    if($ship['shipType'] != 'Structure' && $ship['shipType'] != 'Citadel') {
      if(count($kill['attackers']) == 1 && $kill['attackers'][0]['factionName'] != "Unknown" && $kill['attackers'][0]['factionName'] != "Drifters" && $kill['attackers'][0]['factionName'] != "Serpentis") {
        if(!isset($statsArray[$class]['biggestSoloKill']) || $this->formatValue(intval($kill['zkb']['totalValue'], 10)) > $statsArray[$class]['biggestSoloKill']['value']) {
          $statsArray[$class]['biggestSoloKill']['value'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10));
          $statsArray[$class]['biggestSoloKill']['killID'] = $kill['killID'];
          $statsArray[$class]['biggestSoloKill']['shipName'] = $ship['shipName'];
          $statsArray[$class]['biggestSoloKill']['shipType'] = $ship['shipType'];
          $statsArray[$class]['biggestSoloKill']['typeID'] = $ship['shipTypeID'];
        }
      }
      $npcOnly = true;
      foreach($kill['attackers'] as $attacker) {
        if(!$attacker['factionName'] == "Unknown" || !$attacker['factionName'] == "Drifters" || !$attacker['factionName'] == "Serpentis") {
          $npcOnly = false;
          break;
        }
      }
      if($npcOnly) {
        if(!isset($statsArray[$class]['biggestNPCKill']) || $this->formatValue(intval($kill['zkb']['totalValue'], 10)) > $statsArray[$class]['biggestNPCKill']['value']) {
          $statsArray[$class]['biggestNPCKill']['value'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10));
          $statsArray[$class]['biggestNPCKill']['killID'] = $kill['killID'];
          $statsArray[$class]['biggestNPCKill']['shipName'] = $ship['shipName'];
          $statsArray[$class]['biggestNPCKill']['shipType'] = $ship['shipType'];
          $statsArray[$class]['biggestNPCKill']['typeID'] = $ship['shipTypeID'];
        }
      }
      if(!isset($statsArray[$class]['biggestKill']) || $this->formatValue(intval($kill['zkb']['totalValue'], 10)) > $statsArray[$class]['biggestKill']['value']) {
        $statsArray[$class]['biggestKill']['value'] = $this->formatValue(intval($kill['zkb']['totalValue'], 10));
        $statsArray[$class]['biggestKill']['killID'] = $kill['killID'];
        $statsArray[$class]['biggestKill']['shipName'] = $ship['shipName'];
        $statsArray[$class]['biggestKill']['shipType'] = $ship['shipType'];
        $statsArray[$class]['biggestKill']['typeID'] = $ship['shipTypeID'];
      }
    }
    !isset($statsArray[$class]['kills']['typeIDs'][$ship['shipTypeID']]) ? $statsArray[$class]['kills']['typeIDs'][$ship['shipTypeID']] = 1 : $statsArray[$class]['kills']['typeIDs'][$ship['shipTypeID']] += 1;
    !isset($statsArray[$class]['kills']['typeNames'][$ship['shipType']]) ? $statsArray[$class]['kills']['typeNames'][$ship['shipType']] = 1 : $statsArray[$class]['kills']['typeNames'][$ship['shipType']] += 1;
    !isset($statsArray[$class]['kills']['shipNames'][$ship['shipName']]) ? $statsArray[$class]['kills']['shipNames'][$ship['shipName']] = 1 : $statsArray[$class]['kills']['shipNames'][$ship['shipName']] += 1;
    !isset($statsArray[$class]['kills']['shipRaces'][$ship['shipRace']]) ? $statsArray[$class]['kills']['shipRaces'][$ship['shipRace']] = 1 : $statsArray[$class]['kills']['shipRaces'][$ship['shipRace']] += 1;
    !isset($statsArray[$class]['kills']['shipTechs'][$ship['shipTech']]) ? $statsArray[$class]['kills']['shipTechs'][$ship['shipTech']] = 1 : $statsArray[$class]['kills']['shipTechs'][$ship['shipTech']] += 1;
    !isset($statsArray[$class]['period'][$time]) ? $statsArray[$class]['period'][$time] = 1 : $statsArray[$class]['period'][$time] += 1;
    $record = array('key' => $key, 'stats' => $statsArray);
    $documentExists = r\table('generatedStats')->get($key)->run($conn);
    $documentExists != null ? $result = r\table('generatedStats')->get($key)->replace($record)->run($conn) :
                              $result = r\table('generatedStats')->insert($record)->run($conn);
    $conn->close();
  }

  public function genEntityStats($period, $year, $month) {
    $key = md5(strtoupper('entityStats_'.$period.'_'.$year.'_'.$month));
    $conn = r\connect('localhost', 28015, 'stats');
    $continue = true;
    $page = 0;
    $limit = 1500;
    $subValue = 0;
    $combinedResults = array();

    if($period != 'month') {
      $subValue = intval($this->subValues[strval($period)], 10);
      while($continue) {
        $blockResults = r\table('whKills')
        ->between(
          r\now()->sub($subValue),
          r\now(),
          array('index' => 'killTime')
        )
        ->skip($page * $limit)
        ->limit($limit)
        ->concatMap(function($aKill) {
          return $aKill('attackers')
            ->map(function($each) {
              return r\branch($each('allianceID')->eq(0),
                      $each->merge(array('entityID' => $each('corporationID'), 'isAlliance' => false)),
                      $each->merge(array('entityID' => $each('allianceID'), 'isAlliance' => true)));
            })
            ->map(function($attacker) use(&$aKill) {
              return array(
                'entityID' => $attacker('entityID'),
                'corporationID' => $attacker('corporationID'),
                'corporationName' => $attacker('corporationName'),
                'allianceID' => $attacker('allianceID'),
                'allianceName' => $attacker('allianceName'),
                'killID' => $aKill('killID'),
                'shipTypeID' => $attacker('shipTypeID'),
                'weaponTypeID' => $attacker('weaponTypeID'),
                'systemID' => $aKill('solarSystemID'),
                'value' => $aKill('zkb')('totalValue'),
                'killTime' => $aKill('killTime')
              );
            });
          })
        ->group('entityID')
        ->ungroup()
        ->merge(function($row) {
          return array(
            'entityID' => $row('group'),
            'totalKills' => $row('reduction')('killID')->distinct()->count(),
            'totalISK' => $row('reduction')('value')->distinct()->sum(),
          );
        })
        ->map(function($each) {
          return array(
            'entityID' => $each('entityID'),
            'totalISK' => $each('totalISK'),
            'totalKills' => $each('totalKills'),
            'killsArray' => $each('reduction')
          );
        })
        ->orderBy(r\desc('totalISK'))
        ->run($conn);

        $combinedResults = array_merge_recursive($combinedResults, $blockResults);
        count($blockResults) >= 1 ? $page++ : $continue = false;
      }
    } elseif($period == "month") {
      $endDay = intval(date("t", mktime(0,0,0,$month,1,$year)), 10);
      while($continue) {
        $blockResults = r\table('whKills')
        ->between(
          r\time($year, $month, 1, 0, 0, 0, 'Z'),
          r\time($year, $month, $endDay, 23, 59, 59, 'Z'),
          array('index' => 'killTime')
        )
        ->skip($page * $limit)
        ->limit($limit)
        ->concatMap(function($aKill) {
          return $aKill('attackers')
            ->map(function($each) {
              return r\branch($each('allianceID')->eq(0),
                      $each->merge(array('entityID' => $each('corporationID'), 'isAlliance' => false)),
                      $each->merge(array('entityID' => $each('allianceID'), 'isAlliance' => true)));
            })
            ->map(function($attacker) use(&$aKill) {
              return array(
                'entityID' => $attacker('entityID'),
                'corporationID' => $attacker('corporationID'),
                'corporationName' => $attacker('corporationName'),
                'allianceID' => $attacker('allianceID'),
                'allianceName' => $attacker('allianceName'),
                'killID' => $aKill('killID'),
                'killTime' => $aKill('killTime'),
                'shipTypeID' => $attacker('shipTypeID'),
                'weaponTypeID' => $attacker('weaponTypeID'),
                'systemID' => $aKill('solarSystemID'),
                'value' => $aKill('zkb')('totalValue')
              );
            });
          })
        ->group('entityID')
        ->ungroup()
        ->merge(function($row) {
          return array(
            'entityID' => $row('group'),
            'totalKills' => $row('reduction')('killID')->distinct()->count(),
            'totalISK' => $row('reduction')('value')->distinct()->sum(),
          );
        })
        ->map(function($each) {
          return array(
            'entityID' => $each('entityID'),
            'totalISK' => $each('totalISK'),
            'totalKills' => $each('totalKills'),
            'killsArray' => $each('reduction')
          );
        })
        ->run($conn);

        $combinedResults = array_merge_recursive($combinedResults, $blockResults);
        count($blockResults) >= 1 ? $page++ : $continue = false;
      }
    }

    usort($combinedResults, function($left, $right) {
      return $right['totalISK'] <=> $left['totalISK'];
    });

    $combinedResults = array_slice($combinedResults, 0, 1000, true);

    $toEncode['stats']['ALL'] = array();
    $toEncode['stats']['US'] = array();
    $toEncode['stats']['AU'] = array();
    $toEncode['stats']['EU'] = array();

    foreach($combinedResults as $entity) {
      $entityID = $entity['entityID'];
      $entity['isAlliance'] = false;
      $entity['entityName'] = $entity['killsArray'][0]['corporationName'];
      if($entity['killsArray'][0]['allianceID'] == $entityID) {
        $entity['isAlliance'] = true;
        $entity['entityName'] = $entity['killsArray'][0]['allianceName'];
      }
      if($entityID == 0) {
        $entity['entityName'] = 'NPC';
      }
      $entityIDStr = trim(strval($entityID));
      $tzArray = array("ALL", "US", "AU", "EU");
      foreach($tzArray as $tzVal) {
        if(!isset($toEncode['stats'][$tzVal][$entityIDStr])) {
          $toEncode['stats'][$tzVal][$entityIDStr] = array();
          $toEncode['stats'][$tzVal][$entityIDStr]['isAlliance'] = $entity['isAlliance'];
          $toEncode['stats'][$tzVal][$entityIDStr]['entityID'] = $entity['entityID'];
          $toEncode['stats'][$tzVal][$entityIDStr]['entityName'] = $entity['entityName'];
          $toEncode['stats'][$tzVal][$entityIDStr]['totalKills'] = 0;
          $toEncode['stats'][$tzVal][$entityIDStr]['totalISK'] = 0;
          $toEncode['stats'][$tzVal][$entityIDStr]['shipsUsed'] = array();
          for($i = 1; $i < 10; $i++) {
            $toEncode['stats'][$tzVal][$entityIDStr]['c'.$i.'Kills'] = 0;
          }
        }
      }
      $killsSeen = array();
      foreach($entity['killsArray'] as $kill) {
        $killTime = $kill['killTime']->getTimestamp();
        $killTimeHour = date('H', $killTime);
        $killTimezone = null;
        if($killTimeHour >= 0 && $killTimeHour < 8) {
          $killTimezone = 'US';
        } elseif($killTimeHour >= 8 && $killTimeHour < 16) {
          $killTimezone = 'AU';
        } elseif($killTimeHour >= 16 && $killTimeHour < 24) {
          $killTimezone = 'EU';
        }
        if(!in_array($kill['killID'], $killsSeen)) {
          $systemID = $kill['systemID'];
          $systemData = r\table('whSystems')->get($systemID)->run($conn);
          $systemClass = $this->getClass($systemData['class']);
          if ($systemClass == 0 || $systemClass == null) {
              continue;
          }
          $toEncode['stats']['ALL'][$entityIDStr]['c'.$systemClass.'Kills'] += 1;
          $toEncode['stats'][$killTimezone][$entityIDStr]['c'.$systemClass.'Kills'] += 1;
          $toEncode['stats']['ALL'][$entityIDStr]['totalKills'] += 1;
          $toEncode['stats'][$killTimezone][$entityIDStr]['totalKills'] += 1;
          $toEncode['stats']['ALL'][$entityIDStr]['totalISK'] += $kill['value'];
          $toEncode['stats'][$killTimezone][$entityIDStr]['totalISK'] += $kill['value'];

          array_push($killsSeen, $kill['killID']);
        }
        $shipTypeID = $kill['shipTypeID'];
        if($shipTypeID != 0) {
          $shipType = r\table('shipTypes')->get($shipTypeID)->run($conn);
          $shipClass = $shipType['shipType'];

          !isset($toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass]) ? $toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass]['totalUses'] = 1 : $toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass]['totalUses'] += 1;
          !isset($toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]) ? $toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID] = $shipType : null;
          !isset($toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]['numUses']) ? $toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] = 1 : $toEncode['stats']['ALL'][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] += 1;

          !isset($toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass]) ? $toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass]['totalUses'] = 1 : $toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass]['totalUses'] += 1;
          !isset($toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]) ? $toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID] = $shipType : null;
          !isset($toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]['numUses']) ? $toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] = 1 : $toEncode['stats'][$killTimezone][$entityIDStr]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] += 1;
        }
      }
    }
    $record = array('key' => $key, 'stats' => $toEncode['stats']);
    $documentExists = r\table('generatedEntityStats')->get($key)->run($conn);
    $documentExists != null ? $result = r\table('generatedEntityStats')->get($key)->replace($record)->run($conn) :
                              $result = r\table('generatedEntityStats')->insert($record)->run($conn);
    $conn->close();
  }

  public function addEntityStatsMonth($kill, $period, $year, $month) {
    $key = md5(strtoupper('entityStats_'.$period.'_'.$year.'_'.$month));
    $entityKillSeen = array();
    $toEncode = array();
    $conn = r\connect('localhost', 28015, 'stats');
    $killTime = $kill['killTime']['date'];
    $killTimeHour = date('H', strtotime($killTime));
    $killTimezone = null;
    if($killTimeHour >= 0 && $killTimeHour < 8) {
      $killTimezone = 'US';
    } elseif($killTimeHour >= 8 && $killTimeHour < 16) {
      $killTimezone = 'AU';
    } elseif($killTimeHour >= 16 && $killTimeHour < 24) {
      $killTimezone = 'EU';
    }

    $systemID = $kill['solarSystemID'];
    $systemData = r\table('whSystems')->get($systemID)->run($conn);
    $systemClass = $this->getClass($systemData['class']);
    if ($systemClass == 0 || $systemClass == null) {
      return null;
    }
    $toEncode = r\table('generatedEntityStats')->get($key)->run($conn);
    foreach($kill['attackers'] as $attacker) {
      $entityID = $attacker['allianceID'];
      $entityAlliance = true;
      $entityName = $attacker['allianceName'];
      if($attacker['allianceID'] == 0) {
        $entityID = $attacker['corporationID'];
        $entityAlliance = false;
        $entityName = $attacker['corporationName'];
      }
      if($entityID == 0) {
        $entityName = 'NPC';
      }
      if(!in_array($entityID, $entityKillSeen)) {
        if(!isset($toEncode['stats']['ALL'])) {
          $toEncode['stats']['ALL'] = array();
        }
        $columnResult = array_column($toEncode['stats']['ALL'], 'entityID');
        $arrayKey = count($toEncode['stats']['ALL']);
        if($columnResult != null) {
          $arrayKey = array_search($entityID, $columnResult);
        }
        if(!isset($toEncode['stats']['ALL'][$arrayKey])) {
          $toEncode['stats']['ALL'][$arrayKey] = array();
          $toEncode['stats']['ALL'][$arrayKey]['isAlliance'] = $entityAlliance;
          $toEncode['stats']['ALL'][$arrayKey]['entityID'] = $entityID;
          $toEncode['stats']['ALL'][$arrayKey]['entityName'] = $entityName;
          $toEncode['stats']['ALL'][$arrayKey]['totalKills'] = 0;
          $toEncode['stats']['ALL'][$arrayKey]['totalISK'] = 0;
          $toEncode['stats']['ALL'][$arrayKey]['shipsUsed'] = array();
          for($i = 1; $i < 10; $i++) {
            $toEncode['stats']['ALL'][$arrayKey]['c'.$i.'Kills'] = 0;
          }
        }
        if(!isset($toEncode['stats'][$killTimezone][$arrayKey])) {
          $toEncode['stats'][$killTimezone][$arrayKey] = array();
          $toEncode['stats'][$killTimezone][$arrayKey]['isAlliance'] = $entityAlliance;
          $toEncode['stats'][$killTimezone][$arrayKey]['entityID'] = $entityID;
          $toEncode['stats'][$killTimezone][$arrayKey]['entityName'] = $entityName;
          $toEncode['stats'][$killTimezone][$arrayKey]['totalKills'] = 0;
          $toEncode['stats'][$killTimezone][$arrayKey]['totalISK'] = 0;
          $toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'] = array();
          for($i = 1; $i < 10; $i++) {
            $toEncode['stats'][$killTimezone][$arrayKey]['c'.$i.'Kills'] = 0;
          }
        }
        $toEncode['stats']['ALL'][$arrayKey]['c'.$systemClass.'Kills'] += 1;
        $toEncode['stats'][$killTimezone][$arrayKey]['c'.$systemClass.'Kills'] += 1;
        $toEncode['stats']['ALL'][$arrayKey]['totalKills'] += 1;
        $toEncode['stats'][$killTimezone][$arrayKey]['totalKills'] += 1;
        $toEncode['stats']['ALL'][$arrayKey]['totalISK'] += $kill['zkb']['totalValue'];
        $toEncode['stats'][$killTimezone][$arrayKey]['totalISK'] += $kill['zkb']['totalValue'];
        array_push($entityKillSeen, $entityID);
      }
      $shipTypeID = $attacker['shipTypeID'];
      if($shipTypeID != 0) {
        $shipType = r\table('shipTypes')->get($shipTypeID)->run($conn);
        if($shipType != null) {
          $shipClass = $shipType['shipType'];
          !isset($toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass]) ? $toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass]['totalUses'] = 1 : $toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass]['totalUses'] += 1;
          !isset($toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]) ? $toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID] = $shipType : $toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID] = $shipType;
          !isset($toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]['numUses']) ? $toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] = 1 : $toEncode['stats']['ALL'][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] += 1;
          !isset($toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass]) ? $toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass]['totalUses'] = 1 : $toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass]['totalUses'] += 1;
          !isset($toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]) ? $toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID] = $shipType : $toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID] = $shipType;
          !isset($toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]['numUses']) ? $toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] = 1 : $toEncode['stats'][$killTimezone][$arrayKey]['shipsUsed'][$shipClass][$shipTypeID]['numUses'] += 1;
        }
      }
    }
    $record = array('key' => $key, 'stats' => $toEncode['stats']);
    $documentExists = r\table('generatedEntityStats')->get($key)->run($conn);
    $documentExists != null ? $result = r\table('generatedEntityStats')->get($key)->replace($record)->run($conn) :
                              $result = r\table('generatedEntityStats')->insert($record)->run($conn);
    $conn->close();
  }
}
