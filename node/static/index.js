var socket = io();

// 접속시 실행
socket.on('connect', function() {
    // 이름 입력 받기
    var name = prompt('님 반갑습니다!','');
    // 이름이 빈 칸인 경우
    if(!name) {
        name = '익명';
    }
    // 서버에 새 유저가 왔다고 알림
    socket.emit('newUser', name);
})

socket.on('update', function(data){
    console.log('$(data.name): $(data.message}')
})


function send() {
    // 입력창에 입력된 값 가져오기
    var message = document.getElementById('test').value;
    // 가져온 후 데이터 빈 값으로 변경
    document.getElementById('test').value = '';
    // 서버로 send 이벤트와 데이터 전달
    // * 중요 : 클라이언트와 서버의 이벤트명이 동일해야 값을 받을 수 있음
    // 여기서는 'send'
    socket.emit('send', {msg: message})
}

