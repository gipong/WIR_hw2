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
	
	function cal_graph($graph) {
		$pageRank = array();
		$tempRank = array();
		$change = 1;
		$times = 0;
		
		foreach($graph as $node => $outbound) {
			$pageRank[$node] = 1;
			$tempRank[$node] = 0;
		}
		
		while($change>0.00001 && $times<100) {
			$change = 0;
			$times++;
			
			foreach($graph as $node => $outbound) {
				$outboundCount = count($outbound);
				foreach($outbound as $outlink) {
					$tempRank[$outlink] += $pageRank[$node]/$outboundCount;
				}
			}
		
			foreach($graph as $node => $outbound) {
				$tempRank[$node] = 0.15 + 0.85*$tempRank[$node];
				$change += abs($pageRank[$node] - $tempRank[$node]);
				$pageRank[$node] = $tempRank[$node];
				$tempRank[$node] = 0;
			}
		}
		
		return $pageRank;
	}
	$new = cal_graph($graph);
	var_dump($new);
	
	$fp = fopen("ans8.txt","w");
	foreach($new as $node => $outbound) {
			fwrite($fp,$node."	".$new[$node]."\r\n");
	}
	
	fclose($fp);
	fclose($file);


?>