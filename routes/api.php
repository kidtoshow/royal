<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// 韋峯舊版
Route::any('wash_point_control/{ip}/{port}', 'PLC\\CommunicationController@washPointControl');
Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\CommunicationController@openPoint');
Route::any('open_point_control/{ip}/{port}', 'PLC\\CommunicationController@openPointControl');
Route::any('refund_on/{ip}/{port}', 'PLC\\CommunicationController@refundOn');
Route::any('refund_off/{ip}/{port}', 'PLC\\CommunicationController@refundOff');
Route::any('coin_on/{ip}/{port}', 'PLC\\CommunicationController@coinOn');
Route::any('coin_off/{ip}/{port}', 'PLC\\CommunicationController@coinOff');
Route::any('open_point_status/{ip}/{port}', 'PLC\\CommunicationController@openPointStatus');
Route::any('open_point_zero/{ip}/{port}', 'PLC\\CommunicationController@openPointZero');
Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\CommunicationController@scoreAndRewardStatusZero');
Route::any('score_zero/{ip}/{port}', 'PLC\\CommunicationController@scoreAndRewardZero');
Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\CommunicationController@grandRewardZero');
Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\CommunicationController@smallRewardZero');
Route::any('flash_1_on/{ip}/{port}', 'PLC\\CommunicationController@flash1On');
Route::any('flash_1_off/{ip}/{port}', 'PLC\\CommunicationController@flash1Off');
Route::any('flash_2_on/{ip}/{port}', 'PLC\\CommunicationController@flash2On');
Route::any('flash_2_off/{ip}/{port}', 'PLC\\CommunicationController@flash2Off');
Route::any('pressure/{ip}/{port}', 'PLC\\CommunicationController@pressure');
Route::any('score/{ip}/{port}', 'PLC\\CommunicationController@score');
Route::any('big_reward_status/{ip}/{port}', 'PLC\\CommunicationController@big_reward_status');
Route::any('small_reward_status/{ip}/{port}', 'PLC\\CommunicationController@small_reward_status');

// 阿勳舊版
Route::any('check_auto_card/{ip}/{port}', 'PLC\\CommunicationController@checkAutoCard');
Route::any('start_auto/{ip}/{port}', 'PLC\\CommunicationController@startAuto');
Route::any('stop_auto/{ip}/{port}', 'PLC\\CommunicationController@stopAuto');
Route::any('stake_point/{ip}/{port}', 'PLC\\CommunicationController@stakePoint');
Route::any('start_game/{ip}/{port}', 'PLC\\CommunicationController@startGame');
Route::any('stop_1/{ip}/{port}', 'PLC\\CommunicationController@stop1');
Route::any('stop_2/{ip}/{port}', 'PLC\\CommunicationController@stop2');
Route::any('stop_3/{ip}/{port}', 'PLC\\CommunicationController@stop3');
Route::any('seven_display/{ip}/{port}', 'PLC\\CommunicationController@sevenDisplay');

