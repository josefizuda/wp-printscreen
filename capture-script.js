jQuery(document).ready(function($) {
    $('#captureButton').on('click', function() {
        // Captura a tela usando html2canvas
        html2canvas(document.body).then(function(canvas) {
            // Converte o canvas para uma imagem PNG codificada em base64
            var imageData = canvas.toDataURL('image/png');

            // Envia a imagem para o servidor via AJAX
            $.ajax({
                url: capture_screen_params.ajaxurl,
                type: 'POST',
                data: {
                    action: 'capture_screen',
                    security: capture_screen_params.security,
                    image: imageData
                },
                success: function(response) {
                    // Salva a imagem localmente
                    var a = document.createElement('a');
                    a.href = response;
                    a.download = 'screenshot.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    // Adiciona o botão de compartilhamento do Instagram
                    var shareInstagramButton = $('<button id="shareInstagramButton">Compartilhar no Instagram</button>');
                    shareInstagramButton.on('click', function() {
                        // Mostra uma mensagem orientando o usuário a compartilhar no Instagram manualmente
                        alert('Por favor, abra o aplicativo Instagram e faça o upload da imagem manualmente.');
                    });
                    $('#shareInstagramButtonContainer').html(shareInstagramButton);
                }
            });
        });
    });
});
