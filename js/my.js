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


        $("#addTarget").click(function(){
            var targetUrl=$("#targetUrl").val();
            console.log(targetUrl);

            if(targetUrl!==undefined) {
                $.ajax({
                    url: "./ajax.php",
                    type: "POST",
                    data: "targetUrl=" + targetUrl + "&action=add",
                    success: function (data) {
                        console.log("viz");
                        if(data!==undefined)
                            $("#targetContent tbody").append(JSON.parse(data));

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
                    data: "serverUrl=" + serverUrl + "&action=add",
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
                    data: "serverId=" + serverId + "&action=refresh",
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
                    data: "serverId=" + serverId + "&action=delete",
                    success: function (data) {
                        console.log(data);
                        if(data==1) {
                            row.fadeOut(500);
                        }
                    }
                });
            }
        });

        $("body").on('click','.deleteTgt',function(){
                var row=$(this).parents('.targetRow');
                var targetId=row.data('tid');
                console.log(targetId);
                if(targetId!==undefined) {
                    $.ajax({
                        url: "./ajax.php",
                        type: "POST",
                        data: "targetId=" + targetId + "&action=delete",
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
        $("body").on('click','tr>td.url a',function(){

        var tid=($(this).parents('tr.targetRow').data("tid"));
        console.log($('#spoiler-'+tid).text().length);

        if($('#spoiler-'+tid).text().length==0) {

            $.ajax({
                url: "ajax.php",
                type: "POST",
                data: "action=getsubinfo&tid="+tid,
                success: function (data) {
                    $('#spoiler-'+tid).html(data);

                }
            });
        }

    });


});