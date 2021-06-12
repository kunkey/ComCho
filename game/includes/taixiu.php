<?php
defined('_IN_JOHNCMS') or die('Error: restricted access');

/***
 * Dev By Kunkey
 * JohnCms 6.2.1
 * Date: 11/06/2021
 ****/

echo '
<div class="phdr">
    <i class="fa fa-gamepad" aria-hidden="true"></i> Tài Xỉu Xanh Chín
</div>';
?>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@900&display=swap" rel="stylesheet">
<!-- Include the polyfill -->
<script src="https://cdn.rawgit.com/web-animations/web-animations-js/2.2.2/web-animations.min.js"></script>

<!-- Include Animatelo -->
<script src="https://cdn.rawgit.com/gibbok/animatelo/1.0.3/dist/animatelo.min.js"></script>

<style>

.class-input {
    border: 0 solid #fff;
    background: rgba(255, 255, 255, 0);
    text-align: center
}

@media(min-width:0px) and (max-width:480px) {
    .khung-tx {
        width: 100%!important;
        margin: auto;
        position: absolute;
    }
    .chat-wrap {
        width: 100%!important;
        max-width: 250px!important;
        display: none!important
    }
    .khung-tx .font_size_35 {
        font-size: 5.5vw!important;
        font-weight: 700
    }
    .khung-tx .font_size_25 {
        font-size: 4.2vw!important;
        font-weight: 700
    }
    .khung-tx .font_size_18 {
        font-size: 3.7vw!important;
        font-weight: 700
    }
    .khung-tx .font_size_80 {
        font-size: 3.7vw!important;
        font-weight: 700
    }
    .khung-tx .font_size_100 {
        font-size: 15.5vw!important;
        font-weight: 700
    }
}

#game-taixiu {
    z-index: 10000000000;
    height: 0;
    width: 100%;
    font-weight: 700
}

#game-taixiu #khung-tx {
    position: relative;
    margin: auto
}

.khung-tx {
    width: 55%;
    display: block;
    margin-bottom: 11%;
    max-width: 600px;
    position: relative;
    margin-right: -3%;
    margin: auto;
}

.khung-tx .button-top {
    right: 0;
    top: 0;
    z-index: 2;
    width: 7.5%;
    position: absolute;
    cursor: pointer
}

.khung-tx .button-top img {
    width: 100%;
    cursor: pointer;
    z-index: 100;
    position: absolute
}

.khung-tx .money-xiu,
.khung-tx .money-tai {
    color: #ff0;
    text-shadow: 0 0 8px black, 0 0 8px #000;
    text-align: center
}

.khung-tx .cuoc-xiu,
.khung-tx .cuoc-tai {
    color: #7aff00;
    text-shadow: 0 0 8px black, 0 0 8px #000;
    text-align: center;
    font-weight: 600;
    height: 18%;
    margin-top: 40%;
}

.khung-tx .user-xiu,
.khung-tx .user-tai {
      color: #fff !important;
    text-shadow: 0 0 8px black, 0 0 8px #000;
    text-align: center;
    height: 20%;
    margin-left: 0;
    width: 100%;
    margin-top: -2%;

}

.khung-tx .input-xiu,
.khung-tx .input-tai {
    color: transparent;
    text-shadow: 0 0 0 #ff0;
    &: focus {;
    outline: none;
    }: ;
    color: #ff0 !important;
    font-weight: 600;
    position: absolute;
    text-align: center;
    height: 20%;
    margin-top: .5%;
    width: 23%;
    padding: 0;
    z-index: 49;
    cursor: pointer;
    outline: unset !important;
}

.khung-tx .input-cuoc-hide div {
    height: 60%;
    width: 97%;
    margin-top: 8%;
    border-radius: 25px;
    border: 2px solid rgba(255, 255, 255, 0)
}

