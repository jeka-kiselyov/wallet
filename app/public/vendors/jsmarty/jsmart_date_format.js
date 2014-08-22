
jSmart.prototype.registerPlugin(
    'modifier',
    'date_format',
    function(s, fmt, defaultDate)
    {
    	var fmt  = fmt ? fmt : '%b %e, %Y';
    	var time = s ? s : defaultDate;
    	time = time ? time : 0;
    	time = parseInt(time, 10) * 1000; // milliseconds

    	time = new Date(time);
    	return strftime(fmt, time);
    }
);
