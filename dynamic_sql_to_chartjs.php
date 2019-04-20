<div class="col-md-6"><canvas id="chart1" width='300' height='200'></canvas></div>
<div class="col-md-6"><canvas id="chart2" width='300' height='200'></canvas></div>
<div class="col-md-6"><canvas id="chart3" width='300' height='200'></canvas></div>
<div class="col-md-6"><canvas id="chart4" width='300' height='200'></canvas></div>

<script>
		<?php
		  $sql1 = "SELECT m.name AS `month`, 
					  (SELECT SUM(d.value) FROM sample_data_2 d WHERE d.sex_id = 1 AND d.month_id =m.id ) AS `M`, 
					  (SELECT SUM(d.value) FROM sample_data_2 d WHERE d.sex_id = 2 AND d.month_id =m.id) AS `F`, 
					  (SELECT SUM(d.value) FROM sample_data_2 d WHERE d.sex_id = 3 AND d.month_id =m.id) AS `O` 
					  FROM 
					  		sample_data_2 d, 
					  		sample_data_months m 
					  WHERE 
					  		d.month_id = m.id 
					  GROUP BY m.id;";
		  
		  $sql2 = "SELECT m.name `month`, 
		  				SUM(d.value) `total` 
		  			  FROM sample_data_1 d, 
		  			  		  sample_data_months m 
		  			  WHERE d.month_id = m.id 
		  			  GROUP BY m.id;";
		  
		  echo generateChartJs("chart1", 'line', "Line Chart", "description", $sql1);
		  echo generateChartJs("chart2", 'bar', "Bar Chart", "description", $sql2);
		  echo generateChartJs("chart3", 'doughnut', "Doughnut Chart", "description", $sql2);
		  echo generateChartJs("chart4", 'horizontalBar', "Horizontal Bar Chart", "description", $sql1);		  
		?>
</script>

<?php
/*
 * Written by William Sengdara 
 *
 * Generates data dynamically from a SQL query for ChartJS :
 *  first column is used as the labels
 *  all other columns will be used as the datasets
 **/
function generateChartJs($chartid, $type, $title, $description, $sql){
		global $database;
		
		$res = $database->query($sql) or 
        die(alertbuilder($database->error, 'danger'));

		if (!$res || !$res->num_rows){
			return false;
		} 

		$row = $res->fetch_assoc();
		$cols = array_keys($row);
		
		$labels = array();
		$datasets = array();
		
		if (!sizeof($cols) or sizeof($cols) < 2){
			//"No columns or need more than 1 column");
			return false;
		}
		
		mysqli_data_seek($res,0);
			 		
		while ($row = $res->fetch_assoc()){
			foreach ($cols as $key=>$val){
				switch ($key){
					case 0: /* first column should be your labels */
						$label = $row[$val];
						$labels[] = "'$label'";
						break;
						
					default:
						$data = $row[$val];
						$data = $data ? $data : 0; 
						$datasets[$key-1][] = $data;
						break;
				}
			}
		}
     
     $colors = array();
     $colors[] = "'rgba(54, 162, 235, 0.7)'";
     $colors[] = "'rgba(255, 159, 64, 0.7)'";
     $colors[] = "'rgba(255, 99, 132, 0.7)'";
     $colors[] = "'rgba(75, 192, 192, 0.7)'";
     $colors[] = "'rgba(153, 102, 255, 0.7)'";
     $colors[] = "'rgba(83, 90, 55, 0.7)'";
     $colors[] = "'rgba(113, 102, 25, 0.7)'";
     $colors[] = "'rgba(13, 62, 85, 0.7)'";
     $colors[] = "'rgba(73, 120, 155, 0.7)'";
     $colors[] = "'rgba(154, 62, 245, 0.7)'";                  
     $colors[] = "'rgba(204, 162, 235, 0.7)'";
     $colors[] = "'rgba(55, 159, 64, 0.7)'";
     $colors[] = "'rgba(85, 99, 132, 0.7)'";
     $colors[] = "'rgba(95, 192, 192, 0.7)'";
     $colors[] = "'rgba(53, 102, 255, 0.7)'";
     $colors[] = "'rgba(43, 90, 55, 0.7)'";
     $colors[] = "'rgba(103, 102, 25, 0.7)'";
     $colors[] = "'rgba(130, 62, 85, 0.7)'";
     $colors[] = "'rgba(33, 120, 155, 0.7)'";
     $colors[] = "'rgba(194, 62, 245, 0.7)'";
                       
	$max_data_cols = sizeof($datasets[0]);
	$labels = implode(",", $labels);

	$datasets_ = array();

	foreach($datasets as $key=>$datas){ 
		$data = implode(",",$datas);
		$label = $cols[$key+1]; // ignore the first column as it is our label

		$temp = array();
      for($idx=0; $idx<$max_data_cols; $idx++){ 
      	$temp[] = $colors[$key];
      }
		$colors_ = implode(',', $temp);

		$datasets_[] = "{
							label: '$label',					            
						    data: [$data],
						    backgroundColor: [$colors_],
				            borderColor: [],
				            borderWidth: 1
						}";
	}

	$datasets = implode(",", $datasets_);

	$chartdata = "var ctx = document.getElementById('$chartid').getContext('2d');
					  new Chart(ctx, {
	    						type: '$type',
	    						data: {
	    									labels: [ $labels ], 
	    									datasets: [ $datasets ]
	    								},
							    options: {
										        title: {
										            display: true,
										            text: '$title'
										        }
											   }
								});";
								
	return $chartdata;
}

?>
