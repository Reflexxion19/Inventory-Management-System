(async function() {
  const data = [
    { month: 'Sausis', count: 1 },
    { month: 'Vasaris', count: 2 },
    { month: 'Kovas', count: 3 },
    { month: 'Balandis', count: 4 },
    { month: 'Gegužė', count: 5 },
    { month: 'Birželis', count: 6 },
    { month: 'Liepa', count: 7 },
    { month: 'Rugpjūtis', count: 8 },
    { month: 'Rugsėjis', count: 9 },
    { month: 'Spalis', count: 10 },
    { month: 'Lapkritis', count: 11 },
    { month: 'Gruodis', count: 12 }
  ];

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
            text: 'Inventoriaus paskolos 2025 m.',
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
                  }
              }
          }
        }
      }
    }
  );
})();