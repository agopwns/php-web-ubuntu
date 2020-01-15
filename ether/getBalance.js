var express = require('express');
var app = express();
const https = require('https');
var Web3 = require('web3');
const fs = require('fs');
const cors = require('cors');

var web3 = new Web3(new Web3.providers.WebsocketProvider('wss://ropsten.infura.io/ws'));
const Tx = require('ethereumjs-tx').Transaction;
// console.log(Tx);

var bodyparser = require('body-parser');
app.use(cors());
app.use(bodyparser.json());
app.use(bodyparser.urlencoded({extended : true}));

// 지갑의 이더 잔액 조회
app.post('/getBalance', function(req,res){

    // const walletAddress = "0x9c91e86Fe5E6966CCd59fc0c8B9F48249B409c47";
    const walletAddress = req.param("sender_address");
    console.log("sender_address : " + walletAddress);

    var balance = web3.eth.getBalance(walletAddress, function(error, result){
        if(error){
            console.log(error);
        } else {
            // res.writeHead(200);
            console.log(result);
            var balance = result / 1E18;
            console.log(balance);
            res.json({message : '200 ok', result : balance})
        }
    });
});

// 이더 전송
app.post('/sendEther', function(req,res){
    const send_account = req.param("sender_address");
    const receive_account = req.param("receiver_address");
    const send_value = req.param("send_value");
    const private_key = Buffer.from(req.param("private_key"), 'hex');
    const done_message = req.param("done_message");
    console.log("send_account : " + send_account);
    console.log("receive_account : " + receive_account);
    console.log("send_value : " + send_value);
    console.log("private_key : " + private_key);
    console.log("done_message : " + done_message);

    var new_send_value = 0;
    if(send_value == '1'){
        new_send_value = 1000000000000000000;
    } else if (send_value == '0.1'){
        new_send_value = 100000000000000000;
    } else if (send_value == '0.5'){
        new_send_value = 500000000000000000;
    }
    console.log("new_send_value : " + new_send_value);

    web3.eth.getTransactionCount(send_account, (err, txCount) => {
        const txObject = {
            nonce:    web3.utils.toHex(txCount), // getTransactionCount 의 결과로 txCount 가 나옴
            gasLimit: web3.utils.toHex(1000000), // 가스 리밋은 높을 수록 노드 처리 속도가 빨라짐
            gasPrice: web3.utils.toHex(web3.utils.toWei('99', 'gwei')), // 때문에 적정 수준으로 맞출 필요가 있음
            to: receive_account, // 받는 사람
            value : new_send_value //'0x2386f26fc10000' // 0.01이더 전송 to_hex
        };
        const tx = new Tx(txObject, {'chain':'ropsten'}); // 추가. 해당 체인이 어디인지를 알려줘야 함.
        tx.sign(private_key); // 메인넷과 테스트넷은 개인키 sign 객체가 필수임
        const serializedTx = tx.serialize();
        const raw = '0x' + serializedTx.toString('hex');
        // 본 트랜잭션 작업
        web3.eth.sendSignedTransaction(raw)
            .once('transactionHash', (hash) => { // 보내는 위치
                console.info('transactionHash', 'https://ropsten.etherscan.io/tx/' + hash);
            })
            .once('receipt', (receipt) => { // 영수증. 결과. 성공시 이 부분에 포인트 충전 로직 처리
                console.info('receipt', receipt);
                res.json({message : '200 ok', result : receipt})
            }).on('error', console.error);
    });

});

var options = {
    key: fs.readFileSync('../RTC/fake-keys/privatekey.pem'),
    cert: fs.readFileSync('../RTC/fake-keys/certificate.pem'),
    requestCert: false,
    rejectUnauthorized: false
};
var server = require('https').createServer(options, app);

//app을 listen
server.listen(4001, function(){
    console.log('Connected memo, 4001 port!');
});