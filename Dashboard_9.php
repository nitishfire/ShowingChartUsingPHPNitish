<!DOCTYPE html>
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
  $intensity = [];
  $likelihood = [];
  $query = $conn->query("SELECT intensity, relevance, likelihood, country FROM data");
  foreach($query as $data) {
    $intensity[] = $data['intensity'];
    $likelihood[] = $data['likelihood'];
  }

  $uniqueIntensity = array_unique($intensity);
  sort($uniqueIntensity);
  $uniqueLikelihood = array_unique($likelihood);
  sort($uniqueLikelihood);
?>

<div id="mySidenav" class="sidenav">
<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="./index.php">Intensity Over Years [Line Chart]</a>
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
      <!-- Your filter options here -->
    </div>
    <br>
    <div id="page1" style="width:1200px; height: 500px;">
      <canvas id="myChart"></canvas>
      <div class="controls">
        <button onclick="zoomIn()" class="btncss">Zoom In</button>
        <button onclick="zoomOut()" class="btncss">Zoom Out</button><br><br>
      </div>
    </div>
  </div>
</center>

<script>
var x = <?php echo json_encode($uniqueIntensity); ?>;
var y = <?php echo json_encode($uniqueLikelihood); ?>;

function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

const ctx = document.getElementById('myChart').getContext('2d');
let chart = new Chart(ctx, {
  type: 'scatter',
  data: {
    datasets: [{
      label: 'Intensity vs Likelihood',
      data: x.map((val, index) => ({ x: val, y: y[index] })),
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      zoom: {
        pan: {
          enabled: true,
          mode: 'xy'
        },
        zoom: {
          enabled: true,
          mode: 'xy'
        }
      }
    },
    scales: {
      x: {
        type: 'linear',
        position: 'bottom',
        title: {
          display: true,
          text: 'Intensity'
        }
      },
      y: {
        type: 'linear',
        position: 'left',
        title: {
          display: true,
          text: 'Likelihood'
        }
      }
    }
  }
});

function zoomIn() {
  chart.zoom(1.1);
}

function zoomOut() {
  chart.zoom(0.9);
}
</script>

</body>
</html>
