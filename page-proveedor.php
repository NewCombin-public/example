<?php
include $_SERVER['DOCUMENT_ROOT'] . '/web-summit/wp-load.php';
$site_state = get_site_state();

// Go back if supplir name is empty
if ( empty( $_GET['name'] ) ) {
    wp_redirect( site_url() );
    exit();
}

$supplier = new Supplier( $is_summit = true );
$args = array(
    'slug' => sanitize_title( $_GET['name'] ),
    'limit' => 1,
    'fields' => 'all',
);
$supplier->query( $args );

// Go back if supplir doesn't exist
if ( ! $supplier->the_supplier() ) {
    wp_redirect( site_url() );
    exit();
}

// Load the supplier conference
$conference = new Conference();
$args = array(
    'sponsor_id' => $supplier->id,
);
$conference->query( $args );
$has_conference = $conference->the_conference();

get_header(); 
?>
<script type="text/javascript">
(function(){
    const idUsuario = '<?php echo get_user_id() ?>';
    const idAccion = 72; 
    const idMaterial = <?php $supplier->the_ID() ?>;
    const textoOpcional = 'pageview proveedor  <?php $supplier->the_name() ?>';
    trackingPageView(idUsuario, idAccion, idMaterial, textoOpcional);
})();
</script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/css/lobby-main.css?v=32">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/css/proveedor.css?v=19">

<div id="supplier-id" aria-supplier="<?php $supplier->the_ID() ?>"></div>

<div class="main-margin" style="padding-top: 30px;"></div>

<!-- VOLVER -->
<div class="container-xl volver d-flex align-items-center">
    <img src="<?php echo get_template_directory_uri() ?>/assets/img/newBackArrow-removebg.png" style="vertical-align: middle; height: 16px; margin-right: 6px;"> &nbsp;
    <a href="<?php echo site_url('/#proveedores') ?>" style="vertical-align: top; ">
        <span class="volverText">Todos los proveedores</span>
    </a>
</div>

<!-- TOP BOXES -->

<div class="container topBoxes">
    <div class="row justify-content-center">
        <!-- BOX 1 -->
        <div class="col-12 col-lg-4 box1">
            <span class="darkTitle marca title-min-height" style="font-family: National-Bold !important;"><?php $supplier->the_name() ?></span>
            <div class="d-block d-lg-none" style="height: 20px;">
            </div>
            <div class="square reduceOnSmall d-flex justify-content-center align-items-center p-3 product-border">
                <a href="<?php $supplier->the_landing_link() ?>" target="_blank" style="display: contents">
                    <img src="<?php $supplier->the_image() ?>" style="width: 100%; max-width: 80%; max-height: 85%;">
                </a>
            </div>
            <a href="<?php if ( $supplier->has_landing_link() ) $supplier->the_landing_link(); else $supplier->the_site(); ?>" target="_blank"><p class="blueTitle biggerText"><?php $supplier->the_site_visible() ?></p></a>

                <table class="tableSocial">
                    <tbody>
                        <tr style="float:left">
                            <?php
                            foreach ( $supplier->get_social() as $name => $url ) : ?>
                                <td>
                                    <a href="<?php echo $url; ?>" target="_blank"> 
                                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/<?php echo $name; ?>.svg" width="32px" style="margin-right: 5px;" aling="center">
                                    </a>
                                </td>
                                <?php
                            endforeach;
                            ?>
                        </tr>
                    </tbody>
                </table>

                        
            <div class="textContainer">
                <span style="color: #0c369c;">
                <?php $supplier->the_description(); ?>
                </span>
            </div>
            
            <span class="darkSubTitle categoSmall">Categorías</span>
            
            <div class="textContainer" style="margin-top: 20px; line-height: 1.2; font-weight: bold; color: #00b2e3; font-size: 15px;">
                <table border-collapse:="" collapse;="">
                    <tbody>
                        <?php
                        foreach ( $supplier->get_categories() as $category ) : ?>
                        <tr>
                            <td>
                                <img src="<?php echo get_template_directory_uri() ?>/assets/img/bulletPoint.svg" width="14px">
                            </td>
                            <td width="5%"></td>
                            <td>
                                <span><?php echo $category['name'] ?></span>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr>
                            <td height="10px" colspan="3"></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- BOX 2-->
        <?php 
        $products = $supplier->get_products();
        if ( ! empty( $products ) ) : 
        ?>
        <div class="col-12 col-lg-4 topBox2 box2">
            <span class="darkTitle title-min-height" style="font-family: National-Bold !important;">Productos destacados</span>

            <div class="<?php echo count( $products ) > 1 ? 'splide' : 'mt-5 d-flex justify-content-center' ?>">
                <div class="splide__track product-border">
                    <div class="<?php echo count( $products ) > 1 ? 'splide__list' : '' ?>">
                        <?php
                        foreach ( $products as $product ) : ?>
                        <div class="splide__slide" style="width: 350px;">
                            <div>
                                <div style="width: 100%">
                                    <a href="<?php echo $product['images'][0] ?>" rel="product_images">
                                        <img src="<?php echo $product['images'][0] ?>" class="sliderImg" style="height: 260px; object-fit:contain;">
                                    </a>
                                </div>
                                <div style="width: 100%; background-color: #f6f6f6; padding: 30px; min-height:250px">
                                    <div style="min-height:45px">
                                        <span class="blueTitle" style="font-size: 21px;"><?php echo $product['name'] ?></span>
                                    </div>
                                    <div class="textContainer" style="margin-top: 15px;">
                                        <span>
                                            <?php echo $product['description'] ?>
                                        </span>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
        <!-- BOX 3-->
        <div class="col-12 col-lg-4 box3">
            <span class="darkTitle hideOnMobile title-min-height" style="font-family: National-Bold !important;">Contacto</span>
            <iframe width="100%" height="180" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" class="square hideOnMobile" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDHna7sLBJj7jib8VPe3u9BABXcSGcpRIY&q=<?php echo urlencode( $supplier->get_the_map_location() ) ?>"></iframe>
            <span class="darkSubTitle direccion">Dirección</span>
            <div class="textContainer" style="margin-top: 10px;">
                <span>
                    <?php $supplier->the_address() ?>
                </span>
            </div>
            <iframe width="100%" height="180" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" class="square hideOnDesk mt-3" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDHna7sLBJj7jib8VPe3u9BABXcSGcpRIY&q=<?php echo urlencode( $supplier->get_the_map_location() ) ?>"></iframe>
            <div class="centered-div">
                <div class="switch_box box_1">
                    <table width="100%">
                        <tbody><tr>
                            <td width="80%">
                                <span class="whiteText fontSize">Dejar tarjeta</span>
                            </td>
                            <td width="5%">
    
                            </td>
                            <td width="15%">
                                <input type="checkbox" id="check-card" class="switch_1 check-supplier" aria-action="leaveCard" aria-action-text="Dejar tarjeta" aria-supplier="<?php $supplier->the_ID() ?>" aria-logged-in="<?php echo is_user_logged_in() ? 'true' : 'false' ?>">
                            </td>
                        </tr>
                    </tbody></table>
                </div>
            </div>

            <div class="textContainer">
                <span>
                Enviaremos tus datos a este proveedor para que se contacte contigo.
                </span>
            </div>
            <div class="centered-div">
                <div class="switch_box box_1">
                    <table width="100%">
                        <tbody><tr>
                            <td width="80%">
                                <span class="whiteText fontSize">Marcar Proveedor</span>
                            </td>
                            <td width="5%">
    
                            </td>
                            <td width="15%">
                                <input type="checkbox" id="check-mark" class="switch_1 check-supplier" aria-action="markSupplier" aria-action-text="Marcar proveedor" aria-supplier="<?php $supplier->the_ID() ?>"  aria-logged-in="<?php echo is_user_logged_in() ? 'true' : 'false' ?>">
                            </td>
                        </tr>
                    </tbody></table>
                </div>
            </div>

            <div class="textContainer">
                <span>
                Te enviaremos un correo electrónico con el link de este proveedor. 
                </span>
            </div>
            <?php
                $link = 'mailto:' . $supplier->get_contact_email() . '?subject=Consulta&body=Me pongo en contacto con ustedes ya que ingresé al Directorio de proveedores THE LOGISTICS WORLD y deseo información sobre los productos y servicios que ustedes brindan, gracias.';
                if ( $supplier->has_landing_link() ) {
                    $link = $supplier->get_the_landing_link();
                }
            ?>
            <a href="<?php echo $link; ?>" target="_blank" class="btn btn-ws-secondary d-flex justify-content-center align-items-center" style="min-width: 100%; margin-top: 30px; font-family: 'National';"  data-tracking-accion="75" data-tracking-material="<?php $supplier->the_ID() ?>" data-tracking-texto="proveedor <?php $supplier->the_name() ?>" onclick="tracking(event)">
                Contáctenos
            </a>
        </div>
    </div>
    