Route::prefix('v1')->group(function(){
    Route::prefix('clown')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V1ClownController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V1ClownController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V1ClownController@openPointControl');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V1ClownController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V1ClownController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V1ClownController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V1ClownController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V1ClownController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V1ClownController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V1ClownController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V1ClownController@smallRewardZero');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V1ClownController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V1ClownController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V1ClownController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V1ClownController@bigRewardStatus');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V1ClownController@smallRewardStatus');
        Route::any('start_game/{ip}/{port}', 'PLC\\V1ClownController@joystickShort');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V1ClownController@joystickLong');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V1ClownController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V1ClownController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V1ClownController@stop3');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V1ClownController@sevenDisplay');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V1ClownController@stakePoint');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V1ClownController@joystickShort');
    });

    Route::prefix('fish_machine')->group(function(){
        Route::any('up_single/{ip}/{port}', 'PLC\\V1FishMachineController@upSingle');
        Route::any('down_single/{ip}/{port}', 'PLC\\V1FishMachineController@downSingle');
        Route::any('down_double/{ip}/{port}', 'PLC\\V1FishMachineController@downDouble');
        Route::any('down_five/{ip}/{port}', 'PLC\\V1FishMachineController@downFive');
        Route::any('left_single/{ip}/{port}', 'PLC\\V1FishMachineController@leftSingle');
        Route::any('right_single/{ip}/{port}', 'PLC\\V1FishMachineController@rightSingle');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V1FishMachineController@stakePoint');
        Route::any('shoot_single/{ip}/{port}', 'PLC\\V1FishMachineController@shootSingle');
        Route::any('shoot_five/{ip}/{port}', 'PLC\\V1FishMachineController@shootFive');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V1FishMachineController@openPoint');
        Route::any('wash_point/{ip}/{port}', 'PLC\\V1FishMachineController@washPoint');
        Route::any('down_on/{ip}/{port}', 'PLC\\V1FishMachineController@downOn');
        Route::any('down_off/{ip}/{port}', 'PLC\\V1FishMachineController@downOff');
        Route::any('shoot_on/{ip}/{port}', 'PLC\\V1FishMachineController@shootOn');
        Route::any('shoot_off/{ip}/{port}', 'PLC\\V1FishMachineController@shootOff');
        Route::any('get_score/{ip}/{port}', 'PLC\\V1FishMachineController@getScore');
    });

    Route::prefix('tw_mod')->group(function(){
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V1TwModController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V1TwModController@openPointControl');
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V1TwModController@washPointControl');
        // Route::any('insert_coin/{ip}/{port}', 'PLC\\V1TwModController@insertCoin');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V1TwModController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V1TwModController@coinOff');
        // Route::any('refund_coin/{ip}/{port}', 'PLC\\V1TwModController@refundCoin');
        Route::any('refund_on/{ip}/{port}', 'PLC\\V1TwModController@refundOn');
        Route::any('refund_off/{ip}/{port}', 'PLC\\V1TwModController@refundOff');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V1TwModController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V1TwModController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V1TwModController@stop3');
        Route::any('start_long/{ip}/{port}', 'PLC\\V1TwModController@startLong');
        Route::any('start_short/{ip}/{port}', 'PLC\\V1TwModController@startShort');
        Route::any('reward_switch_long/{ip}/{port}', 'PLC\\V1TwModController@rewardSwitchLong');
        Route::any('reward_switch_short/{ip}/{port}', 'PLC\\V1TwModController@rewardSwitchShort');

        Route::any('open_point_status/{ip}/{port}', 'PLC\\V1TwModController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V1TwModController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V1TwModController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V1TwModController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V1TwModController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V1TwModController@smallRewardZero');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V1TwModController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V1TwModController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V1TwModController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V1TwModController@bigRewardStatus');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V1TwModController@smallRewardStatus');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V1TwModController@sevenDisplay');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V1TwModController@stakePoint');
        Route::any('start_game/{ip}/{port}', 'PLC\\V1TwModController@startShort');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V1TwModController@startLong');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V1TwModController@startShort');
    });

    Route::prefix('mari')->group(function(){
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V1MariController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V1MariController@openPointControl');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V1MariController@openPointStatus');
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V1MariController@washPointControl');
        Route::any('left_button_long/{ip}/{port}', 'PLC\\V1MariController@leftButtonLong');
        Route::any('left_button_short/{ip}/{port}', 'PLC\\V1MariController@leftButtonShort');
        Route::any('right_button_long/{ip}/{port}', 'PLC\\V1MariController@rightButtonLong');
        Route::any('right_button_short/{ip}/{port}', 'PLC\\V1MariController@rightButtonShort');
        Route::any('small_button/{ip}/{port}', 'PLC\\V1MariController@smallButton');
        Route::any('big_button/{ip}/{port}', 'PLC\\V1MariController@bigButton');
        Route::any('start_button/{ip}/{port}', 'PLC\\V1MariController@startButton');
        Route::any('bar_button_long/{ip}/{port}', 'PLC\\V1MariController@barButtonLong');
        Route::any('bar_button_short/{ip}/{port}', 'PLC\\V1MariController@barButtonShort');
        Route::any('apple_button_long/{ip}/{port}', 'PLC\\V1MariController@appleButtonLong');
        Route::any('apple_button_short/{ip}/{port}', 'PLC\\V1MariController@appleButtonShort');
        Route::any('watermelon_button_long/{ip}/{port}', 'PLC\\V1MariController@watermelonButtonLong');
        Route::any('watermelon_button_short/{ip}/{port}', 'PLC\\V1MariController@watermelonButtonShort');
        Route::any('star_button_long/{ip}/{port}', 'PLC\\V1MariController@starButtonLong');
        Route::any('star_button_short/{ip}/{port}', 'PLC\\V1MariController@starButtonShort');
        Route::any('77_button_long/{ip}/{port}', 'PLC\\V1MariController@button77Long');
        Route::any('77_button_short/{ip}/{port}', 'PLC\\V1MariController@button77Short');
        Route::any('bell_button_long/{ip}/{port}', 'PLC\\V1MariController@bellButtonLong');
        Route::any('bell_button_short/{ip}/{port}', 'PLC\\V1MariController@bellButtonShort');
        Route::any('melon_button_long/{ip}/{port}', 'PLC\\V1MariController@melonButtonLong');
        Route::any('melon_button_short/{ip}/{port}', 'PLC\\V1MariController@melonButtonShort');
        Route::any('orange_button_long/{ip}/{port}', 'PLC\\V1MariController@orangeButtonLong');
        Route::any('orange_button_short/{ip}/{port}', 'PLC\\V1MariController@orangeButtonShort');
        Route::any('lychee_button_long/{ip}/{port}', 'PLC\\V1MariController@lycheeButtonLong');
        Route::any('lychee_button_short/{ip}/{port}', 'PLC\\V1MariController@lycheeButtonShort');

        Route::any('open_point_by_second/{ip}/{port}/{amount}/{second}', 'PLC\\V1MariController@openPointBySec');
        Route::any('apple_button_long_by_second/{ip}/{port}/{second}', 'PLC\\V1MariController@appleButtonLongBySecond');
    });

    Route::prefix('15r')->group(function(){
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V1R15Controller@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V1R15Controller@openPointControl');
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V1R15Controller@washPointControl');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V1R15Controller@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V1R15Controller@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V1R15Controller@stop3');
        Route::any('stop_4/{ip}/{port}', 'PLC\\V1R15Controller@stop4');
        Route::any('stop_5/{ip}/{port}', 'PLC\\V1R15Controller@stop5');
        Route::any('start_button/{ip}/{port}', 'PLC\\V1R15Controller@startButton');
        Route::any('auto_button/{ip}/{port}', 'PLC\\V1R15Controller@autoButton');
        Route::any('max_bet/{ip}/{port}', 'PLC\\V1R15Controller@maxBet');
        Route::any('info_button/{ip}/{port}', 'PLC\\V1R15Controller@infoButton');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V1R15Controller@openPointStatus');

        Route::any('stop_5_by_second/{ip}/{port}/{second}', 'PLC\\V1R15Controller@stop5BySec');
        Route::any('open_point_by_second/{ip}/{port}/{amount}/{second}', 'PLC\\V1R15Controller@openPointBySec');
        Route::any('wash_point_control_by_second/{ip}/{port}/{second}', 'PLC\\V1R15Controller@washPointControlBySec');
    });

    Route::prefix('7pk')->group(function(){
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V17pkController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V17pkController@openPointControl');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V17pkController@openPointStatus');
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V17pkController@washPointControl');
        Route::any('press_point/{ip}/{port}', 'PLC\\V17pkController@pressPoint');
        Route::any('press_small/{ip}/{port}', 'PLC\\V17pkController@pressSmall');
        Route::any('press_big/{ip}/{port}', 'PLC\\V17pkController@pressBig');
        Route::any('set_ratio/{ip}/{port}', 'PLC\\V17pkController@setRatio');
        Route::any('get_point/{ip}/{port}', 'PLC\\V17pkController@getPoint');
        Route::any('collect_point/{ip}/{port}', 'PLC\\V17pkController@collectPoint');
        Route::any('all_open/{ip}/{port}', 'PLC\\V17pkController@allOpen');
        Route::any('reveal_card/{ip}/{port}', 'PLC\\V17pkController@revealCard');
        Route::any('open_point_by_second/{ip}/{port}/{amount}/{second}', 'PLC\\V17pkController@openPointBYSec');
    });

    Route::prefix('20_slot')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V120SlotController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V120SlotController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V120SlotController@openPointControl');
        Route::any('refund_on/{ip}/{port}', 'PLC\\V120SlotController@refundOn');
        Route::any('refund_off/{ip}/{port}', 'PLC\\V120SlotController@refundOff');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V120SlotController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V120SlotController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V120SlotController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V120SlotController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V120SlotController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V120SlotController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V120SlotController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V120SlotController@smallRewardZero');
        Route::any('chance/{ip}/{port}', 'PLC\\V120SlotController@chance');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V120SlotController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V120SlotController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V120SlotController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V120SlotController@big_reward_status');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V120SlotController@small_reward_status');


        // 阿勳 API 斯洛
        Route::any('check_auto_card/{ip}/{port}', 'PLC\\V120SlotController@checkAutoCard');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V120SlotController@startAuto');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V120SlotController@stopAuto');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V120SlotController@stakePoint');
        Route::any('start_game/{ip}/{port}', 'PLC\\V120SlotController@startGame');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V120SlotController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V120SlotController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V120SlotController@stop3');
        Route::any('draw_card_stop_123_bar/{ip}/{port}', 'PLC\\V120SlotController@drawCardStop123Bar');
        Route::any('draw_card_stop_123_blue_7/{ip}/{port}', 'PLC\\V120SlotController@drawCardStop123Blue7');
        Route::any('draw_card_stop_123_red_7/{ip}/{port}', 'PLC\\V120SlotController@drawCardStop123Red7');
        Route::any('draw_card_stop_321_bar/{ip}/{port}', 'PLC\\V120SlotController@drawCardStop321Bar');
        Route::any('draw_card_stop_321_blue_7/{ip}/{port}', 'PLC\\V120SlotController@drawCardStop321Blue7');
        Route::any('draw_card_stop_321_red_7/{ip}/{port}', 'PLC\\V120SlotController@drawCardStop321Red7');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V120SlotController@sevenDisplay');
    });

    Route::prefix('20_pak')->group(function(){
        Route::any('display_turn_point/{ip}/{port}', 'PLC\\V120PakController@displayTurnPoint');
        Route::any('display_score/{ip}/{port}', 'PLC\\V120PakController@displayScore');
        Route::any('display_open_point/{ip}/{port}', 'PLC\\V120PakController@displayOpenPoint');
        Route::any('display_all_status/{ip}/{port}', 'PLC\\V120PakController@displayAllStatus');

        // 鋼珠機 PLC
        Route::any('plc_turn_point/{ip}/{port}', 'PLC\\V120PakController@plcTurnPointAmount');
        Route::any('plc_draw_status/{ip}/{port}', 'PLC\\V120PakController@plcBigRewardStatus');
        Route::any('plc_dealer_status/{ip}/{port}', 'PLC\\V120PakController@plcSmallRewardStatus');
        Route::any('plc_push/{ip}/{port}', 'PLC\\V120PakController@plcPush');
        Route::any('plc_down_turn/{ip}/{port}', 'PLC\\V120PakController@plcDownTurn');
        Route::any('plc_up_trun/{ip}/{port}', 'PLC\\V120PakController@plcUpTurn');
        Route::any('plc_start_or_stop/{ip}/{port}', 'PLC\\V120PakController@plcStartOrStop');
        Route::any('plc_sub_point/{ip}/{port}', 'PLC\\V120PakController@plcSubPoint');
        Route::any('plc_wash_point/{ip}/{port}', 'PLC\\V120PakController@plcWashPoint');
        Route::any('plc_open_point_amount/{ip}/{port}/{amount}', 'PLC\\V120PakController@plcControlOpenPointAmount');
        Route::any('plc_control_open_point/{ip}/{port}', 'PLC\\V120PakController@plcControlOpenPoint');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V120PakController@rewardSwitch');
        Route::any('plc_open_point_status/{ip}/{port}', 'PLC\\V120PakController@plcOpenPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V120PakController@openPointZero');
        Route::any('plc_turn_point_zero/{ip}/{port}', 'PLC\\V120PakController@plcTurnPointZero');
        Route::any('plc_up_down_long/{ip}/{port}', 'PLC\\V120PakController@plcDownTurnLong');
        Route::any('plc_up_turn_long/{ip}/{port}', 'PLC\\V120PakController@plcUpTurnLong');
        Route::any('plc_pak_open_point_verify/{ip}/{port}/{amount}', 'PLC\\V120PakController@plcPakOpenPointVerify');
        Route::any('pak_open_verify_v2/{ip}/{port}/{amount}', 'PLC\\V120PakController@pakOpenVerifyV2');
    });
});


