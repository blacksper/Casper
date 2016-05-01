/**
 * Created by Lalka on 06.04.2016.
 */




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
                var script = document.createElement( 'script' );
                script.type = 'text/javascript';

                script.text = data;
                $("body").append( script );

                console.log(data);
                $("body").append(123);
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
                        if(data!==undefined)
                            $("#campaignContent tbody").append(JSON.parse(data));

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



        $("body").on('click','.refresh',function(){
            var row=$(this).parents('.serverRow');
            var serverId=row.attr('value');
            row.find('.ip').html('<img style="background-color: inherit;" width="50px" height="50px" src="images/1.gif">');
            row.find('.status').html('<img style="background-color: inherit;" width="50px" height="50px" src="images/1.gif">');

            if(serverId!==undefined) {
                $.ajax({
                    url: "./ajax.php",
                    type: "POST",
                    data: "page=main&serverId=" + serverId + "&action=refresh",
                    success: function (data) {
                        console.log(data);
                        if(data!==undefined) {
                            decodeData=JSON.parse(data);
                            row.find('.ip').text(decodeData['ip']);
                            row.find('.status').text(decodeData['statusArr']['stmsg']);
                            row.find('.status').removeClassWild("btn-*");
                            row.find('.status').addClass(decodeData['statusArr']['status']);
                        }
                    }
                });
            }
        });

        $("body").on('click','.deleteSrv',function(){
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




    function showAlert(){
        $("#myAlert").addClass("in");
    }

    /*$(".url a").click(function(){
        alert("work");
        showAlert();
    });*/

    //$('tr>td.url a').click(function(){

    $("body").on('click', 'tr>td.dateScan a', function () {
        //alert(123);
            if ($('#myModal').html() == undefined) {
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
                    $('.modal-dialog').html(data);
                    $('#myModal').modal();
                }
            });
        console.log($('#spoiler-' + scid).text().length);
    });

    $("boddy").on('click', 'tr>td.dateScan a', function () {
        //alert(123);
        if ($('#myModal').html() == undefined) {
            $('body').append("<div id='myModal' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel'>" +
                "<div class='modal-dialog modal-lg'>" +

                "</div>" +
                "</div>");
        }
        var tid = ($(this).parents('tr.targetRow').data("tid"));
            $.ajax({
                url: "ajax.php",
                type: "POST",
                data: "action=getsubinfo&tid=" + tid,
                success: function (data) {
                    $('.modal-dialog').html(data);
                    $('#myModal').modal();
                }
            });
        console.log($('#spoiler-' + tid).text().length);
    });

});