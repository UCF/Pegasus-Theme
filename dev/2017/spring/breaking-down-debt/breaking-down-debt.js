/*!
 * chartjs-plugin-deferred
 * http://chartjs.org/
 * Version: 0.2.0
 *
 * Copyright 2016 Simon Brunel
 * Released under the MIT license
 * https://github.com/chartjs/chartjs-plugin-deferred/blob/master/LICENSE.md
 */
"use strict";!function(){function e(e,t){var n=parseInt(e,10);return isNaN(n)?0:"string"==typeof e&&e.indexOf("%")!==-1?n/100*t:n}function t(t){var n=t[s],r=t.chart.canvas;if(null===r.offsetParent)return!1;var a=r.getBoundingClientRect(),f=e(n.yOffset||0,a.height),d=e(n.xOffset||0,a.width);return a.right-d>=0&&a.bottom-f>=0&&a.left+d<=window.innerWidth&&a.top+f<=window.innerHeight}function n(e){var t=l.Deferred.defaults,n=e.options.deferred,r=o.getValueOrDefault;return void 0===n?n={}:"boolean"==typeof n&&(n={enabled:n}),{enabled:r(n.enabled,t.enabled),xOffset:r(n.xOffset,t.xOffset),yOffset:r(n.yOffset,t.yOffset),delay:r(n.delay,t.delay),appeared:!1,delayed:!1,loaded:!1,elements:[]}}function r(e){var n=e.target,r=n[i];r.ticking||(r.ticking=!0,l.platform.defer(function(){var e,n,a=r.instances.slice(),f=a.length;for(n=0;n<f;++n)e=a[n],t(e)&&(d(e),e[s].appeared=!0,e.update());r.ticking=!1}))}function a(e){var t=e.nodeType;if(t===Node.ELEMENT_NODE){var n=o.getStyle(e,"overflow-x"),r=o.getStyle(e,"overflow-y");return"auto"===n||"scroll"===n||"auto"===r||"scroll"===r}return e.nodeType===Node.DOCUMENT_NODE}function f(e){for(var t,n,f=e.chart.canvas,d=f.parentElement;d;)a(d)&&(t=d[i]||(d[i]={}),n=t.instances||(t.instances=[]),0===n.length&&d.addEventListener("scroll",r,{passive:!0}),n.push(e),e[s].elements.push(d)),d=d.parentElement||d.ownerDocument}function d(e){e[s].elements.forEach(function(t){var n=t[i].instances;n.splice(n.indexOf(e),1),n.length||(o.removeEvent(t,"scroll",r),delete t[i])}),e[s].elements=[]}var l=window.Chart,o=l.helpers,i="_chartjs_deferred",s="_deferred_model";l.Deferred=l.Deferred||{},l.Deferred.defaults={enabled:!0,xOffset:0,yOffset:0,delay:0},l.platform=o.extend(l.platform||{},{defer:function(e,t,n){var r=function(){e.call(n)};t?window.setTimeout(r,t):o.requestAnimFrame.call(window,r)}}),l.plugins.register({beforeInit:function(e){var t=e[s]=n(e);t.enabled&&f(e)},beforeDatasetsUpdate:function(e){var n=e[s];if(!n.enabled)return!0;if(!n.loaded){if(!n.appeared&&!t(e))return!1;if(n.appeared=!0,n.loaded=!0,d(e),n.delay>0)return n.delayed=!0,l.platform.defer(function(){n.delayed=!1,e.update()},n.delay),!1}return!n.delayed&&void 0}})}();

// Utility method to add commas to numbers
function formatNumber(nStr) {
    nStr = Math.round(nStr * 1000);
    nStr += '';
    var x = nStr.split('.'),
        x1 = x[0],
        x2 = x.length > 1 ? '.' + x[1] : '',
        rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return '$' + x1 + x2;
}

function setChartDefaults() {
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
}