.khung-tx .input-cuoc-hide.active div {
    animation: changesolid .35s infinite alternate;
    -webkit-animation: changesolid .35s infinite alternate;
    -moz-animation: changesolid .35s infinite alternate;
    -ms-animation: changesolid .35s infinite alternate;
    -o-animation: changesolid .35s infinite alternate
}

.placered::placeholder {
  color:#ff0 !important;
}

.placered::-ms-placeholder {
  color:#ff0 !important;
}

.placered::-moz-placeholder {
  color:#ff0 !important;
}

.placered::-webkit-input-placeholder {
  color:#ff0 !important;
}

.placered::-o-input-placeholder {
  color:#ff0 !important;
}


.placewhite::placeholder {
    color: rgba(255, 255, 255, 0.8)!important
}

.placewhite::-ms-placeholder {
    color: rgba(255, 255, 255, 0.8)!important
}

.placewhite::-moz-placeholder {
    color: rgba(255, 255, 255, 0.8)!important
}

.placewhite::-webkit-input-placeholder {
    color: rgba(255, 255, 255, 0.8)!important
}

.placewhite::-o-input-placeholder {
    color: rgba(255, 255, 255, 0.8)!important
}

.khung-tx .clock-big {
    font-family: 'Roboto Slab', serif;
    text-shadow: 0 0 8px black, 0 0 8px #000;
    font-weight: 800;
    position: absolute;
    top: 8%;
    left: 23%;
    color: #dada25;
}

.khung-tx .btn-xiu,
.khung-tx .btn-tai {
width: 6.5%;
    float: left;
    height: 110%;
    border: 0;
    background: url(imgs/taixiu/ico_kq_xiu.png) 0 0 / cover no-repeat;
    margin: 0 .5%;
    cursor: pointer;
}

.khung-tx .btn-tai {
    background-image: url(imgs/taixiu/ico_kq_tai.png)
}

.khung-tx .his {
    float: left;
    width: 57%;
    text-align: center;
    margin-left: 21.7%;
    margin-top: .4%;
    height: 14.8%;
    margin-bottom: -1%;
    padding: 2.05% 0
}

.khung-tx .group-button {
    float: left;
    width: 100%;
    text-align: center;
    height: auto;
    margin-top: 2%;
    display: none;
    -webkit-touch-callout: none!important;
    -webkit-user-select: none!important;
    -khtml-user-select: none!important;
    -moz-user-select: none!important;
    -ms-user-select: none!important;
    user-select: none!important
}

