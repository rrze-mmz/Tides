// resources/js/chartsSetup.js
import Chart from 'chart.js/auto';

export function generatePieChart(data) {
  new Chart(document.getElementById('tides-pie'), {
    type: 'pie',
    data: {
      labels: data.map((row) => row.name),
      datasets: [
        {
          data: data.map((row) => row.count),
          backgroundColor: [
            'rgb(54, 162, 235)',
            'rgb(240,6,6)',
            'rgb(42,206,10)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)',
          ],
        },
      ],
    },
  });
}

export function generateLineChart(data) {
  const labels = Object.keys(data).sort(); // Sort dates
  const dataWorld = labels.map((date) => data[date].total_world);
  const dataBavaria = labels.map((date) => data[date].total_bavaria);
  const dataGermany = labels.map((date) => data[date].total_germany);
  new Chart(document.getElementById('tides-line'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Bavaria',
          data: dataBavaria,
          borderColor: 'rgb(54, 162, 235)',
          borderWidth: 1,
        },
        {
          label: 'Germany',
          data: dataGermany,
          borderColor: 'rgb(240,6,6)',
          borderWidth: 1,
        },
        {
          label: 'World',
          data: dataWorld,
          borderColor: 'rgb(42,206,10)',
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}

export function generateBarChart(data) {
  new Chart(document.getElementById('tides-bar'), {
    type: 'bar',
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
    data: {
      labels: data.map((row) => row.name),
      datasets: [
        {
          label: 'Clips Views',
          data: data.map((row) => row.count),
          // fill: false,
          // backgroundColor: [
          //   'rgb(54, 162, 235)',
          //   'rgb(240,6,6)',
          //   'rgb(42,206,10)',
          //   'rgba(75, 192, 192, 0.2)',
          //   'rgba(54, 162, 235, 0.2)',
          //   'rgba(153, 102, 255, 0.2)',
          //   'rgba(201, 203, 207, 0.2)',
          // ],
          // borderColor: [
          //   'rgb(255, 99, 132)',
          //   'rgb(255, 159, 64)',
          //   'rgb(255, 205, 86)',
          //   'rgb(75, 192, 192)',
          //   'rgb(54, 162, 235)',
          //   'rgb(153, 102, 255)',
          //   'rgb(201, 203, 207)',
          // ],
          borderWidth: 1,
        },
      ],
    },
  });
}