</div>

<?php if ( $has_conference ) : ?>
<!-- CONFERENCIA -->
<div class="container esperamos">
    <span class="darkTitle"><?php if( $conference->is_finished() ) : ?>Revive nuestra conferencia ON DEMAND<?php else : ?>Te esperamos en nuestra conferencia<?php endif ?></span>
</div>

<!-- Conferencias Desktop-->
<div class="d-none d-lg-block" style="margin-bottom: 30px;">
<?php
$conference->reset_position();
$i = 0;
while( $conference->the_conference() ) : $i++; ?>
    <div class="container d-none d-lg-block" style="margin-top:20px;">
        <div class="form-group" style="border-top: 6px solid #00b2e3; padding-top: 15px; padding-bottom: 10px; background-color: #f6f6f6;">
            <table width="100%" cellspacing="10">
            <thead>
                <tr style="padding: 15px;">
                    <?php if ( 'after' != $site_state ) : ?>
                    <td width="12.5%" align="left" valign="top" style="height: 194px; min-width: 214px;">
                        <div style="padding: 10px 20px;">
                            <i class="fa fa-calendar-check-o fa-2x" style="color: #00b2e3;"></i>
                            <span style="font-size: 19px; font-family: National-Bold; font-weight: bolder; margin-left: 6px;">
                                <?php echo $conference->get_the_date('%e') ?> de <?php echo $conference->get_the_date('%B') ?>
                            </span>
                        </div>
                    </td>
                    <td width="2.5%">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/divider.png" class="bigDivider bigDivider<?php echo $i ?>">
                        </td>
                    <?php endif ?>
                    <td width="10%" align="center" valign="top">
                        <div id="bajame<?php echo $i ?>" style="height: 0px;">
                        </div>
                        <a href="<?php $conference->the_slug() ?>">
                        <?php foreach ( $conference->get_speakers() as $speaker ) : ?>
                        <div style="padding: 10px 10px;">
                            <div class="circleSpeakerCont" style="background-image: url(<?php echo $speaker['image'] ?>);">
                            </div>
                        </div>
                        <?php endforeach ?>
                        </a>
                    </td>
                    <td width="52.5%" align="left" valign="top">
                        <div style="padding: 10px 20px 40px 20px;">
                            <a href="<?php $conference->the_slug() ?>">
                                <span style="display: block; color: #0c369c; font-family: 'National-Bold'; font-size: 18px; line-height: 1.2;"><?php $conference->the_title() ?></span>
                                <span style="display: block; font-size: 15px; color: #414141; margin-top: 5px; line-height: 1.3;"><?php $conference->the_topic() ?></span>
                            </a>
                            <div style="height: 27px; margin-top: 5px;">
                                <span id="hideMe<?php echo $i ?>" style="display: block; font-size: 18px; color: #00b2e3; font-family: 'National-Bold'; text-transform: uppercase;"><?php $conference->the_speakers() ?></span>
                            </div>
                        </div>

                        <div style="padding: 0px 20px;">
                            <div class="wrapper center-block d-none d-lg-block">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div id="collapse<?php echo $i ?>" aria-id="<?php echo $i ?>" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="heading<?php echo $i ?>">
                                            <div class="panel-body">
                                                <table width="100%" style="margin-bottom: 20px;">
                                                    <tr>
                                                        <td width="47.5%" valign="top">
                                                            <?php 
                                                            $v = 0;
                                                            foreach ( $conference->get_speakers() as $speaker ) : $v++; ?>
                                                                <span style="display: block; font-size: 18px; color: #00b2e3; font-family: 'National-Bold'; text-transform: uppercase; <?php if( $v > 1 ) : echo 'margin-top: 60px;'; else : echo 'margin-top: 5px;'; endif ?>"><?php echo $speaker['full_name']; ?></span>
                                                                <span style="display: block; font-size: 14px; color: #414141; line-height: 1.3; margin-top: 5px; margin-bottom:20px"><?php echo $speaker['cv_short']; ?></span>
                                                            <?php endforeach ?>
                                                        </td>
                                                        <td width="5%">
                                                        </td>
                                                        <td width="47.5%" valign="top">
                                                            <span style="display: block; color: #0c369c; font-family: 'National-Bold'; font-size: 18px; line-height: 1.2;"> ¿Qué aprenderás?</span>
                                                            <?php foreach ( $conference->get_bullets() as $bullet ) : ?>
                                                            <div class="d-flex">
                                                                <i class="fa fa-circle me-2" aria-hidden="true" style="font-size: .8rem; margin-top: 10px; color: #0c369c;"></i>
                                                                <span style="display: block; font-size: 14px; color: #414141; line-height: 1.3; margin: 5px 0;"><?php echo $bullet ?></span>
                                                            </div>
                                                            <?php endforeach ?>
                                                            <a href="<?php $conference->the_slug() ?>" class="btn btn-ws-secondary d-flex justify-content-center align-items-center" style="height: auto;margin-top: 20px; font-family: 'National';">
                                                                    VER INFORMACIÓN COMPLETA
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="panel-heading" role="tab" id="heading<?php echo $i ?>" style="max-width:120px; background-color: white;">
                                            <h4 class="panel-title">
                                            <a id="verInfo<?php echo $i ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?>" aria-expanded="false" aria-controls="collapse<?php echo $i ?>" class="collapsed" style="color: #0c369c; font-weight: normal; font-family: 'National';">
                                                VER MÁS
                                            </a>
                                            </h4>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="2%">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/divider.png" class="bigDivider bigDivider<?php echo $i ?>">
                        </td>
                    <td width="25%" align="left" valign="top">
                        <?php if ( 'after' != $site_state ) : ?>
                        <div style="padding: 0px 15px 0px 0px;">
                            <table>
                                <tr>
                                    <td width="37.5%">
                                        <i class="fa fa-clock-o fa-3x" style="color: #00b2e3;"></i>
                                    </td>
                                    <td align="center" valign="center">
                                        <span style="display: block; margin-bottom: 5px;">
                                            <strong style="font-size: 22px">Horarios</strong>
                                        </span>
                                    </td>
                                </tr>
                            </table> 
                        </div>
                        <?php endif ?>
                        <?php 
                        $is_finished = $conference->is_finished();
                        $is_now = $conference->is_now( $minutes_before = 2 );
                        ?>
                        <div <?php if ( $is_finished || $is_now ) echo 'class="d-none"' ?>>
                            <div style="padding: 20px 15px 15px 0px;">
                                <span style="display: block; color:#FF6900; font-family: 'National-Bold'; font-size: 13px;">Elige el horario de tu preferencia</span>
                            </div>
                            <div style="padding: 10px 15px 0px 0px;">
                                <div class="conference__actions" style="margin: 0px;">
                                <?php foreach ( $conference->get_times() as $time ) : ?>
                                    <div class="conference__time btn-agendar-conf<?php echo $time['id'] ?> <?php echo $conference->is_scheduled( $time['id'] ) ? 'bg-orange' : 'bg-red'; ?> <?php if ( $conference->get_state( $time['id'] ) === 'next' ) echo 'btn_agrendar'; else echo 'remove-hand'; ?>" data-conference_id="<?php echo $time['id'] ?>" data-scheduled="<?php echo $conference->is_scheduled( $time['id'] ) ? 'true' : 'false' ?>" data-tracking-accion="77" data-tracking-material="<?php echo $time['id'] ?>" data-tracking-texto="conferencia <?php $conference->the_title() ?>" onclick="tracking(event)">
                                        <span class="span-time <?php if ( $conference->get_state( $time['id'] ) !== 'next' ) echo 'd-none' ?>"><?php echo $time['date']->format('H:i A') ?>
                                        <div class="scheduled-small " style="font-size: 12px; <?php if ( ! $conference->is_scheduled( $time['id'] ) ) : ?>display:none<?php endif ?>">AGENDADA</div>
                                        </span>
                                        <span class="span-add <?php if ( $conference->get_state( $time['id'] ) !== 'next' ) echo 'd-block' ?>"><div style="font-size:12px;"><?php echo strtoupper( $conference->get_button_text( $time['id'] ) ) ?></div></span>
                                    </div>
                                <?php endforeach ?>
                                </div>            
                            </div>
                            <div style="padding: 10px 15px 0px 0px;">
                                <span style="display: block; font-size: 14px; color: #414141;">Tiempo Ciudad de México</span>
                            </div>
                        </div>
                        <div class="conference__ondemand <?php if ( ! $is_finished ) echo 'd-none' ?> <?php if ( 'after' == $site_state ) : ?>conference__ondemand_no_margin conference__ondemand_bg_orange<?php endif ?>">
                            <a href="<?php $conference->the_slug() ?>"><?php echo str_replace( 'ON DEMAND EL', 'ON DEMAND<br>A PARTIR DEL', strtoupper( $conference->get_button_text( $time['id'] ) ) ) ?></a>
                        </div>
                        <div class="conference__ondemand conference_bg_red <?php if ( ! $is_now ) echo 'd-none' ?> <?php if ( 'after' == $site_state ) : ?>conference__ondemand_no_margin<?php endif ?>">
                            <a href="<?php $conference->the_slug() ?>"><img src="<?php echo get_template_directory_uri() ?>/assets/img/bulletPoint.svg" style="filter: brightness(0) invert(1); width: 13px; margin-right: 5px; margin-top: 1px;"/>EN VIVO AHORA</a>
                        </div>
                    </td>
                </tr>
            </thead>
            </table>
        </div>
    </div>
    <?php 
