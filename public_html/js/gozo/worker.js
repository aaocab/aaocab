import { io } from "https://cdn.socket.io/4.3.2/socket.io.esm.min.js";

var socketManager = function () {

    var manager = null;
    var domain = "http://localhost:3000";
    var userSocket = null;


    this.initUserSocket = function (token) {
        userSocket = io(domain, {
            auth: {
                token: token
            }
        });
        userSocket.on("statusMessage", (message) => {
            console.log(message);
            console.log(message.username);

        });
        userSocket.on("message", (message) => {
            console.log(message);
//          console.log(message.username); 
            if(document.getElementById("gozonow1"))
            {
               let check = document.getElementById("gozonow1").value;
               if(check == 1)
               {
                   console.log("Calling checkLog...");
                   checkLog();
               }
            } 

        });
        userSocket.on("disconnect", () => {
            console.log("socket disconnected");
        });
//        io.on('message', function () {
//            message("New request for booking");
//        });

        userSocket.emit("broadcast", {text: 'Hello world all'});
        userSocket.on("joinNotify", (msg) => {
            console.log("message > " + msg);
        });
        userSocket.on("roomId", (msg) => {
            console.log("roomName msg > " + msg);
        });
//        userSocket.on("emitMessageToVendors", (bkgId) => {
//            userSocket.to("userType_2").emit(  "New request for booking id" + bkgId);
//        });

    };


    this.initAdminSocket = function (token) {
        if (manager == null) {
            init();
        }
        userSocket = manager.socket("/", {
            token: token
        })
    }

    var open = function () {
        if (manager != null) {
            manager.open((err) => {
                if (err) {
                    console.log(err);
                } else {
                    console.log(this.userSocket);
                    console.log("Connection established successfully");
                }
            });
        }
    };
};


var socket = new socketManager();
 //socket.initUserSocket("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd3d3Lmdvem9jYWJzLmNvbVwvIiwic3ViIjoiMiIsImF1ZCI6IjEzNyIsImV4cCI6MTY0MzIwNTg1OCwiaWF0IjoxNjQzMTE5NDU4LCJ0b2tlbiI6ImtxM2hmMGdza3FhbG1laG5lcWMzdm10MzFwIiwiZGV2aWNlIjp7InZlcnNpb24iOiIzLjIyLjIwMTA3LWRlYnVnIiwidW5pcXVlSWQiOiI0Y2ExNmFiYzZkMDQ2Njk0IiwiZGV2aWNlTmFtZSI6InNhbXN1bmcgU00tTTMwN0YiLCJ0b2tlbiI6ImRBTUxDQjlRVEoyM095SHI4TnlGMEg6QVBBOTFiRUdxcjN3bmt5MmZPUDRZNUw3dElxNjluRnZFa2RwRzluRFcwQzFGbTFMWXVtRHNjOE9Ka1Q0dnVXYk4xbWZnNUNfblZoLVFxVElIdkp4ZzVGUmRGQzMtd2ozNWdlMVd5eTdfRUVaUWh3TmY1czVHbFl5ZExsLVJVSmRPY3lUWHZOd1EwMS0ifX0.2jdjJf9hjS_J5SeuDmZN01Eo7Rql7lu0Z0kf76fyqCI");
socket.initUserSocket("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvd3d3Lmdvem9jYWJzLmNvbVwvIiwic3ViIjoiMiIsImF1ZCI6IjEzNyIsImV4cCI6MTY0NDA0ODU4MCwiaWF0IjoxNjQzOTYyMTgwLCJ0b2tlbiI6ImtxM2hmMGdza3FhbG1laG5lcWMzdm10MzFwIiwiZGV2aWNlIjp7InZlcnNpb24iOiIzLjIyLjIwMTA3LWRlYnVnIiwidW5pcXVlSWQiOiI0Y2ExNmFiYzZkMDQ2Njk0IiwiZGV2aWNlTmFtZSI6InNhbXN1bmcgU00tTTMwN0YiLCJ0b2tlbiI6ImRBTUxDQjlRVEoyM095SHI4TnlGMEg6QVBBOTFiRUdxcjN3bmt5MmZPUDRZNUw3dElxNjluRnZFa2RwRzluRFcwQzFGbTFMWXVtRHNjOE9Ka1Q0dnVXYk4xbWZnNUNfblZoLVFxVElIdkp4ZzVGUmRGQzMtd2ozNWdlMVd5eTdfRUVaUWh3TmY1czVHbFl5ZExsLVJVSmRPY3lUWHZOd1EwMS0ifX0.IEZ1qHepK8t5jXnx2cDc33SGOfGBILzElnsF_T7Yj2c");
