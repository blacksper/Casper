/**
 * Created by Lalka on 06.04.2016.
 */
var offset = 20;
var limit = 10;



(function($) {
    $.fn.removeClassWild = function(mask) {
        return this.removeClass(function(index, cls) {
            var re = mask.replace(/\*/g, '\\S+');
            return (cls.match(new RegExp('\\b' + re + '', 'g')) || []).join(' ');
        });
    };
})(jQuery);



$(document).ready(function(){
    $("#login").click(function(){
        var password=$("#password").val();
        var username=$("#username").val();
        $.ajax({
            url:"./auth.php",
            type: "POST",
            data: "username="+username+"&password="+password,
            success:function(data) {

                if (data != "") {
                    //alert(22);
                    var script = document.createElement('script');
                    script.type = 'text/javascript';

                    script.text = data;
                    $("body").append(script);

                    console.log(data);
                } else {
                    //alert(123);
                    $('input#password').after('<div class="alert alert-danger"> ' +
                        '<strong>Error:</strong>Неверный логин или пароль ' +
                        '</div>');
                }
                //$("body").append(123);
            }
        });

    });


    $("#addCampaign").click(function () {
        var campaignName = $("#campaignName").val();
        console.log(campaignName);

        if (campaignName !== undefined) {
                $.ajax({
                    url: "./ajax.php",
                    type: "POST",
                    data: "page=main&campaignName=" + campaignName + "&action=add",
                    success: function (data) {
                        console.log("viz");
                        if (data !== undefined) {
                            qwe = JSON.parse(data);
                            htmll = $.parseHTML(qwe);
                            $(htmll).addClass('success').prependTo('#campaignContent tbody');
                            //$("#campaignContent tbody").prepend(htmll);

                        }

                    }
                });
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
        var strForHash = $("#strForHash").val();
        var hashType = $("#hashType").val();
        var cid = $_GET('cid');
        if ((strForHash !== undefined) && (hashType !== undefined) && (cid !== undefined)) {
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
        }

    });

        $("#addServer").click(function(){
            var serverUrl=$("#serverUrl").val();
            console.log(serverUrl);

            if(serverUrl!==undefined) {
                $.ajax({
                    url: "./ajax.php",
                    type: "POST",
                    data: "page=main&serverUrl=" + serverUrl + "&action=add",
                    success: function (data) {
                        console.log(data);
                        if(data!==undefined)
                            $("#serverContent tbody").append(JSON.parse(data));

                    }
                });
            }

        });

    $("body").on('click', '#moreGitRows', function () {
        //var serverUrl=$("#serverUrl").val();
        var scid = $(this).parents('.modal-content').data("scid");
        console.log(scid);

        if (scid !== undefined) {
            $.ajax({
                url: "./ajax.php",
                type: "POST",
                data: "page=campaigns&scid=" + scid + "&action=getGitRows&offset=" + offset + "&limit=" + limit,
                success: function (data) {
                    console.log(limit + " " + offset);
                    if (data !== undefined) {
                        $("#gitTable tbody").append(JSON.parse(data));
                        offset += limit;
                    }


                }
            });
        }

        });



        $("body").on('click','.refresh',function(){
            var row=$(this).parents('.serverRow');
            var serverId=row.attr('value');
            var ip = row.find('.ip');
            var rstat = row.find('.status');

            ip.html('<img style="background-color: inherit;" width="30px" height="30px" src="images/loader.gif">')
            rstat.html('<img style="background-color: inherit;" width="30px" height="30px" src="images/loader.gif">');

            if(serverId!==undefined) {
                $.ajax({
                    url: "./ajax.php",
                    type: "POST",
                    data: "page=main&serverId=" + serverId + "&action=refresh",
                    success: function (data) {
                        console.log(data);
                        if(data!==undefined) {
                            decodeData=JSON.parse(data);
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
            var row=$(this).parents('.serverRow');
            var serverId=row.data('sid');
            console.log(serverId);
            if(serverId!==undefined) {
                $.ajax({
                    url: "./ajax.php",
                    type: "POST",
                    data: "page=main&serverId=" + serverId + "&action=delete",
                    success: function (data) {
                        console.log(data);
                        if(data==1) {
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
                            if(data==1) {
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
                url: "./scan.php",
                type: "POST",
                data: "filename=" + filename + "&action=downloadSrc&filepath=" + filepath,
                success: function (data) {
                    console.log(data);
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