endwhile; ?>
</div>

<!-- Mobile -->
<?php
$conference->reset_position();
$i = 0;
while( $conference->the_conference() ) : $i++; ?>
<div class="container esperamos d-block d-lg-none" style="margin-top:40px;">
    <div class="form-group" style="border-top: 6px solid #00b2e3; padding: 20px 25px; background-color: #f6f6f6;">
        <?php if ( 'after' != $site_state ) : ?>
        <div style="padding: 20px 0px;">
            <i class="fa fa-calendar-check-o" style="color: #00b2e3; font-size: 35px;"></i>
            <span style="margin-left: 15px; font-size: 22px;color: #414141; font-family: 'National'; font-weight: bold;">
                <?php echo $conference->get_the_date('%e de %B') ?>
            </span>
        </div>
        <?php endif ?>

        <div>
            <a href="<?php $conference->the_slug() ?>">
                <span style="display: block; font-size: 16px; color: #414141; font-family: 'National';"><?php $conference->the_topic() ?></span>
                <span style="display: block; color: #0c369c; font-family: 'National-Bold'; font-size: 22px; line-height: 1.2; margin: 10px 0px;"><?php $conference->the_title() ?></span>
            </a>
            <span style="display: block; font-size: 20px; color: #00b2e3; font-family: 'National-Bold'; text-transform: uppercase; margin-top: 5px;"><?php $conference->the_speakers() ?></span>
        </div>
        <?php 
        $is_finished = $conference->is_finished();
        $is_now = $conference->is_now( $minutes_before = 2 );
        ?>
        <div <?php if ( $is_finished || $is_now ) echo 'class="d-none"' ?>>
            <div style="padding: 20px 0px; display: flex;
            align-items: center;
            justify-content: center;">
                <span style="display: block; color:#FF6900; font-family: 'National-Bold'; font-size: 17px;">Elige el horario de tu preferencia</span>
            </div>
            <?php 
            $v = 0;
            foreach ( $conference->get_times() as $time ) : ?>
            <div <?php if ( $v++ != 0 ) : ?>class="time-border-top"<?php else : ?>style="padding: 0px 0px;"<?php endif ?>>
                <table width="100%">
                    <tr>
                        <td width="50%">    
                            <div style="display: flex; align-items: center; justify-content: left;">
                                <i class="fa fa-clock-o fa-lg" style="color: #00b2e3; font-size: 35px;"></i>
                                <span class="time-mobile">
                                <?php echo $time['date']->format('H:i A') ?>
                                </span>
                            </div>
                        </td>
                        <td width="50%">
                            <span class="btn btn-ws-primary d-inline-flex justify-content-center align-items-center btn-agendar-conf<?php echo $time['id'] ?> <?php echo $conference->is_scheduled( $time['id'] ) ? 'bg-orange' : 'bg-red'; ?> <?php if ( $conference->get_state( $time['id'] ) === 'next' ) echo 'btn_agrendar'; else echo 'remove-hand'; ?>" data-conference_id="<?php echo $time['id'] ?>" data-scheduled="<?php echo $conference->is_scheduled( $time['id'] ) ? 'true' : 'false' ?>" data-device="mobile" data-tracking-accion="77" data-tracking-material="<?php echo $time['id'] ?>" data-tracking-texto="conferencia <?php $conference->the_title() ?>" onclick="tracking(event)" style="width: 100%; height: 50px;">
                            <?php echo strtoupper( $conference->get_button_text( $time['id'] ) ) ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <?php endforeach ?>

            <div style="padding: 10px 0px; display: flex;
            justify-content: center;">
                <span style="display: block; font-size: 16px; color: #414141; font-family: 'National';">Tiempo Ciudad de México</span>
            </div>
        </div>
        <div class="conference__ondemand <?php if ( ! $is_finished ) echo 'd-none' ?> <?php if ( 'after' == $site_state ) : ?>conference__ondemand_no_margin conference__ondemand_bg_orange<?php endif ?>">
            <a href="<?php $conference->the_slug() ?>"><?php echo str_replace( 'ON DEMAND EL', 'ON DEMAND<br>A PARTIR DEL', strtoupper( $conference->get_button_text( $time['id'] ) ) ) ?></a>
        </div>
        <div class="conference__ondemand conference_bg_red <?php if ( ! $is_now ) echo 'd-none' ?> <?php if ( 'after' == $site_state ) : ?>conference__ondemand_no_margin<?php endif ?>">
            <a href="<?php $conference->the_slug() ?>"><img src="<?php echo get_template_directory_uri() ?>/assets/img/bulletPoint.svg" style="filter: brightness(0) invert(1); width: 13px; margin-right: 5px; margin-top: 1px;"/>EN VIVO AHORA</a>
        </div>
        <div style="padding: 0px 0px;">
            <div class="wrapper center-block d-block d-lg-none" style="margin-top: 20px;">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    
                    <div class="panel panel-default">
                        
                        <div class="panel-heading" role="tab" id="headingM<?php echo $i ?>">
                            <table width="100%">
                                <tr>
                                    <td width="60%">
                                    </td>
                                    <td width="40%" style="background-color: white; ">
                                        <span class="panel-title">
                                            <a role="button" aria-id="<?php echo $i ?>" data-toggle="collapse" data-parent="#accordion" href="#collapseM<?php echo $i ?>" aria-expanded="false" aria-controls="collapseM<?php echo $i ?>" class="collapsed" style="color: #0c369c; font-weight: normal; font-size: 20px;" id="turnColorMobile<?php echo $i ?>">
                                                VER +
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                        <div id="collapseM<?php echo $i ?>" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="headingM<?php echo $i ?>" style="">
                            <div class="panel-body">
                                <table width="100%" style="margin-bottom: 20px;">
                                    <tr>
                                    <td valign="top">
                                        <?php foreach ( $conference->get_speakers() as $speaker ) : ?>
                                        <div style="padding: 10px; text-align:left;">
                                            <a href="<?php $conference->the_slug() ?>">
                                                <div class="circleSpeakerCont" style="background-image: url(<?php echo $speaker['image'] ?>);"></div>
                                            </a>
                                            <span style="display: block; font-size: 18px; color: #00b2e3; font-family: 'National-Bold'; text-transform: uppercase; margin-top: 5px;"><?php echo $speaker['full_name']; ?></span>
                                            <span style="display: block; font-size: 14px; color: #414141; line-height: 1.3; margin-top: 5px;"><?php echo $speaker['cv_short']; ?></span>
                                        </div>
                                        <?php endforeach ?>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="padding: 10px; text-align: left;">
                                                <span style="display: block; color: #0c369c; font-family: 'National-Bold'; font-size: 18px; line-height: 1.2;"> ¿Qué aprenderás?</span>
                                                <?php foreach ( $conference->get_bullets() as $bullet ) : ?>
                                                <div class="d-flex">
                                                    <i class="fa fa-circle me-2" aria-hidden="true" style="font-size: .7rem; margin-top: 10px"></i>
                                                    <span style="display: block; font-family: 'National'; font-size: 14px; color: #414141; line-height: 1.3; margin: 5px 0;"><?php echo $bullet ?></span>
                                                </div>
                                                <?php endforeach ?>
                                            </div>
                                            <a href="<?php $conference->the_slug() ?>" class="btn btn-ws-secondary d-flex justify-content-center align-items-center" style="height: auto;margin-top: 20px; font-family: 'National';">
                                                    VER INFORMACIÓN COMPLETA
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endwhile ?>
<?php endif ?>
<?php
$keywords = $supplier->get_products_services();
if ( $keywords ) : ?>
<!-- Palabra clave desktop -->
<section class="d-none d-lg-block" style="background-color:#e7e5e5; margin-top: 40px; padding-top: 40px; margin-bottom: 40px; padding-bottom: 20px;">
    <div class="container d-none d-lg-block" style="color: #0c369c; font-size: 25px;font-weight: bold;letter-spacing: .5px;">
        <span>Si deseas información sobre nuestros productos o servicios, <span style="font-family: National-Bold">haz clic sobre esa palabra.</span></span>
    </div>

    <div class="container d-none d-lg-block" style="margin-top: 30px;">
        <table width="100%" cellspacing="10" id="esperamosTable">
            <tr style="padding: 10px; vertical-align: top;">
                <?php
                $block = array();
                $i = 1;

                foreach ( $keywords as $keyword ) {
                    $block[ $i ][] = ucfirst( $keyword['name'] );
                    if ( $i < 4 ) {
                        $i++;
                    } else {
                        $i = 1;
                    }
                } 
                for ( $i = 1; $i <= sizeof( $block ); $i++ ) :
                    if ( $i != 1 ) : ?>
                    <td width="2%"></td>
                    <?php endif ?>
                    <td width="23.5%">
                        <ul>
                            <?php foreach( $block[ $i ] as $keyword ) : ?>
                            <li>
                            <a href="mailto:<?php echo $supplier->get_request_email() ?>?subject=Solicito%20información%20sobre%20<?php echo $keyword ?>&body=Me%20pongo%20en%20contacto%20con%20ustedes%20ya%20que%20ingresé%20al%20Directorio%20de%20proveedores%20THE%20LOGISTICS%20WORLD%20y%20deseo%20información%20sobre%20<?php echo $keyword ?>" data-tracking-accion="76" data-tracking-material="<?php $supplier->the_ID() ?>" data-tracking-texto="producto o servicio: <?php echo $keyword ?>" onclick="tracking(event)">
                                <?php echo $keyword ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                <?php endfor ?>
            </tr>
        </table>
    </div>