Route::prefix('v2')->group(function(){
    // 韋峯 API 斯洛
    Route::any('wash_point_control/{ip}/{port}', 'PLC\\V2CommunicationController@washPointControl');
    Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V2CommunicationController@openPoint');
    Route::any('open_point_control/{ip}/{port}', 'PLC\\V2CommunicationController@openPointControl');
    Route::any('refund_on/{ip}/{port}', 'PLC\\V2CommunicationController@refundOn');
    Route::any('refund_off/{ip}/{port}', 'PLC\\V2CommunicationController@refundOff');
    Route::any('coin_on/{ip}/{port}', 'PLC\\V2CommunicationController@coinOn');
    Route::any('coin_off/{ip}/{port}', 'PLC\\V2CommunicationController@coinOff');
    Route::any('open_point_status/{ip}/{port}', 'PLC\\V2CommunicationController@openPointStatus');
    Route::any('open_point_zero/{ip}/{port}', 'PLC\\V2CommunicationController@openPointZero');
    Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V2CommunicationController@scoreAndRewardStatusZero');
    Route::any('score_zero/{ip}/{port}', 'PLC\\V2CommunicationController@scoreAndRewardZero');
    Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V2CommunicationController@grandRewardZero');
    Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V2CommunicationController@smallRewardZero');
    // Route::any('flash_1_on/{ip}/{port}', 'PLC\\V2CommunicationController@flash1On');
    // Route::any('flash_1_off/{ip}/{port}', 'PLC\\V2CommunicationController@flash1Off');
    // Route::any('flash_2_on/{ip}/{port}', 'PLC\\V2CommunicationController@flash2On');
    // Route::any('flash_2_off/{ip}/{port}', 'PLC\\V2CommunicationController@flash2Off');
    Route::any('chance/{ip}/{port}', 'PLC\\V2CommunicationController@chance');
    Route::any('reward_switch/{ip}/{port}', 'PLC\\V2CommunicationController@rewardSwitch');
    Route::any('pressure/{ip}/{port}', 'PLC\\V2CommunicationController@pressure');
    Route::any('score/{ip}/{port}', 'PLC\\V2CommunicationController@score');
    Route::any('big_reward_status/{ip}/{port}', 'PLC\\V2CommunicationController@big_reward_status');
    Route::any('small_reward_status/{ip}/{port}', 'PLC\\V2CommunicationController@small_reward_status');


    // 阿勳 API 斯洛
    Route::any('check_auto_card/{ip}/{port}', 'PLC\\V2CommunicationController@checkAutoCard');
    Route::any('start_auto/{ip}/{port}', 'PLC\\V2CommunicationController@startAuto');
    Route::any('stop_auto/{ip}/{port}', 'PLC\\V2CommunicationController@stopAuto');
    Route::any('stake_point/{ip}/{port}', 'PLC\\V2CommunicationController@stakePoint');
    Route::any('start_game/{ip}/{port}', 'PLC\\V2CommunicationController@startGame');
    Route::any('stop_1/{ip}/{port}', 'PLC\\V2CommunicationController@stop1');
    Route::any('stop_2/{ip}/{port}', 'PLC\\V2CommunicationController@stop2');
    Route::any('stop_3/{ip}/{port}', 'PLC\\V2CommunicationController@stop3');
    Route::any('draw_card_stop_123_bar/{ip}/{port}', 'PLC\\V2CommunicationController@drawCardStop123Bar');
    Route::any('draw_card_stop_123_blue_7/{ip}/{port}', 'PLC\\V2CommunicationController@drawCardStop123Blue7');
    Route::any('draw_card_stop_123_red_7/{ip}/{port}', 'PLC\\V2CommunicationController@drawCardStop123Red7');
    Route::any('draw_card_stop_321_bar/{ip}/{port}', 'PLC\\V2CommunicationController@drawCardStop321Bar');
    Route::any('draw_card_stop_321_blue_7/{ip}/{port}', 'PLC\\V2CommunicationController@drawCardStop321Blue7');
    Route::any('draw_card_stop_321_red_7/{ip}/{port}', 'PLC\\V2CommunicationController@drawCardStop321Red7');
    Route::any('seven_display/{ip}/{port}', 'PLC\\V2CommunicationController@sevenDisplay');

    //小丑機
    Route::prefix('clown')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V2ClownController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V2ClownController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V2ClownController@openPointControl');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V2ClownController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V2ClownController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V2ClownController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V2ClownController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V2ClownController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V2ClownController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V2ClownController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V2ClownController@smallRewardZero');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V2ClownController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V2ClownController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V2ClownController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V2ClownController@bigRewardStatus');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V2ClownController@smallRewardStatus');
        Route::any('start_game/{ip}/{port}', 'PLC\\V2ClownController@joystickShort');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V2ClownController@joystickLong');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V2ClownController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V2ClownController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V2ClownController@stop3');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V2ClownController@sevenDisplay');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V2ClownController@stakePoint');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V2ClownController@joystickShort');
    });

    Route::prefix('mari')->group(function(){
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V2MariController@openPoint');
        // Route::any('open_point_control/{ip}/{port}', 'PLC\\V2MariController@openPointControl');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V2MariController@openPointStatus');
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V2MariController@washPointControl');
        Route::any('left_button_long/{ip}/{port}', 'PLC\\V2MariController@leftButtonLong');
        Route::any('left_button_short/{ip}/{port}', 'PLC\\V2MariController@leftButtonShort');
        Route::any('right_button_long/{ip}/{port}', 'PLC\\V2MariController@rightButtonLong');
        Route::any('right_button_short/{ip}/{port}', 'PLC\\V2MariController@rightButtonShort');
        Route::any('small_button_long/{ip}/{port}', 'PLC\\V2MariController@smallButtonLong');
        Route::any('small_button/{ip}/{port}', 'PLC\\V2MariController@smallButtonShort');
        Route::any('big_button_long/{ip}/{port}', 'PLC\\V2MariController@bigButtonLong');
        Route::any('big_button/{ip}/{port}', 'PLC\\V2MariController@bigButtonShort');
        Route::any('start_button_long/{ip}/{port}', 'PLC\\V2MariController@startButtonLong');
        Route::any('start_button/{ip}/{port}', 'PLC\\V2MariController@startButtonShort');
        Route::any('bar_button_long/{ip}/{port}', 'PLC\\V2MariController@barButtonLong');
        Route::any('bar_button_short/{ip}/{port}', 'PLC\\V2MariController@barButtonShort');
        Route::any('apple_button_long/{ip}/{port}', 'PLC\\V2MariController@appleButtonLong');
        Route::any('apple_button_short/{ip}/{port}', 'PLC\\V2MariController@appleButtonShort');
        Route::any('watermelon_button_long/{ip}/{port}', 'PLC\\V2MariController@watermelonButtonLong');
        Route::any('watermelon_button_short/{ip}/{port}', 'PLC\\V2MariController@watermelonButtonShort');
        Route::any('star_button_long/{ip}/{port}', 'PLC\\V2MariController@starButtonLong');
        Route::any('star_button_short/{ip}/{port}', 'PLC\\V2MariController@starButtonShort');
        Route::any('77_button_long/{ip}/{port}', 'PLC\\V2MariController@button77Long');
        Route::any('77_button_short/{ip}/{port}', 'PLC\\V2MariController@button77Short');
        Route::any('bell_button_long/{ip}/{port}', 'PLC\\V2MariController@bellButtonLong');
        Route::any('bell_button_short/{ip}/{port}', 'PLC\\V2MariController@bellButtonShort');
        Route::any('melon_button_long/{ip}/{port}', 'PLC\\V2MariController@melonButtonLong');
        Route::any('melon_button_short/{ip}/{port}', 'PLC\\V2MariController@melonButtonShort');
        Route::any('orange_button_long/{ip}/{port}', 'PLC\\V2MariController@orangeButtonLong');
        Route::any('orange_button_short/{ip}/{port}', 'PLC\\V2MariController@orangeButtonShort');
        Route::any('lychee_button_long/{ip}/{port}', 'PLC\\V2MariController@lycheeButtonLong');
        Route::any('lychee_button_short/{ip}/{port}', 'PLC\\V2MariController@lycheeButtonShort');
        Route::any('button_initialize/{ip}/{port}', 'PLC\\V2MariController@buttonInitialize');

        Route::any('open_point_by_second/{ip}/{port}/{amount}/{second}', 'PLC\\V2MariController@openPointBySec');
        Route::any('apple_button_long_by_second/{ip}/{port}/{second}', 'PLC\\V2MariController@appleButtonLongBySecond');
    });
});

