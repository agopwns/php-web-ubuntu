const express = require('express');
const socket = require('socket.io');
const https = require('https');
const fs = require('fs');
const app = express(); // express 객체 생성
const port = 3100;
var roomName;

// express https 서버 생성
var options = {
    key: fs.readFileSync('../RTC/fake-keys/privatekey.pem'),
    cert: fs.readFileSync('../RTC/fake-keys/certificate.pem'),
    requestCert: false,
    rejectUnauthorized: false
};
var server = require('https').createServer(options, app);

// const server = https.createServer(app);
// 생성된 서버를 socket.io에 바인딩
const io = socket(server);

app.get('/', function(request, response){
    fs.readFile('./static/index.html', function(err,data){
        if(err){
            console.error('index 불러오기 에러');
            response.send('에러');
        } else {
            response.writeHead(200, {'Content-Type':'text/html'});
            response.write(data);
            response.end();
        }
    })
})

// socket.on 뒤에 들어가는 값들은 클라이언트에서 던져주는 값
// 해당 값에 따라 동작이 달라짐
io.sockets.on('connection', function(socket){

    var instanceId = socket.id;


    /* 새로운 유저가 접속했을 경우 다른 소켓에게도 알려줌 */
    socket.on('newUser', function(name) {
        console.log('socket id : ' + instanceId);
        console.log(name + ' 님이 접속하였습니다.');

        /* 소켓에 이름 저장해두기 */
        socket.name = name;
    })

    // socket.join 으로 들어갈 방의 이름을 명시해주고 해당 방을 생성
    socket.on('joinRoom',function (data) {
        console.log(data);
        socket.join(data.roomName);
        roomName = data.roomName;
    });

    socket.on('reqMsg', function (data) {
        data.name = socket.name; // 이름 추가
        console.log(data);
        // io.sockets.in(roomName).emit('recMsg', {comment: socket.name + " : " + data.comment + "\r\n"});
        socket.in(roomName).emit('update', data);
    })

})

/* 서버를 3100 포트로 listen */
server.listen(port, function() {
    console.log('서버 실행 중..')
})


