var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var mysql = require('mysql');

var con = mysql.createConnection({
    host: "127.0.0.1",
    user: "root",
    password: "100100",
    database: "yafoouz",
    charset : 'utf8mb4'

});
function handleDisconnect()
{
    con = mysql.createConnection({
        host: "127.0.0.1",
        user: "root",
        password: "100100",
        database: "yafoouz",
        charset : 'utf8mb4'

    });
}
io.on('connection',function(socket){
    console.log('Starting');
    // console.log('chat message-{socket.handshake.query.group_id}');
    let room = `chat message-${socket.handshake.query.group_id}`;
    socket.on('chat message', function(msg){
        var json = JSON.parse(msg);
        let event_name = `chat message-${json.data.id}`;

        var sql = `INSERT INTO group_chats (message,name,group_id,user_id,user_photo,created_at) VALUES ("${json.data.message}","${json.data.name}","${json.data.id}","${json.data.user_id}","${json.data.user_photo}","${json.data.created_at}")`;
        con.query(sql, function (err, result) {
            if (err) throw err;
            console.log(result.affectedRows + " record(s) created");
        });

        con.connect('error',function (err) {
            console.log('db error', err.code);

            //- The server close the connection.
            if(err.code === "PROTOCOL_CONNECTION_LOST"){
                console.log("/!\\ The server close the connection. /!\\ ("+err.code+")");
                handleDisconnect();
            }

            //- Connection in closing
            else if(err.code === "PROTOCOL_ENQUEUE_AFTER_QUIT"){
                console.log("/!\\ Connection in closing. /!\\ ("+err.code+")");
                setTimeout(handleDisconnect, 2000);
            }

            //- Fatal error : connection variable must be recreated
            else if(err.code === "PROTOCOL_ENQUEUE_AFTER_FATAL_ERROR"){
                console.log("/!\\ Connection variable must be recreated. /!\\ ("+err.code+")");
                setTimeout(handleDisconnect, 2000);
            }

            //- Error because a connection is already being established
            else if(err.code === "PROTOCOL_ENQUEUE_HANDSHAKE_TWICE"){
                console.log("/!\\ Connection is already being established. /!\\ ("+err.code+")");
            }

            //- Anything else
            else{
                console.log("/!\\ Cannot establish a connection with the database. /!\\ ("+err.code+")");
                throw err;
            }

        });
        //   io.emit('chat message', msg)
        socket.broadcast.emit(room,msg)
    });
    socket.on('disconnect', function(){
        console.log('user disconnected');
    });
});
server.listen(6999);
