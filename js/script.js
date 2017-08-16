$(document).ready(function(){
    var followers_info = "";
    function fetchUserInfo() {
        $.ajax({
            url: './controller.php?userdata=true',
            dataType: 'json',
            type: 'GET',
            success: function(results) {
                $("#name_user").html(results.name);
                $("#user_pic").attr('src',results.propic);
                $("#name_user_left").html(results.name);
                $("#user_pic_left").attr('src',results.propic);
                $("#name_user_mid").html(results.name);
                $("#user_pic_mid").attr('src',results.propic);
                $('#followers-names').attr('data-value',results.screen_name);
                var list = '';
                var length = results.tweets.length;
                length = (length>=10) ? 10 : length;
                for(i=0;i<length;i++) {
                    if(i==0){
                        list += '<div class="item active" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    }
                    else {
                        list += '<div class="item" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    }

                }
                $('.carousel-inner').html(list);
                if(followers_info=="")
                    followers_info = results.followers[0].users;
                length = results.followers[0].users.length;
                length = (length>=10) ? 10 : length;
                list = "";
                for(i=0;i<length;i++) {
                    var id = results.followers[0].users[i].screen_name;
                    var anchor = "<a class='followers-name' data-value='" + id + "' >&nbsp;&nbsp;&nbsp;" + results.followers[0].users[i].name + "</a>";
                    list += "<div class='col-md-12 follower'>" + "<img src='" + results.followers[0].users[i].profile_image_url  + "' "+ " style='border-radius:50%' />" + anchor + "</div>";
                }
                $('#followers').html(list);
            }
        });
    }
    fetchUserInfo();
    $(document.body).on('click', '.followers-name', function(){
        var id = $(this).attr('data-value');
        $.ajax({
            url: './controller.php?followers=true&usr_id='+id,
            dataType: 'json',
            type: 'GET',
            success: function(results) {
                var name = results.name;
                $("#name_user_mid").text(name);
                $("#user_pic_mid").attr('src',results.propic);
                var list = '';
                var length = results.tweets.length;
                length = (length>=10) ? 10 : length;
                for(i=0;i<length;i++) {
                    if(i==0){
                        list += '<div class="item active" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    }
                    else {
                        list += '<div class="item" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    }
                }
                list = (length==0) ? '<br />' : list;
                $('.carousel-inner').html(list);
            }
        });
    });
    $(document).on('input', '#searchbox', function() {
        var data = $(this).val();
        var list = '';
        if( data != "" ) {
            var length = followers_info.length;
            var pattern = new RegExp("^.*"+data+".*$",'i');
            for( i=0;i<length;i++ ) {
                var name = followers_info[i].name;
                if (pattern.test(name) == true) {
                    var id = followers_info[i].screen_name;
                    var anchor = "<a class='followers-name' data-value='" + id + "' >&nbsp;&nbsp;&nbsp;" + name + "</a>";
                    list += "<div class='col-md-12 follower'>" + "<img src='" + followers_info[i].profile_image_url  + "' "+ " style='border-radius:50%' />" + anchor + "</div><br /><br />";
                }
            }
        }
        $('#search').html(list);
    });
});