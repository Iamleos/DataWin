;
// @debug2012
// site common js
var SITE = {};
SITE.extend = function(oldObj,newObj){
    var tmp = {};
    for(var i in oldObj){
        tmp[i] = oldObj[i];
    }
    for(var i in newObj){
        tmp[i] = newObj[i];
    }

    return tmp;
}
SITE.URL = {
    params:function(url){
        url = url || location.href;
        var a = url.split('?')
        a.shift();
        var s = a.join('?');
        var a = s.split('&');
        var params = {};
        for(var i = 0,n = a.length;i<n;i++){
            var d = a[i].split('=');
            params[d[0]] = d[1];
        }
        return params;
    },

    normalizeUrl:function(params,url){
        url = url || location.href;
        var d = SITE.extend(this.params(url),params)
        var s = url.split('?')[0]+'?';
        var a = [];
        for(var i in d){
            a.push(i+'='+d[i]);
        }

        return s+ a.join('&');
    }
};
SITE.strPad = function(str,len,glue){
    var l = String(str).length;
    len = len || 2;
    glue = glue || '0';
    while(l < len){
        str = glue+str;
        l++;
    }
    return str;
}
SITE.dateFormat = function(format,datetime){
    if(datetime){
        var date = new Date(datetime*1000);
    }else{
        var date = new Date();
    }

    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();

    format = format || 'yyyy-mm-dd hh:ii';
    console.log(format);
    return format.replace('yyyy',SITE.strPad(year)).replace('mm',SITE.strPad(month)).replace('dd',SITE.strPad(day))
        .replace('hh',SITE.strPad(hours)).replace('ii',SITE.strPad(minutes)).replace('ss',SITE.strPad(seconds));
}
/**
 * 格式化日期，如2015/01/29 转成 2015-01-29
 * @param dateString "2015/01/29"
 * @param glue "/"
 * @param format
 * @returns {XML}
 */
SITE.formatDate = function(dateString,glue,format){
    format = format || 'yyyy-mm-dd';
    if(glue){
        dateString = dateString.replace(new RegExp(glue,'g'),'');
    }
    var year = dateString.substr(0,4);
    var month = dateString.substr(4,2);
    var day = dateString.substr(6,2);

    return format.replace('yyyy',year).replace('mm',month).replace('dd',day);
}
/**
 * @param url
 * @returns {string}
 */
SITE.ensureUrl = function(url){
    return CONFIG.base_url+ '/' + url;
}
/**
 * @param url
 * @returns {string}
 */
SITE.ensureThemeUrl = function(url){
    return CONFIG.theme_base_url + '/' + url;
}
/**
 * @param s
 * @returns {string}
 */
SITE.htmlencode = function(s){
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(s));
    return div.innerHTML;
}
/**
 * @param s
 * @returns {string}
 */
SITE.htmldecode = function(s){
    var div = document.createElement('div');
    div.innerHTML = s;
    return div.innerText || div.textContent;
}
/**
 * format number
 * @param number
 * @param num
 * @returns {*}
 */
SITE.toFixed = function(number,num){
    num = num || 2;
    if(typeof number.toFixed != 'undefined'){
        return number.toFixed(num)
    }
    return number;
}
$(function(){
    // fix css style
    // $(".soussy").remove('soussy').addClass('ssjjyyy').addClass('htphb');

    // search
    $("#search-btn").click(function(){
        var q = $.trim($(this).prev().val());
        if(CONFIG.controller_id == 'query'){
            window.location.href = SITE.URL.normalizeUrl({q:q,search_field:$("#search-field").val()});
        }else{
            window.location.href = SITE.ensureUrl('query/wx?q='+q);
        }
//        if(q){
//            if(CONFIG.controller_id == 'query'){
//                window.location.href = SITE.URL.normalizeUrl({q:q,search_field:$("#search-field").val()});
//            }else{
//                window.location.href = SITE.ensureUrl('query/wx?q='+q);
//            }
//        }
    });

    // show qrcode
    $('.show-qrcode').click(function(){
        $(this).next().toggle();
    });

    // keydown
    $("#search-input").keydown(function(e){
        if(e.keyCode == 13){
            var q = $.trim($(this).val());
            if(CONFIG.controller_id == 'query'){
                window.location.href = SITE.URL.normalizeUrl({q:q,search_field:$("#search-field").val()});
            }else{
                window.location.href = SITE.ensureUrl('query/wx?q='+q);
            }
//            if(q){
//                if(CONFIG.controller_id == 'query'){
//                    window.location.href = SITE.URL.normalizeUrl({q:q,search_field:$("#search-field").val()});
//                }else{
//                    window.location.href = SITE.ensureUrl('query/wx?q='+q);
//                }
//            }
        }
    });

    // search field selector
    $('#select-arrow').click(function(){
        $(this).parent().find('#search-field-ul li').show();
    });

    /*$("#search-field-ul").click(function(){
        //if($(this).find('li:visible').size() < 4){
            $(this).find('li').show();
        //}
    });*/

    $('#search-field-ul li a').click(function(){
        var rel = $(this).data('rel');
        $("#search-field").val(rel);
    });

    // top nav icons
    // wechat icon
    $("#tp_06").hover(function(){
        $(this).attr('src',SITE.ensureThemeUrl('images/xtb_1_03.png'));
    },function(){
        $(this).attr('src',SITE.ensureThemeUrl('images/tp_06.png'));
    }).click(function(){
            $(this).next().toggle();
        });

    // weibo icon
    $("#tp_08").hover(function(){
        $(this).attr('src',SITE.ensureThemeUrl('images/xtb_1_05.png'));
    },function(){
        $(this).attr('src',SITE.ensureThemeUrl('images/tp_08.png'));
    });

    // qq
    $("#tp_10").hover(function(){
        $(this).attr('src',SITE.ensureThemeUrl('images/xtb_1_07.png'));
    },function(){
        $(this).attr('src',SITE.ensureThemeUrl('images/tp_10.png'));
    });

    // advance search
    $(".advance-search-btn").click(function(){
        if($(".advance-search-area:visible").size()){
            $(".advance-search-area").slideUp();
        }else{
            $(".advance-search-area").slideDown();
        }
    });
});