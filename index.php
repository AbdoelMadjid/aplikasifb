<?php
    //Konfigurasi untuk aplikasi facebook 
    define('APP_ID', '601245019919518'); 
    define('APP_SECRET', '12fbe805313cb80a6d7ec7f8b8d0d555'); 
    define('MAIN_URL', 'http://saturadio.comoj.com/index.php/'); 
    define('APP_URL', 'http://apps.facebook.com/pilihdini/'); 

    if (isset($_GET['code'])){
        header("Location: " . APP_URL);
        exit;
    }

    $user = null; 
    try{
        include_once "facebook.php";
    }
    catch(Exception $o){
        echo '<pre>';
        print_r($o);
        echo '</pre>';
    }
    // Create our Application instance
    $facebook = new Facebook(array(
      'appId'  => APP_ID,
      'secret' => APP_SECRET,
      'cookie' => true,
    ));

    // Mendapatkan user ID
    $user = $facebook->getUser();
   
    $loginUrl = $facebook->getLoginUrl(
            array(
                'scope' => 'publish_stream,read_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown'
            )
    );

    if ($user) {
      try {
        // Mengetahui user Login atau tidak
        $user_profile = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
      }
    }

    if (!$user) {
        echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
        exit;
    }
    
    //Mendapatkan deskripsi user
    $userInfo = $facebook->api("/$user");

    function d($d){
        echo '<pre>';
        print_r($d);
        echo '</pre>';
    }
    
    if ($user){
        //Contoh fql query mengunakan legacy method dan pengoperasian parameter
        //Selengkapnya lihat http://developers.facebook.com/docs/reference/fql/
        try{
            $fql = "select name, hometown_location, sex, pic_square from user where uid=" . $user;
          
            $param = array(
                'method' => 'fql.query',
                'query' => $fql,
                'callback' => ''
            );
            $fqlResult = $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
    }
?>    
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Aplikasi Update Status via BlackBerry</title>
        <script type="text/javascript">
        /* Tab Menu */        
        var tabLinks = new Array();
        var contentDivs = new Array();

        function navtabs() {

        var tabListItems = document.getElementById('tabs').childNodes;
        for ( var i = 0; i < tabListItems.length; i++ ) {
        if ( tabListItems[i].nodeName == "LI" ) {
          var tabLink = getFirstChildWithTagName( tabListItems[i], 'A' );
          var id = getHash( tabLink.getAttribute('href') );
          tabLinks[id] = tabLink;
          contentDivs[id] = document.getElementById( id );
        }
        }
        var i = 0;
        for ( var id in tabLinks ) {
        tabLinks[id].onclick = showTab;
        tabLinks[id].onfocus = function() { this.blur() };
        if ( i == 0 ) tabLinks[id].className = 'selected';
        i++;
        }

        var i = 0;
        for ( var id in contentDivs ) {
        if ( i != 0 ) contentDivs[id].className = 'tabContent hide';
        i++;
        }
        }

        function showTab() {
              var selectedId = getHash( this.getAttribute('href') );

        for ( var id in contentDivs ) {
          if ( id == selectedId ) {
            tabLinks[id].className = 'selected';
            contentDivs[id].className = 'tabContent';
        } else {
          tabLinks[id].className = '';
          contentDivs[id].className = 'tabContent hide';
          }
        }

        return false;
        }

        function getFirstChildWithTagName( element, tagName ) {
        for ( var i = 0; i < element.childNodes.length; i++ ) {
          if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
          }
        }

        function getHash( url ) {
        var hashPos = url.lastIndexOf ( '#' );
        return url.substring( hashPos + 1 );
        }

        window.onload=function(){
        navtabs();
        }
        </script>
        <style type="text/css">
        body {
            background: #fff;
            font-family: "Lucida Grande", Tahoma, Verdana, Arial, sans-serif;
            font-size: 11px;
            margin: 2px;
           padding: 0px;
            text-align: left;
        }
        a{
                text-decoration: none;
                color: blue;
            }
        a:hover{
                text-decoration: underline;
                color: olive;
            }
        h1, h2, h3, h4, h5 {
            font-size: 13px;
           color: #333;
            margin: 0px;
            padding: 0px;
        }
    h1 { font-size: 14px; }
    h4, h5 { font-size: 11px; }

        #tabs{
        font-size: 100%;
        }
    
        .fb-tabs {
            border-bottom: 1px solid #898989;
            padding: 16px 0px; /* top and bottom, left and right */
        }
        .fb-tabs .left_tabs {
            float: left;
            padding-left: 10px;
        }
        .fb-tabs .right_tabs {
            float: right;
            padding-right: 10px;
        }

        .fb-tabitems {
            display: inline;
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .fb-tabitems li {
            display: inline;
            padding: 2px 0px 3px;
           background: #f1f1f1 url(http://www.facebook.com/images/components/toggle_tab_gloss.gif) top left repeat-x;
        }

        .fb-tabitems li a {
            border: 1px solid #898989;
            color: #333;
            font-weight: bold;
            padding: 2px 8px 3px 9px;
        }

        .fb-tabitems li a small {
            font-size: 11px;
            font-weight: normal;
        }

        .fb-tabitems li a:focus {
            outline: 0px;
        }

        .fb-tabitems li.first a {
            border:1px solid #898989;
        }

        .fb-tabitems li a.selected  {
            background: #6d84b4;
            border: 1px solid #3b5998;
            border-left: 1px solid #5973a9;
            border-right: 1px solid #5973a9;
            color: #fff;
            margin-left: -1px;
        }

        .fb-tabitems li.last a.selected {
            margin-left:-1px;
            border-left:1px solid #5973a9;
            border-right:1px solid #36538f;
        }

        .fb-tabitems li.first.last a.selected {
            border: 1px solid #36538f;
        }

        .fb-tabitems li a.selected:hover {
            text-decoration: none;
        }
        .inputbutton {
        border-style: solid;
        border-top-width: 1px;
        border-left-width: 1px;
        border-bottom-width: 1px;
        border-right-width: 1px;
        border-top-color: #D9DFEA;
        border-left-color: #D9DFEA;
        border-bottom-color: #0e1f5b;
        border-right-color: #0e1f5b;
        background-color: #3b5998;
        color: #ffffff;
        font-size: 11px;
        font-family: "Lucida Grande", Tahoma, Verdana, Arial, sans-serif;
        padding: 2px 15px 3px 15px;
        text-align: center;
        }
        .fberrorbox {  
        background-color: #ffebe8;  
        border: 1px solid #dd3c10;  
        color: #333333;  
        padding: 10px;  
        font-size: 13px;  
        font-weight: bold;  
        }
        .fbbluebox  
        {  
        background-color: #eceff6;  
        border: 1px solid #d4dae8;  
        color: #333333;  
        padding: 10px;  
        font-size: 13px;  
        font-weight: bold;  
        }  
        #tab-1,#tab-2,#tab-3,#tab-4,#tab-5 {padding: 10px 5px}

        div.tabContent.hide { display: none; }
        </style>
        <script type="text/javascript">

            function JSupdateStatus(){
                var status = document.getElementById('status').value;
                    FB.api('/me/feed', 'post', { message: status }, function(response) {
                     if (response && response.post_id) {
                             document.getElementById('info-2').innerHTML = "<div align='center' class='fberrorbox' style='width:200px'>Update Error</div>";
                        } else {
                             document.getElementById('info-2').innerHTML = "<div align='center' class='fbbluebox' style='width:200px'>Update Sukses</div>";
                        }
                   });
            }
                       
            function streamPublish(name, description, hrefTitle, hrefLink){        
                FB.ui({ 
                  method: 'feed',
                  message: '',
                  name: name,
                  caption: 'APLIKASI UPDATE STATUS VIA BLACKBERRY',
                  description: (description),
                  link: '<?=APP_URL?>',
                  picture: 'http://i944.photobucket.com/albums/ad281/ariadiforester/blackberry.jpg',
                  actions: [
                       { name: hrefTitle, link: hrefLink }
                  ]
                 },
                   function(response) {
                     if (response && response.post_id) {
                       document.getElementById('info-1').innerHTML = "<div align='center' class='fbbluebox' style='width:200px'>Telah diterbitkan</div>";
                     } else {
                       document.getElementById('info-1').innerHTML = "<div align='center' class='fberrorbox' style='width:200px'>Batal diterbitkan</div>";
                     }
                   }
                 );
                    //Selengkapnya lihat http://developers.facebook.com/docs/reference/dialogs/feed/
            }
            function publishStream(){
                streamPublish("APLIKASI UPDATE STATUS", 'Silakan update statusmu, keep enjoy..', 'See me more', '<?=APP_URL?>');
            }

            function inviteFriends(){
                 var receiverUserIds = FB.ui({ 
                        method : 'apprequests',
                        message: 'Ready... Set... Go... check this app',
                 },
                 function(receiverUserIds) {
                          console.log("IDS : " + receiverUserIds.request_ids);
                        }
                 );
                 //Selengkapnya lihat http://developers.facebook.com/docs/reference/dialogs/requests/
            }

        </script>
    </head>

<body>
    <div id="fb-root"></div>
    <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
     <script type="text/javascript">
       FB.init({
         appId  : '<?=APP_ID?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML        
       });
     </script>
<br>          
    <h1><center><font color='Blue'>APLIKASI UPDATE STATUS VIA BLACKBERRY</color></center></h1>

<div class="fb-tabs clearfix">
    <center>
        <div class="left_tabs">
        <ul id="tabs" class="fb-tabitems clearfix">
        <li><a href="#tab-1"><span>Update Status</span></a></li>
        <li><a href="#tab-2"><span>Kirim ke Dinding</span></a></li>
        <li><a href="#tab-3"><span>Radio Online</span></a></li>
        <li><a href="#tab-4"><span>Kritik dan Saran</span></a></li>
        </ul>
        </div>
        <div class="right_tabs">
            <ul class="fb-tabitems clearfix">
                <li><a href="#tab-5" onclick="inviteFriends(); return false;"><span>Undang Teman</span></a></li>
            </ul>
        </div>
    </center>
</div>

    <div class="tabContent" id="tab-1">
        <?php
        $usersts = $facebook->api('/me/statuses?limit=1');           
        ?>
        <form name="statusUpdate_form" action="index.php" method="post">
        <table>
        <tr>
        <td rowspan="2"><img src="http://graph.facebook.com/<?=$user?>/picture" alt="user photo" /></td>
        <td valign="top" width="250"><strong><blink>Status Terakhir :</blink></strong></td>
        </tr>
        <tr>
        <td valign="top" width="250"><?php echo $usersts["data"][0]["message"] ?></td>
        </tr>
        </table>
        <div id="info-2"></div>  
            <textarea name="statusUpdate" id="status" rows="4" cols="40"></textarea>
            <br />
            <input type="button" class="inputbutton" onclick="JSupdateStatus(); return false;" value="Update Status" />
        </form>
    </div>

    <div class="tabContent" id="tab-2">
        <div id="info-1"></div>
        <input type="button" class="inputbutton" onclick="publishStream(); return false;" value="KIRIM KE DINDING KLIK DI SINI" />
    </div>

    <div class="tabContent" id="tab-3">
    <center><iframe src="http://itech-center.co.cc/player/radio.htm" width="236" height="279" scrolling="no" frameborder="0"></iframe></center>
    </div>
    <div class="tabContent" id="tab-4">
    <div class="fb-comments" data-href="www.facebook.com/pages/Aneka-Informasi/259651547448296" data-num-posts="5" data-width="750"></div>
    </div>
</div>
<br>
<center>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</center>
<center><script type='text/javascript' src='http://www.mahesajenar.com/scripts/ayat.js?t=5'></script></center>
<center>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</center>
<center><iframe allowtransparency='true' frameborder='0' height='17' id='NewsWindow' marginheight='0' marginwidth='0' scrolling='no' src='http://www.elshinta.com/v2003a/scrolling.htm' style='background-color: transparent;' width='750'></iframe></center>
<br>
<h3><center>Developed by <a href='http://lidapha.blogspot.com/' target='_blank' title='Visit me'>Ariadi Forester</a></center></h3>
    </body>
</html>
