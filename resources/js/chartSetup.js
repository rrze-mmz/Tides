// resources/js/chartSetup.js
import Chart from 'chart.js/auto';

export function generateChart(type, data, mainLabel) {
  new Chart(document.getElementById('tides-pie'), {
    type: type,
    data: {
      labels: data.map((row) => row.name),
      datasets: [
        {
          label: mainLabel,
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