</section>

<!-- Mobile -->
<section class="d-block d-lg-none" style="background-color:#e7e5e5; margin-top: 50px; margin-bottom: 50px; padding: 15px 10px;"> 
    <div class="container esperamos d-block d-lg-none" style="color: #0c369c; font-size: 20px; line-height: 1.2;">
        <span>Seleccione una palabra para recibir por correo información específica.</span>
    </div>

    <div class="container selectdiv" style="margin-top: 20px; padding-bottom: 30px;">
        <select class="form-control mySelect" id="producto-servicio" aria-email="<?php echo $supplier->get_request_email() ?>">
            <option value="volvo">Busqueda por producto / servicio</option>
            <?php foreach ( $keywords as $keyword ) : ?>
            <option value="<?php echo ucfirst( $keyword['name'] ) ?>"><?php echo ucfirst( $keyword['name'] ) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</section>
<?php endif ?>

<?php if ( $products = $supplier->get_products() ) : ?> 
<!-- GALERIA DE PRODUCTOS -->
<div class="container esperamos">
    <span class="d-none d-lg-block darkTitle">Productos y servicios de <span style="font-family: National-Bold;"><?php $supplier->the_name() ?></span></span>
    <span class="darkTitle d-lg-none" style="line-height: 1;">Productos y servicios</span>
    <span class="darkTitle d-lg-none" style="line-height: 1;"><?php $supplier->the_name() ?></span>