Route::prefix('v3')->group(function(){
    // 鋼珠機 七段顯示器
    Route::any('display_turn_point/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@displayTurnPoint');
    Route::any('display_score/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@displayScore');
    Route::any('display_open_point/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@displayOpenPoint');
    Route::any('display_all_status/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@displayAllStatus');

    // 鋼珠機 PLC
    Route::any('plc_turn_point/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcTurnPointAmount');
    Route::any('plc_draw_status/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcBigRewardStatus');
    Route::any('plc_dealer_status/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcSmallRewardStatus');
    Route::any('plc_push/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcPush');
    Route::any('plc_down_turn/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcDownTurn');
    Route::any('plc_up_trun/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcUpTurn');
    Route::any('plc_start_or_stop/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcStartOrStop');
    Route::any('plc_sub_point/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcSubPoint');
    Route::any('plc_wash_point/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcWashPoint');
    Route::any('plc_open_point_amount/{ip}/{port}/{amount}', 'PLC\\V2StellBallCommunicationController@plcControlOpenPointAmount');
    Route::any('plc_control_open_point/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcControlOpenPoint');
    Route::any('reward_switch/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@rewardSwitch');
    Route::any('plc_pak_open_point_verify/{ip}/{port}/{amount}', 'PLC\\V2StellBallCommunicationController@plcPakOpenPointVerify');
    Route::any('plc_open_point_status/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcOpenPointStatus');
    Route::any('plc_down_turn_long/{ip}/{port}', 'PLC\\V2StellBallCommunicationController@plcDownTurnLong');

    Route::prefix('slot')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V3SlotCommunicationController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V3SlotCommunicationController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V3SlotCommunicationController@openPointControl');
        Route::any('refund_on/{ip}/{port}', 'PLC\\V3SlotCommunicationController@refundOn');
        Route::any('refund_off/{ip}/{port}', 'PLC\\V3SlotCommunicationController@refundOff');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V3SlotCommunicationController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V3SlotCommunicationController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V3SlotCommunicationController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V3SlotCommunicationController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V3SlotCommunicationController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V3SlotCommunicationController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V3SlotCommunicationController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V3SlotCommunicationController@smallRewardZero');
        Route::any('chance/{ip}/{port}', 'PLC\\V3SlotCommunicationController@chance');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V3SlotCommunicationController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V3SlotCommunicationController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V3SlotCommunicationController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V3SlotCommunicationController@big_reward_status');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V3SlotCommunicationController@small_reward_status');


        // 阿勳 API 斯洛
        Route::any('check_auto_card/{ip}/{port}', 'PLC\\V3SlotCommunicationController@checkAutoCard');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V3SlotCommunicationController@startAuto');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V3SlotCommunicationController@stopAuto');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V3SlotCommunicationController@stakePoint');
        Route::any('start_game/{ip}/{port}', 'PLC\\V3SlotCommunicationController@startGame');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V3SlotCommunicationController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V3SlotCommunicationController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V3SlotCommunicationController@stop3');
        Route::any('draw_card_stop_123_bar/{ip}/{port}', 'PLC\\V3SlotCommunicationController@drawCardStop123Bar');
        Route::any('draw_card_stop_123_blue_7/{ip}/{port}', 'PLC\\V3SlotCommunicationController@drawCardStop123Blue7');
        Route::any('draw_card_stop_123_red_7/{ip}/{port}', 'PLC\\V3SlotCommunicationController@drawCardStop123Red7');
        Route::any('draw_card_stop_321_bar/{ip}/{port}', 'PLC\\V3SlotCommunicationController@drawCardStop321Bar');
        Route::any('draw_card_stop_321_blue_7/{ip}/{port}', 'PLC\\V3SlotCommunicationController@drawCardStop321Blue7');
        Route::any('draw_card_stop_321_red_7/{ip}/{port}', 'PLC\\V3SlotCommunicationController@drawCardStop321Red7');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V3SlotCommunicationController@sevenDisplay');
    });

    //小丑機
    Route::prefix('clown')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V3ClownController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V3ClownController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V3ClownController@openPointControl');
        Route::any('refund_on/{ip}/{port}', 'PLC\\V3ClownController@refundOn');
        Route::any('refund_off/{ip}/{port}', 'PLC\\V3ClownController@refundOff');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V3ClownController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V3ClownController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V3ClownController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V3ClownController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V3ClownController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V3ClownController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V3ClownController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V3ClownController@smallRewardZero');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V3ClownController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V3ClownController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V3ClownController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V3ClownController@bigRewardStatus');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V3ClownController@smallRewardStatus');
        Route::any('start_game/{ip}/{port}', 'PLC\\V3ClownController@joystickShort');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V3ClownController@joystickLong');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V3ClownController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V3ClownController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V3ClownController@stop3');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V3ClownController@sevenDisplay');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V3ClownController@stakePoint');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V3ClownController@joystickShort');
    });
});

