<?php
echo '
<div class="container">
      <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
          <div class="copyright text-center text-xl-left text-muted">
            &copy; 2019 <a href="https://falixnodes.host" class="font-weight-bold ml-1" target="_blank">FalixNodes</a>
          </div>
        </div>
        <div class="col-xl-6">
          <ul class="nav nav-footer justify-content-center justify-content-xl-end">
            <li class="nav-item">
              <a href="https://falixnodes.host/UserAgreementv4.pdf" class="nav-link" target="_blank">User Agreement</a>
            </li>
			&nbsp; &nbsp; &nbsp; &nbsp;
			<li class="nav-item">
						<!-- Rounded switch -->
						Light
						<label class="switch">
						  <input type="checkbox" id="themeSwitcher" onclick="themeswitch();">
						  <span class="slider round"></span>
						</label>
						Dark
			</li>
          </ul>
		  
        </div>
      </div>
</div>

<script>
	// theme handler
	function setCookie(cname, cvalue, exdays) {
	  var d = new Date();
	  d.setTime(d.getTime() + (exdays*24*60*60*1000));
	  var expires = "expires="+ d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
	
	function getCookie(cname) {
	  var name = cname + "=";
	  var decodedCookie = decodeURIComponent(document.cookie);
	  var ca = decodedCookie.split(\';\');
	  for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == \' \') {
		  c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
		  return c.substring(name.length, c.length);
		}
	  }
	  return "";
	}
	function ForceThemeCheck() {
		if( getCookie("theme") == "" ) {
			setCookie("theme", "light", 365);
			document.styleSheets[4].disabled = true;
			document.styleSheets[3].disabled = false;
			document.getElementById(\'themeSwitcher\').checked = false;
		} else {
			if( getCookie("theme") == "light" ) {
				document.styleSheets[4].disabled = true;
				document.styleSheets[3].disabled = false;
				document.getElementById(\'themeSwitcher\').checked = false;
			}
			if( getCookie("theme") == "dark" ) {
				document.styleSheets[3].disabled = true;
				document.styleSheets[4].disabled = false;
				document.getElementById(\'themeSwitcher\').checked = true;
			}
		}
	}
	function themeswitch() {
		if (document.getElementById(\'themeSwitcher\').checked) 
		{
			 setCookie("theme", "dark", 365);
			 ForceThemeCheck();
		} else {
			 setCookie("theme", "light", 365);
			 ForceThemeCheck()
		}
	}
	window.onload = ForceThemeCheck();
</script>
';