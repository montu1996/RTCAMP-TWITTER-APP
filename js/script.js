$(document).ready(function() {
    var followers_info = "";
    var screen_name = "";

    function fetchUserInfo() {
        $.ajax({
            url: './controller.php?userdata=true',
            dataType: 'json',
            type: 'GET',
            success: function(results) {
                $("#name_user").html(results.name);
                $("#user_pic").attr('src', results.propic);
                $("#name_user_left").html(results.name);
                $("#user_pic_left").attr('src', results.propic);
                $("#name_user_mid").html(results.name);
                $("#user_pic_mid").attr('src', results.propic);
                $('#followers-names').attr('data-value', results.screen_name);
                var list = '';
                var length = results.tweets.length;
                length = (length >= 10) ? 10 : length;
                screen_name = results.screen_name;
                for (i = 0; i < length; i++) {
                    if (i == 0) {
                        list += '<div class="item active" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    } else {
                        list += '<div class="item" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    }

                }
                $('.carousel-inner').html(list);
                $('#myCarousel').carousel();
                fetchFollowersInfo();
            }
        });
    }

    function fetchFollowersInfo() {
        $.ajax({
            url: './controller.php?fetchFollowers=' + screen_name,
            dataType: 'json',
            type: 'GET',
            success: function(results) {
                var list = "";
                var length = results.followers.length;
                followers_info = results.followers;
                length = (length >= 10) ? 10 : length;
                for (i = 0; i < length; i++) {
                    var id = results.followers[i].screen_name;
                    var anchor = "<a class='followers-name' data-value='" + id + "' >&nbsp;&nbsp;&nbsp;" + results.followers[i].name + "</a>";
                    list += "<div class='col-md-12 follower'>" + "<img src='" + results.followers[i].propic + "' " + " style='border-radius:50%' />" + anchor + "</div>";
                }
                $('#followers').html(list);
            }
        });
    }

    $(document.body).on('click', '.followers-name', function() {
        var id = $(this).attr('data-value');
        $.ajax({
            url: './controller.php?followers=true&usr_id=' + id,
            dataType: 'json',
            type: 'GET',
            success: function(results) {
                $('.carousel-inner').html("");
                var name = results.name;
                $("#name_user_mid").text(name);
                $("#user_pic_mid").attr('src', results.propic);
                var list = '';
                var length = results.tweets.length;
                length = (length >= 10) ? 10 : length;
                for (i = 0; i < length; i++) {
                    if (i == 0) {
                        list += '<div class="item active" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    } else {
                        list += '<div class="item" style="height:100px"><br /><b>' + results.tweets[i].text + '</b></div>';
                    }
                }
                list = (length == 0) ? '<br />' : list;
                $('.carousel-inner').html(list);
                $('#myCarousel').carousel();
            }
        });
    });
    $(document).on('input', '#searchbox', function() {
        var data = $(this).val();
        var list = '';
        if (data != "") {
            var length = followers_info.length;
            var pattern = new RegExp("^.*" + data + ".*$", 'i');
            for (i = 0; i < length; i++) {
                var name = followers_info[i].name;
                if (pattern.test(name) == true) {
                    var id = followers_info[i].screen_name;
                    var anchor = "<a class='followers-name' data-value='" + id + "' >&nbsp;&nbsp;&nbsp;" + name + "</a>";
                    list += "<div class='col-md-12 follower'>" + "<img src='" + followers_info[i].propic + "' " + " style='border-radius:50%' />" + anchor + "</div><br /><br />";
                }
            }
        }
        $('#search').html(list);
    });
    fetchUserInfo();
});