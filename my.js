/**
 * Created by Егор on 08.10.2015.
 */
$(document).ready(function(){

    $('.showadd').click(function(){
        //alert($('.addform').text());
        if(!$('.addform').text()){
            //var tt=$(this).attr('name');
            $('.pol').append(
                '<form method=POST action="/" class="navbar-form navbar-left addform" role=search>'
            +'<div class="form-group asf">'
            +'<input name=sname style="width:200px;" class=form-control placeholder=Script_path type=text>'
            +'</div>'
            +'<button name=add type=button class="btn btn-default addurl">Добавить</button>'
            +'</form>');
            $('.addurl').on("click",function(){
                //alert(123);
                var sname=$('.asf').find('input[name=sname]').val();
                //alert(sname);
                var nname=$(this).attr('name');
                $.ajax({
                    type: 'POST',
                    url: 'ajax.php',
                    data:'action='+nname+'&sname='+sname,
                    cache: false,
                    success: function(response){
                        if(response!=-1){

                            $('tbody').append(
                            '<tr class=tesst value=\"'+response+'\">'
                            +'<td class=url> '+sname+'</td> <td class=ip> -1</td>'
                            +'<td class=\"status info\">UNKNOWN</td>'
                            +'<td><div class=\"btn-group btns\" >'
                            +'<button name=\"ref\" id=\"ref\"  type=\"button\" class=\"btn btn-info\"><span class=\"glyphicon glyphicon-refresh\"></span></button>'
                            +'<button name=\"edit\" type=\"button\" id=\"edit\" class=\"btn btn-warning\"><span class=\"glyphicon glyphicon-cog\"></span></button>'
                            +'<button name=\"del\" value=\"'+response+'\" type=\"submit\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span></button> </div></td>'
                            +'</tr>');
                            if($("#errurl").html())
                                $("#errurl").remove();
                        }else{
                            if($("#errurl").html()){
                                $("#errurl").animate({opacity: 0.1}, 1000);
                                $("#errurl").animate({opacity: 2}, 1000);
                                return;
                            }

                                $(".addform").append("<div id=\"errurl\" class=\"alert alert-danger\" role=\"alert\"><strong>Ошибка : </strong>неверный хост</div>");
                                return;

                        }
                    }
                });
            });
        }else{
            $('.addform').remove();
        }
    });


    $('body').on('click','.btns button',function(){
        var me = this;
        var id=$(this).parents('tr').attr('value');
        var nname = $(this).attr('name');
        var status = $(me).parents('tr').find('.status');
        var ip = $(me).parents('tr').find('.ip');
        $.ajax({
                    type: 'POST',
                    url: 'ajax.php',
                    data:'cid='+id+'&action='+nname,
                    cache: false,
                    success: function(response){
                        if(nname=='del'){
                            if(response==1)
                                status.parents('tr').remove();
                        }

                if(nname!='ref')
                    return;


                if(response!=0){

                    // $('.activeClass').removeClass('activeClass');
                    // $(this).addClass('activeClass');

                    //if(status.html()=='BAD'){
                    //ip.removeClass('danger');
                    //ip.addClass('success');
                    ip.html(response);
                    // }

                    if(status.html()=='BAD'||status.html()=='UNKNOWN'){
                        status.removeClass('danger info');
                        status.addClass('success');
                        status.html('GOOD');
                    }
                    //if(status.html()=='UNKNOWN'){
                    //    status.removeClass('info');
                    //    status.addClass('success');
                    //    status.html('GOOD');
                    //}

                }else{
                    if(status.html()=='GOOD'||status.html()=='UNKNOWN'){
                        status.removeClass('success');
                        status.addClass('danger');
                        status.html('BAD');
                    }
                }


            }
        });
    });

    $('body').on('click','.bibi',function(){


        alert(123);


        });

});