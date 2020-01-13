var express = require('express');
var app = express();
var Web3 = require('web3');
var web3 = new Web3(new Web3.providers.WebsocketProvider('wss://ropsten.infura.io/ws'));
const Tx = require('ethereumjs-tx').Transaction;
console.log(Tx);

const send_account    = "0x9c91e86Fe5E6966CCd59fc0c8B9F48249B409c47";
const receive_account = "0x8e2318C0bD23517C8237fe33e281578cC832839e";
const privateKey = Buffer.from('B20A44DE820059B5555CE6E20C0E80491F0BBD16A3C927E98157C7B8DEC128D3', 'hex');


//메인홈페이지
app.get('/main', function(req,res){

    web3.eth.getTransactionCount(send_account, (err, txCount) => {

        const txObject = {
            nonce:    web3.utils.toHex(txCount),
            gasLimit: web3.utils.toHex(1000000), // Raise the gas limit to a much higher amount
            gasPrice: web3.utils.toHex(web3.utils.toWei('10', 'gwei')),
            to: receive_account,
            value :  '0x2386f26fc10000' //0.01이더 전송 to_hex
        };

        const tx = new Tx(txObject, {'chain':'ropsten'});
        tx.sign(privateKey);

        const serializedTx = tx.serialize();
        const raw = '0x' + serializedTx.toString('hex');

        web3.eth.sendSignedTransaction(raw)
            .once('transactionHash', (hash) => {
                console.info('transactionHash', 'https://ropsten.etherscan.io/tx/' + hash);
            })
            .once('receipt', (receipt) => {
                console.info('receipt', receipt);
            }).on('error', console.error);
    });

});
//app을 listen
app.listen(4000, function(){
    console.log('Connected memo, 4000 port!');
});