function initSelectiveCrisis() {
    var data = {
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
    };

    var options = {
        scales: {
            yAxes: [{
                gridLines: {
                    borderDash: [5, 15],
                    drawBorder: false,
                }
            }],
            xAxes: [{
                gridLines: {
                    display: false,
                    drawBorder: false,
                    zeroLineColor: '#ffffff',
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItems, data) {
                    return tooltipItems.yLabel + '%';
                }
            }
        },
        deferred: {           // enabled by default
            yOffset: '75%',   // defer until 50% of the canvas height are inside the viewport
            delay: 500        // delay of 500 ms after the canvas is considered inside the viewport
        }
    };

    var $selectiveCrisis = $("#selective-crisis"),
        selectiveCrisis = new Chart($selectiveCrisis, {
            type: 'line',
            data: data,
            options: options
        });
}

function initDebtInContext() {
    var data = {
        labels: ["UCF*", "FLORIDA (SUS)", "PUBLIC", "PRIVATE NON-PROFIT", "FOR-PROFIT"],
        datasets: [
            {
                label: "",
                backgroundColor: [
                    'rgb(249, 180, 70)',
                    'rgb(108, 183, 217)',
                    'rgb(197, 192, 184)',
                    'rgb(92, 155, 167)',
                    'rgb(210, 124, 80)'
                ],
                data: [21.824, 25.000, 25.500, 32.300, 39.950]
            }
        ]
    };

    var options = {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true
                },
                gridLines: {
                    borderDash: [5, 15],
                    drawBorder: false,
                    zeroLineColor: '#fff',
                }
            }],
            yAxes: [{
                display: false,
                gridLines: {
                    zeroLineColor: '#fff',
                }
            }]
        },
        tooltips: {
            enabled: false
        },
        legend: {
            display: false
        },
        deferred: {           // enabled by default
            yOffset: '75%',   // defer until 50% of the canvas height are inside the viewport
            delay: 500        // delay of 500 ms after the canvas is considered inside the viewport
        },
        animation: {
            duration: 0,
            onComplete: function () {
                console.log('complete');
                var self = this,
                    chartInstance = this.chart,
                    ctx = chartInstance.ctx;

                ctx.font = 'bold 20px Arial';
                ctx.textAlign = "left";

                Chart.helpers.each(self.data.datasets.forEach((dataset, datasetIndex) => {
                    var meta = self.getDatasetMeta(datasetIndex),
                        total = 0, //total values to compute fraction
                        labelxy = [],
                        offset = Math.PI / 2, //start sector from top
                        radius,
                        centerx,
                        centery,
                        label,
                        lastend = 0; //prev arc's end line: starting with 0

                    for (var val of dataset.data) { total += val; }

                    Chart.helpers.each(meta.data.forEach((element, index) => {
                        radius = 0.9 * element._model.outerRadius - element._model.innerRadius;
                        centerx = element._model.x;
                        centery = element._model.y;
                        label = element._model.label;
                        var thispart = dataset.data[index],
                            arcsector = Math.PI * (2 * thispart / total);
                        if (element.hasValue() && dataset.data[index] > 0) {
                            labelxy.push(lastend + arcsector / 2 + Math.PI + offset);
                        }
                        else {
                            labelxy.push(-1);
                        }
                        lastend += arcsector;

                        ctx.fillStyle = "#fff";
                        ctx.fillText(label, 40, centery + 8);
                        ctx.fillStyle = "#000";
                        ctx.fillText(formatNumber(val), centerx - 90, centery + 8);
                    }), self);
                }), self);

            }
        }
    };

    var $debtInContext = $("#debt-in-context"),
        debtInContext = new Chart($debtInContext, {
            type: 'horizontalBar',
            data: data,
            options: options
        });
}

function initRelativeConsequences() {

    var data = {
        labels: ["", "", "", "", ""],
        datasets: [
            {
                label: "",
                backgroundColor: [
                    'rgb(210, 124, 80)',
                    'rgb(92, 155, 167)',
                    'rgb(197, 192, 184)',
                    'rgb(108, 183, 217)',
                    'rgb(249, 180, 70)',
                ],
                data: [15, 10, 11, 5, 4],
            }
        ]
    };

    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                },
                gridLines: {
                    borderDash: [5, 15],
                    drawBorder: false,
                }
            }],
            xAxes: [{
                gridLines: {
                    display: false,
                    drawBorder: false,
                    zeroLineColor: '#fff',
                }
            }],
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItems, data) {
                    return tooltipItems.yLabel + '%';
                }
            }
        },
        legend: {
            display: false
        },
        deferred: {           // enabled by default
            yOffset: '75%',   // defer until 50% of the canvas height are inside the viewport
            delay: 500        // delay of 500 ms after the canvas is considered inside the viewport
        }
    };

    var $relativeConsequences = $("#relative-consequences"),
        relativeConsequences = new Chart($relativeConsequences, {
            type: 'bar',
            data: data,
            options: options
        });
}

function initClearComparison() {
    var data = {
        labels: [""],
        datasets: [
            {
                label: "UCF (2015-16)",
                backgroundColor: [
                    'rgb(249, 180, 70)',
                ],
                data: [44],
            },
            {
                label: "Public (2011-12)",
                backgroundColor: [
                    'rgb(197, 192, 184)',
                ],
                data: [36],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(249, 180, 70)',
                ],
                data: [29],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(197, 192, 184)',
                ],
                data: [26],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(249, 180, 70)',
                ],
                data: [14],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(197, 192, 184)',
                ],
                data: [17],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(249, 180, 70)',
                ],
                data: [11],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(197, 192, 184)',
                ],
                data: [15],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(249, 180, 70)',
                ],
                data: [2],
            },
            {
                label: "",
                backgroundColor: [
                    'rgb(197, 192, 184)',
                ],
                data: [6],
            }
        ]
    };

    var options = {
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
                            return tooltipItems.xLabel + ' ' + tooltipItems.yLabel + '%';
                        }
                    }
                },
                deferred: {           // enabled by default
                    yOffset: '75%',   // defer until 50% of the canvas height are inside the viewport
                    delay: 500        // delay of 500 ms after the canvas is considered inside the viewport
                }
            };

    var $clearComparison = $("#clear-comparison"),
        clearComparison = new Chart($clearComparison, {
            type: 'horizontalBar',
            data: data,
            options: options
        });
}

function init() {
    setChartDefaults();
    initSelectiveCrisis();
    initDebtInContext();
    initRelativeConsequences();
    initClearComparison();
}

$(init);