.khung-tx .group-button .button {
    cursor: pointer;
    width: 15.6%;
    height: 85%;
    float: left;
    margin: .25% .5%;
    background: url(https://nro2021.com/lib2/imgdep/chontien.png) 0 0 / cover no-repeat;
    position: relative;
    background-size: 100% 100%
}

.khung-tx .group-button .button.blue {
    height: 100%;
    width: 23%;
    margin-left: 16%;
    background-image: url(https://nro2021.com/lib2/imgdep/close.png)
}

.khung-tx .group-button .button.green {
    height: 100%;
    width: 23%;
    background-image: url(https://nro2021.com/lib2/imgdep/datcuoc.png)
}

.khung-tx .group-button .button.red {
    height: 100%;
    width: 23%;
    background-image: url(https://nro2021.com/lib2/imgdep/huy.png)
}

.khung-tx .group-button .middle {
    position: absolute;
    white-space: nowrap;
    color: #fff;
    text-shadow: 0 0 8px black, 0 0 8px #000
}

.khung-tx .group-button .middle:after {
    content: attr(data-txt)
}

.khung-tx .his div:hover~ :nth-child(14) {
    -webkit-animation: none!important;
    -moz-animation: none!important;
    -o-animation: none!important;
    animation: none!important
}

.khung-tx .his div:hover {
    animation: updown .5s infinite alternate;
    -webkit-animation: updown .5s infinite alternate;
    -moz-animation: updown .5s infinite alternate;
    -ms-animation: updown .5s infinite alternate;
    -o-animation: updown .5s infinite alternate
}

.khung-tx .his div:nth-child(14) {
    animation: updown .5s infinite alternate;
    -webkit-animation: updown .5s infinite alternate;
    -moz-animation: updown .5s infinite alternate;
    -ms-animation: updown .5s infinite alternate;
    -o-animation: updown .5s infinite alternate
}

.btn-cuocMoney {
    outline: 0;
    padding: .5%;
    color: #333;
    border-radius: 5px;
    background-color: #fff;
    border-color: #ccc;
    box-shadow: 0 0 5px 3px #000;
    border: 0 solid #000;
    font-weight: 900;
    cursor: pointer
}

.btn-cuocMoney:hover,
.btn-cuocMoney:focus {
    background-color: #673AB7!important;
    color: #fff!important
}

.btn-tattay {
    outline: 0;
    transform: scale(0.95);
    padding: .5%;
    color: #fff;
    text-shadow: 0 0 3px black, 0 0 3px #000;
    border-radius: 5px;
    background-color: #f55541;
    border-color: #ccc;
    box-shadow: 0 0 5px 3px #000;
    border: 0 solid #000;
    font-weight: 900;
    cursor: pointer
}

.btn-cancel {
    outline: 0;
    transform: scale(0.95);
    padding: .5%;
    color: #fff;
    text-shadow: 0 0 3px black, 0 0 3px #000;
    border-radius: 5px;
    background-color: #f0ad4e;
    border-color: #ccc;
    box-shadow: 0 0 5px 3px #000;
    border: 0 solid #000;
    font-weight: 900;
    cursor: pointer
}

.btn-agree {
    outline: 0;
    transform: scale(0.95);
    padding: .5%;
    color: #fff;
    text-shadow: 0 0 3px black, 0 0 3px #000;
    border-radius: 5px;
    background-color: #00a65a;
    border-color: #ccc;
    box-shadow: 0 0 5px 3px #000, 0 0 5px 3px #000;
    border: 0 solid #000;
    font-weight: 900;
    cursor: pointer
}

.khung-tx .tai-wrap,
.khung-tx .xiu-wrap {
    width: 23%;
    height: 60%;
    float: left;
    margin-top: 5%;
}

.khung-tx .tai-wrap .money-tai,
.khung-tx .xiu-wrap .money-xiu {
    height: 20%;
    margin-top: 11%;
    width: 100%
}

.khung-tx .tai-wrap .icon,
.khung-tx .xiu-wrap .icon {
    background: url(imgs/taixiu/tai_default.png) 0 0 /cover no-repeat;
    width: 60%;
    height: 28%;
    margin-left: 20%
}

.khung-tx .xiu-wrap .icon {
    background-image: url(imgs/taixiu/xiu_default.png)
}

.khung-tx .tai-wrap .icon.kq {
    background-image: url(imgs/taixiu/tai_on.png)!important;
    background-size: 100% auto;
    background-repeat: no-repeat;
    -webkit-animation: zoomout .5s infinite alternate;
    -moz-animation: zoomout .5s infinite alternate;
    animation: zoomout .5s infinite alternate
}

.khung-tx .xiu-wrap .icon.kq {
    background-image: url(imgs/taixiu/xiu_on.png)!important;
    background-size: 100% auto;
    background-repeat: no-repeat;
    -webkit-animation: zoomout .5s infinite alternate;
    -moz-animation: zoomout .5s infinite alternate;
    animation: zoomout .5s infinite alternate
}

.khung-tx #vung-taixiu {
    margin-top: 0;
    margin-left: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    background: url(https://nro2021.com/lib/img/ngocrong/bat.png) 0 0 / cover no-repeat;
    border: 0 solid #fff;
    z-index: 51;
    cursor: pointer;
    display: none
}

.khung-tx .clock,
.khung-tx .clock img {
    position: absolute;
    width: 100%;
    height: 100%
}

.khung-tx #game-taixiu2 {

        width: 32%;
    height: 60%;
    float: left;
    margin-top: 10%;
    position: relative;
    background-position: center;
    background-size: 100%;
}

.khung-tx #game-taixiu2.nantx .vung {
    display: block
}

.khung-tx #game-taixiu2 #vung-taixiu .vung_number span {
    display: table-cell;
    text-align: center;
    vertical-align: middle
}

.khung-tx #game-taixiu2 #vung-taixiu .vung_number {
    display: table;
    width: 30%;
    height: 32%;
    background: #000;
    border-radius: 50%;
    color: #fff;
    margin-top: 50%;
    margin-left: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    -o-transform: translate(-50%, -50%)
}

