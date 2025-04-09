(async function() {
  const data = [
    { month: 'Sausis', count: monthlyLoans[0] },
    { month: 'Vasaris', count: monthlyLoans[1] },
    { month: 'Kovas', count: monthlyLoans[2] },
    { month: 'Balandis', count: monthlyLoans[3] },
    { month: 'Gegužė', count: monthlyLoans[4] },
    { month: 'Birželis', count: monthlyLoans[5] },
    { month: 'Liepa', count: monthlyLoans[6] },
    { month: 'Rugpjūtis', count: monthlyLoans[7] },
    { month: 'Rugsėjis', count: monthlyLoans[8] },
    { month: 'Spalis', count: monthlyLoans[9] },
    { month: 'Lapkritis', count: monthlyLoans[10] },
    { month: 'Gruodis', count: monthlyLoans[11] }
  ];

  let title = "Inventoriaus paskolos".concat(" ", year, " ", "m.");

  new Chart(
    document.getElementById('acquisitions'),
    {
      type: 'bar',
      data: {
        labels: data.map(row => row.month),
        datasets: [
          {
            label: 'Inventoriaus paskolos (vnt.)',
            data: data.map(row => row.count)
          }
        ]
      },
      options: {
        plugins: {
          legend: {
            display: false // This hides the legend
          },
          title: {
            display: true,
            text: title,
            font: {
              size: 24
            }
          }
        },
        scales: {
          y: {
              ticks: {
                  callback: function(value, index, ticks) {
                      return value + ' vnt.';
                  },
                  stepSize: 1
              }
          }
        }
      }
    }
  );
})();