<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title>控制測試頁面</title>
  </head>
  <body>
    <div class="container">
        <div class="row fixed-top mt-1 mb-1">
            <input class="mx-auto" type="text" name="ip" value="" placeholder="請輸入IP" style="width:80%;">
        </div>

        <div class="row mt-5">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="seven_display">讀取開分</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="pressure">讀取BET</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="score">讀取WIN</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="big_reward_status">讀取BB</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="small_reward_status">讀取RB</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="open_1">開分1次</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="open_5">開分5次</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="open_10">開分10次</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="wash_zero">洗分清零</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="wash_remainder">洗分餘數</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="move_on">移分ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="move_off">移分OFF</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="start_auto">啟動自動</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="stop_auto">停止自動</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="stake_point">壓分</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="start_game">開始</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary"  data-action="stop_1">停1</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary"  data-action="stop_2">停2</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="stop_3">停3</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="draw_card_stop_123_red_7">抓牌停123紅7</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="draw_card_stop_123_blue_7">抓牌停123藍7</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="draw_card_stop_123_bar">抓牌停123bar</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="draw_card_stop_321_red_7">抓牌停321紅7</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="draw_card_stop_321_blue_7">抓牌停321藍7</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="draw_card_stop_321_bar">抓牌停321Bar</a>
                <div class="message"></div>
            </div>
        </div>

        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_all_off">輸出點位全OFF</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_1_on">輸出點位1 ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_2_on">輸出點位2 ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_3_on">輸出點位3 ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_4_on">輸出點位4 ON</a>
                <div class="message"></div>
            </div>
        </div>

        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_5_on">輸出點位5 ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_6_on">輸出點位6 ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_7_on">輸出點位7 ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_8_on">輸出點位8 ON</a>
                <div class="message"></div>
            </div>
            <div class="col">
            </div>
        </div>

        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_1_pulse">輸出點位1 Pulse</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_2_pulse">輸出點位2 Pulse</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_3_pulse">輸出點位3 Pulse</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_4_pulse">輸出點位4 Pulse</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_5_pulse">輸出點位5 Pulse</a>
                <div class="message"></div>
            </div>
        </div>

        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_6_pulse">輸出點位6 Pulse</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_7_pulse">輸出點位7 Pulse</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="out_8_pulse">輸出點位8 Pulse</a>
                <div class="message"></div>
            </div>
            <div class="col">
                
            </div>
            <div class="col">
                
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $('.btn').click(function(e){
            e.preventDefault();
            $('.message').text('');
            let object = $(this);
            if($('input[name=ip]').val()==''){
                $('input[name=ip]').focus();
                return false;
            }
            $.ajax({
                url: 'api/shuangmei/slot/v1/'+ $(this).data('action') + '/' + $('input[name=ip]').val() + '/8000',
                // data: {'ip': $('input[name=ip]').val()},
                success: function(json){
                    object.siblings('.message').text(json.message);
                },
                error: function(){
                    object.siblings('.message').text('網路異常');
                }
            });
        });
    </script>
  </body>
</html>