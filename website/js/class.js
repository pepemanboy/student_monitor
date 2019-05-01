// Global variables
var g_data;
var g_date;
var g_timeStep;
var g_timeGood;
var g_timeBad;
var g_timeStamp;
var g_timeStart = 0;
var g_notifTime = 0;
var g_notifTimeBad = 0;
var g_notifStart = false;
var g_notifThreshold = 1;
var g_notifReset = false;


/**
 *	Reset button
 */
$("#sm-class-reset-button").click(function() {
	reset();
});


function updateUI()
{
	if (g_timeStart)
	{
		$("#valeria-start").text(g_timeStart);
		var good = new Date(g_timeGood * 1000).toISOString().substr(11, 8);
		$("#valeria-good").text(good);
		var bad = new Date(g_timeBad * 1000).toISOString().substr(11, 8);
		$("#valeria-bad").text(bad);
	}
	else
	{
		$("#valeria-start").text('-');
		$("#valeria-good").text('-');
		$("#valeria-bad").text('-');
	}
	
}

function reset()
{
	$.post("communication.php", {
			website: "true",
			operation: "reset"
		},
		function(data, status) {
			if (data == "OK")
			{
				g_timeStart = 0;
				g_timeGood = 0;
				g_timeBad = 0;
				g_notifStart = false;
				g_notifThreshold = 1;
				updateUI();
			}
			else
				alert("Reset error = " + data);
		});
}

function update()
{
	$.post("communication.php", {
			website: "true",
			operation: "get"
		},
		function(data, status) {
			if (!data.includes("ERROR"))
			{
				g_data = data;

				json_data = jQuery.parseJSON(data);
				var n = json_data.values.length;

				g_timeStep = [];

				g_timeGood = 0;
				g_timeBad = 0;

				if (n)
				{
					for(var i = 0; i < n; ++i)
					{
						var d0 = new Date(moment(json_data.dates[i], 'YYYY-MM-DD HH:mm:ss').toDate()).getTime();
						var d1;
						if (i < n-1)
							d1 = new Date(moment(json_data.dates[i+1], 'YYYY-MM-DD HH:mm:ss').toDate()).getTime();
						else
							d1 = new Date().getTime();			
						var diff_s = Math.floor((d1-d0)/1000);
						g_timeStep.push(diff_s);
						if (diff_s > 10)
							json_data.values[i] = "bad";
					}

					for (var i = 0; i < n; ++i)
					{
						if (json_data.values[i]=="good")
							g_timeGood += g_timeStep[i];
						else
							g_timeBad += g_timeStep[i];
					}
					g_timeStart = json_data.dates[0];



					// Notifications
					if (!g_notifStart)
					{
						g_notifStart = true;
						g_notifTimeBad = g_timeBad;	
						g_notifTime = g_timeGood + g_timeBad; 				
					}
					else
					{
						if (g_timeBad > g_notifTimeBad)
						{
							var timeNow = g_timeGood + g_timeBad;
							if ((timeNow - g_notifTime) > g_notifThreshold)
							{
								if (!g_notifReset)
								{
									g_notifThreshold = 30;
									alert("Timebad = " + g_timeBad + " notiftimeBad = " + g_notifTimeBad);
									// notify("Valeria is not paying attention.");
									g_notifTime = timeNow;
									g_notifReset = true;
								}
								else
									g_notifReset = false;

								g_notifTimeBad = g_timeBad;
																
							}
						}



					}
				}				

				// Update UI
				updateUI();
			}
			else
				alert("Get error = " + data);
		});
}

$(document).ready(function(){
  	var x = setInterval(update, 1000);
});

function _notify(title, body)
{
	var notification = new Notification(title, {
      icon: 'img/android-chrome-192x192.png',
      body: body,
    });
}

function notify(body)
{
	title = "Student Monitor";
	var b = false;
	if (!("Notification") in Window)
	{
		alert("This browser does not support notifs");
	}

	else if (Notification.permission == "granted")
	{
		_notify(title, body);
	}
	else if (Notification.permission != "denied")
	{
		Notification.requestPermission(
			function(permission)
			{
				if (permission == "granted")
				{
					_notify(title, body);
				} 
			}
			);
	}
}