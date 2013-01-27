<?php 
	$file = fopen('hw2dataset/graph_8.txt','r');
	$graph = array();
	while(!feof($file)) {
		$input = fgets($file);
		$output = explode(',',$input);
		
		if(!isset($graph[(int)$output[0]])) {
			$graph[(int)$output[0]] = array();
		}
		if(!isset($graph[(int)$output[1]])) {
			$graph[(int)$output[1]] = array();
		}
		array_push($graph[(int)$output[0]],(int)$output[1]);
	}
	var_dump($graph);
	//echo count($graph);
	
	function cal_graph($graph) {
		$auth = array();
		$auth_temp = array();
		$hub = array();
		$hub_temp = array();
		
		foreach($graph as $node => $outbound) {
			$auth[$node] = 1/count($graph);
			$hub[$node] = 1/count($graph);
			$auth_temp[$node] = 0;
			$hub_temp[$node] = 0;
		}
		var_dump($auth);
		$times = 0;
		$change = 0;
		while($times<100) {
			$times++;
			foreach($graph as $node => $outbound) {
				$auth_temp[$node] = $auth[$node];
				$hub_temp[$node] = $hub[$node];
			}
			
			foreach($graph as $node => $outbound) {
				foreach($outbound as $outlink) {
					$auth[$outlink] += $hub[$node];
				}
			}
		
		 	foreach($graph as $node => $outbound) {
				foreach($outbound as $outlink) {
					$hub[$node] += $auth[$node];
				}
			}  

			$sum_auth = 0;
			$sum_hub = 0;
			foreach($graph as $node => $outbound) {
					$sum_auth += $auth[$node];
					$sum_hub += $hub[$node];
			} 
			
			foreach($graph as $node => $outbound) {
				$auth[$node] = $auth[$node]/$sum_auth;
				$hub[$node] = $hub[$node]/$sum_hub;
			}

			if(( abs($auth_temp[$node] - $auth[$node]) + abs($hub_temp[$node] - $hub[$node]) )<0.001) {
				break;
			}
		}
		echo $times;
	//	return array('auth' => $auth, 'hub' => $hub, 'auth_temp' => $auth_temp, 'hub_temp' => $hub_temp);
		return array('auth' => $auth, 'hub' => $hub);
	}
	$new = cal_graph($graph);
	var_dump($new);
 	$fp = fopen("ans_8.txt","w");
	$countNode = count($new['auth']);
	
	for ($i = 1; $i <= $countNode; $i++) {
			fwrite($fp,$i."	".$new['auth'][$i]."	".$new['hub'][$i]."\r\n");
	}
	
	fclose($fp); 
	fclose($file);


?>