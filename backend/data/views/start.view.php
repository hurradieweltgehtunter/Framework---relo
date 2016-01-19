<div class="container">
    <div class="row spacer">
        <div class="col-sm-12">
            <div class="page-header">
              <h1>RELO PIONIERE<small> Administrationsbereich</small></h1>
            </div>
        </div>
    </div>

    <div class="row">
    	<div class="col-xs-12">
    		<div class="whitebg">
	    		<ul class="nav nav-tabs">
					<li role="presentation" class="first active"><a href="#overview">Kundenübersicht</a></li>
					<li role="presentation" class=""><a href="#account">Meine Account</a></li>
					<li role="" class="nav_clientsearch">
						<form class="form-inline">
							<div class="form-group">
						    	<label class="sr-only" for="exampleInputAmount">Suchbegriff eingeben</label>
						    	<div class="input-group">
						      		<div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
						      		<input type="text" class="form-control" id="clientsearch" placeholder="Suchbegriff eingeben">
						    	</div>
						  	</div>
                            <a href="#" data-placement="bottom" data-toggle="popover" title="Suchoptionen" data-content="-Suchbegriff: Schliesst den Suchbegriff der Suche aus. <br />%Suchbegriff: Such nach Einträgen die auf 'Suchbegriff' enden">?</a>
                            <img class="ajax-loader" src="data/img/ajax-loader.gif" />
						</form>
					</li>
					<li role="" id="logout" class="last"><a href="logout">Logout</a></li>
				</ul>

	    		<div class="tab-content ">
	            	<!-- TAB 1 BEGIN -->
	            	<div role="tabpanel" class="tab-pane active" id="overview">
			    		<div class="table-responsive">
							<table class="table table-striped table-hover" id="clients">
								<thead> 
									<tr> 
										<th>Vorname</th> 
										<th>Nachname</th> 
										<th>Stadt</th> 
										<th>Telefon</th> 
										<th>E-Mail</th> 
										<th class="tablesorter-noSort">&nbsp;</th>
									</tr> 
								</thead>
								<tbody>
								<?php
									echo start::getTable();
								?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- TAB 1 END -->

					<!-- TAB 2 BEGIN -->
	                <div role="tabpanel" class="tab-pane" id="account">
                        <div class="row">
                            <div class="col-sm-6">
                            	<h2>Meine Daten</h2>
                                <form>
                                    <div class="form-group">
                                        <label for="firstname">Vorname</label>
                                        <input type="text" class="form-control autosave" id="firstname" value="<?php echo $this->user->data['firstname']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="lastname">Nachname</label>
                                        <input type="text" class="form-control autosave" id="lastname" value="<?php echo $this->user->data['lastname']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="mail">E-Mail-Adresse *</label>
                                        <input type="text" class="form-control autosave" id="mail" value="<?php echo $this->user->data['mail']; ?>">
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-6">
                            	<h2>Profilbild</h2>
                                <div class="profilepic_container">
	                                <?php 
	                                    echo start::getProfilePic();
	                                ?>
	                            </div>
                                <div id="dropzoneform_beprofilepic" class="dropzone" method="POST" ></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <h2>Neues Passwort</h2>
                            </div>
                        </div>

                        <div class="row">
                            <form id="newPassword" method="POST">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password_new">Neues Passwort</label>
                                        <input type="password" class="form-control" id="password_new" value="">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password_new2">Neues Passwort wiederholen</label>
                                        <input type="password" class="form-control" id="password_new2" value="">
                                    </div>
                                    <button type="submit" href="#" class="btn btn-default" id="saveNewPassword">Passwort speichern</button>
                                </div>
                            </form>
                        </div>
					</div>
					<!-- TAB 2 END -->
				</div>
			</div>
    	</div>
    </div>
</div>