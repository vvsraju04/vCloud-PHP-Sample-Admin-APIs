<html>
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.1/semantic.min.css"></link>
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.1/semantic.min.js"></script>
    <style>
    body{
       padding-top:30px 
    }
    </style> 
</head> 
<body>
<div class="ui container segment">
    <h2 class="ui row header">vCloud Director Sample Admin API Console </h2>
    <div class="ui divider"></div>
    <div class="ui row compact message" id="msg">
      <b>Welcome</b> ! Enter vCloud Director server IP/hostname , login username an password <!-- with certificate (if any )-->
    </div>
	<div class="ui top attached tabular menu">
      <a class="active item" data-tab="first">Session Credentials</a>
      <a class="item" data-tab="second">Admin Tasks</a>
    </div>
    <div class="ui bottom attached active tab segment" data-tab="first">
      <div class="ui form">
        <div class="required field">
          <label>vCloud Director server IP/hostname</label>
          <input name="server" id="server" placeholder="IP/hostname" type="text">
        </div>
        <div class="required field">
          <label>vCloud Director login username</label>
          <input name="username" id="username" placeholder="Username" type="text">
        </div>
        <div class="required field">
          <label>vCloud Director login password</label>
          <input name="password" id="password" placeholder="*********" type="password">
        </div>
        <div class="ui labeled icon primary submit button" id="save_cred"><i class="save icon"></i>Save</div>
        <div class="ui labeled icon secondary reset button" ><i class="refresh icon"></i>Reset</div>
      </div>
    </div>
    <div class="ui bottom attached tab segment" data-tab="second">
     <div class="ui four column grid"> <div class="row">
      <div id="admin_tasks" class="ui one column wide inverted vertical pointing menu">
        <a id="getRoles" class="item">
          Get Roles
        </a>
        <a id ="getSystemOrg-getUsers" class="item">
          Get System Org Users
        </a>
        <a id ="getSystemOrg-getGroups" class="item">
          Get System Org Groups
        </a>
        <a id="getRights" class="item">
          Get Rights
        </a>
      </div>
      <div class="three column wide segment" >Result:</div>
     </div></div>
    </div>
</div>
<Script>
$('.menu .item')
.tab()
;
function setFieldsInStorage()
{
    if($("#server").val() == "" || $("#server").val() == ""  || $("#password").val() == "" )
    {
        $("#msg").removeClass("success");
        $("#msg").addClass("negative");
        $("#msg").html("<b>vCloud Director Credentials are not completely submitted.</b>");
        return;
    }
    //Set server / username / password in browser html5 web storage
    localStorage["server"]=$("#server").val();
    localStorage["username"]=$("#server").val();
    localStorage["password"]=$("#password").val();
}
function getFieldFromStorage(field){
    return localStorage[field];
}
$("#admin_tasks a").click(
    function(e)
    {
        $("#admin_tasks a").removeClass("active");
        $(this).addClass("active");
        server=getFieldFromStorage("server");
        username=getFieldFromStorage("username");
        password=getFieldFromStorage("password");
        if(typeof(server) == "undefined" || typeof(username) == "undefined"  || typeof(password) == "undefined" ||server == "" || username == ""  || password == ""   )
        {
            $("#msg").removeClass("success");
            $("#msg").addClass("negative");
            $("#msg").html("<b>vCloud Director Credentials are not completely submitted.</b>");
            return;
        }
        method=this.id;
        sub_method="";
        if(method.split("-")[1] !== undefined )
        {
            sub_method=method.split("-")[1];
            method=method.split("-")[0];
        }
        console.log("here");
        task="Admin";
        method_desc=$(this).text();
        var jqxhr = $.ajax( "admin.php?method="+method+"&task"+task+"&server="+server+"&username="+username+"&password="+password)
        .done(function(jsonArr) {
          $("#msg").removeClass("negative");
          $("#msg").addClass("success");
          $("#msg").html("Success ! "+method_desc +" executed with results" );
        })
        .fail(function() {
            $("#msg").removeClass("success");
            $("#msg").addClass("negative");
            $("#msg").html("Failure ! "+method_desc +" execution failed " );
        });
    }
);
$("#save_cred").click(setFieldsInStorage);
$('.ui.form')
.form({
  fields: {
    username : 'empty',
    server   : 'empty',
    password : 'empty',
    on       :'blur'
  }
})
;
</Script>
</body>
</html>