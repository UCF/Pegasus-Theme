        window.onload = function ()
        {
			/* 15-year enrollment comparison between state universities chart */
			
					   /* '96  '97   '98    '99   '00   '01   '02   '03   '04   '05   '06   '07   '08   '09   '10   '11 */
			var e_ucf = [27684,28685,30206,31673,33713,35850,38501,41535,42465,44856,47226,48897,50181,53472,56236,58587];
			var e_uf  = [39863,41713,42336,43382,45114,46515,47373,47858,47993,49693,50912,51725,51475,50691,49827,49589];
			var e_usf = [36266,34036,33654,34839,35561,37221,38854,40945,42238,42660,41799,44869,46174,47122,49074,48574];
			var e_fsu = [30154,30401,31071,32878,33971,34982,36210,36884,38431,39146,39973,40555,38717,39785,40416,41087];
			var e_fiu = [29720,30012,30421,31293,31945,31727,33349,33228,34865,36904,38097,38290,34159,38208,40841,43831];
            var line1 = new RGraph.Line('line1', e_fiu, e_fsu, e_usf, e_uf, e_ucf);
            line1.Set('chart.background.grid', false);
            line1.Set('chart.linewidth', 12);
            line1.Set('chart.gutter.left', 70);
			line1.Set('chart.gutter.right',105);
			line1.Set('chart.gutter.top',30);
			line1.Set('chart.gutter.bottom',70);			
            line1.Set('chart.hmargin', 5);
            line1.Set('chart.tickmarks', null);
            line1.Set('chart.units.post', '');
			line1.Set('chart.ylabels', false);
			line1.Set('chart.ymin',20000);
			line1.Set('chart.ymax',58587);
            line1.Set('chart.colors', ['#0b75bc', '#bf1e2e', '#0ca14d', '#f1592a', '#ffc907']);
			line1.Set('chart.background.grid.border', false);
            line1.Set('chart.background.grid.autofit', true);
            line1.Set('chart.background.grid.autofit.numhlines', 0);
			line1.Set('chart.background.grid.vlines',false);
            line1.Set('chart.curvy', 0);
			line1.Set('chart.noaxes', true);
			
			/* 15-year SAT comparison between state universities chart */
					  /* '96  '97  '98  '99  '00  '01  '02  '03  '04  '05  '06  '07  '08  '09  '10  '11 */
			var s_avg = [1130,1140,1141,1142,1142,1132,1132,1142,1140,1150,1151,1151,1155,1160,1175,1175];		  
			var s_ucf = [1119,1130,1135,1147,1149,1153,1165,1175,1170,1177,1201,1217,1219,1225,1237,1251];
			var s_uf  = [1244,1241,1268,1267,1265,1265,1265,1267,1281,1286,1274,1275,1292,1279,1304,1283];
			var s_usf = [1087,1102,1102,1081,1085,1045,1056,1084,1108,1135,1131,1148,1160,1185,1169,1188];
			var s_fsu = [1146,1146,1162,1178,1188,1204,1184,1194,1199,1187,1206,1214,1231,1222,1228,1230];
			var s_fiu = [1087,1123,1105,1112,1110,1129,1144,1153,1128,1112,1134,1144,1161,1157,1140,1139];
            var line2 = new RGraph.Line('line2', s_fiu, s_fsu, s_usf, s_uf, s_ucf, s_avg);
            line2.Set('chart.background.grid', false);
            line2.Set('chart.linewidth', 12);
            line2.Set('chart.gutter.left', 65);
			line2.Set('chart.gutter.right',175);
			line2.Set('chart.gutter.top',10);
			line2.Set('chart.gutter.bottom',58);
            line2.Set('chart.hmargin', 5);
            line2.Set('chart.tickmarks', null);
            line2.Set('chart.units.post', '');
			line2.Set('chart.ylabels', false);
			line2.Set('chart.ymin',1000);
			line2.Set('chart.ymax',1304);
            line2.Set('chart.colors', ['#0b75bc', '#bf1e2e', '#0ca14d', '#f1592a', '#ffc907', '#000']);
			line2.Set('chart.background.grid.border', false);
            line2.Set('chart.background.grid.autofit', true);
            line2.Set('chart.background.grid.autofit.numhlines', 0);
			line2.Set('chart.background.grid.vlines',false);
            line2.Set('chart.curvy', 0);
			line2.Set('chart.noaxes', true)
			
			
			if (!document.all || RGraph.isIE9up()) {
                line1.Set('chart.shadow', false);
				line2.Set('chart.shadow', false);
            }
			
			/* Animate the line generation based on viewport position */
			
			$('canvas#line1').bind('inview', function (event, visible) {
				if (visible == true) {
					RGraph.Effects.Line.jQuery.Trace(line1); /* Line animation */
					$('img#enrollment_gradient').delay(600).fadeIn(1100); /* Fade in the gradient fill afterward */
				} else {
					$('canvas#line1').unbind('inview');
				}
			});
			$('canvas#line2').bind('inview', function (event, visible) {
				if (visible == true) {
					RGraph.Effects.Line.jQuery.Trace(line2);
					$('img#sats_gradient').delay(600).fadeIn(1100);
					
				} else {
					$('canvas#line2').unbind('inview');
				}
			});
			
        }