        $(function() {
            var state = true;
            $("#mobile_btn").click(function(){
                if (state == true) {
                    $("#mobile_captcha").val("");
                    $("#mobile_private").val("");
                    $("#mobile_set1").attr("style","display:none");
                    $("#mobile_set2").attr("style","display:inline;");
                    $(".txt").attr("style","border:#999 solid 1px;");
                    state = false;
                }else{
                    $.ajax({
                        type:'POST',
                        url:'admin.php?action=plugins&operation=config&do=$do&identifier=geetest&pmod=geetestcloud',
                        data: 'mobile_keyset='+$("#mobile_captcha").val()+'/'+$("#mobile_private").val(),

                        success:function(){
                            
                        }
                        
                    });
                    $.ajax({
                        type:'GET',
                        async:false,
                        url:'http://account.geetest.com/api/discuz/value',
                        dataType:'jsonp',
                        data:{"captchaid":$("#mobile_captcha").val(),"privatekey":$("#mobile_private").val()},
                        jsonp:"callback",
                       
                        success:function(callback){
                            if (callback.success == "fail") {
                                alert(callback.success);
                                state = false;
                                $("#mobile_set2").attr("style","display:inline");
                                $("#mobile_set1").attr("style","display:none");
                                window.location.reload();
                                
                            };
                            if (callback.success == "success") {
                                alert(callback.success);
                                state = true;
                                $("#mobile_set1").attr("style","display:inline");
                                $("#mobile_set2").attr("style","display:none");
                                window.location.reload();
                            };
                        }
                        
                    });
                    
                };
            });     
        });
        

        $(function(){
            $("input").blur(function(){
                if ($.trim($("#mobile_captcha").val()).length != 32 ) {
                    $("#msg_mobile_id").html("<span style='color:red;'>&#105;&#100;&#38271;&#24230;&#19981;&#27491;&#30830;&#65281;</span>");
                    $("#label_mobile_id").css('display','none'); 
                }else{
                    $("#label_mobile_id").show();
                    $("#msg_mobile_id").html("");
                };
                if ($.trim($("#mobile_private").val()).length != 32) {
                    $("#msg_mobile_key").html("<span style='color:red;'>&#107;&#101;&#121;&#38271;&#24230;&#19981;&#27491;&#30830;&#65281;</span>");
                    $("#label_mobile_key").css('display','none'); 
                }else{
                    $("#label_mobile_key").show();
                    $("#msg_mobile_key").html("");
                };
            });
        });