</div>

<!-- Desktop -->
<div class="container d-none d-lg-block">
    <div class="row" style="--bs-gutter-x: 3rem;">
        <?php foreach ( $products as $product ) : ?>
        <div class="col-4 mt-5">
            <div class="product-border">
                <div id="product-image-slider" class="splide mt-0 product-image-hide-slider">
                    <div class="splide__track d-flex">
                        <div class="splide__list align-items-center" style="height: 300px !important;">
                            <?php
                            foreach( $product['images'] as $image ) : ?>
                            <div class="splide__slide text-center">
                                <a href="<?php echo $image ?>" rel="product_images">
                                    <img src="<?php echo $image ?>" width="100%" style="max-height: 300px; object-fit: contain; max-width: 100%; width: auto;">
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <table width="100%" cellspacing="10" style="background-color: #f6f6f6;">
                    <tr style="height: 120px; vertical-align: top;">
                        <td width="30%" style="padding: 20px 20px 0 20px;">
                            <span class="orangeTitle" style="margin-left: 0;"><?php echo $product['name'] ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <span style="display: block; color:rgb(119, 118, 118); line-height: 1.5; font-size: 14px; margin-left: 20px; margin-right:20px; min-height: 105px;"><?php echo $product['description'] ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" height="20px"></td>
                    </tr>
                    <tr>
                        <td width="30%" style="background-color: #f6f6f6; padding: 0px 20px 0px 20px; height: 50px;">
                            <table width="100%" cellspacing="10" id="esperamosTable" style="margin-bottom: 15px;">
                                <tbody>
                                    <tr style="padding: 10px;">
                                        <?php if ( ! empty( $product['file'] ) ) : ?>
                                        <td width="45%">
                                            <a href="<?php echo $product['file'] ?>" target="_blank">
                                                <table width="100%" cellspacing="10" id="esperamosTable">
                                                    <tbody>
                                                        <tr style="padding: 10px;">
                                                            <td width="20%">
                                                                <img src="<?php echo get_template_directory_uri() ?>/assets/img/downloadIcon.svg" width="25px">
                                                            </td>
                                                            <td width="10%"> 
                                                            </td>
                                                            <td width="70%">
                                                                <span style="font-size: 14px; display: block;
                                                                line-height: 1.2; color:rgb(87, 87, 87); font-family: 'National-Bold">Descargar ficha técnica</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </a>     
                                        </td>
                                        <td width="5%"> 
                                            <img src="<?php echo get_template_directory_uri() ?>/assets/img/blueDevider.png">
                                        </td>
                                        <?php endif ?>
                                        <td>
                                            <?php
                                                $link = "mailto:" . $supplier->get_request_email() . "?subject=Solicito cotización de {$product['name']}&body=Me pongo en contacto con ustedes ya que ingresé al Directorio de proveedores THE LOGISTICS WORLD y deseo una cotización de {$product['name']}";
                                                if ( $supplier->has_landing_link() ) {
                                                    $link = $supplier->get_the_landing_link();
                                                }
                                            ?>
                                            <a href="<?php echo $link ?>" target="_blank" data-tracking-accion="83" data-tracking-material="<?php $supplier->the_ID() ?>" data-tracking-texto="cotización producto: <?php echo $product['name'] ?>" onclick="tracking(event)">
                                                <span class="darkSubTitle" style="margin: 0; font-size: 16px; font-family: 'National-Bold'; color: #0c369c;">
                                                    Solicitar cotización
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
</div>

