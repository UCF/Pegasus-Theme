function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.'),
        x1 = x[0],
        x2 = x.length > 1 ? '.' + x[1] : '',
        rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function init() {
    // Font Defaults
    Chart.defaults.global.defaultFontColor = '#000';

    // Tooltip Defaults
    Chart.defaults.global.tooltips.bodyFontFamily = "'Gotham SSm 7r','Gotham SSm A','Gotham SSm B',sans-serif";
    Chart.defaults.global.tooltips.bodyFontSize = 14;
    Chart.defaults.global.tooltips.titleFontFamily = "'Gotham SSm 7r','Gotham SSm A','Gotham SSm B',sans-serif";
    Chart.defaults.global.tooltips.titleFontSize = 14;
    Chart.defaults.global.tooltips.xPadding = 15;
    Chart.defaults.global.tooltips.yPadding = 15;
    Chart.defaults.global.tooltips.cornerRadius = 0;
    Chart.defaults.global.tooltips.backgroundColor = '#333';
    Chart.defaults.global.tooltips.caretSize = 10;
    Chart.defaults.global.tooltips.displayColors = false;

    // Line Chart Defaults
    Chart.defaults.global.elements.line.tension = 0;
    Chart.defaults.global.elements.point.radius = 4;
    Chart.defaults.global.elements.point.hitRadius = 5;

    var $selectiveCrisis = $("#selective-crisis"),
        selectiveCrisis = new Chart($selectiveCrisis, {
            type: 'line',
            data: {
                labels: ["'01", "'06", "'07", "'08", "'09", "'10", "'11", "'12"],
                datasets: [
                    {
                        type: 'line',
                        label: 'Public (UCF)   ',
                        data: [40, 41, 42, 43, 44, 45, 46, 47],
                        backgroundColor: 'rgba(251,181,55,0.7)',
                        borderColor: 'rgba(251,181,55,0.7)'
                    },
                    {
                        type: 'line',
                        label: 'Private Non-Profit   ',
                        data: [60, 60, 61, 60, 59, 60, 61, 62],
                        backgroundColor: 'rgba(90,155,168,0.7)',
                        borderColor: 'rgba(90,155,168,0.7)'
                    },
                    {
                        type: 'line',
                        label: 'Private For-Profit   ',
                        data: [61, 62, 65, 63, 70, 73, 75, 80],
                        backgroundColor: 'rgba(212,124,75,0.7)',
                        borderColor: 'rgba(212,124,75,0.7)'
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            borderDash: [5, 15]
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: "#ffffff"
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItems, data) {
                            return tooltipItems.yLabel + '%';
                        }
                    }
                }
            }
        });

    var $debtInContext = $("#debt-in-context"),
        debtInContext = new Chart($debtInContext, {
            type: 'horizontalBar',
            data: {
                labels: [""],
                datasets: [
                    {
                        label: "UCF*",
                        backgroundColor: [
                            'rgb(249, 180, 70)',
                        ],
                        data: [21.824]
                    },
                    {
                        label: "Florida (SUS)",
                        backgroundColor: [
                            'rgb(108, 183, 217)',
                        ],
                        data: [25.000],
                    },
                    {
                        label: "Public",
                        backgroundColor: [
                            'rgb(197, 192, 184)',
                        ],
                        data: [25.500],
                    },
                    {
                        label: "Private Non-Profit",
                        backgroundColor: [
                            'rgb(92, 155, 167)'
                        ],
                        data: [32.300],
                    },
                    {
                        label: "For-Profit",
                        backgroundColor: [
                            'rgb(210, 124, 80)'
                        ],
                        data: [39.950],
                    }
                ]
            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            borderDash: [5, 15]
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItems, data) {
                            return '$' + addCommas(Math.round(tooltipItems.xLabel * 1000));
                        }
                    }
                }
            }
        });

    var $relativeConsequences = $("#relative-consequences"),
        relativeConsequences = new Chart($relativeConsequences, {
            type: 'bar',
            data: {
                labels: [""],
                datasets: [
                    {
                        label: "For-Profit     ",
                        backgroundColor: [
                            'rgb(210, 124, 80)'
                        ],
                        data: [15],
                    },
                    {
                        label: "Private Non-Profit     ",
                        backgroundColor: [
                            'rgb(92, 155, 167)'
                        ],
                        data: [10],
                    },
                    {
                        label: "Public     ",
                        backgroundColor: [
                            'rgb(197, 192, 184)',
                        ],
                        data: [11],
                    },
                    {
                        label: "Florida     ",
                        backgroundColor: [
                            'rgb(108, 183, 217)',
                        ],
                        data: [5],
                    },
                    {
                        label: "UCF",
                        backgroundColor: [
                            'rgb(249, 180, 70)',
                        ],
                        data: [4],
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            labelString: "percentage"
                        },
                        gridLines: {
                            borderDash: [5, 15]
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItems, data) {
                            return tooltipItems.yLabel + '%';
                        }
                    }
                }
            }
        });

    var $clearComparison = $("#clear-comparison"),
        clearComparison = new Chart($clearComparison, {
            type: 'horizontalBar',
            data: {
                labels: ["$0", "$1 - $19,999", "$20,000 - $29,999"],
                datasets: [
                    {
                        label: "UCF (2015-16)",
                        backgroundColor: [
                            'rgb(249, 180, 70)',
                        ],
                        data: [36],
                    },
                    {
                        label: "Public (2011-12)",
                        backgroundColor: [
                            'rgb(197, 192, 184)',
                        ],
                        data: [40],
                    },
                    {
                        label: "UCF (2015-16)",
                        backgroundColor: [
                            'rgb(249, 180, 70)',
                        ],
                        data: [42],
                    },
                    {
                        label: "Public (2011-12)",
                        backgroundColor: [
                            'rgb(197, 192, 184)',
                        ],
                        data: [45],
                    }
                ]
            },
            options: {
                scales: {
                    xAxes: [{
                        gridLines: {
                            borderDash: [5, 15]
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItems, data) {
                            return tooltipItems.xLabel + ' ' + tooltipItems.yLabel + '%';
                        }
                    }
                }
            }
        });
}

$(init);