Route::prefix('v3.1')->group(function(){
    // 鋼珠機 七段顯示器
    Route::prefix('pachinko')->group(function(){
        Route::any('display_turn_point/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@displayTurnPoint');
        Route::any('display_score/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@displayScore');
        Route::any('display_open_point/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@displayOpenPoint');
        Route::any('display_all_status/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@displayAllStatus');

        // 鋼珠機 PLC
        Route::any('plc_turn_point/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcTurnPointAmount');
        Route::any('plc_draw_status/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcBigRewardStatus');
        Route::any('plc_dealer_status/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcSmallRewardStatus');
        Route::any('plc_push/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcPush');
        Route::any('plc_down_turn/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcDownTurn');
        Route::any('plc_up_trun/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcUpTurn');
        Route::any('plc_start_or_stop/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcStartOrStop');
        Route::any('plc_sub_point/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcSubPoint');
        Route::any('plc_wash_point/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcWashPoint');
        Route::any('plc_open_point_amount/{ip}/{port}/{amount}', 'PLC\\V31StellBallCommunicationController@plcControlOpenPointAmount');
        Route::any('plc_control_open_point/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcControlOpenPoint');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@rewardSwitch');
        Route::any('plc_pak_open_point_verify/{ip}/{port}/{amount}', 'PLC\\V31StellBallCommunicationController@plcPakOpenPointVerify');
        Route::any('plc_open_point_status/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcOpenPointStatus');
        Route::any('plc_down_turn_long/{ip}/{port}', 'PLC\\V31StellBallCommunicationController@plcDownTurnLong');
        Route::any('pak_open_verify_v2/{ip}/{port}/{amount}', 'PLC\\V31StellBallCommunicationController@pakOpenVerifyV2');
    });

    Route::prefix('slot')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V31SlotCommunicationController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V31SlotCommunicationController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V31SlotCommunicationController@openPointControl');
        Route::any('refund_on/{ip}/{port}', 'PLC\\V31SlotCommunicationController@refundOn');
        Route::any('refund_off/{ip}/{port}', 'PLC\\V31SlotCommunicationController@refundOff');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V31SlotCommunicationController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V31SlotCommunicationController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V31SlotCommunicationController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V31SlotCommunicationController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V31SlotCommunicationController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V31SlotCommunicationController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V31SlotCommunicationController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V31SlotCommunicationController@smallRewardZero');
        Route::any('chance/{ip}/{port}', 'PLC\\V31SlotCommunicationController@chance');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V31SlotCommunicationController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V31SlotCommunicationController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V31SlotCommunicationController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V31SlotCommunicationController@big_reward_status');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V31SlotCommunicationController@small_reward_status');


        // 阿勳 API 斯洛
        Route::any('check_auto_card/{ip}/{port}', 'PLC\\V31SlotCommunicationController@checkAutoCard');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V31SlotCommunicationController@startAuto');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V31SlotCommunicationController@stopAuto');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V31SlotCommunicationController@stakePoint');
        Route::any('start_game/{ip}/{port}', 'PLC\\V31SlotCommunicationController@startGame');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V31SlotCommunicationController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V31SlotCommunicationController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V31SlotCommunicationController@stop3');
        Route::any('draw_card_stop_123_bar/{ip}/{port}', 'PLC\\V31SlotCommunicationController@drawCardStop123Bar');
        Route::any('draw_card_stop_123_blue_7/{ip}/{port}', 'PLC\\V31SlotCommunicationController@drawCardStop123Blue7');
        Route::any('draw_card_stop_123_red_7/{ip}/{port}', 'PLC\\V31SlotCommunicationController@drawCardStop123Red7');
        Route::any('draw_card_stop_321_bar/{ip}/{port}', 'PLC\\V31SlotCommunicationController@drawCardStop321Bar');
        Route::any('draw_card_stop_321_blue_7/{ip}/{port}', 'PLC\\V31SlotCommunicationController@drawCardStop321Blue7');
        Route::any('draw_card_stop_321_red_7/{ip}/{port}', 'PLC\\V31SlotCommunicationController@drawCardStop321Red7');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V31SlotCommunicationController@sevenDisplay');
    });

    Route::prefix('clown')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V31ClownController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V31ClownController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V31ClownController@openPointControl');
        Route::any('refund_on/{ip}/{port}', 'PLC\\V31ClownController@refundOn');
        Route::any('refund_off/{ip}/{port}', 'PLC\\V31ClownController@refundOff');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V31ClownController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V31ClownController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V31ClownController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V31ClownController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V31ClownController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V31ClownController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V31ClownController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V31ClownController@smallRewardZero');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V31ClownController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V31ClownController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V31ClownController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V31ClownController@bigRewardStatus');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V31ClownController@smallRewardStatus');
        Route::any('start_game/{ip}/{port}', 'PLC\\V31ClownController@joystickShort');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V31ClownController@joystickLong');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V31ClownController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V31ClownController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V31ClownController@stop3');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V31ClownController@sevenDisplay');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V31ClownController@stakePoint');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V31ClownController@joystickShort');
    });
});

