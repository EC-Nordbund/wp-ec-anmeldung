<?php
/*
Template Name: Landingpage
*/
?>
<?php global $optimizer;?>
<?php global $eca; ?>

<?php get_header('landingpage'); ?>

<div id="content">
    <div id="loader"></div>
    <div id="message" class="animate-bottom" style="display: none;">
    </div>
    
    <script>
        const base_url = 'https://www.ec-nordbund.de/wp-json/ec-api/v1/anmeldung/';
        const default_title = 'Anfrage konnte nicht verarbeitet werden.';
        const default_body = '<p>Ooops, das tut uns Leid.</p>'
            + '<p>Bitte sende uns eine E-Mail an <a href="mailto:webmaster@ec-nordbund.de?subject=Fehler%20beim%20best%C3%A4tigen%20der%20Anmeldung'
            + '&body=Fehlerbeschreibung%3A%0A%0A%0AFehlerdetails%3A%0A%20-%20Aktueller%20Link%3A%0A%20-%20Status%20(falls%20angezeigt)%3A%0A%20-%20evtl.%20Screenshot%3A%0A%0A">webmaster@ec-nordbund.de</a> damit wir die Fehlerursache so schnell wie möglich untersuchen können.</p>';
    
        var token = '';

    </script>

    <?php echo $eca->handle_request($_GET); ?>
    
    <script>
    const loader = document.getElementById('loader');
    const message = document.getElementById('message');

    function generateMessage(title, body) {
        message.innerHTML = ''; //reset

        var header = document.createElement("h1");
        var htext = document.createTextNode(title);
        
        header.appendChild(htext);
        message.appendChild(header);

        if(title.length > 0 && body.length > 0) {
            const hr = document.createElement("hr");
            message.appendChild(hr);
        }

        message.innerHTML = message.innerHTML + body;
    }

    function showMessage() {
        loader.style.display = "none";
        message.style.display = "inherit";
    }
    
    if(token.length > 0 ) {
        generateMessage(default_title, default_body);

        var reload_counter = 0;

        var request = new XMLHttpRequest();
        request.open('GET', base_url + token, true);

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var data = JSON.parse(request.responseText);
            
                console.log(data);

                if('status' in data && 'message' in data) {

                    // if title and body exists for given status
                    if(data.message.title !== ''  && data.message.body !== '') {
                        generateMessage(data.message.title, data.message.body);
                    } else {
                        generateMessage('Unbekannter Status: ' + data.status, default_body);
                    }
                }
            } else {
                generateMessage('API-Abfrage gescheitert', default_body);
            }
            showMessage();
        };

        request.send();

    } else {
        message.classList.remove("animate-bottom");
        generateMessage('Dieser Link funktioniert leider nicht.', '');

        showMessage();
    }
    </script>
</div>

<?php get_footer('landingpage'); ?>