<!-- Mobile -->
<div class="container d-lg-none" style="margin-top: 30px;">
    <div class="splide">
        <div class="splide__track">
            <div class="splide__list">
                <?php foreach ( $products as $product ) : ?>
                <div class="splide__slide" style="width: 330px;">
                    <table width="95%" align="center" class="mobileSliderAbajo1 product-border">
                        <tbody>
                            <tr>
                                <td width="30%" class="text-center">
                                    <a href="<?php echo $product['images'][0] ?>" rel="product_images">
                                        <img src="<?php echo $product['images'][0] ?>" style=" max-height: 250px; object-fit: contain; width:auto; max-width: 100%;">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px; border:1px solid #aaaaaa; border-width: 0px 0px 1px 0px; background-color: #f6f6f6;">
                                    <span class="orangeTitle" style="margin-left: 0px; display: block; margin-bottom: 10px; font-family: 'National-bold'; min-height: 82px;"><?php echo $product['name'] ?></span>
                                    <span style="display: block; color:rgb(119, 118, 118); line-height: 1.5; min-height: 114px;"><?php echo $product['description'] ?></span>
                                </td>
                            </tr>
                            <?php if ( ! empty( $product['file'] ) ) : ?>
                            <tr>
                                <td style="padding: 20px; border:1px solid #aaaaaa; border-width: 0px 0px 1px 0px; background-color: #f6f6f6;">
                                    <a href="<?php echo $product['file'] ?>" target="_blank">
                                        <table width="100%">
                                            <tbody><tr style="padding: 10px;">
                                                <td width="70%" align="left" style="border: 0px;">
                                                    <span style="font-size: 16px; display: block;
                                                    line-height: 1.2; color:rgb(87, 87, 87); font-family: 'National-Bold';">Descargar ficha técnica</span>
                                                </td>
                                                <td width="30%" align="right" style="border: 0px;">
                                                    <img src="<?php echo get_template_directory_uri() ?>/assets/img/downloadIcon.svg" width="25px">
                                                </td>
                                                
                                            </tr>
                                        </tbody></table>
                                    </a>     
                                </td>
                            </tr>
                            <?php endif ?>
                            <tr>
                                <td style="padding: 20px; background-color: #f6f6f6;"> 
                                    <?php
                                        $link = "mailto:" . $supplier->get_request_email() . "?subject=Solicito cotización de {$product['name']}&body=Me pongo en contacto con ustedes ya que ingresé al Directorio de proveedores THE LOGISTICS WORLD y deseo una cotización de {$product['name']}";
                                        if ( $supplier->has_landing_link() ) {
                                            $link = $supplier->get_the_landing_link();
                                        }
                                    ?>
                                    <a href="<?php echo $link ?>" target="_blank" data-tracking-accion="83" data-tracking-material="<?php $supplier->the_ID() ?>" data-tracking-texto="cotización producto: <?php echo $product['name'] ?>" onclick="tracking(event)">
                                        <table width="100%">
                                            <tbody><tr style="padding: 10px;">
                                                <td width="70%" align="left" style="border: 0px;">
                                                    <span style="font-size: 16px; display: block;
                                                    line-height: 1.2; font-weight: bold; color:#0c369c; font-family: 'National-Bold';">Solicitar cotización</span>
                                                </td>
                                                <td width="30%" align="right" style="border: 0px;">
                                                    <i class="fa fa-chevron-right fa" aria-hidden="true" style="color: #0c369c"></i>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </a>     
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div class="d-block d-lg-none" style="height: 40px;"> </div>
<?php endif ?>

