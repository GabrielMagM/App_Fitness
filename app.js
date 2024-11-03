//Desplegar informacion de desafios en ventana emergente

    const cliente = new SoapClient(null, {
        location: "http://localhost/services/soap.php",
        uri: "http://localhost/services/soap.php",
        trace: 1
    });

    idChallenge = consulta;
    try {
        cliente.call("ObtenerInfo", {id: idChallenge}, function(response) {
            console.log(response);
            alert(response);
        });
    } catch (e) {
        console.log(e);
    }

