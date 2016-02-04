/*jslint */
/*global AdobeEdge: false, window: false, document: false, console:false, alert: false */
(function (compId) {

    "use strict";
    var im='images/',
        aud='media/',
        vid='media/',
        js='js/',
        fonts = {
        },
        opts = {
            'gAudioPreloadPreference': 'auto',
            'gVideoPreloadPreference': 'auto'
        },
        resources = [
        ],
        scripts = [
        ],
        symbols = {
            "stage": {
                version: "6.0.0",
                minimumCompatibleVersion: "5.0.0",
                build: "6.0.0.400",
                scaleToFit: "none",
                centerStage: "none",
                resizeInstances: false,
                content: {
                    dom: [
                        {
                            id: 'maze',
                            type: 'image',
                            rect: ['0px', '0px', '628px', '842px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"maze.png",'0px','0px']
                        },
                        {
                            id: 'RoundRect2',
                            type: 'rect',
                            rect: ['27px', '7px', '25px', '23px', 'auto', 'auto'],
                            borderRadius: ["10px", "10px", "10px", "10px"],
                            fill: ["rgba(192,192,192,1)"],
                            stroke: [0,"rgb(0, 0, 0)","none"]
                        }
                    ],
                    style: {
                        '${Stage}': {
                            isStage: true,
                            rect: ['null', 'null', '628px', '842px', 'auto', 'auto'],
                            overflow: 'hidden',
                            fill: ["rgba(255,255,255,1)"]
                        }
                    }
                },
                timeline: {
                    duration: 8000,
                    autoPlay: true,
                    data: [
                        [
                            "eid12",
                            "top",
                            0,
                            0,
                            "linear",
                            "${maze}",
                            '0px',
                            '0px'
                        ],
                        [
                            "eid13",
                            "top",
                            2010,
                            0,
                            "linear",
                            "${maze}",
                            '0px',
                            '0px'
                        ],
                        [
                            "eid14",
                            "location",
                            0,
                            8000,
                            "linear",
                            "${RoundRect2}",
                            [[39.5, 18.5, 0, 0, 0, 0,0],[68.45, 28.28, 42.22, 12.35, 81.77, 23.93,30.59],[78.49, 117.3, 36.69, 30.72, 53.2, 44.55,122.74],[125.89, 108.7, -15.17, -26.62, -24.09, -42.26,179.56],[161.16, 96.79, 36.57, -18.54, 46.79, -23.72,219.81],[150.48, 62.31, -94.73, 0.19, -56.75, 0.11,265.99],[87.17, 64.62, -28.86, 2.43, -76.77, 6.46,329.37],[103.74, 24.79, 88.61, -5.73, 62.9, -4.07,380.92],[178.89, 32.15, 30.48, 10.81, 78.69, 27.92,457.03],[186.43, 61.15, 26.79, 16.66, 30.59, 19.02,488.56],[216.35, 62.6, 22.09, 18.12, 27.01, 22.16,519.31],[217.46, 26.48, 28.2, 27.51, 22.14, 21.59,559.56],[251.8, 30.97, 25.8, 30.24, 28.45, 33.35,595.62],[252.61, 79.54, 31.36, 42.3, 25.79, 34.78,645.48],[308.15, 80.62, 32.85, 49.47, 31.67, 47.69,704.5],[321.72, 132.09, 21.19, 36.3, 32.25, 55.25,758.17],[276.87, 140.43, 30.4, 55.25, 21.09, 38.33,809.9],[279.06, 206.84, 17.62, 32.8, 31.89, 59.36,877.52],[318.01, 215.09, 56.95, 105.05, 18.25, 33.66,919.37],[366.95, 330.14, 22.9, 43.86, 58.41, 111.86,1044.46],[413.92, 350.11, 33.16, 63.45, 23, 44.01,1097.32],[407.31, 409.96, -20.34, 41.3, -37.44, 76,1160.46],[360.76, 431.2, 13.76, 27.39, 20.66, 41.12,1215.68],[364.04, 457.64, 30.76, 57.15, 14.78, 27.46,1242.63],[419.9, 474.56, 44.92, 73.16, 34.66, 56.45,1304.11],[430.05, 578.83, 26.81, 44.5, 44.9, 74.54,1410.18],[485.84, 583.79, 33.73, 49.05, 28.48, 41.41,1469.09],[499.16, 603.79, 28.27, 44.56, 30.77, 48.51,1493.13],[571.33, 602.43, 28.42, 47.18, 27.78, 46.11,1568.56],[581.87, 620.01, 9.53, 30.29, 17.14, 54.49,1589.58],[609.64, 625.41, 16.81, 71.48, 8.01, 34.07,1620.89],[607.74, 691.71, 0.66, 46.25, 1.17, 82.44,1687.49],[595.37, 739.54, 2.4, 47.08, 2.31, 45.41,1737.14],[593.14, 765.25, 5.27, 14.12, 12.05, 32.31,1763.39],[558.71, 768.02, 6.48, 10.96, 10.42, 17.63,1799.61],[559.78, 801.68, 47.19, 26.99, 28.68, 16.41,1834.72],[613.5, 800.5, 0, 0, 0, 0,1889.37]]
                        ],
                        [
                            "eid10",
                            "left",
                            0,
                            0,
                            "linear",
                            "${maze}",
                            '0px',
                            '0px'
                        ],
                        [
                            "eid11",
                            "left",
                            2010,
                            0,
                            "linear",
                            "${maze}",
                            '0px',
                            '0px'
                        ]
                    ]
                }
            }
        };

    AdobeEdge.registerCompositionDefn(compId, symbols, fonts, scripts, resources, opts);

    if (!window.edge_authoring_mode) AdobeEdge.getComposition(compId).load("edge_edgeActions.js");
})("maze-anime");
