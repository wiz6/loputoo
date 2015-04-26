<?php
require_once('config.php');
?>
<!DOCTYPE html>
<html>
<head>

    <title></title>
    <script src="<?=$config['cdn_uri']?>"></script>
    <script>
        $(document).ready(function() {
            $('#install').click(function(){
	            $('#msg-error').hide();
	            $('#msg-done').hide();
                $.ajax({
                    url:'lib/tracker.php?action=install',
                    data: {
                        database_config: {
                            db_name:$('#db_name').val(),
                            db_user:$('#db_user').val(),
                            db_password:$('#db_password').val(),
                            db_host:$('#db_host').val()
                        }
                    },
                    type:'POST'
                }).done(function(data){
	                if (!data) {
		                return;
	                }
	                data = JSON.parse(data);
	                if (!data.result) {
		                $('#msg-error').text('Error: '+data.msg).show();
		                return;
	                }
                    $('#msg-done').show();
                }).fail(function() {
                });
            });
        });
    </script>
</head>
<body>
<form method="POST">
    <div id="msg-done" style="display:none; background-color: #00994C; font-weight:bold; border: 1px solid green; width:100%; line-height: 50px; vertical-align: middle;">
        Install complete!
    </div>
	<div id="msg-error" style="display:none; background-color: #FF9999; font-weight:bold; border: 1px solid green; width:100%; line-height: 50px; vertical-align: middle;">
	</div>
    <br>
    This install creates database tables and saves your db settings.<br>
    Insert your database configuration:<br>
    <table>
        <tr><td>Database name: </td><td><input type="text" id="db_name"></td></tr>
        <tr><td>Database user: </td><td><input type="text" id="db_user"></td></tr>
        <tr><td>Database password: </td><td><input type="password" id="db_password"></td></tr>
        <tr><td>Database host: </td><td><input type="text" id="db_host"></td></tr>
        <tr><td colspan="2"><input type="button" value="Install" id="install"></td></tr>
    </table>
</form>
</body>
</html>