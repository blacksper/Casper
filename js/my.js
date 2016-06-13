/**
 * Created by Lalka on 06.04.2016.
 */
var offset = 20;
var limit = 10;


(function ($) {
    $.fn.removeClassWild = function (mask) {
        return this.removeClass(function (index, cls) {
            var re = mask.replace(/\*/g, '\\S+');
            return (cls.match(new RegExp('\\b' + re + '', 'g')) || []).join(' ');
        });
    };
})(jQuery);






$(document).ready(function () {
    $("#login").click(function () {
        var password = $("#password").val();
        var username = $("#username").val();
        $.ajax({
            url: "./auth.php",
            type: "POST",
            data: "username=" + username + "&password=" + password,
            success: function (data) {

                if (data != "") {
                    //alert(22);
                    var script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.text = data;
                    $("body").append(script);

                    console.log(data);
                } else {
                    //alert(123);
                    if ($(".alert-danger").html() == undefined) {
                        $('input#password').after('<div class="alert alert-danger"> ' +
                            '<strong>Error:</strong>Неверный логин или пароль ' +
                            '</div>');
                    } else {
                        $('.alert-danger').fadeOut(500);
                        $('.alert-danger').fadeIn(500);
                    }

                }
                //$("body").append(123);
            }
        });

    });




    $("#addCampaign").click(function () {
        if($('.error').html()!==undefined)
            $('.error').remove();
        var serverUrl = $("#serverUrl").val();
        var campaignName = $("#campaignName").val();
        console.log(campaignName);

        if ((campaignName != undefined)&&(campaignName!=="")) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&campaignName=" + campaignName + "&action=add",
                success: function (data) {
                    if($('.error').html()!==undefined)
                        $('.error').remove();
                    // console.log("viz");
                    if (data !== undefined) {
                        qwe = JSON.parse(data);
                        htmll = $.parseHTML(qwe);
                        $(htmll).addClass('success').prependTo('#campaignContent tbody');
                        //$("#campaignContent tbody").prepend(htmll);

                    }

                }
            });
        }else{
            $("#campaigns").after(
                '<div class="alert alert-danger error">' +
                ' <strong>Ошибка</strong> ' +
                'Не указано имя кампании! ' +
                '</div>'
            );
        }

    });

    function $_GET(key) {
        var s = window.location.search;
        s = s.match(new RegExp(key + '=([^&=]+)'));
        return s ? s[1] : false;
    }

    //alert( $_GET('cid') );

    $("#addTargets").click(function () {
        var targetUrls = $("#targetsArea").val();
        console.log(targetUrls);
        console.log("CID IS " + $_GET('cid'));
        if (targetUrls !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=campaigns&targetUrls=" + targetUrls + "&action=add&cid=" + $_GET('cid'),
                success: function (data) {
                    console.log("viz");
                    if (data !== undefined) {
                        qwe = JSON.parse(data);
                        htmll = $.parseHTML(qwe);
                        $(htmll).addClass('success').prependTo('#targetsContent tbody');
                        //$("#targetsContent tbody").prepend(htmll);
                        //$("#targetsContent tbody").append(data);
                    }

                }
            });
        }

    });

    $("#addHash").click(function () {
        if($('.error').html()!==undefined)
            $('.error').remove();
        var strForHash = $("#strForHash").val();
        var hashType = $("#hashType").val();
        var cid = $_GET('cid');
        if (((strForHash !== undefined)&&(strForHash!=="")) && (hashType !== undefined) && (cid !== undefined)) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=campaigns&type=" + hashType + "&action=addHash&strForHash=" + strForHash + "&cid=" + cid,
                success: function (data) {
                    if (data !== undefined) {
                        qwe = JSON.parse(data);
                        htmll = $.parseHTML(qwe);
                        //htmll.addClass("success");
                        //$("#hashesContent tbody").append(htmll);
                        $(htmll).addClass('success').prependTo('#hashesContent tbody');
                    }
                }
            });
        }else {
            if ($(".error").html() == undefined) {
                $("#hashesAdd").append(
                    '<div style="width:410px;margin: 10px 4px 0 0;" class="alert alert-danger error">' +
                    ' <strong>Ошибка</strong> ' +
                    'Не указана строка для вычисления хэша! ' +
                    '</div>');
            } else {
                //$(".error").fadeIn
                $('.error').fadeOut(500);
                $('.error').fadeIn(500);
            }
        }

    });

    $("#addServer").click(function () {
        if($('.error').html()!==undefined)
            $('.error').remove();
        var serverUrl = $("#serverUrl").val();
        console.log(serverUrl);

        if ((serverUrl != undefined)&&(serverUrl!=="")) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&serverUrl=" + serverUrl + "&action=add",
                success: function (data) {
                    if($('.error').html()!==undefined)
                        $('.error').remove();
                    //console.log(data);
                    if (data !== undefined) {
                        datajdecode = JSON.parse(data);
                        if(datajdecode==""){
                            $("#servers").after(
                                '<div class="alert alert-danger error">' +
                                ' <strong>Ошибка</strong> ' +
                                'Неверный путь до ИС! ' +
                                '</div>'
                            );

                        }else {
                            html = $.parseHTML(datajdecode);
                            $(html).addClass("success");
                            $("#serverContent tbody").prepend(html);
                        }
                    }

                }
            });
        }else{
        $("#servers").after(
            '<div class="alert alert-danger error">' +
            ' <strong>Ошибка</strong> ' +
            'Не указан путь до ИС! ' +
            '</div>'
        );
    }

    });

    $('body').on('shown.bs.tab','a[data-toggle="tab"]',function(){
        if ($(".error").html() !== undefined)
            $(".error").remove();
    });

    $("body").on('click', '.refresh', function () {
        var row = $(this).parents('.serverRow');
        var serverId = row.attr('value');
        var ip = row.find('.ip');
        var rstat = row.find('.status');

        ip.html('<img style="background-color: inherit;" width="30px" height="30px" src="images/loader.gif">')
        rstat.html('<img style="background-color: inherit;" width="30px" height="30px" src="images/loader.gif">');

        if (serverId !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&serverId=" + serverId + "&action=refresh",
                success: function (data) {
                    console.log(data);
                    if (data !== undefined) {
                        decodeData = JSON.parse(data);
                        ip.text(decodeData['ip']);

                        rstat.text(decodeData['statusArr']['stmsg']);
                        rstat.removeClassWild("");
                        rstat.addClass("col-md-1 " + decodeData['statusArr']['status']);
                    }
                }
            });
        }
    });

    $("body").on('click', '.deleteSrv', function () {
        var row = $(this).parents('.serverRow');
        var serverId = row.data('sid');
        console.log(serverId);
        if (serverId !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&serverId=" + serverId + "&action=delete",
                success: function (data) {
                    console.log(data);
                    if (data == 1) {
                        //alert();
                        row.fadeOut(500);
                    }
                }
            });
        }
    });

    $("body").on('click', '.deleteCmp', function () {
        var row = $(this).parents('.campaignRow');
        var campaignId = row.data('cid');
        console.log(campaignId);
        if (campaignId !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&campaignId=" + campaignId + "&action=delete",
                success: function (data) {
                    console.log(data);
                    if (data == 1) {
                        row.fadeOut(500);
                    }
                }
            });
        }
    });
    $("body").on('click', '.deleteTgt', function () {
        var row = $(this).parents('.targetRow');
        var targetId = row.data('tid');
        console.log(targetId);
        if (targetId !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&targetId=" + targetId + "&action=delete",
                success: function (data) {
                    console.log(data);
                    if (data == 1) {
                        row.fadeOut(500);
                    }
                }
            });
        }
    });
    $("body").on('click', '.deleteScn', function () {
        var row = $(this).parents('.scanRow');
        var scanId = row.data('scid');
        console.log(scanId);
        if (scanId !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&scanId=" + scanId + "&action=delete",
                success: function (data) {
                    console.log(data);
                    if (data == 1) {
                        row.fadeOut(500);
                    }
                }
            });
        }
    });
    $("body").on('click', '.deleteHash', function () {
        var row = $(this).parents('.hashRow');
        var hid = row.data('hid');
        console.log(hid);
        if (hid !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=main&hashId=" + hid + "&action=delete",
                success: function (data) {
                    console.log(data);
                    if (data == 1) {
                        row.fadeOut(500);
                    }
                }
            });
        }
    });

    $("body").on('click', '#saveNote', function () {
        var note = $("#noteText").val();
        var targetId = $(this).data('tid');
        //var targetId = row.data('tid');
        console.log(targetId);
        if (targetId !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=campaigns&tid=" + targetId + "&action=saveNote&note=" + note,
                success: function (data) {
                    if (data == 1) {
                        if ($("#sohr").html() !== undefined) {
                            $('#sohr').fadeOut(500);
                            $('#sohr').fadeIn(500);
                        } else {

                            $('.modal-footer').append("<div id='sohr'><p style='font-size: 18px' class='text-success text-left'>Сохранено</p></div>");
                        }

                        console.log(data);
                    }

                }
            });
        }
    });

    $("body").on('click', 'tr>td.dateScan a', function () {

        var status = $(this).parents('tr.scanRow').find('span').attr("value");
        console.log(status);
        if (status == 1) {
            if (($('#myModal').html() == undefined)) {
                $('body').append("<div id='myModal' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel'>" +
                    "<div class='modal-dialog modal-lg'>" +

                    "</div>" +
                    "</div>");
            }
            var scid = ($(this).parents('tr.scanRow').data("scid"));
            console.log(scid);
            $.ajax({
                url: "ajax.php",
                type: "POST",
                data: "page=campaigns&action=getScanDetails&scid=" + scid,
                success: function (data) {
                    $('.modal-dialog').html(JSON.parse(data));
                    $('#myModal').modal();
                }
            });
            console.log($('#spoiler-' + scid).text().length);
        }
    });

    $("body").on('click', '#moreGitRows', function () {
        //var serverUrl=$("#serverUrl").val();
        var btnplace = $(this);
        var scid = btnplace.parents('#gitDumpTable').data("scid");
        console.log(scid);
        var searchText = $("#searchText").val();
        if (searchText == undefined)
            searchText = "";

        if (scid !== undefined) {
            btnplace.html('<img class="loader" style="background-color: inherit;" width="30px" height="30px" src="images/loader.gif">');
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=campaigns&scid=" + scid + "&action=getGitRows&offset=" + offset + "&limit=" + limit + "&searchText=" + searchText,
                success: function (data) {
                    console.log(limit + " " + offset);
                    if (data !== undefined) {
                        $("#gitTable tbody").append(JSON.parse(data));
                        offset += limit;
                        btnplace.html('<button id="moreGitRows" class="btn btn-info btn-xs">MORE <span class="glyphicon glyphicon-download"></span></button>');
                    }

                }
            });
        }

    });

    $("body").on('click', '#searchGit', function () {
        var pnt = $(this).parents(".form-group");
        var tid = pnt.find(".targetsList option:selected").val();


        // console.log(action);
        if (tid > 0) {

            //var type = $('.action').val();
            var type = pnt.find(".action").val();
            console.log(type);
            var searchText = $("#searchText").val();
            getGitDetails(tid, type, searchText);
        } else {
            if ($(".error").html() == undefined) {
                $("#gitdumper .form-group").append(
                    '<div style="margin: 10px 4px 0 0;" class="alert alert-danger error">' +
                    ' <strong>Ошибка</strong> ' +
                    'Цель не выбрана! ' +
                    '</div>');
            } else {
                //$(".error").fadeIn
                $('.error').fadeOut(500);
                $('.error').fadeIn(500);
            }
        }

    });

    function getGitDetails(tid, type, searchText) {
        //console.log("kill me");
        if (tid !== undefined && type !== undefined) {

            var loader = $(".loader");
            //console.log(loader);
            if (searchText == undefined) searchText = "";

            if (($('#gitDumpTable').html() != undefined))
                $('#gitDumpTable').remove();

            if (loader.html() === undefined) {
                $("#gitDumper .form-group").append('<img class="loader" style="background-color: inherit;" width="50px" height="50px" src="images/loader.gif">');
                loader = $(".loader");
            }

            console.log(tid);
            $.ajax({
                url: "ajax.php",
                type: "POST",
                data: "page=campaigns&action=getGitDetails&tid=" + tid + "&type=" + type + "&searchText=" + searchText,
                success: function (data) {
                    if (data !== undefined) {

                        $('#gitDumper .row .form-group').append(JSON.parse(data));
                        loader.remove();
                    }
                }
            });
        }

    }

    function dogitdump(tid) {

        var loader = $(".loader");
        //console.log(loader);
        if (searchText == undefined) searchText = "";

        if (($('#gitDumpTable').html() != undefined))
            $('#gitDumpTable').remove();

        if (loader.html() === undefined) {
            $("#gitDumper .form-group").append('<img class="loader" style="background-color: inherit;" width="50px" height="50px" src="images/loader.gif">');
            loader = $(".loader");
        }

        var type = "gitdump";
        console.log("before getgit");
        $.ajax({
            url: "ajax.php",
            type: "POST",
            //data: "page=scan&action=doScan&tid=" + tid + "&type=" + type,
            data: "page=scan&action=gitDump&tid=" + tid,
            success: function (data) {
                datajdecode = JSON.parse(data);

                var searchText = $('#searchtext').val();
                getGitDetails(tid, type, searchText);

            }
        });


    }



    $("body").on('click', '.doScan', function () {
        //var pnt = $(this).parents(".form-group");
        var pnt = $(this).parents(".form-group");
        var tid = pnt.find(".targetsList option:selected").val();
        var action = pnt.find(".action").val();
        var r;

        console.log(action + " actn");

        var sids = "";
        pnt.find("select[name*='sid[]'] option:selected").each(function (i, selected) {
            sids += "&sid[]=" + $(selected).val();
        });

        console.log(tid);
        console.log(action);
        if ((tid !== undefined) && (tid > 0)) {
            if ($(".error").html() !== undefined)
                $(".error").remove();

            switch (action) {
                case "mscan":
                    var filename = pnt.find("select[name*='filename'] option:selected").val();
                    var type = pnt.find("select[name*='action'] option:selected").val();
                    options = "&action=" + action + "&filename=" + filename + sids + "&type=" + type;
                    //console.log(options);
                    r=doScan(tid, options);
                    break;
                case "wpBrute":
                case "dleBrute":
                case "joomlaBrute":
                    var loginfile = pnt.find("select[name*='loginfile'] option:selected").val();
                    var passwordfile = pnt.find("select[name*='passwordfile'] option:selected").val();
                    console.log(tid);
                    options = "&action=" + action + "&loginfile=" + loginfile + "&passwordfile=" + passwordfile + sids;
                    r=doScan(tid, options);
                    break;
                case "nmapScan":
                    optionscan = pnt.find("select[name*='option'] option:selected").val();
                    options = "&action=" + action + "&option=" + optionscan;
                    r=doScan(tid, options);
                    //document.location("./index.php");
                    //document.location.href = document.location.href;

                    break;
                case "detectCms":
                    options = "&action=" + action;
                    r=doScan(tid, options);

                    break;
                case "gitDump":

                    options = "&action=" + action;
                    dogitdump(tid);
                    //doScan(tid, options);
                    //getGitDetails();

                    break;
            }
            if(r==0){
                if ($(".error").html() == undefined) {
                    $(pnt).append(
                        '<div style="margin: 10px 4px 0 0;" class="alert alert-danger error">' +
                        ' <strong>Ошибка</strong> ' +
                        'Цель не выбрана! ' +
                        '</div>');
                } else {
                    $('.error').fadeOut(500);
                    $('.error').fadeIn(500);
                }

            }

            //if()

            //console.log(options);
            //doScan(tid, options);
            //document.location.reload();

        } else {
            if ($(".error").html() == undefined) {
                $(pnt).append(
                    '<div style="margin: 10px 4px 0 0;" class="alert alert-danger error">' +
                    ' <strong>Ошибка</strong> ' +
                    'Цель не выбрана! ' +
                    '</div>');
            } else {
                $('.error').fadeOut(500);
                $('.error').fadeIn(500);
            }

        }
    });

    function doScan(tid, options) {
        if (options == undefined)
            options = "";
        if(tid!==undefined) {

            $.ajax({
                url: "ajax.php",
                type: "POST",
                //data: "page=scan&action=doScan&tid=" + tid + "&type=" + type,
                data: "page=scan&tid=" + tid + options,
                success: function (data) {
                    datajdecode = JSON.parse(data);
                    html = $.parseHTML(datajdecode);
                    $(html).addClass("success");
                    $('#scansCampaignContent tbody').prepend(html);
                }
            });
        }else{
           return 0;
        }

    }


    $("body").on('change', '#gitDumper .row .form-group .form-inline .targetsList', function () {

        if ($(".error").html() !== undefined) {
            $(".error").remove();
            //alert(123);
        }

        var tid = $(this).val();
        var type = "gitdump";
        var searchText = $('#searchtext').val();
        if (tid > 0)
            getGitDetails(tid, type, searchText);
        else if ($(".doScan").html() !== undefined) {
            $(".doScan").remove()
        }
    });

    $("body").on('click', '.editNote', function () {
        var tid = ($(this).parents('tr.targetRow').data("tid"));
        //var status = $(this).parents('tr.scanRow').find('span').attr("value");
        console.log(tid);

        if ($('#myModal').html() == undefined) {
            $('body').append("<div id='myModal' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel'>" +
                "<div class='modal-dialog modal-md'>" +

                "</div>" +
                "</div>");
        }

        $.ajax({
            url: "ajax.php",
            type: "POST",
            data: "page=campaigns&action=getNote&tid=" + tid,
            success: function (data) {
                $('.modal-dialog').html(JSON.parse(data));
                $('#myModal').modal();
            }
        });
        console.log($('#spoiler-' + tid).text().length);
    });

    $("body").on('click', '.downSrc', function () {

        var row = $(this).parents('.gitRow');
        var lst = row.find('td:last');
        lst.html('<img style="background-color: inherit;" width="30px" height="30px" src="images/loader.gif">');
        var filepath = row.find('.filepath .cc1 .cc2').html();
        var filename = row.find('.filename .cc1 .cc2').text();
        //row.find('.status').html('<img style="background-color: inherit;" width="50px" height="50px" src="images/1.gif">');
        console.log(666 + ' ' + filepath + " " + filename);

        if (filepath !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=scan&filename=" + filename + "&action=downloadSrc&filepath=" + filepath,
                success: function (data) {
                    console.log(data);
                    data = JSON.parse(data);
                    if ((data !== undefined) && (data == 1)) {
                        row.removeClassWild(" *");
                        row.addClass("success");
                    } else {
                        row.removeClassWild(" *");
                        row.addClass("danger");
                    }
                    lst.html('<button class="btn btn-info btn-sm downSrc"><span class="glyphicon glyphicon-download-alt"></span></button>');
                }
            });
        }


    });


});