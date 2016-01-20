<div class="container">
    <div class="row">
    	<div class="col-md-12 text-center">
    		<img src="data/img/pionier-icon.png" id="pionier-icon" />
    		<div id="registrationmsg">
    		<?php 
    			if(request::get(1) == 'activation')
    			{
    				session_destroy();
    				unset($_COOKIE['auth_cookie']);
    				
    				$user = login::activate(); 
	    			if($user->errmsg == '') 
	    				echo '<div class="activationmessage">Account aktiviert. bitte anmelden.</div>'; 
	    			else 
	    				echo $user->errmsg; 
	    		}
	    		else
	    			$user = new user();
    		?>
    		</div>
    	</div>
    </div>

    <div class="row">
    	<div class="col-md-4 col-md-offset-1 text-center" id="login">
	    	<h2>Bereits registriert? <br />Hier einloggen!</h2>
	    	<form id="form_login" method="POST">
		        <input required="required" placeholder="E-Mail-Adresse" name="mail" type="text" id="mail" value="<?php if(request::get(1) == 'activate' && $user->errmsg == '') echo $user->get('mail'); ?>">
		        <input required="required" placeholder="Passwort" name="password" type="password" id="password"<?php if(request::get(1) == 'activate' && $user->errmsg == '') echo 'autofocus'; ?>>
		        <div class="checkbox">
				    <label>
				    	<input type="checkbox" id="storelogin"> Anmeldung speichern? 
				    </label>
		  		</div>
		        <input value="Anmelden" type="submit">
		        <span class="" style="display: none;"></span>
		        <div class="row">
			    	<div class="col-xs-12" class="">
			    		<span id="errorbox_login">
			    			 FEHLER
			    		</span>
			    		<img src="data/img/ajax-loader.gif" class="ajaxloader" />
			    	</div>
		    	</div>
		    </form>

    	</div>
    	
    	<div class="col-md-1"></div>

    	<div class="col-md-4 col-md-offset-1 text-center" id="register">
	    	<h2>Noch kein Pionier? <br />Jetzt registrieren und Pionier werden!</h2>
	    	<form id="form_register">
	    		<div class="input_container">
			        <input required="required" placeholder="Emailadresse*" id="mail" name="EMAIL" type="email" autocomplete="off">
			        <input placeholder="Passwort" id="password" type="password">
			        <input placeholder="Passwort wiederholen" id="password2" type="password">
			        <div class="checkbox">
					    <label>
					    	<input type="checkbox" id="storelogin"> Anmeldung speichern? 
					    </label>
			  		</div>
			        <input value="Registrieren" type="submit">
			    </div>
		        
		        <div class="row">
			    	<div class="col-xs-12" class="">
			    		<span id="errorbox_register"></span>
			    		<img src="data/img/ajax-loader.gif" class="ajaxloader" />
			    	</div>
		    	</div>
		    </form>
    	</div>

    	<div class="col-md-1"></div>
    </div>
</div>