<?php if ( $videos = $supplier->get_videos() ) : ?>
<!-- VIDEOS -->
<div class="container esperamos">
    <span class="darkTitle">Videos</span>
</div>

<!-- Desktop -->
<div class="container d-none d-lg-block" style="margin-top: 30px;">
    <div class="row" style="--bs-gutter-x: 3rem;">
    <?php foreach ( $supplier->get_videos() as $video ) : ?>
        <div class="col-4 mb-5">
            <table width="100%" class="product-border" cellspacing="10" style="background-color: #f6f6f6; ">
                <tr style="padding: 10px;">
                    <td width="30%">
                        <iframe class="video-desktop" width="100%" height="250px" src="<?php echo $video['url'] ?>"></iframe>
                    </td>
                </tr>
                <tr style="padding: 10px;">
                    <td width="30%" style="padding: 10px 20px; height: 113px; vertical-align: top;">
                        <span class="orangeTitle" style="margin-left: 0;"><?php echo $video['title'] ?></span>
                    </td>
                </tr>
                <tr style="padding: 10px;">
                    <td width="30%" style="padding: 10px 20px; height: 120px; vertical-align: top;">
                        <span style="display: block; color:rgb(119, 118, 118); line-height: 1.5;"><?php echo $video['description'] ?></span>
                    </td>
                </tr>
            </table>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Mobile -->
<div class="container d-lg-none" style="margin-top: 30px;">
    <div class="<?php if ( count( $videos ) > 1 ) : ?>splide<?php else : ?>d-flex justify-content-center<?php endif ?>">
        <div class="splide__track">
            <div class="splide__list">
                <?php foreach ( $videos as $video ) : ?>
                <div class="splide__slide" style="width: 330px;">
                    <table width="95%" align="center" class="mobileSliderAbajo1 product-border">
                        <tbody>
                            <tr style="background-color: #f6f6f6;">
                                <td> 
                                    <iframe width="100%" height="250px" src="<?php echo $video['url'] ?>"></iframe>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 15px 30px;  background-color: #f6f6f6;">
                                    <span class="orangeTitle" style="margin-left: 0px; display: block; margin-bottom: 10px; font-family: 'National-Bold'; min-height: 92px;"><?php echo $video['title'] ?></span>
                                    <span style="display: block; color:rgb(119, 118, 118); line-height: 1.5; font-size: 16px; min-height: 60px; min-height: 150px;"><?php echo $video['description'] ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>  
</div>

<div class="d-block d-lg-none" style="height: 40px;">
</div>
<?php endif ?>

<?php if ( $files = $supplier->get_files() ) : ?>

<!-- FICHAS TECNICAS -->
<div class="container esperamos" style="margin-top: 10px;">
    <span class="darkTitle">Fichas técnicas</span>
</div>

<!-- Desktop -->
<div class="container d-none d-lg-block" style="margin-bottom:50px;">
    <div class="row" style="--bs-gutter-x: 3rem;">
        <?php foreach ( $files as $file ) : ?>
        <div class="col-4 mt-5">
            <div class="product-border" style="background-color: #f6f6f6; border-top: 6px solid #00b2e3; padding: 20px">
                <table width="100%" cellspacing="10">
                    <tr style="padding: 10px;">
                        <td width="30%" style="height: 95px; vertical-align: top;">
                            <span class="orangeTitle" style="margin-left: 0;">
                                <?php echo $file['title'] ?>
                            </span>
                        </td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td width="30%" height="20px"></td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td width="30%">
                            <span style="display: block; color:rgb(119, 118, 118); line-height: 1.5; min-height: 144px;"><?php echo $file['description'] ?></span>
                        </td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td width="30%" height="20px"></td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td width="30%">
                            <table width="100%" cellspacing="10" id="esperamosTable">
                                <tr style="padding: 10px;">
                                    <td width="45%">
                                        <a href="">
                                            <table width="100%" cellspacing="10" id="esperamosTable">
                                                <tr style="padding: 10px;">
                                                    <td width="20%">
                                                        <a href="<?php echo $file['url'] ?>" target="_blank">
                                                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/downloadIcon.svg" width="25px">
                                                        </a>
                                                    </td>
                                                    <td width="10%"> 
                                                    </td>
                                                    <td width="70%">
                                                        <a href="<?php echo $file['url'] ?>" target="_blank">
                                                        <span style="font-size: 14px; display: block;
                                                        line-height: 1.2; color:rgb(87, 87, 87);  font-family: 'National-Bold';">Descargar ficha técnica</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </a>     
                                    </td>
                                    <td width="10%"> 
                                    </td>
                                    <td width="45%">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Mobile -->
