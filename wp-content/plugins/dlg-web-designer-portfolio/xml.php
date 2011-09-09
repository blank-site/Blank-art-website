<? 




require( '../../../wp-load.php' );
	
	global $wpdb;
	
	
	
	echo '<media>';
		$results = $wpdb->get_results("SELECT * FROM wp_dlg_portfolio where type = 1 order by sort", ARRAY_A);

	
	
	
	for($i=0;$i<count($results);$i++){
		
		
		echo '	<image>
		<source>/wp-content/plugins/dlg-portfolio/uploads/'.$results[$i]['banner'].'</source>
		<thumb>/wp-content/plugins/dlg-portfolio/uploads/thumbs/'.$results[$i]['picture'].'</thumb>
		<url>?pid='.$results[$i]['id'].'</url>
		<timerDelay>3</timerDelay>
		<target>_self</target>
	<effect>wave</effect>
		<text><![CDATA[<head>'.$results[$i]['name'].'</head>]]></text>
		<startX>492|300|340|512|585|695|330</startX>
		<finishX>492|320|370|502|600|680|320</finishX>
		
		<startY>10|20|110|30|120|10|-50</startY>
		<finishY>180|20|110|30|120|10|290</finishY>
		
		<!--
		<startY>180|10|10|10|10|10|-50</startY>
		<finishY>190|20|70|120|170|220|290</finishY>
		-->
		<startAlpha>0|0|0|0|0|0|0</startAlpha>
		<finishAlpha>1|1|1|1|1|1|1</finishAlpha>
		<backColor>0x152b36|0x152b36|0x152b36|0x152b36|0x152b36|0x152b36|0xe8e0e0</backColor>
		<backAlpha>1|1|1|1|1|1|1</backAlpha>
		<startRotation>90|0|0|0|0|0|90</startRotation>
		<finishRotation>0|0|0|0|0|0|0</finishRotation>
		<startScaleX>1|1|1|1|1|1|1</startScaleX>
		<finishScaleX>1|1|1|1|1|1|1</finishScaleX>
		<startScaleY>1|1|1|1|1|1|1</startScaleY>
		<finishScaleY>1|1|1|1|1|1|1</finishScaleY>
		<transitions>easeoutsine|easeoutsine|easeoutsine|easeoutsine|easeoutsine|easeoutsine|easeoutsine</transitions>
		<times>.7|.7|.7|.7|.7|.7|.7</times>
		<delay>0|1.6|1.2|.8|1|1.4|2.5</delay>
		<!--
		<delay>0|.8|1|1.2|1.4|1.6|2.5</delay>
		-->
	</image> ';
		
	}
	
	
	echo '</media>';

?>