.khung-tx #game-taixiu2 .kq-num div {
    position: absolute
}

.khung-tx #game-taixiu2 .kq-num {
    position: absolute;
    height: 26%;
    width: 25%;
    margin-top: 15%;
    margin-left: 72%;
    opacity: 0;
    border: 2.5px solid rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    background: #FF5722;
    font-weight: bolder;
    color: #fff
}

.khung-tx #game-taixiu2 .roll-play {
    position: absolute;
    top: -23%;
    width: 100%;
    height: 22%;
    text-align: center;
    color: #fff;
    border-radius: 15px;
    text-shadow: 0 0 4px black, 0 0 4px black, 0 0 4px #000
}

.khung-tx #game-taixiu2 .clock-small {
    position: absolute;
    background: rgba(0, 0, 0, 0.7);
    top: 10%;
    width: 22%;
    height: 22%;
    left: 81%;
    text-align: center;
    color: #fdfdfd;
    -webkit-transform: translateX(-50%);
    -moz-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    -o-transform: translateX(-50%);
    transform: translateX(-50%);
    border-radius: 17px;
    padding: 7px;
}

.khung-tx #game-taixiu2.time .vung,
.khung-tx #game-taixiu2.time .effect,
.khung-tx #game-taixiu2.time .clock-small,
.khung-tx #game-taixiu2.time .kq-num {
    display: none
}

.khung-tx #game-taixiu2.time .clock-big {
    display: block
}

.chat-wrap {
    width: 25%;
    display: block;
    max-width: 270px;
    position: relative;
    float: left
}

.chat-wrap.off {
    display: none
}

.chat-wrap .chat-content {
    height: 72%;
    overflow: hidden;
    padding: 10% 10% 0;
    position: relative
}

.chat-wrap .chat-inner {
    overflow: hidden;
    height: 100%
}

.chat-wrap .chat-footer {
    width: 93%;
    height: 14%;
    margin-top: 1%;
    background: none;
    font-size: 20px;
    font-weight: 600
}

.chat-wrap .chat-input {
    width: 100%;
    height: 100%;
    background: none;
    border: 0 solid #fff;
    padding: 0 12%;
    color: #fff;
    z-index: 49
}

.chat-wrap .chat-inner p {
    margin: 0;
    color: #fff;
    line-height: 1.05;
    cursor: default
}

.chat-wrap .chat-inner p .u-name {
    color: #CDDC39;
    font-weight: 400
}

.khung-tx .effect {
    background: url() 0 0 / cover no-repeat;
    visibility: visible;
    z-index: 5;
    height: 100%;
    margin-top: -8%;
    opacity: 0
}

.khung-tx .his .tooltip_tx {
    position: absolute;
    color: #fff;
    background: rgba(0, 0, 0, 0.6);
    padding: 3px 15px;
    margin-top: 4%;
    left: 12.5%;
    white-space: nowrap;
    z-index: 49;
    display: none
}

.khung-tx .clogame {
margin-left: -110%;
    margin-top: 38%
}

.khung-tx .wingame,
.khung-tx .allgame {
margin-top: 150%;
    margin-left: -30%;
}

.khung-tx .hisgame {
margin-top: 290%;
    margin-left: 7%;
}

