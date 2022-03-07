<?php

// Configura a página de caracteres do PHP para UTF-8
// DEVE ser a primeira linha de código do site.
header("Content-type: text/html; charset=utf-8");

// Faz conexão com MySQL/MariaDB
// Os dados da conexão estão em "_config.ini"
$i = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/_config.ini', true);

/*
// Debug do MySQL
echo '<pre>';
print_r($i);
echo '</pre>';
exit;
*/

foreach ($i as $key => $value) :
    if ($_SERVER['SERVER_NAME'] === $key) :

        // Conexão com MySQL/MariaDB usando "mysqli" (orientada a objetos)
        @$conn = new mysqli($value['server'], $value['user'], $value['password'], $value['database']);

        // Trata possíveis exceções
        if ($conn->connect_error) die("Falha de conexão com o banco e dados: " . $conn->connect_error);
    endif;
endforeach;

/*
// Consulta de teste. Apague depois de testar.
$result = $conn->query("SELECT * FROM articles");

while ($row = $result->fetch_assoc()) :
    echo '<pre>';
    echo $row['article_title'] . ' - ' . $row['article_intro'];
    echo '</pre>';
endwhile;
*/

// Teste de INSERT. Apague depois de testar.
$conn->query("INSERT INTO contacts (contact_name, contact_email, contact_subject, contact_message ) VALUES ('Joca', 'joca@email', 'Teste', 'Mensagem do joca')");

echo '<hr><hr>';

/**
 * Variáveis do tema
 */

// Nome do aplicativo
$site_name = "Decodificando";

// Slogan do site
$site_slogan = "Just Code it.";

// Logotipo do site
$site_logo = "/img/logo_decodificando.jpg";

// Proprietário do aplicativo (mensagem de copyright)
$site_owner = "Turma 2021.1";

// Define o título <title>...</title> de cada página
$page_title = "";

// Define o link ativo no menu principal
$page_menu = "";

// Define o fuso horário (opcional).
date_default_timezone_set('America/Sao_Paulo');
