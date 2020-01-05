var io = require('socket.io').listen(81);

var clients = [];
console.log("서버 실행 중");
io.sockets.on( 'connection', function ( socket ) {

    /* 새로운 유저가 접속했을 경우 다른 소켓에게도 알려줌 */
    // socket.on('newUser', function(name) {
    //     console.log(name + ' 님이 접속하였습니다.');
    //
    //     /* 소켓에 이름 저장해두기 */
    //     socket.name = name;
    //
    //     /* 모든 소켓에게 전송 */
    //     io.sockets.emit('update', {type: 'connect', name: 'SERVER', message: name + '님이 접속하였습니다.'});
    // })

    // 특정 유저 로그인시
    socket.on('login', function(data) {
        var clientInfo = new Object();
        clientInfo.uid = data.uid;
        clientInfo.id = socket.id;
        clients.push(clientInfo);
        console.log('data.uid : ' + data.uid + ', socket.id : ' + socket.id);
    });

    socket.on('message special user', function(data) {
        // 클라이언트 소켓 아이디를 통해서 그 소켓을 가진 클라이언트에만 메세지를 전송
        for (var i=0; i < clients.length; i++) {
            var client = clients[i];
            console.log('client.uid = '+ client.uid);
            if (client.uid == data.uid) {
                console.log('매핑 성공 client.uid ='+ client.uid);
                io.sockets.to(client.id).emit('message', data.msg);
                console.log(client.uid + ' 유저에게 메세지 전송 : ' + data.msg);
                break;
            }
        }
    });
    socket.on('disconnect', function() {
        for (var i=0; i < clients.length; i++) {
            var client = clients[i];
            if (client.id == socket.id) {
                clients.splice(i, 1);
                break;
            }
        }
        console.log('user disconnected');
    });


});