function animateSonar(n){n&&n.hasClass("sonar-anime")&&(n.hide(),setTimeout(function(){n.show()},2e3)),n.toggleClass("sonar-anime")}function setupWayPoint(n){var e=new Waypoint({element:$(".nano-wire-img"),handler:function(e){"down"===e&&($(".sonar").show(),setInterval(animateSonar,3e3,$(".sonar")),n.addClass("show"),this.destroy())},offset:100})}function wireArticleInit(){var n=$(".fade-in-tool-tip-trigger");n.popover(),$.getScript(THEME_JS_URL+"/jquery.waypoints.min.js").done(function(){setupWayPoint(n)})}$(wireArticleInit);