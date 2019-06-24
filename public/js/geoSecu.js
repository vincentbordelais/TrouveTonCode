
<!-- jQuery -->



    $(function() {

        // 1. Une requète ajax simple
        /* $.ajax('https://ipapi.co/json/').done(function(data) {

            console.log( data );
            console.log( data.ip );
            console.log( data.city );

        }); */

        // 2. getJSON, c'est encore plus simple !
        $.getJSON('https://ipapi.co/json/', function(data) {

            /* Avec append :
            $('form').append(`
                <p class="geoSecu">
                    Votre IP : ${data.ip} vous situe à : ${data.city} - ${data.region}
                </p>
            `);
            */
            // Avec appendTo / prependTo :
            $(`
                <p class="geoSecu">
                    Votre IP : ${data.ip} vous situe à : ${data.city} - ${data.region}
                </p>
            `).prependTo( 'form' );

        });

    });


