<div class="notifier">Daten gespeichert</div>

<div class="container">
    <div class="row spacer">
        <div class="col-sm-12">
            <h1>PIONIER BEREICH</h1>
        </div>
    </div>

    <div class="row whitebg">
        <div class="col-sm-12">

                <ul class="nav nav-tabs">
                  <li role="presentation" class="first active"><a href="#home">Mein Account</a></li>
                  <li role="presentation" class=""><a href="#gallery">Meine Bilder</a></li>
                  <li role="presentation" class="last"><a href="#messages">Support<span class="msgcounter"></span></a></li>
                  <li role="" id="logout" class="last"><a href="logout">Logout</a></li>
                </ul>

                <div class="tab-content">
                    <!-- TAB 1 BEGIN -->
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <div class="row">
                            <div class="col-xs-12">
                                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <h2>Profilbild</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 profilpic_container">
                                <?php 
                                    echo start::getProfilePic();
                                ?>
                            </div>

                            <div class="col-sm-6">
                            <div id="dropzoneform_profilepic" class="dropzone" method="POST" ></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <h2>Meine Daten</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
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
                                        <label for="street">Straße & Hausnummer</label>
                                        <input type="text" class="form-control autosave" id="street" value="<?php echo $this->user->data['street']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="zip">Postleitzahl</label>
                                        <input type="text" class="form-control autosave" id="zip" value="<?php echo $this->user->data['zip']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="city">Ort</label>
                                        <input type="text" class="form-control autosave" id="city" value="<?php echo $this->user->data['city']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="country">Land</label>
                                        <input type="text" class="form-control autosave" id="country" value="<?php echo $this->user->data['country']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="autosave" id="newsletter"<?php
                                                if($this->user->data['newsletter'] == 1)
                                                    echo ' checked';
                                                ?>> Newsletter abboniert
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-6">
                                <form class="">
                                    <div class="form-group">
                                        <label for="phone">Telefonnummer</label>
                                        <input type="text" class="form-control autosave" id="phone" placeholder="Telefonnummer" value="<?php echo $this->user->data['phone']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="mail">E-Mail-Adresse</label>
                                        <input type="text" class="form-control autosave" id="mail" value="<?php echo $this->user->data['mail']; ?>" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="birthdate">Geburtsdatum</label>
                                        <input type="text" class="form-control autosave" id="birthdate" value="<?php echo $this->user->data['birthdate']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="biketype">Fahrradtyp</label>
                                        <input type="text" class="form-control autosave" id="biketype" value="<?php echo $this->user->data['biketype']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="reseller">Händler deines Vertrauens</label>
                                        <input type="text" class="form-control autosave" id="reseller" value="<?php echo $this->user->data['reseller']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="known_from">Woher kennst du RELO?</label>
                                        <textarea class="form-control autosave" id="known_from" rows="3" type="text"><?php echo $this->user->data['known_from']; ?></textarea>
                                    </div>
                                </form>
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
                                    <a href="#" class="btn btn-default" id="saveNewPassword">Passwort speichern</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- TAB 1 END -->

                    <!-- TAB 2 BEGIN -->
                    <div role="tabpanel" class="tab-pane" id="gallery">
                        <div class="row" id="dropzonerow">
                            <div class="col-xs-12">
                                <div id="dropzoneform" class="dropzone" method="POST" ></div>
                            </div>
                        </div>
                        
                        <?php
                            echo start::getImages($_SESSION['user_id']);
                        ?>

                        
                    </div>
                    <!-- TAB 2 END -->

                    <div role="tabpanel" class="tab-pane " id="messages">
                        <h3>Support</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam
                        </p>

                        <div class="chat">
                        <?php
                            echo start::getChat();
                        ?>
                        </div>

                        <div class="chatinput_wrap">
                                <form>
                                    <input type="text" id="chatinput" autocomplete="off" placeholder="Verfasse eine Nachricht..." autofocus>
                                </form>
                            </div>

                        

                        <div class="clear"></div>  
                    </div>


                    <div role="tabpanel" class="tab-pane" id="settings">...</div>
                </div>
            </div>
        </div>
    </div>
