<?
$bundle = \backend\themes\shop\pageAssets\shop\statistics\managerOrders::register($this);
?>

<? foreach($arr as $key=>$chart) { ?>
    <div class="panel panel-flat">
        <div class="panel-body">
            <h5 class="panel-title"><?=$key;?></h5>
            <div class="chart-container">
                <div class="chart" id="google-combo-<?=$key;?>"></div>
            </div>
        </div>
    </div>
<? } ?>

<script>
    // Chart settings
    function drawCombo() {
        <?
        foreach($arr as $key=>$chart) {
        ?>

        // Options
        var options_combo<?=$key;?> = {
            fontName: 'Roboto',
            height: 400,
            fontSize: 12,
            chartArea: {
                left: '5%',
                width: '90%',
                height: 350
            },
            seriesType: "bars",
            series: {
                <?=count($chart['0'])-2;?>: {
                    type: "line",
                    pointSize: 10
                }
            },
            tooltip: {
                textStyle: {
                    fontName: 'Roboto',
                    fontSize: 13
                }
            },
            vAxis: {
                gridlines:{
                    color: '#e5e5e5',
                    count: 10
                },
                minValue: 0
            },
            legend: {
                position: 'top',
                alignment: 'center',
                textStyle: {
                    fontSize: 12
                }
            }
        };

        <?
            echo 'var data'.$key.' = google.visualization.arrayToDataTable(['."\r\n";
            foreach($chart as $line) {
                echo '['.implode(', ', $line).'],'."\r\n";
            }
            echo ']);';
            echo 'var combo = new google.visualization.ComboChart($(\'#google-combo-'.$key.'\')[0]);';
            echo 'combo.draw(data'.$key.', options_combo'.$key.');';
        }
        ?>

    }
</script>