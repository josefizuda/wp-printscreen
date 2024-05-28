<?php
/*
Plugin Name: Captura de Tela
Plugin URI: https://seusite.com
Description: Plugin para capturar a tela do site.
Version: 1.0
Author: Seu Nome
Author URI: https://seusite.com
License: GPLv2 or later
*/

// Adiciona um shortcode para inserir o botão de captura de tela
add_shortcode('capture_button', 'capture_button_shortcode');

function capture_button_shortcode() {
    // Gera um nonce de segurança
    $security = wp_create_nonce('capture-screen-nonce');

    // Inclui a biblioteca html2canvas
    wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js', array(), '0.4.1', true);

    // Adiciona o JavaScript necessário
    wp_enqueue_script('capture-script', plugins_url('capture-script.js', __FILE__), array('jquery'), '1.0', true);

    // Passa os parâmetros para o script JavaScript
    wp_localize_script('capture-script', 'capture_screen_params', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'security' => $security,
    ));

    // Retorna o HTML do botão de captura de tela com os estilos CSS fornecidos
    return '<button id="captureButton" style="font-family: Poppins, Sans-serif; font-size: 14px; font-weight: 600; color: #D4D0B3; background-color: #B9141B; border-radius: 10px; padding: 10px 20px; border: none; cursor: pointer;">Capturar Tela</button>';
}

// Manipula a solicitação de captura de tela
add_action('wp_ajax_capture_screen', 'capture_screen');
add_action('wp_ajax_nopriv_capture_screen', 'capture_screen');

function capture_screen() {
    // Verifica se o nonce é válido
    check_ajax_referer('capture-screen-nonce', 'security');

    // Captura a tela usando html2canvas
    $imageData = $_POST['image'];

    // Salva a imagem no servidor (opcional)
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['path'] . '/screenshot.png';
    $file_url = $upload_dir['url'] . '/screenshot.png';
    file_put_contents($file_path, base64_decode(str_replace('data:image/png;base64,', '', $imageData)));

    // Retorna o URL da imagem
    echo $file_url;

    // Certifique-se de sair após o envio do arquivo
    wp_die();
}