.khung-tx .guigame {
margin-top: 428%;
    margin-left: -26%;
}

.khung-tx .nangame {
    margin-top: 430%;
    margin-left: -525%
}

</style>
            
        <div class="rmenu" style="height: 270px;">

        <div id="game-taixiu" class="khung_game_show ui-draggable actigame" style="display: block; left: 316px; top: 105.391px;">
            <div id="khung-tx">
                <div class="khung-tx">
                    <div class="button-top">
                        <img class="allgame" src="imgs/taixiu/thongke.png">
                        <img class="hisgame" src="imgs/taixiu/lichsu.png">
                        <img class="guigame" src="imgs/taixiu/help.png">
                    </div>
                                            <div class="font_size_20" style="position:absolute;width: 100%;height: 100%;">
                                                <div class="tai-wrap" style="margin-left:11%;">
                                                    <div id="iconTai" class="icon kq"></div>
                                                    <div id="moneyTai" class="money-tai">0</div>
                                                    <input type="number" id="tai" readonly="" onclick="show_btnBet('Tai')" class="input-tai class-input font_size_20 placered" placeholder="Cược Tài" style="z-index: 50;">
                                                        <div class="cuoc-tai"></div>
                                                        <div id="userTai" class="user-tai">0</div>
                                                    </div>
                                                    <div id="game-taixiu2" class="time">
                                                        <div class="clock"></div>
                                                        <div class="roll-play">
                                                            <span id="idGame">xxxxx</span>
                                                        </div>
                                                        <div id="time" class="clock-big font_size_100 middle" style="display: block;">??</div>
                                                        <div id="timeChoPhienMoi" class="clock-small"></div>
                                                        <div id="roll" class="effect" style="background-position-y: 0%; opacity: 1; background-image: url(&quot;imgs/taixiu/xx6_1.png&quot;), url(&quot;imgs/taixiu/xx5_2.png&quot;), url(&quot;imgs/taixiu/xx3_3.png&quot;);"></div>
                                                    </div>
                                                    <div class="xiu-wrap">
                                                        <div id="iconXiu" class="icon"></div>
                                                        <div id="moneyXiu" class="money-xiu">0</div>
                                                        <input type="number" id="xiu" readonly=""  onclick="show_btnBet('Xiu')" class="input-xiu class-input font_size_20 placered" placeholder="Cược Xỉu" style="z-index: 50;">
                                                            <div class="cuoc-xiu"></div>
                                                            <div id="userXiu" class="user-xiu">0</div>
                                                        </div>
                                                        <div class="his" style="z-index: 49;" onmouseout="hide_roll_tx()">
                                                            <div class="btn-xiu"></div>
                                                            <div class="btn-xiu"></div>
                                                            <div class="btn-tai"></div>
                                                            <div class="btn-tai"></div>
                                                            <div class="btn-xiu"></div>
                                                            <div class="btn-tai"></div>
                                                            <div class="btn-xiu"></div>

                                                            <span class="tooltip_tx font_size_18"></span>
                                                        </div>
                                                    </div>
                                                    <img src="imgs/taixiu/anh1.png" width="100%">
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        </div>                

<div class="container" id="btnBetTai" style="margin-top: -40px;text-align: center;"> 
    <button type="button" onclick="choiceValueBet('tai', 100)" style="margin-bottom: 5px;" class="btn btn-sm btn-primary">100</button>
    <button type="button" onclick="choiceValueBet('tai', 500)" style="margin-bottom: 5px;" class="btn btn-sm btn-secondary">500</button>
    <button type="button" onclick="choiceValueBet('tai', 1000)" style="margin-bottom: 5px;" class="btn btn-sm btn-warning">1.000</button>
    <button type="button" onclick="choiceValueBet('tai', 2000)" style="margin-bottom: 5px;" class="btn btn-sm btn-success">2.000</button>
    <button type="button" onclick="choiceValueBet('tai', 5000)" style="margin-bottom: 5px;" class="btn btn-sm btn-secondary">5.000</button>
    <button type="button" onclick="Bet('tai')" style="margin-bottom: 5px;" class="btn btn-sm btn-danger">Đặt</button> 
    <button type="button" onclick="clearValue('tai')" style="margin-bottom: 5px;" class="btn btn-sm btn-danger">Hủy</button> 
