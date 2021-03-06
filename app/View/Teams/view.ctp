<ul class="nav nav-pills pull-right">
	<li role="presentation" class="active">
		<a href="<?php echo $this->Html->url('/Teams/view/' . $team['Team']['id']); ?>">
			<span class="glyphicon glyphicon-home"></span>
		</a>
	</li>

	<li role="presentation">
		<a href="<?php echo $this->Html->url('/BacklogColumns/view/' . $team['Team']['id']); ?>">
			<span class="glyphicon glyphicon-list"></span>
		</a>
	</li>

	<li role="presentation">
		<a href="<?php echo $this->Html->url('/Sprints/view/' . $team['Team']['id']); ?>">
			<span class="glyphicon glyphicon-gift"></span>
		</a>
	</li>

	<li role="presentation">
		<a href="<?php echo $this->Html->url('/Dailys/view/' . $team['Team']['id']); ?>">
			<span class="glyphicon glyphicon-calendar"></span>
		</a>
	</li>
</ul>

<div class="page-header">
  <h1><?php echo $team['Team']['name']; ?></h1>
</div>


<br>

<div>
	<div class="col-md-8">
		<div id="container" style="width:100%; height:400px;"></div>
	</div>

	<div class="col-md-4">
		<div id="container2" style="width:100%; height:400px;"></div>
	</div>

<?php
$series = array();
$lastDay = array();
if (count($matrix)) {
	foreach ($matrix as $i => $col) {
		$serieVals = array();


		$categories = array();
		foreach ($col as $j => $lin) {
			array_push($categories, $j);
			array_push($serieVals, $lin);

			

			$pieSerie = '[\'' . $i . '\', ' . $lin . ']';
		}

		$serie = '{';
		$serie .= 'name: \'' . $i . '\',';
		$serie .= 'data: [';
		$serie .= implode(', ', $serieVals);
		$serie .= ']';
		$serie .= '}';

		array_push($series, $serie);

		array_push($lastDay, $pieSerie);
	}

}

$categoriesLbl = implode('\',\'', $categories);
?>


	<script type="text/javascript">

$(function () {

var titleText;
var subtitleText;
var pointsText;
var tooltipText;

var title = "<?php echo $team['Team']['method']; ?>";


if (title == 'KANBAN') {
    titleText = 'CFD (Cumulative Flow Diagrams)';
    subtitleText = '';
    pointsText = 'User Story points';
    tooltipText = ' US';
} else {
    titleText = 'Sprint Burnup';
    subtitleText = 'Meta do Sprint';
    pointsText = 'Story points';
    tooltipText = ' points';
}

$('#container').highcharts({
        chart: {
            type: 'area'
        },
        title: {
            text: titleText
        },
        subtitle: {
            text: subtitleText
        },
        xAxis: {
            categories: ['<?php echo $categoriesLbl; ?>'],
            
            title: {
                enabled: false
            }
        },
        yAxis: {
            title: {
                text: pointsText
            },
        },
        tooltip: {
            shared: true,
            valueSuffix: tooltipText
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666'
                }
            }
        },
        series: [
        	<?php echo implode(',', $series); ?>
        ]
    });



if (title != 'KANBAN') {    
    $('#container2').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Sprint Backlog'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                	<?php echo implode(',', $lastDay); ?>
                    
                ]
            }]
        });
}

});

	</script>



</div>