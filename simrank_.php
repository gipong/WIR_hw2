<?php
	$file = fopen('hw2dataset/graph_8.txt','r');
	$graph = array();
	$update_graph = array();
	while(!feof($file)) {
		$input = fgets($file);
		$output = explode(',',$input);
		if(!isset($graph[(int)$output[0]])) {
			$graph[(int)$output[0]] = array();
			$update_graph[(int)$output[0]] = array();
		}
		if(!isset($graph[(int)$output[1]])) {
			$graph[(int)$output[1]] = array();
			$update_graph[(int)$output[1]] = array();
		}
		array_push($graph[(int)$output[0]],(int)$output[1]);
		array_push($update_graph[(int)$output[0]],(int)$output[1]);
	}
	
	
	function cal_graph($graph, $update_graph) {
		$Nnode = count($graph);
		$record = array();
		$record_t = array();
		$ind = array();
		$times = 0;
		
		foreach($graph as $node => $outbound) {
			foreach($outbound as $outlink) {
				if($node == $outlink)
					continue;
				if(!in_array($node,$update_graph[$outlink]))
					array_push($update_graph[$outlink],$node);
			}
		}
		
		foreach($graph as $node => $outbound) {
			$record[$node] = array();
			for ($i = 1; $i <= $Nnode; $i++) {
				if($i == $node) {
					$record[$node][$i] = 1;
					$record_t[$node][$i] = 1;
				}
				else { 
					$record[$node][$i] = 0;
					$record_t[$node][$i] = 0;
				}
			}
		}
		
		foreach($update_graph as $node => $outbound) {
			$ind[$node] = array();
			foreach($outbound as $outlink) {
				$outboundCount = count($outbound);
				if($outboundCount>0)
					$ind[$node] = $outboundCount;
				else 
					$ind[$node] = 0;
			}
		}
		
		while($times<100) {
			$times++;
			
			foreach($graph as $node => $outbound) {
				foreach($graph as $Nnode => $outbound) {
					if($Nnode == $node)
						continue;
					$sum = 0;
					
					for ($i = 0; $i < count($update_graph[$node]); $i++) {
						for ($j = 0; $j < count($update_graph[$Nnode]); $j++) {
							$sum += $record[$update_graph[$node][$i]][$update_graph[$Nnode][$j]];
						//	echo "i: ".$update_graph[$node][$i]." +++ j:".$update_graph[$Nnode][$j]."<br />";
						}
					}
					$record_t[$node][$Nnode] = $sum*( 0.6/($ind[$node]*$ind[$Nnode]));
				}
			}
			
			for ($i = 1; $i <= $Nnode; $i++) {
				for ($j = 1; $j <= $Nnode; $j++) {
					$record[$i][$j] = $record_t[$i][$j];
				}
			}
			
		}
		
		return $record;
	}
	$new = cal_graph($graph, $update_graph);
	var_dump($new);
	
	$fp = fopen("ans8.txt","w");
	$countNode = count($new);
	echo $countNode;
	for($i = 1; $i <= $countNode; $i++) {
		$str = '';
		for($j = 1; $j <= $countNode; $j++) {
			$str = $str."	".$new[$i][$j];
		}
		fwrite($fp,$i.$str."\r\n");
	}
	
	fclose($fp);
	fclose($file);



?>