/**** socket fun ****/
'use strict';


const path = require('path');
const fs = require('fs');
const helper = require('./helper');

class Socket {

    constructor(socket) {
        this.io = socket;
    }

    socketEvents() {
        this.io.on('connection', (socket) => {
            const ConnectedUserID = socket.request._query['id'];


            socket.broadcast.emit('userConnected', {
                userId: ConnectedUserID,
                socket_id: socket.id
            });

            // get user online status
            socket.on('isUserConnected', async (userId) => {
                console.log(ConnectedUserID + ' ask userStatus ' + userId);
                const result = await helper.isUserConnected(userId);
                this.io.to(socket.id).emit('isUserConnectedRes', {
                    userData: result.userData,
                });
            });

            // send the messages to the user
            socket.on('addMessage', async (response) => {
                console.log(response);
                socket.id = response.user_id;

                var otherUsersIds = response.other_users;
                if (otherUsersIds.length) {
                    otherUsersIds.forEach(function (user_id) {
                        socket.to(user_id).emit('addMessageResponse', response);
                    });
                }
            });



            socket.on('disconnect', async () => {
                console.log('user_id ', ConnectedUserID, ' disConnected');

                // make user online 0 and delete socket id from DB
                //const isLoggedOut = await helper.logoutUser(socket.id);
                const isLoggedOut = await helper.logoutUser(ConnectedUserID);
                socket.broadcast.emit('userDisconnect', {
                    userId: ConnectedUserID,
                });
            });


        });
    }

    // start user connecting then call socketEvents
    socketConfig() {
        this.io.use(async (socket, next) => {
            let userId = socket.request._query['id'];
            socket.id = parseInt(userId);
            let userSocketId = socket.id;

            // make user online and save socket id in DB
            const response = await helper.addSocketId(userId, userSocketId);
            if (userId != 0 && response && response !== null) {
                console.log('user_id ', userId, ' connected');
                next();
            } else {
                console.error(`Socket connection failed, for  user Id ${userId}.`);
            }
        });
        //console.log('in socket js');
        this.socketEvents();
    }
}
module.exports = Socket;
