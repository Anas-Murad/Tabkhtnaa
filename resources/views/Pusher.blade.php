<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('e70b1a8696531eeb6991', {
            cluster: 'eu'
        });

        var event = 'EVENT_NAME';
        var message = {
           "test":1522
        };





        var channel = pusher.subscribe('user-channel.1');
        channel.bind('live_location', function(data) {
            $('#log').append($(`<div>${JSON.stringify(data)}</div>`))
            console.log('data ' , JSON.stringify(data))
        });


        var callback = (eventName, data) => {
            var labeel = `bind global: The event ${eventName} was triggered with data ${JSON.stringify(data)}`;
            $('#log').append($(`<div>${labeel}</div>`))
            console.log(labeel);
        };
        // bind to all events on the connection
        pusher.bind_global(callback);





        var callbackchannel = (eventName, data) => {
            var labeel = `bind global channel: The event ${eventName} was triggered with data ${JSON.stringify(
                data
            )}`;
            $('#log').append($(`<div>${labeel}</div>`))
            console.log(labeel);
        };
        channel.bind_global(callbackchannel);



        // channel.unbind(eventName, callback);
        pusher.trigger('user-channel.1', 'EVENT_NAME', message);

    </script>
</head>
<body>
<h1>Pusher Test</h1>
<p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
</p>


<div id="log"></div>
</body>
