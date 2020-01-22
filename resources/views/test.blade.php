<!doctype html>
<html>
<head>
    <title>Socket.IO chat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font: 13px Helvetica, Arial; }
        form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
        form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
        form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
        #messages { list-style-type: none; margin: 0; padding: 0; }
        #messages li { padding: 5px 10px; }
        #messages li:nth-child(odd) { background: #eee; }
    </style>
</head>
<body>
<ul id="messages"></ul>
<form action="">
    <input id="m" autocomplete="off" /><button>Send</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
<script>
    var socket = io('127.0.0.1:6999');
    var s=JSON.stringify({
        "channel": "chat message",
        "data": {
            "id": 10,
            "name": "ibrahim",
            "user_id": 10,
            "user_photo": "http://yafuzsport.com/uploads/users/image_1566829007.jpg",
            "voice": "http://yafuzsport.com/uploads/voice/voice.mpeg",
            "message": "test  to  run  node forever",
            "created_at": "2019-11-14"
        }
    });
    socket.emit('chat message', s);

    socket.on('chat message',function (s) {
        console.log(s);

    })
</script>
</body>
</html>
