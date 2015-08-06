        $(function() {
            var state = true;
            $("#web_btn").click(function(){
                if (state == true) {
                    $("#web_captcha").val("");
                    $("#web_private").val("");
                    $("#web_set2").attr("style","display:inline;");
                    $("#web_set1").attr("style","display:none");
                    $(".txt").attr("style","border:#999 solid 1px;");
                    state = false;
                }else{
                    $.ajax({
                        type:'POST',
                        url:'admin.php?action=plugins&operation=config&do=$do&identifier=geetest&pmod=geetestcloud',
                        data: 'web_keyset='+$("#web_captcha").val()+'/'+$("#web_private").val(),

                        success:function(){
                            
                        }
                        
                    });
                    $.ajax({
                        type:'GET',
                        async:false,
                        url:'http://my.geetest.com/api/discuz/value',
                        dataType:'jsonp',
                        data:{"captchaid":$("#web_captcha").val(),"privatekey":$("#web_private").val()},
                        jsonp:"callback",
                       
                        success:function(callback){
                            if (callback.success == "fail") {
                                alert(callback.success);
                                state = false;
                                $("#web_set2").css('display','inline'); 
                                $("#web_set1").css('display','none'); 
                                window.location.reload();
                                
                            };
                            if (callback.success == "success") {
                                alert(callback.success);
                                state = true;
                                $("#web_set1").css('display','inline'); 
                                $("#web_set2").css('display','none'); 
                                window.location.reload();
                            };
                        }
                        
                    });
                    
                };
            });     
        });
        

        $(function(){
            $("input").blur(function(){
                if ($.trim($("#web_captcha").val()).length != 32 ) {
                    $("#msg_web_id").html("<span style='color:red;'>&#105;&#100;&#38271;&#24230;&#19981;&#27491;&#30830;&#65281;</span>");
                    $("#label_web_id").css('display','none'); 
                }else{
                    $("#label_web_id").show();
                    $("#msg_web_id").html("");
                };
                if ($.trim($("#web_private").val()).length != 32) {
                    $("#msg_web_key").html("<span style='color:red;'>&#107;&#101;&#121;&#38271;&#24230;&#19981;&#27491;&#30830;&#65281;</span>");
                    $("#label_web_key").css('display','none'); 
                }else{
                    $("#label_web_key").show();
                    $("#msg_web_key").html("");
                };
            });
        });
