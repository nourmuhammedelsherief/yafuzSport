/*** main server ****/

'use strict';


const express       = require('express');
const http          = require('http');
const socketio      = require('socket.io');
const socketEvents  = require('/socketFun');

const path    = require( 'path' );
const fs      = require( 'fs' );
const utf8    = require( 'utf8' );

class Server {
    constructor() {
        this.port = 3027;
        this.host = `localhost`;
        this.app    = express();
        this.http   = http.createServer( this.app );
        this.socket = socketio(this.http);
    }

    appRun(){



        new socketEvents(this.socket).socketConfig();

        this.http.listen(this.port, this.host, () => {
            console.log(`Listening on ${this.host}:${this.port}`);
        });

    }
}

const app = new Server();
app.appRun();

