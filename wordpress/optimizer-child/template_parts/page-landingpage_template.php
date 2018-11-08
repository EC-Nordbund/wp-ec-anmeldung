<?php
/*
Template Name: Landingpage
*/
?>
<?php global $optimizer;?>
<?php global $eca; ?>

<?php get_header('landingpage'); ?>

<div id="content">
    <div id="message">
        <?php echo $eca->handle_request($_GET); ?>
    </div>
    
    <script>
        const base_url = 'https://www.ec-nordbund.de/wp-json/ec-api/v1/anmeldung/';
        const default_title = 'Anfrage konnte nicht verarbeitet werden.';
        const default_body = '<p>Ooops, das tut uns Leid.</p>'
            + '<p>Bitte sende uns eine E-Mail an <a href="mailto:webmaster@ec-nordbund.de?subject=Fehler%20beim%20best%C3%A4tigen%20der%20Anmeldung'
            + '&body=Fehlerbeschreibung%3A%0A%0A%0AFehlerdetails%3A%0A%20-%20Aktueller%20Link%3A%0A%20-%20Status%20(falls%20angezeigt)%3A%0A%20-%20evtl.%20Screenshot%3A%0A%0A">webmaster@ec-nordbund.de</a> damit wir die Fehlerursache so schnell wie möglich untersuchen können.</p>';
    
        var token = '';

    </script>

</div>

<?php get_footer('landingpage'); ?>