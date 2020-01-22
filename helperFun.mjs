/**** helper func *****/
'user strict';

const DB 	= require('./db');
const path 	= require('path');
const fs 	= require('fs');

class Helper{

    constructor(app){
        this.db = DB;
    }

    async addSocketId(userId, userSocketId){
        try {
            return await this.db.query(`UPDATE users SET socket_id = ?, online= ? WHERE id = ?`, [userSocketId,'1',userId]);
        } catch (error) {
            console.log(error);
            return null;
        }
    }

    async logoutUser(userSocketId){
        try {
            //return await this.db.query(`UPDATE users SET socket_id = ?, online= ? WHERE socket_id = ?`, ['','0',userSocketId]);
            return await this.db.query(`UPDATE users SET socket_id = ?, online= ? WHERE id = ?`, ['','0',userSocketId]);
        } catch (error) {
            console.warn(error);
            return null;
        }

    }

    isUserConnected(userId){
        try {
            return Promise.all([
                this.db.query(`SELECT id, name, socket_id, online, updated_at FROM users WHERE id = ?`, [userId])
            ]).then( (response) => {
                return {
                    userData : response[0]
                };
            }).catch( (error) => {
                console.warn(error);
                console.log(1111);
                return (null);
            });
        } catch (error) {
            console.warn(error);
            return null;
        }
    }

}
module.exports = new Helper();

