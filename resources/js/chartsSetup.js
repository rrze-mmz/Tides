// resources/js/chartsSetup.js
import Chart from 'chart.js/auto';

// Fill dates will fill the days without views with zero
function fillDates(data) {
  const sortedKeys = Object.keys(data).sort();
  const startDate = new Date(sortedKeys[0].substr(0, 10)); // start from the first data point
  const endDate = new Date(sortedKeys[sortedKeys.length - 1].substr(0, 10)); // end at the last data point

  const filledData = {};
  for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
    const dateKey = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(
      2,
      '0'
    )}-${String(d.getDate()).padStart(2, '0')} 00:00:00`;
    filledData[dateKey] = data[dateKey] || 0; // Fill with 0 if no data for this day
  }

  return filledData;
}

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
    responsive: true,
    maintainAspectRatio: false,
  });
}

export function generateClipViewsLineChart(jsonData) {
  // const completeData = fillDates(jsonData);
  const labels = Object.keys(jsonData).sort();
  const data = labels.map((label) => jsonData[label]);
  new Chart(document.getElementById('tides-clip-views-line'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          data: data,
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        xAxes: [
          {
            type: 'time',
            time: {
              unit: 'day',
              displayFormats: {
                day: 'MMM DD, YYYY',
              },
            },
            distribution: 'linear',
          },
        ],
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
            },
          },
        ],
      },
      responsive: true,
      maintainAspectRatio: false,
    },
  });
}

export function generateGeolocationLineChart(data) {
  const labels = Object.keys(data).sort(); // Sort dates
  const dataWorld = labels.map((date) => data[date].total_world);
  const dataBavaria = labels.map((date) => data[date].total_bavaria);
  const dataGermany = labels.map((date) => data[date].total_germany);
  new Chart(document.getElementById('tides-geolocation-line'), {
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
    responsive: true,
    maintainAspectRatio: false,
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
          borderWidth: 1,
        },
      ],
    },
  });
}
