const express = require('express');
const socket = require('socket.io');
const http = require('http');
const fs = require('fs');
const app = express(); // express 객체 생성
const port = 3000;

// express http 서버 생성
const server = http.createServer(app);
// 생성된 서버를 socket.io에 바인딩
const io = socket(server);

// css와 js 사용할 수 있게 추가
// app.use('./css', express.static('./static/css'));
// app.use('./js', express.static('./static/js'));

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

    /* 새로운 유저가 접속했을 경우 다른 소켓에게도 알려줌 */
    socket.on('newUser', function(name) {
        console.log(name + ' 님이 접속하였습니다.');

        /* 소켓에 이름 저장해두기 */
        socket.name = name;

        /* 모든 소켓에게 전송 */
        io.sockets.emit('update', {type: 'connect', name: 'SERVER', message: name + '님이 접속하였습니다.'});
    })

    /* 전송한 메시지 받기 */
    socket.on('message', function(data) {
        /* 받은 데이터에 누가 보냈는지 이름을 추가 */
        data.name = socket.name;

        console.log(data);

        /* 보낸 사람을 제외한 나머지 유저에게 메시지 전송 */
        socket.broadcast.emit('update', data);
    })

    /* 접속 종료 */
    socket.on('disconnect', function() {
        console.log(socket.name + '님이 나가셨습니다.');

        /* 나가는 사람을 제외한 나머지 유저에게 메시지 전송 */
        socket.broadcast.emit('update', {type: 'disconnect', name: 'SERVER', message: socket.name + '님이 나가셨습니다.'});
    })
})


/* 서버를 8080 포트로 listen */
server.listen(port, function() {
    console.log('서버 실행 중..')
})


