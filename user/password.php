<?php

// Inclui arquivo de configuração
require_once $_SERVER['DOCUMENT_ROOT'] . "/_config.php";

/*******************************************
 * Seu código PHP desta página entra aqui! *
 *******************************************/

// Variáveis do script
$form['feedback'] = '';

// Testa se usuário está logado
if (!isset($_COOKIE['user'])) header('Location: /');

// Testa se o formulário foi enviado
if (isset($_POST['send'])) :

    // Obtém os dados do formulário já sanitizados
    $form['password'] = sanitize('password', 'string');
    $form['password2'] = sanitize('password2', 'string');
    $form['password3'] = sanitize('password3', 'string');

    // Verificar se existe algum campo vazio
    if ($form['password'] === '' or $form['password2'] === '' or $form['password3'] === '') :
        $form['feedback'] = '<h3 style="color:red">Erro! Todos os campos devem ser preenchidos!</h3>';

    // Verifica se a nova senha e a repetição são iaguais
    elseif ($form['password2'] !== $form['password3']) :
        $form['feedback'] = '<h3 style="color:red">Erro! A nova senha e a repetição não coincidem!</h3>';
    else :

        // Query que atualiza os dados do usuário no banco de dados.
        $sql = <<<SQL

UPDATE users SET
	user_password = SHA2('{$form['password2']}', 512)
WHERE user_id = '{$user['user_id']}'
AND user_password = SHA2('{$form['password']}', 512);

SQL;

        // Excuta a query
        $conn->query($sql);

    endif;

endif;

/*********************************************
 * Seu código PHP desta página termina aqui! *
 *********************************************/

// Define o título DESTA página.
$page_title = "Alterar senha";

// Opção ativa no menu
$page_menu = "passord";

// Inclui o cabeçalho da página
require_once $_SERVER['DOCUMENT_ROOT'] . "/_header.php";

?>

<?php // Conteúdo 
?>
<article>

    <h2>Alterar senha</h2>

    <?php echo $form['feedback'] ?>

    <p>Preencha os campos abaixo para alterar sua senha.</p>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

        <input type="hidden" name="send" value="true">

        <p>
            <label for="password">Senha atual:</label>
            <input type="password" name="password" id="password" value="" placeholder="Senha atual">
        </p>

        <p>
            <label for="password2">Nova senha:</label>
            <input type="password" name="password2" id="password2" value="" placeholder="Nova senha">
            <small>Sua senha deve ter pelo menos 7 caracteres, uma letra maiúscula, uma minúscula e um número.</small>
        </p>

        <p>
            <label for="password3">Nova senha:</label>
            <input type="password" name="password3" id="password3" value="" placeholder="Repita a nova senha">
        </p>

        <p>
            <button type="submit">Salvar</button>
        </p>

    </form>

</article>

<?php // Barra lateral 
?>
<aside>

    <h3>Seções:</h3>

    <ul>
        <li><a href="/sections/front.php">Front-end</a></li>
        <li><a href="/sections/back.php">Back-end</a></li>
        <li><a href="/sections/full.php">Full-stack</a></li>
    </ul>

</aside>

<?php

// Inclui o rodapé da página
require_once $_SERVER['DOCUMENT_ROOT'] . "/_footer.php";

?>