</div>

<div class="container" id="btnBetXiu" style="margin-top: -40px;text-align: center;"> 
    <button type="button" onclick="choiceValueBet('xiu', 100)" style="margin-bottom: 5px;" class="btn btn-sm btn-secondary">100</button>
    <button type="button" onclick="choiceValueBet('xiu', 500)" style="margin-bottom: 5px;" class="btn btn-sm btn-success">500</button>
    <button type="button" onclick="choiceValueBet('xiu', 1000)" style="margin-bottom: 5px;" class="btn btn-sm btn-warning">1.000</button>
    <button type="button" onclick="choiceValueBet('xiu', 2000)" style="margin-bottom: 5px;" class="btn btn-sm btn-secondary">2.000</button>
    <button type="button" onclick="choiceValueBet('xiu', 5000)" style="margin-bottom: 5px;" class="btn btn-sm btn-primary">5.000</button>
    <button type="button" onclick="Bet('xiu')" style="margin-bottom: 5px;" class="btn btn-sm btn-danger">Đặt</button> 
    <button type="button" onclick="clearValue('xiu')" style="margin-bottom: 5px;" class="btn btn-sm btn-danger">Hủy</button> 
</div>
                                    </div>



<div style="margin-bottom: 0px;"></div>

<div class="phdr"><i class="fa fa-history"></i> Lịch Sử Kết Quả</div>
<div class="rmenu"></div>



<!-- DIV CHUA CACHE IAMGES -->
<div id="cache-div" style="display:none;"></div>


<script src="http://103.159.50.183:3000/socket.io/socket.io.js"></script>

<script>

const authToken = '<?php echo $_SESSION['authtoken'];?>';

$(document).ready(() => {
    $('#btnBetTai').hide();
    $('#btnBetXiu').hide();
});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}


function show_btnBet(betChoice) {
    (betChoice == 'Tai') ? $('#btnBetXiu').hide() : $('#btnBetTai').hide();
    $('#btnBet'+betChoice).show();
}

function choiceValueBet(betChoice, value) {
    let moneySet;
    var current_val = $('#'+betChoice).val();
    (current_val == "") ? moneySet = 0 : moneySet = Number($('#'+betChoice).val());

    $('#'+betChoice).val(moneySet + value);
}

function clearValue(betChoice) {
    $('#'+betChoice).val('');
    (betChoice == 'tai') ? $('#btnBetTai').hide() : $('#btnBetXiu').hide();
}

function Bet(betChoice) {
        socket.emit('pull',{
            id : socket.id,
            dice: betChoice,
            money: Number($('#'+betChoice).val())
        })
        $('#'+betChoice).val('');
}


idGame = document.getElementById("idGame");
time = document.getElementById("time");
moneyTai = document.getElementById("moneyTai");
moneyXiu = document.getElementById("moneyXiu");
userTai = document.getElementById("userTai");
userXiu = document.getElementById("userXiu");

gameStart = () => {
    $('#timeChoPhienMoi').fadeOut(100);
    $('#timeChoPhienMoi').html();

    time.style.color = "#dada25";
    roll = document.getElementById("roll");
    time.style.display = "block";
    roll.style.backgroundImage = "";
    roll.style.display = "block"; 
}


