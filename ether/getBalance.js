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

//메인홈페이지
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

    // // console.log(balance);
    // //
    //
    // res.write("balance : " + balance);
    // res.send(balance);

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