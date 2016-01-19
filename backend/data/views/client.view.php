<?php
$pioneer = new user(request::get(1));
?>
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
                  <li id="overview" class="first"><a href="start">< zur Übersicht</a></li>
                  <li role="presentation" class="active"><a href="#home">Kundenaccount</a></li>
                  <li role="presentation" class=""><a href="#gallery">Pionier-Bilder</a></li>
                  <li role="presentation" class="last"><a href="#messages">Support</a></li>
                  <li role="" id="logout" class="last"><a href="logout">Logout</a></li>
                </ul>

                <div class="tab-content">
                    <!-- TAB 1 BEGIN -->
                    <div role="tabpanel" class="tab-pane active" id="home" data-clientid="<?php echo $pioneer->get('id'); ?>">

                        <div class="row">
                            <div class="col-xs-12">
                                <h2>Kundendaten</h2>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-11">
                                <div class="form-group">
                                    <label for="firstname">Link zum ERP</label>
                                    <input type="text" class="form-control autosave" id="erp_link" value="<?php echo $pioneer->get('erp_link'); ?>">
                                </div>
                            </div>

                            <div class="col-xs-1 erp_wrap">
                                <a href="#" class="erplink" target="_blank"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <form>
                                    <div class="form-group">
                                        <label for="firstname">Vorname *</label>
                                        <input type="text" class="form-control autosave" id="firstname" value="<?php echo $pioneer->get('firstname'); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="lastname">Nachname *</label>
                                        <input type="text" class="form-control autosave" id="lastname" value="<?php echo $pioneer->get('lastname'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="street">Straße & Hausnummer *</label>
                                        <input type="text" class="form-control autosave" id="street" value="<?php echo $pioneer->get('street'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="zip">Postleitzahl *</label>
                                        <input type="text" class="form-control autosave" id="zip" value="<?php echo $pioneer->get('zip'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="city">Ort *</label>
                                        <input type="text" class="form-control autosave" id="city" value="<?php echo $pioneer->get('city'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="country">Land *</label>
                                        <input type="text" class="form-control autosave" id="country" value="<?php echo $pioneer->get('country'); ?>">
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
                                        <input type="text" class="form-control autosave" id="phone" placeholder="Telefonnummer" value="<?php echo $pioneer->get('phone'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="mail">E-Mail-Adresse</label>
                                        <input type="text" class="form-control autosave" id="mail" value="<?php echo $pioneer->get('mail'); ?>" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="birthdate">Geburtsdatum</label>
                                        <input type="text" class="form-control autosave" id="birthdate" value="<?php echo $pioneer->get('birthdate'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="biketype">Fahrradtyp</label>
                                        <input type="text" class="form-control autosave" id="biketype" value="<?php echo $pioneer->get('biketype'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="reseller">Händler deines Vertrauens</label>
                                        <input type="text" class="form-control autosave" id="reseller" value="<?php echo $pioneer->get('reseller'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="known_from">Woher kennst du RELO?</label>
                                        <textarea class="form-control autosave" id="known_from" rows="3" type="text"><?php echo $pioneer->get('known_from'); ?></textarea>
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
                                <div class="col-sm-12">
                                    <a href="#" class="btn btn-default" id="saveNewPassword">Neues Passwort zusenden</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- TAB 1 END -->

                    <!-- TAB 2 BEGIN -->
                    <div role="tabpanel" class="tab-pane" id="gallery">
                        
                        <?php
                            echo client::getImages($pioneer->get('id'));
                        ?>
                    </div>
                    <!-- TAB 2 END -->

                    <div role="tabpanel" class="tab-pane" id="messages">
                        <h3>Support</h3>
                        <p>
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam
                        </p>

                        <div class="chat">
                        <?php
                            echo client::getChat($pioneer->get('id'));
                        ?>
                        </div>

                        <div class="chatinput_wrap">
                                <form>
                                    <input type="text" id="chatinput" autocomplete="off" placeholder="Verfasse eine Nachricht..." autofocus>
                                </form>
                            </div>
                        <div class="clear"></div>  
                    </div>
                </div>
            </div>
        </div>
    </div>