<div class="esperamos d-block d-lg-none" style="margin-top: 0;">
    <?php foreach ( $supplier->get_files() as $file ) : ?>
    <a href="" style="text-decoration: none;">
        <div style="border-bottom:rgb(177, 177, 177) 1px solid; ">
            <table width="100%" id="fichasAbajo3">
                <tbody><tr style="padding: 10px;">
                    <td width="70%" align="left" style="border: 0px;">
                        <span style="font-size: 16px; display: block;
                        line-height: 1.2; font-weight: bold; color: #00b2e3; font-family: 'National-Bold';"><?php echo $file['title'] ?></span>
                    </td>
                    <td width="30%" align="right" style="border: 0px;">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/downloadIcon.svg" width="25">
                    </td>
                </tr>
            </tbody></table>
        </div>
    </a>
    <?php endforeach ?>
    <button type="button" onclick="location.href='<?php echo site_url('/#proveedores') ?>'" class="btn btn-ws-secondary" style="min-width: 100%; margin: 20px 0px; font-family: 'National';">
        Visitar proveedores
    </button>
</div>
<?php endif ?>
<div class="container mb-5">El contenido publicado en cada perfil de proveedor es total responsabilidad de la empresa que proporcionó la información (textos, pdfs, catálogos, videos, etc.).</div>
<!-- SCRIPTS -->
<script src="<?php echo get_template_directory_uri() ?>/assets/js/js.cookie.js"></script>

<!-- Slider -->
<script>
    /*ORIGINAL ARROW: 'm15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z'**/
    document.addEventListener('DOMContentLoaded', function () {
        var elms = document.getElementsByClassName( 'splide' );
        for ( var i = 0, len = elms.length; i < len; i++ ) {
            new Splide( elms[ i ], {
            arrowPath: 'm15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z',
            type: 'loop',
            pagination: true,
            arrows: true,
            autoplay: true,
            interval: 3500
        } ).mount();
        }
    });
</script>

<script>
$(document).ready(function() {

    const BASE_PATH = window.location.origin + "/web-summit/";
    
    function showError( body, title = 'Error' ) {
        $('#main-modal .modal-title').html(title);
        $('#main-modal .modal-body').html(body);
        $('#main-modal').modal("show");
    }
    
    // Change the check status based on cookie
    let supplier = $('#supplier-id').attr('aria-supplier');
    let data = Cookies.getJSON('supplier-'+supplier);
    if( data ) {
        if(data.leaveCard) {
            $('#check-card').attr("checked",true);
        }
        if(data.markSupplier) {
            $('#check-mark').attr("checked",true);
        }
    }

    // Detect check click ( Mark Supplier - Leave Card )
    $('.check-supplier').click(function() {
        let action = this.getAttribute('aria-action');
        let loggedIn = this.getAttribute('aria-logged-in');

        if ($(this).is(':checked')) {

            if(loggedIn == 'true') {
                
                // Fill the data
                var formData = {
                    'action' : action,
                    'supplier_id': this.getAttribute('aria-supplier'),
                };

                // Process using ajax
                $.ajax({
                    type        : 'POST',
                    url         : '<?php echo get_template_directory_uri() ?>/ajax-email.php', 
                    data        : formData,
                    dataType    : 'json',
                    encode      : true
                })
                .done(function(data) {
                    if (data.success) {
                        // Save data in a cookie, update if exist
                        let actionCookie = Cookies.getJSON('supplier-'+formData.supplier_id);
                        let leaveCard = markSupplier = false;
                        if(actionCookie) {
                            leaveCard =  actionCookie.leaveCard;
                            markSupplier = actionCookie.markSupplier;
                        }
                        let action = {
                            'leaveCard' : formData.action == 'leaveCard' || leaveCard,
                            'markSupplier' : formData.action == 'markSupplier' || markSupplier,
                        };
                        Cookies.set('supplier-'+formData.supplier_id, action, {expires: 30});

                        // Show modal
                        showError( data.message, 'Muchas gracias por su interés' );
                    } else {
                        // Log errors
                        showError(data.errors);
                        $(this).prop( "checked", false );
                    }
                })
                .fail(function(err) {
                    showError('Hubo un error, intente nuevamente.');
                    $(this).prop( "checked", false );
                    console.log(err);
                });
            } else {
                // Update modal text
                let actionText = this.getAttribute('aria-action-text');
                showError( `Para ${actionText.toLowerCase()} debes iniciar sesión.<br>Si aún no estás registrado, hazlo hoy mismo y sin costo, dando <a style="font-family:'National-Bold'" href="${BASE_PATH}registro/">clic aquí.</a>`);
                $(this).prop( "checked", false );
            }
        } else {
            // Update cookie removing the action from this supplier
            let supplier = this.getAttribute('aria-supplier');
            let actionCookie = Cookies.getJSON('supplier-'+supplier);
            actionCookie[action] = false;
            Cookies.set('supplier-'+supplier, actionCookie, {expires: 30});
        }
    });

    $('#producto-servicio').on('change', (e) => {
        let email = e.target.getAttribute('aria-email');
        let keyword = e.target.value;
        let link = document.createElement('a');
        link.href = 'mailto:' + email + '?subject=Solicito información sobre ' + keyword + '&body=Me pongo en contacto con ustedes ya que ingresé al Directorio de proveedores THE LOGISTICS WORLD y deseo información sobre ' + keyword;
        document.body.appendChild(link);
        link.click();
        trackingPageView("", 76, <?php echo $supplier->the_ID() ?>, `producto o servicio: ${keyword}`);
    });
});

</script>
<?php
get_footer( null, [ 'is_directory' => 'true' ] );
?>
