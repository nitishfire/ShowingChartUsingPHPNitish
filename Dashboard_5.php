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
  $query = $conn->query("SELECT intensity, relevance, likelihood, country FROM data");
  foreach($query as $data) {
    $intensity[] = $data['intensity'];
    $relevance[] = $data['relevance'];
    $likelihood[] = $data['likelihood'];
    $country[] = $data['country'];
  }

  $uniqueCountry = array_unique($country);
  sort($uniqueCountry);
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
              <option>Select Start Country</option>
              <?php foreach($uniqueCountry as $c): ?>
              <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
              <?php endforeach; ?>
            </select>
            <select id="end">
              <option>Select End Country</option>
              <?php foreach($uniqueCountry as $c): ?>
              <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
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
var labels = <?php echo json_encode($uniqueCountry); ?>;
var alllikelihood = <?php echo json_encode($likelihood); ?>;
var all = <?php echo json_encode($uniqueCountry); ?>;

function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

const ctx = document.getElementById('myChart').getContext('2d');
let chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [
      {
        label: 'Relevance Over Years',
        data: alllikelihood,
        backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
        ],
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
          text: 'Country'
        }
      },
      y: {
        beginAtZero: true,
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

function applyFilter() {
  const start = document.getElementById('start').value.trim();
  const end = document.getElementById('end').value.trim();

  if (start === "" || end === "") {
    alert('Please select both start country and end country.');
    return;
  }

  const filteredLabels = [];
  const filteredData = [];

  for (let i = 0; i < all.length; i++) {
    if (all[i] >= start && all[i] <= end) { 
      filteredLabels.push(labels[i]); 
      filteredData.push(alllikelihood[i]);
    }
  }

  chart.data.labels = filteredLabels;
  chart.data.datasets[0].data = filteredData;
  chart.update();
}


</script>

</body>
</html>