Route::prefix('v4')->group(function(){
    Route::prefix('slot')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V4SlotController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V4SlotController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V4SlotController@openPointControl');
        Route::any('refund_on/{ip}/{port}', 'PLC\\V4SlotController@refundOn');
        Route::any('refund_off/{ip}/{port}', 'PLC\\V4SlotController@refundOff');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V4SlotController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V4SlotController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V4SlotController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V4SlotController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V4SlotController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V4SlotController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V4SlotController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V4SlotController@smallRewardZero');
        Route::any('chance/{ip}/{port}', 'PLC\\V4SlotController@chance');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V4SlotController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V4SlotController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V4SlotController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V4SlotController@big_reward_status');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V4SlotController@small_reward_status');


        // 阿勳 API 斯洛
        Route::any('check_auto_card/{ip}/{port}', 'PLC\\V4SlotController@checkAutoCard');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V4SlotController@startAuto');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V4SlotController@stopAuto');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V4SlotController@stakePoint');
        Route::any('start_game/{ip}/{port}', 'PLC\\V4SlotController@startGame');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V4SlotController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V4SlotController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V4SlotController@stop3');
        Route::any('draw_card_stop_123_bar/{ip}/{port}', 'PLC\\V4SlotController@drawCardStop123Bar');
        Route::any('draw_card_stop_123_blue_7/{ip}/{port}', 'PLC\\V4SlotController@drawCardStop123Blue7');
        Route::any('draw_card_stop_123_red_7/{ip}/{port}', 'PLC\\V4SlotController@drawCardStop123Red7');
        Route::any('draw_card_stop_321_bar/{ip}/{port}', 'PLC\\V4SlotController@drawCardStop321Bar');
        Route::any('draw_card_stop_321_blue_7/{ip}/{port}', 'PLC\\V4SlotController@drawCardStop321Blue7');
        Route::any('draw_card_stop_321_red_7/{ip}/{port}', 'PLC\\V4SlotController@drawCardStop321Red7');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V4SlotController@sevenDisplay');
        Route::any('big_reward_count/{ip}/{port}', 'PLC\\V4SlotController@bigRewardCount');
        Route::any('small_reward_count/{ip}/{port}', 'PLC\\V4SlotController@smallRewardCount');
        Route::any('big_reward_count_reset/{ip}/{port}', 'PLC\\V4SlotController@bigRewardCountReset');
        Route::any('small_reward_count_reset/{ip}/{port}', 'PLC\\V4SlotController@smallRewardCountReset');
        Route::any('coin_in_count/{ip}/{port}', 'PLC\\V4SlotController@coinInCount');
        Route::any('coin_out_count/{ip}/{port}', 'PLC\\V4SlotController@coinOutCount');
        Route::any('coin_in_count_reset/{ip}/{port}', 'PLC\\V4SlotController@coinInCountReset');
        Route::any('coin_out_count_reset/{ip}/{port}', 'PLC\\V4SlotController@coinOutCountReset');
        Route::any('version/{ip}/{port}', 'PLC\\V4SlotController@version');
        Route::any('service_status/{ip}/{port}', 'PLC\\V4SlotController@serviceStatus');
        Route::any('service_clear/{ip}/{port}', 'PLC\\V4SlotController@serviceClear');
    });

    Route::prefix('pachinko')->group(function(){
        Route::any('display_turn_point/{ip}/{port}', 'PLC\\V4StellBallController@displayTurnPoint');
        Route::any('display_score/{ip}/{port}', 'PLC\\V4StellBallController@displayScore');
        Route::any('display_open_point/{ip}/{port}', 'PLC\\V4StellBallController@displayOpenPoint');
        Route::any('display_all_status/{ip}/{port}', 'PLC\\V4StellBallController@displayAllStatus');

        // 鋼珠機 PLC
        Route::any('plc_turn_point/{ip}/{port}', 'PLC\\V4StellBallController@plcTurnPointAmount');
        Route::any('plc_draw_status/{ip}/{port}', 'PLC\\V4StellBallController@plcBigRewardStatus');
        Route::any('plc_dealer_status/{ip}/{port}', 'PLC\\V4StellBallController@plcSmallRewardStatus');
        Route::any('plc_push/{ip}/{port}', 'PLC\\V4StellBallController@plcPush');
        Route::any('plc_down_turn/{ip}/{port}', 'PLC\\V4StellBallController@plcDownTurn');
        Route::any('plc_up_trun/{ip}/{port}', 'PLC\\V4StellBallController@plcUpTurn');
        Route::any('plc_start_or_stop/{ip}/{port}', 'PLC\\V4StellBallController@plcStartOrStop');
        Route::any('plc_sub_point/{ip}/{port}', 'PLC\\V4StellBallController@plcSubPoint');
        Route::any('plc_wash_point/{ip}/{port}', 'PLC\\V4StellBallController@plcWashPoint');
        Route::any('plc_open_point_amount/{ip}/{port}/{amount}', 'PLC\\V4StellBallController@plcControlOpenPointAmount');
        Route::any('plc_control_open_point/{ip}/{port}', 'PLC\\V4StellBallController@plcControlOpenPoint');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V4StellBallController@rewardSwitch');
        Route::any('plc_pak_open_point_verify/{ip}/{port}/{amount}', 'PLC\\V4StellBallController@plcPakOpenPointVerify');
        Route::any('plc_open_point_status/{ip}/{port}', 'PLC\\V4StellBallController@plcOpenPointStatus');
        Route::any('coin_in_count/{ip}/{port}', 'PLC\\V4StellBallController@coinInCount');
        Route::any('coin_out_count/{ip}/{port}', 'PLC\\V4StellBallController@coinOutCount');
        Route::any('coin_in_count_reset/{ip}/{port}', 'PLC\\V4StellBallController@coinInCountReset');
        Route::any('coin_out_count_reset/{ip}/{port}', 'PLC\\V4StellBallController@coinOutCountReset');
        Route::any('version/{ip}/{port}', 'PLC\\V4StellBallController@version');
        Route::any('service_status/{ip}/{port}', 'PLC\\V4StellBallController@serviceStatus');
        Route::any('service_clear/{ip}/{port}', 'PLC\\V4StellBallController@serviceClear');
        Route::any('big_reward_count/{ip}/{port}', 'PLC\\V4StellBallController@bigRewardCount');
        Route::any('small_reward_count/{ip}/{port}', 'PLC\\V4StellBallController@smallRewardCount');
        Route::any('big_reward_count_reset/{ip}/{port}', 'PLC\\V4StellBallController@bigRewardCountReset');
        Route::any('small_reward_count_reset/{ip}/{port}', 'PLC\\V4StellBallController@smallRewardCountReset');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V4StellBallController@openPointZero');
        Route::any('plc_turn_point_zero/{ip}/{port}', 'PLC\\V4StellBallController@plcTurnPointZero');
        Route::any('plc_up_turn_long/{ip}/{port}', 'PLC\\V4StellBallController@plcUpTurnLong');
        Route::any('pak_open_verify_v2/{ip}/{port}/{amount}', 'PLC\\V4StellBallController@pakOpenVerifyV2');
    });
});

