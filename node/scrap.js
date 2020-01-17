var express = require('express');
var app = express();
const https = require('https');

const axios = require("axios");
const cheerio = require("cheerio");
const request = require("request");

// 지갑의 이더 잔액 조회
app.post('/getBalance', function(req,res){

    var returnHtml =  '';
    var sourceUrl = 'https://trends.google.co.kr/trends/trendingsearches/daily/rss?geo=KR';
    var title = new Array(),
        description = new Array(),
        views = new Array(),
        date = new Array(),
        url = new Array();
    function convertWeekDaysByKorean(weekday) {
        if (weekday.length < 1) {
            return "";
        }
        weekday = weekday.substring(0, weekday.length-1);
        switch (weekday) {
            case "Mon" :
                return "월요일";
            case "Tue" :
                return "화요일";
            case "Wed" :
                return "수요일";
            case "Thu" :
                return "목요일";
            case "Fri" :
                return "금요일";
            case "Sat" :
                return "토요일";
            case "Sun" :
                return "일요일";
        }
        return "";
    }
    function getDataFormat(date) {
        let MONTHS = {Jan : "1", Feb : "2", Mar : "3", Apr : "4", May : "5", Jun : "6", Jul : "7", Aug : "8", Sep : "9", Oct : "10", Nov : "11", Dec : "12"}
        let arr = date.split(' ');

        if (arr.length >= 4) {
            return (arr[3] + " " + MONTHS[arr[2]] + " " + arr[1] + " " + convertWeekDaysByKorean(arr[0]));
        }
        else {
            return "";
        }
    }
    request(sourceUrl, function(error, response, html){
        if (!error) {
            var $ = cheerio.load(html, {xmlMode : true});
            $('item').each(function () {
                title.push($(this).children('title').text());
            });
            $('item').each(function () {
                let pubDate = $(this).children('pubDate').text();
                date.push(getDataFormat(pubDate));
            });
            $('item').each(function () {
                description.push($(this).children('ht\\:news_item').children('ht\\:news_item_title').text());
            });
            $('item').each(function () {
                views.push($(this).children('ht\\:approx_traffic').text());
            });
            $('item').each(function () {
                url.push($(this).children('ht\\:news_item').children('ht\\:news_item_url').first().text());
            });
            let tempDate, rank = 1;
            for (let i = 0; i < title.length; i++) {
                if (tempDate != date[i]) {
                    tempDate = date[i];
                    rank = 1;
                    returnHtml += "<br><b>" + "<span style=\"color:red\">" + tempDate + "</span></b><br>";
                    console.log("<br><b>" + "<span style=\"color:red\">" + tempDate + "</span></b><br>");
                }
                console.log("<br>" + rank + ". <b> <a href="+ url[i] + ">" + title[i] + "</a></b>  조회수 : " + views[i] + "<br>");
                console.log(description[i] + "<br>");
                rank++;
            }
        }
    });

});


