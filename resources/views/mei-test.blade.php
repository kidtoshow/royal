<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title>雙美鋼珠控制測試頁面</title>
  </head>
  <body>
    <div class="container">
        <div class="row fixed-top mt-1 mb-1">
            <input class="mx-auto" type="text" name="ip" value="" placeholder="請輸入要測試的IP" style="width:80%;">
        </div>
        <div class="row mt-5">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="display_turn_point">轉分</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="display_score">得分</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="display_open_point">開分</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="display_all_status">一次取全部</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_draw_status">開獎狀態</a>
                <div class="message"></div>
            </div>
            <div class="col">
                
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_push">PUSH</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_push_2hz">PUSH2HZ</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_push_5hz">PUSH5HZ</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_down_turn">下轉</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary"  data-action="plc_up_turn_100">上轉100</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary"  data-action="plc_up_turn_500">上轉500</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary"  data-action="plc_start_or_stop">自動開始/停止</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_start_1">開始1次</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_sub_point">下珠</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_wash_zero">洗分清零</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_wash_remainder">洗分餘數</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_open_1">開分1次</a>
                <div class="message"></div>
            </div>
        </div>
        <div class="row mt-1 mb-1">
            <div class="col">
                <a href="" class="btn btn-primary" data-action="plc_open_10">開分10次</a>
                <div class="message"></div>
            </div>
            <div class="col">
                <a href="" class="btn btn-primary" data-action="reward_switch">大賞燈切換</a>
                <div class="message"></div>
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
                url: 'api/shuangmei/jackpot/v1/'+ $(this).data('action') + '/' + $('input[name=ip]').val() + '/8000',
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