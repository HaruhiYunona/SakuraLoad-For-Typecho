/**
 * jQuery.SakuraLoad.js
 * @authors HaruhiYunona (lashanda13fg@gmail.com)
 * @date    2022/1/15 12:00:00
 * @modify  2022/1/15 13:00:00
 * @version 0.1.0
 */

function SakuraLoad(content, type, link) {
    if (type == 'picture' && link.indexOf('http') != -1) {
        var sakuraImg = '<div class="sakura-loader-picture"><img style="width:140px;height:auto;border-radius: 15px;" src="' + link + '"></div>';
    } else {
        var sakuraImg = '<div class="sakura-loader"><svg version="1.1" id="sakura-loader-d" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve"><path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z"><animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite" /></path></svg><div class="sakura-load-msg">' + content + '</div>';
    }
    var sakuraHtm = '<div class="sakura-load">' + sakuraImg + '</div></div >';
    $('body').prepend(sakuraHtm);
// 请保留版权说明
if (window.console && window.console.log) {
    console.log("%c SakuraLoad %c https://mdzz.pro ","color: #fff; margin: 1em 0; padding: 5px 0; background: #FFB6C1;","margin: 1em 0; padding: 5px 0; background: #EFEFEF;");
}
}