gameOver = (dice) => {
    // hiệu ứng lắc xúc xắc
    roll = document.getElementById("roll");
    time.style.display = "none";
    roll.style.backgroundImage = "";
    roll.style.backgroundImage = 'url(imgs/taixiu/roll1.gif)';
    roll.style.display = "block"; 
    
    let resultValue;
    (dice.dice1 + dice.dice2 + dice.dice3 <= 9) ? resultValue = 'Xiu' : resultValue = 'Tai';

    // chờ xuất 3 xúc xắc ra
    setTimeout(()=>{
        roll.style.backgroundImage = "";
        roll.style.opacity = "1.0";
        roll.style.backgroundImage = 'url("imgs/taixiu/xx'+dice.dice1+'_1.png"), url("imgs/taixiu/xx'+dice.dice2+'_2.png"), url("imgs/taixiu/xx'+dice.dice3+'_3.png")';

        // rung kết quả
        var options = {
          duration: 700,
          delay: 700,
          iterations: Infinity,
          direction: 'alternate',
          fill: 'both'
        };
        window.animatelo.tada('#icon'+resultValue, options);

        setTimeout(() => {
            window.animatelo.tada('#icon'+resultValue);
        }, 9000);

    },2500);
    
}



// var socket = io.connect('103.159.50.183:3000', {
//     extraHeaders: {
//         Authorization: authToken
//     }
// }); 

var socket = io.connect('103.159.50.183:3000', {
  query: "token=" + authToken
});


socket.on('connect', () => {
  console.log('connected');
})

socket.on('gameData', function (data) {
    if(data.time.toString().length == 1){
        window.animatelo.bounceIn('#moneyTai');
        window.animatelo.bounceIn('#moneyXiu');
        time.style.color = "#ff0000";
        time.innerHTML  = data.time == 0 ? '' : '0' + data.time;
    }else{
        time.innerHTML  = data.time;
    }

    idGame.innerHTML = '#' + data.idGame;
    userTai.innerHTML = data.userTai;
    userXiu.innerHTML = data.userXiu;
    moneyTai.innerHTML = numberWithCommas(data.moneyTai);
    moneyXiu.innerHTML = numberWithCommas(data.moneyXiu);
});

socket.on('gameOver', function (data) {
    gameOver(data);
});

socket.on('gameStart', function (data) {
    gameStart();
    //showStt('Game bắt đầu');
});

socket.on('pull', function (data) {
    if(data.status == 'success'){
        //showStt('Đặt cược thành công');
    }else if(data.status == 'error'){
        //showStt(data.error);
        cuteToast({
            type: "error", // or 'info', 'error', 'warning'
            message: data.error,
            timer: 1500
        });
    }
    
});

socket.on('gameWaitNewRound', (data) => {
    setTimeout(() => {
        loopATime = setInterval(function() {   
            data.time --;
            if (data.time == 0){
                clearInterval(loopATime);
            }
            $('#timeChoPhienMoi').html(data.time);

        }, 1000);
        // thời gian chờ phiên mới
        $('#timeChoPhienMoi').show();
    }, 4500);
});

socket.on('winGame', function (data) {
    setTimeout(() => {
        cuteToast({
            type: "success", // or 'info', 'error', 'warning'
            message: data.msg,
            timer: 6000
        });
    }, 2600);

});


// LOAD CACHE IMAGES
$(document).ready(() => {
    const pathImg = 'imgs/taixiu/';
    const typeimg = 'png';

    for(var dice1 = 1; dice1 <= 6; dice1++) {
        const srcImg = pathImg+ 'xx' + dice1 +  '_1.' + typeimg;
        $('#cache-div').append('<img src="' +srcImg+ '"/>');
    }
    for(var dice2 = 1; dice2 <= 6; dice2++) {
        const srcImg = pathImg+ 'xx' + dice2 +  '_2.' + typeimg;
        $('#cache-div').append('<img src="' +srcImg+ '"/>');
    }
    for(var dice3 = 1; dice3 <= 6; dice3++) {
        const srcImg = pathImg+ 'xx' + dice3 +  '_3.' + typeimg;
        $('#cache-div').append('<img src="' +srcImg+ '"/>');
    }

});

</script>