Route::prefix('v5')->group(function(){
    Route::any('start_auto', 'SHUANGMEI\\SLOT\\V5Controller@startAuto');
    Route::any('stop_auto', 'SHUANGMEI\\SLOT\\V5Controller@stopAuto');
    Route::any('press', 'SHUANGMEI\\SLOT\\V5Controller@press');
    Route::any('start', 'SHUANGMEI\\SLOT\\V5Controller@start');
    Route::any('stop1', 'SHUANGMEI\\SLOT\\V5Controller@stop1');
    Route::any('stop2', 'SHUANGMEI\\SLOT\\V5Controller@stop2');
    Route::any('stop3', 'SHUANGMEI\\SLOT\\V5Controller@stop3');
    Route::any('open1', 'SHUANGMEI\\SLOT\\V5Controller@open1');
    Route::any('open10', 'SHUANGMEI\\SLOT\\V5Controller@open10');
    Route::any('wash_zero', 'SHUANGMEI\\SLOT\\V5Controller@washZero');
    Route::any('wash_remainder', 'SHUANGMEI\\SLOT\\V5Controller@washRemainder');
    Route::any('move_on', 'SHUANGMEI\\SLOT\\V5Controller@moveOn');
    Route::any('move_off', 'SHUANGMEI\\SLOT\\V5Controller@moveOff');
    Route::any('stake_zero', 'SHUANGMEI\\SLOT\\V5Controller@stakeZero');
    Route::any('score_zero', 'SHUANGMEI\\SLOT\\V5Controller@scoreZero');
    Route::any('bb_rb_zero', 'SHUANGMEI\\SLOT\\V5Controller@bbRbZero');
    Route::any('load_score_1', 'SHUANGMEI\\SLOT\\V5Controller@loadScore1');
    Route::any('load_score_2', 'SHUANGMEI\\SLOT\\V5Controller@loadScore2');
    Route::any('load_score', 'SHUANGMEI\\SLOT\\V5Controller@loadScore');
    Route::any('load_stake', 'SHUANGMEI\\SLOT\\V5Controller@loadStake');
    Route::any('load_bb_rb', 'SHUANGMEI\\SLOT\\V5Controller@loadBbRb');
});

