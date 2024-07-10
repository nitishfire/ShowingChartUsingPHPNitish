<html>
<head>
    <link rel="stylesheet" href="./CSS/Sidebar.css">
    <link rel="stylesheet" href="./CSS/MainBody.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.1/dist/chartjs-plugin-zoom.min.js"></script>
    <style>
        .slider {
            accent-color: purple;
            width: 300px;
        }

        .controls {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php
include('./DBConnection.php');
$query = $conn->query("SELECT topic, intensity FROM data");
$topics = [];
foreach ($query as $data) {
    $topics[] = $data['topic'];
    $intensity[] = $data['intensity'];
}

$uniqueTopics = array_unique($topics);
sort($uniqueTopics);
?>

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="./Dashboard_1.php">Intensity Over Years [Line Chart]</a>
  <a href="./Dashboard_2.php">Likelihood Over Years [Line Chart]</a>
  <a href="./Dashboard_3.php">Relevance Over Years [Line Chart]</a>
  <a href="./Dashboard_4.php">Intensity Over Country [Bar Chart]</a>
  <a href="./Dashboard_5.php">Likelihood Over Country [Bar Chart]</a>
  <a href="./Dashboard_6.php">Relevance Over Country [Bar Chart]</a>
  <a href="./Dashboard_7.php">Distribution of Topics [Pie Chart]</a>
  <a href="./Dashboard_8.php">Distribution of Ssector [Pie Chart]</a>
  <a href="./Dashboard_9.php">Intensity VS Likelihood [Scatter Chart]</a>
</div>

<span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Nitish Project</span>
<br><br>

<center>
    <div id="d1">
        <div class="filter">
            <b><label style="font-family: 'Lato', sans-serif; background-color: #F5F6FA;">Filters</label></b>
            <div class="custom-select">
                <select id="start">
                    <option>Select Start Topic</option>
                    <?php foreach ($uniqueTopics as $c): ?>
                        <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="end">
                    <option>Select End Topic</option>
                    <?php foreach ($uniqueTopics as $c): ?>
                        <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button onclick="applyFilter()" class="btncss">Apply Filter</button>
        </div>
        <br>
        <div id="page1" style="width: 1200px; height: 500px;">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</center>

<script>
    var labels = <?php echo json_encode($uniqueTopics); ?>;
    var data = <?php echo json_encode($intensity); ?>;

    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }

    const ctx = document.getElementById('myChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Distribution of Topics Over Intensity',
                data: data,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                zoom: {
                    pan: {
                        enabled: true,
                        mode: 'x'
                    },
                    zoom: {
                        enabled: true,
                        mode: 'x'
                    }
                }
            }
        }
    });

    function applyFilter() {
        const start = document.getElementById('start').value.trim();
        const end = document.getElementById('end').value.trim();

        if (start === "" || end === "") {
            alert('Please select both start topic and end topic.');
            return;
        }

        const filteredLabels = [];
        const filteredData = [];

        for (let i = 0; i < labels.length; i++) {
            if (labels[i] >= start && labels[i] <= end) {
                filteredLabels.push(labels[i]);
                filteredData.push(data[i]); 
            }
        }

        chart.data.labels = filteredLabels;
        chart.data.datasets[0].data = filteredData;
        chart.update();
    }
</script>

</body>
</html>
