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
  $query = $conn->query("SELECT intensity, relevance, likelihood, end_year, start_year FROM data");
  foreach($query as $data) {
    $intensity[] = $data['intensity'];
    $relevance[] = $data['relevance'];
    $likelihood[] = $data['likelihood'];
    $startyear[] = $data['start_year'];
    $endyear[] = $data['end_year'];
  }

  $uniqueEndYears = array_unique($endyear);
  sort($uniqueEndYears);
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
          <b><label style="font-family: 'Lato', sans-serif; background-color: #F5F6FA;">Filters</label></b>
          <div class="custom-select">
            <select id="startYear">
              <option>Select Start Year</option>
              <?php foreach($uniqueEndYears as $year): ?>
              <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
              <?php endforeach; ?>
            </select>
            <select id="endYear">
              <option>Select End Year</option>
              <?php foreach($uniqueEndYears as $year): ?>
              <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button onclick="applyFilter()" class="btncss">Apply Filter</button>
    </div>
  </br>
    <div id="page1" style="width:1200px; height: 500px;">
      <canvas id="myChart"></canvas>

      <div class="controls">
          <button onclick="zoomIn()" class="btncss">Zoom In</button>
          <button onclick="zoomOut()" class="btncss">Zoom Out</button></br></br>
      </div>
    </div>
  </div>
</center>

<script>
var labels = <?php echo json_encode($uniqueEndYears); ?>;
var allIntensity = <?php echo json_encode($intensity); ?>;
var allEndYears = <?php echo json_encode($uniqueEndYears); ?>;

function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

const ctx = document.getElementById('myChart').getContext('2d');
let chart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [
      {
        label: 'Intensity Over Years',
        data: allIntensity,
        borderColor: '#5752D1',
        borderWidth: 1,
        fill: false
      }
    ]
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
    },
    scales: {
      x: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Years'
        }
      },
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Intensity'
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

function applyFilter() {
  const startYear = parseInt(document.getElementById('startYear').value);
  const endYear = parseInt(document.getElementById('endYear').value);

  if (isNaN(startYear) || isNaN(endYear)) {
    alert('Please select both start year and end year.');
    return;
  }

  const filteredLabels = [];
  const filteredData = [];

  for (let i = 0; i < allEndYears.length; i++) {
    if (allEndYears[i] >= startYear && allEndYears[i] <= endYear) {
      filteredLabels.push(allEndYears[i]);
      filteredData.push(allIntensity[i]);
    }
  }

  chart.data.labels = filteredLabels;
  chart.data.datasets[0].data = filteredData;
  chart.update();
}

</script>

</body>
</html>