Route::prefix('shuangmei')->group(function(){
    Route::prefix('jackpot')->group(function(){
        Route::prefix('v1')->group(function(){
            Route::any('display_turn_point/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@turnPoint');
            Route::any('display_score/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@score');
            Route::any('display_open_point/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@openPoint');
            Route::any('display_all_status/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@getAllStatus');
            Route::any('plc_turn_point/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@turnPointAmount');
            Route::any('plc_draw_status/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@plcBigRewardStatus');
            Route::any('plc_dealer_status/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@plcSmallRewardStatus');
            Route::any('plc_push/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@push');
            Route::any('plc_push_2hz/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@push2Hz');
            Route::any('plc_push_5hz/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@push5Hz');
            Route::any('plc_down_turn/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@turnDown');
            Route::any('plc_up_turn_100/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@turnUp100');
            //Route::any('plc_up_turn_500/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@turnUp500');
            Route::any('plc_start_or_stop/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@startStop');
            Route::any('plc_start_1/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@start1');
            Route::any('plc_sub_point/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@steelDown');
            Route::any('plc_wash_zero/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@washZero');
            Route::any('plc_wash_remainder/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@washRemainder');
            Route::any('plc_open_1/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@open1');
            Route::any('plc_open_10/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@open10');
            Route::any('reward_switch/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@rewardSwitch');
            //Route::any('plc_auto_up_turn/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@autoUpTurn');
            Route::any('plc_push_stop/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@pushStop');
            Route::any('clear_status/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@clearStatus');
            Route::any('load_total_open/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@loadOpen');
            Route::any('load_total_wash/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@loadWash');
            Route::any('load_auto_status/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@loadAutoStatus');
            Route::any('plc_open_times/{ip}/{port}/{times}', 'SHUANGMEI\\JACKPOT\\V1Controller@openTimes');
            Route::any('check_wash_status/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@loadCheckWashStatus');
            Route::any('grant_approval/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@grantApproval');
            Route::any('all_up_turn/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@allUpTurn');
            Route::any('all_down_turn/{ip}/{port}/{ratio}', 'SHUANGMEI\\JACKPOT\\V1Controller@allDownTurn');
            Route::any('combine_status/{ip}/{port}', 'SHUANGMEI\\JACKPOT\\V1Controller@combineStatus');
            Route::any('check_lottery/{ip}/{port}','SHUANGMEI\\JACKPOT\\V1Controller@checkLottery');
            Route::any('open_any_point/{ip}/{port}/{score}', 'SHUANGMEI\\JACKPOT\\V1Controller@openAnyPoint');
        });
    });

    Route::prefix('slot')->group(function(){
        Route::prefix('v1')->group(function(){
            Route::any('open_point_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@getAll');
            Route::any('seven_display/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@loadScore');
            Route::any('pressure/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@loadBet');
            Route::any('score/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@loadWin');
            Route::any('big_reward_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@bbStatus');
            Route::any('small_reward_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@rbStatus');
            Route::any('load_bb/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@loadBb');//大獎累計值
            Route::any('load_rb/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@loadRb');//小獎累計值
            Route::any('open_1/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@open1');
            Route::any('open_5/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@open5');
            Route::any('open_10/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@open10');
            Route::any('wash_zero/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@washZero');
            Route::any('wash_remainder/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@washRemainder');
            Route::any('move_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@moveOn');
            Route::any('move_off/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@moveOff');
            Route::any('start_auto/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@startAuto');
            Route::any('stop_auto/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@stopAuto');
            Route::any('stake_point/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@press');
            Route::any('start_game/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@start');
            Route::any('stop_1/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@stop1');
            Route::any('stop_2/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@stop2');
            Route::any('stop_3/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@stop3');
            Route::any('draw_card_stop_123_red_7/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@drawStop123Red7');
            Route::any('draw_card_stop_123_blue_7/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@drawStop123Blue7');
            Route::any('draw_card_stop_123_bar/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@drawStop123Bar');
            Route::any('draw_card_stop_321_red_7/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@drawStop321Red7');
            Route::any('draw_card_stop_321_blue_7/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@drawStop321Blue7');
            Route::any('draw_card_stop_321_bar/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@drawStop321Bar');
            Route::any('out_all_off/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@outAllOff');
            Route::any('out_1_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out1On');
            Route::any('out_2_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out2On');
            Route::any('out_3_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out3On');
            Route::any('out_4_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out4On');
            Route::any('out_5_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out5On');
            Route::any('out_6_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out6On');
            Route::any('out_7_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out7On');
            Route::any('out_8_on/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out8On');
            Route::any('out_1_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out1Pulse');
            Route::any('out_2_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out2Pulse');
            Route::any('out_3_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out3Pulse');
            Route::any('out_4_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out4Pulse');
            Route::any('out_5_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out5Pulse');
            Route::any('out_6_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out6Pulse');
            Route::any('out_7_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out7Pulse');
            Route::any('out_8_pulse/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@out8Pulse');
            Route::any('reward_switch/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@rewardSwitch');
            Route::any('clear_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@clearStatus');
            Route::any('plc_open_times/{ip}/{port}/{times}', 'SHUANGMEI\\SLOT\\V1Controller@openTimes');
            Route::any('check_wash_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@loadCheckWashStatus');
            Route::any('check_wash_key_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@loadCheckWashKeyStatus');
            Route::any('grant_approval/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@grantApproval');
            Route::any('bb_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@bbStatus');
            Route::any('rb_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@rbStatus');
            Route::any('combine_status/{ip}/{port}', 'SHUANGMEI\\SLOT\\V1Controller@combineStatus');
            Route::any('check_lottery/{ip}/{port}','SHUANGMEI\\SLOT\\V1Controller@checkLottery');
            Route::any('open_any_point/{ip}/{port}/{score}', 'SHUANGMEI\\SLOT\\V1Controller@openAnyPoint');
        });
    });

    Route::prefix('multifunction')->group(function(){
        Route::prefix('v1')->group(function(){
            Route::any('all_status/{ip}/{port}', 'SHUANGMEI\\MULTIFUNCTION\\V1Controller@allStatus');
            Route::any('load_table/{ip}/{port}/{table}', 'SHUANGMEI\\MULTIFUNCTION\\V1Controller@loadTable');
            Route::any('clear_table/{ip}/{port}/{table}', 'SHUANGMEI\\MULTIFUNCTION\\V1Controller@clearTable');
            Route::any('output_point_lo/{ip}/{port}/{point}', 'SHUANGMEI\\MULTIFUNCTION\\V1Controller@outputPointLo');
            Route::any('output_point_lo_hi/{ip}/{port}/{point}/{unit}', 'SHUANGMEI\\MULTIFUNCTION\\V1Controller@outputPointLoHi');
            Route::any('output_point_hi/{ip}/{port}/{point}', 'SHUANGMEI\\MULTIFUNCTION\\V1Controller@outputPointHi');
        });
    });
});

Route::prefix('daheng')->group(function(){
    Route::prefix('clown')->group(function(){
        Route::any('wash_point_control/{ip}/{port}', 'PLC\\V2ClownController@washPointControl');
        Route::any('open_point/{ip}/{port}/{amount}', 'PLC\\V2ClownController@openPoint');
        Route::any('open_point_control/{ip}/{port}', 'PLC\\V2ClownController@openPointControl');
        Route::any('coin_on/{ip}/{port}', 'PLC\\V2ClownController@coinOn');
        Route::any('coin_off/{ip}/{port}', 'PLC\\V2ClownController@coinOff');
        Route::any('open_point_status/{ip}/{port}', 'PLC\\V2ClownController@openPointStatus');
        Route::any('open_point_zero/{ip}/{port}', 'PLC\\V2ClownController@openPointZero');
        Route::any('score_reward_status_zero/{ip}/{port}', 'PLC\\V2ClownController@scoreAndRewardStatusZero');
        Route::any('score_zero/{ip}/{port}', 'PLC\\V2ClownController@scoreAndRewardZero');
        Route::any('big_reward_status_zero/{ip}/{port}', 'PLC\\V2ClownController@grandRewardZero');
        Route::any('small_reward_status_zero/{ip}/{port}', 'PLC\\V2ClownController@smallRewardZero');
        Route::any('reward_switch/{ip}/{port}', 'PLC\\V2ClownController@rewardSwitch');
        Route::any('pressure/{ip}/{port}', 'PLC\\V2ClownController@pressure');
        Route::any('score/{ip}/{port}', 'PLC\\V2ClownController@score');
        Route::any('big_reward_status/{ip}/{port}', 'PLC\\V2ClownController@bigRewardStatus');
        Route::any('small_reward_status/{ip}/{port}', 'PLC\\V2ClownController@smallRewardStatus');
        Route::any('start_game/{ip}/{port}', 'PLC\\V2ClownController@joystickShort');
        Route::any('start_auto/{ip}/{port}', 'PLC\\V2ClownController@joystickLong');
        Route::any('stop_1/{ip}/{port}', 'PLC\\V2ClownController@stop1');
        Route::any('stop_2/{ip}/{port}', 'PLC\\V2ClownController@stop2');
        Route::any('stop_3/{ip}/{port}', 'PLC\\V2ClownController@stop3');
        Route::any('seven_display/{ip}/{port}', 'PLC\\V2ClownController@sevenDisplay');
        Route::any('stake_point/{ip}/{port}', 'PLC\\V2ClownController@stakePoint');
        Route::any('stop_auto/{ip}/{port}', 'PLC\\V2ClownController@joystickShort');
    });
});
